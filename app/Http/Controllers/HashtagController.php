<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;

class HashtagController extends Controller
{
    public function show(string $slug)
    {
        $tag = Hashtag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->with(['user', 'comments.user'])
            ->withCount('likes')
            ->latest()
            ->paginate(10);

        return view('tags.show', compact('tag', 'posts'));
    }
}



