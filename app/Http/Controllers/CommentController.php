<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => 'required|string|max:500',
        ]);

        $user = $request->user();

        $isOwnPost = $post->user_id === $user->id;
        $followsAuthor = $user->following()
            ->where('users.id', $post->user_id)
            ->exists();

        if (! $isOwnPost && ! $followsAuthor) {
            return back()->with('comment_error', 'You can only comment on posts by users you follow.');
        }

        $post->comments()->create([
            'user_id' => $user->id,
            'body' => $data['body'],
        ]);

        return back();
    }
}
