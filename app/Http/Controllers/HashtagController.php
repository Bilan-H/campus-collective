<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;

class HashtagController extends Controller
{
    public function show(Hashtag $hashtag)
    {
        $posts = $hashtag->posts()->with(['user','hashtags'])->latest()->get();
        return view('hashtags.show', compact('hashtag', 'posts'));
    }
}

