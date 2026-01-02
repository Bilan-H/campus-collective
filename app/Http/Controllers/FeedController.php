<?php

namespace App\Http\Controllers;

use App\Models\Post;

class FeedController extends Controller
{
    public function index()
    {
        $posts = \App\Models\Post::with(['user', 'comments.user'])
            ->withCount('likes')
            ->latest()
            ->get();

        return view('feed', compact('posts'));
    }
}

