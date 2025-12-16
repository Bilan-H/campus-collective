<?php

namespace App\Http\Controllers;

use App\Models\Post;

class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();

        return view('feed', compact('posts'));
    }
}

