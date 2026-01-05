<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Notifications\PostLiked;

class LikeController extends Controller
{
    public function store(Post $post)
    {
        $post->likes()->syncWithoutDetaching([auth()->id()]);

        // notify owner of post
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new PostLiked($post, auth()->user()));
        }

        return response()->json([
            'liked' => true,
            'likes' => $post->likes()->count(),
        ]);
    }

    public function destroy(Post $post)
    {
        $post->likes()->detach(auth()->id());

        return response()->json([
            'liked' => false,
            'likes' => $post->likes()->count(),
        ]);
    }
}





