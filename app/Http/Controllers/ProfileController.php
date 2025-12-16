<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load(['posts.hashtags']);
        $auth = auth()->user();

        $isMe = $auth->id === $user->id;
        $isFollowing = ! $isMe && $auth->following()->where('users.id', $user->id)->exists();

        return view('users.show', compact('user', 'isMe', 'isFollowing'));
    }
}

