<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinksResource;
use App\Models\Links;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class LinksController extends Controller
{

    public function index()
    {
        $links = Links::all();
        return response()->json(['success' => true, 'data' => LinksResource::collection($links)]);
    }

    public function store(Request $request)
    {
        $results =  array();

        if (array_is_list($request->all())) {
            foreach ($request->all() as $i => $item) {
                $validated =  $this->validation($item);
                if ($validated == "ERR")
                    return response()->json(['success' => false, 'message' => "Validation error."]);

                if ($this->isAvailable($item['long_url'])) {
                    $link = new Links();
                    $link->create($item);
                    $results[$item['long_url']] =  url('') . '/' . $link->short_ally;
                } else {
                    $results[$item['long_url']] = "Your URL is unreachable";
                }
            }
            return response()->json(['results' => $results]);
        } else {
            $validated =  $this->validation($request->all());

            if ($validated == "ERR")
                return response()->json(['success' => false, 'message' => "Validation error."]);

            if ($this->isAvailable($validated['long_url'])) {
                $link = new Links();
                $link->create($validated);
                return response()->json(['success' => true, 'message' => url('') . '/' . $link->short_ally]);
            } else
                return response()->json(['success' => false, 'message' => 'Your URL is unreachable.']);
        }
    }

    public function visit(Request $request, $ally)
    {
        $link = Links::where('short_ally', $ally)->first();
        if ($link === null) {
            abort(404);
        }
        $link->view($request);
        return redirect()->away($link->long_url);
    }


    public function update(Request $request, $ally)
    {
        $validated =  $this->validation($request->all());
        if ($validated == "ERR")
            return response()->json(['success' => false, 'message' => "Validation error."]);

        if ($this->isAvailable($validated['long_url'])) {
            $link = Links::where('short_ally', $ally)->first();

            if ($link != null) {
                $link->path($validated);
                return response()->json(['success' => true, 'message' => 'OK.']);
            } else
                return response()->json(['success' => false, 'message' => "Record not found."]);
        } else
            return response()->json(['success' => false, 'message' => 'Your URL is unreachable.']);
    }


    public function destroy($id)
    {
        $link = Links::where('id', $id);
        $link->delete();
        return response()->json(['success' => true, 'message' => 'URL deleted by ID.']);
    }

    public function validation($item)
    {
        $validator =  Validator::make($item, [
            'long_url' => 'required|url|max:255',
            'title' => 'sometimes|string|max:255',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return "ERR";
        } else {
            return $validator->validate();
        }
    }


    public function isAvailable($url)
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
