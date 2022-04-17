<?php

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use App\Models\Link;
use App\Http\Resources\LinkResource;

class LinksService
{

    public function storeSingle(array $request)
    {
        $link = new Link();
        if ($this->isAvailable($request['long_url']))
            $link->create($request);
        else
            $link->error = "Your URL is unreachable";
        return  new  LinkResource($link);
    }


    public function storeArray(array $request)
    {
        $results =  array();
        foreach ($request as $i => $item) {
            $link = new Link();
            if ($this->isAvailable($item['long_url']))
                $link->create($item);
            else
                $link->error = "Your URL is unreachable";
            array_push($results, $link);
        }
        return LinkResource::collection($results);
    }

    public function UpdateByAlly(array $validated, string $ally)
    {
        $link = Link::where('short_ally', $ally)->firstOrFail();
        if ($this->isAvailable(($validated['long_url'])))
            $link->patch($validated);
        else
            $link->error = "Your URL is unreachable";
        return new  LinkResource($link);
    }


    public function isAvailable(string $url)
    {
        $client = new Client();
        try {
            $response = $client->get($url, ['timeout' => 1]);
        } catch (GuzzleException) {
            return false;
        }
        return true;
    }
}
