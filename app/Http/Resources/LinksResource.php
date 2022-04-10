<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LinksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->id,
            'long_url' => $this->long_url,
            'short_url' => url('').'/'.$this->short_ally,
            'title' => $this->title,
            'tags' => $this->tags()->pluck('name')
        ];
    }
}
