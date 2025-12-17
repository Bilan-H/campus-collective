<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
    public function show(User $user)
    {
        $viewer = auth()->user();

        $posts = Post::with(['user', 'comments.user', 'likes'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $isFollowing = $viewer->following()->where('users.id', $user->id)->exists();

        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('users.show', compact(
            'user', 'posts', 'isFollowing', 'followersCount', 'followingCount'
        ));
    }
}
3



