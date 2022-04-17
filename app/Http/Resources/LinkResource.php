<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
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
            'long_url' =>  $this->when($this->long_url  !== null && $this->error  == null,  $this->long_url),
            'short_url' => $this->when($this->short_ally !== null && $this->error  == null,  url($this->short_ally)),
            'title' =>  $this->when($this->title  !== null && $this->error  == null, $this->title),
            'tags' => $this->when(!empty($this->tags) && $this->error  == null, $this->tags()->pluck('name')),
            'error' => $this->when($this->error  !== null,  $this->error)
        ];
    }
}
