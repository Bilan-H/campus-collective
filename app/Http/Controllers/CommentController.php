<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\PostCommented;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ]);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $comment->load('user');

        //Notify post owner 
        if ($post->user_id !== $request->user()->id) {
            $post->user->notify(new PostCommented($post, $request->user(), $comment));
        }

        // JSON for live update
        if ($request->expectsJson()) {
            return response()->json([
                'id' => $comment->id,
                'body' => $comment->body,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                ],
                'created_human' => $comment->created_at->diffForHumans(),
            ]);
        }

        return back()->with('success', 'Commented!');
    }
}


