<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use Illuminate\Http\Request;
use App\Models\Link;
use App\Http\Requests\LinkRequest;
use App\Services\LinksService;

class LinksController extends Controller
{

    public function __construct(private  LinksService  $linkService)
    {
    }

    public function index()
    {
        $links = Link::all();
        return LinkResource::collection($links);
    }

    public function store(LinkRequest $request)
    {
        $validated = $request->validated();
        if (array_is_list($request->all()))
            return     $this->linkService->storeArray($validated);
        else
            return     $this->linkService->storeSingle($validated);
    }

    public function visit(Request $request, string $ally)
    {
        $link = Link::where('short_ally', $ally)->firstOrFail();
        $link->view($request);
        return redirect()->away($link->long_url);
    }

    public function update(LinkRequest $request, string $ally)
    {
        $validated = $request->validated();
       return  $this->linkService->UpdateByAlly($validated, $ally);
    }

    public function destroy(string $id)
    {
        $link = Link::where('short_ally', $id);
        $link->delete();
        return response()->json(['success' => true, 'message' => 'URL deleted by short ally!']);
    }
}
