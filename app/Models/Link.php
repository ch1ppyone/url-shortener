<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{

    public function tags()
    {
        return $this->hasMany(Tag::class, 'link_id', 'id');
    }

    public function journals()
    {
        return $this->hasMany(Journal::class, 'link_id', 'id');
    }

    public function view($request)
    {
        $this->journals()->create([
            'user_agent' =>  $request->header('user-agent'),
            'ip' => $request->ip()
        ]);
    }

    public function create($validated)
    {
        $this->title = $validated['title'];
        $this->long_url =  $validated['long_url'];
        $this->short_ally =  substr(uniqid(), 6);
        $this->save();

        $tags = array_map(fn (string $tag) => new Tag(['name' => $tag]), $validated['tags']);
        $this->tags()->saveMany($tags);
    }

    public function patch($validated)
    {
        $this->title = $validated['title'];
        $this->long_url =  $validated['long_url'];
        $tags = array_map(fn (string $tag) => new Tag(['name' => $tag]), $validated['tags']);
        $this->tags()->delete();
        $this->tags()->saveMany($tags);

    }
}
