<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(Request $request)
{
    $user = $request->user();

    // counts (works once your followers/following relationships exist on User)
    $followersCount = method_exists($user, 'followers') ? $user->followers()->count() : 0;
    $followingCount = method_exists($user, 'following') ? $user->following()->count() : 0;

    // your posts (requires User->posts() or Post model with user_id)
    $posts = method_exists($user, 'posts')
        ? $user->posts()->latest()->get()
        : \App\Models\Post::where('user_id', $user->id)->latest()->get();

    return view('profile.edit', compact('user', 'followersCount', 'followingCount', 'posts'));
}

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user->fill($data);
        $user->save();

        return back()->with('success', 'Profile updated.');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}


