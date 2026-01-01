<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
    public function show(User $user)
    {
        $viewer = auth()->user();

        $posts = Post::with(['user', 'comments.user'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $viewer = auth()->user();
        $isFollowing = $viewer ? $viewer->following()->where('users.id', $user->id)->exists() : false;

        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('users.show', compact(
            'user', 'posts', 'isFollowing', 'followersCount', 'followingCount'
        ));
    }
}