<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        return back();
    }
}

