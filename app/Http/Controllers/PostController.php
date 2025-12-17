<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Hashtag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Post $post)
    {
        $post->load(['user', 'hashtags', 'comments.user']);

        return view('posts.show', compact('post'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caption' => 'required|string|max:255',
        ]);

        $post = $request->user()->posts()->create($data);

        // Hashtags extracted from caption: #word
        preg_match_all('/#(\w+)/', $data['caption'], $matches);
        $names = collect($matches[1])->map(fn ($t) => strtolower($t))->unique();

        $ids = $names->map(function ($name) {
            return Hashtag::firstOrCreate(
                ['slug' => $name],
                ['name' => $name, 'slug' => $name]
            )->id;
        });

        $post->hashtags()->sync($ids);

        return redirect()->route('feed.index')->with('success', 'Post created!');
    }
}

