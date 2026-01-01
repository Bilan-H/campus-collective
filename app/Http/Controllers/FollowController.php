<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class FollowController extends Controller
{
    public function store(User $user): RedirectResponse
    {
        $me = auth()->user();

        abort_if($me->id === $user->id, 403);

        // attach only if not already following
        $me->following()->syncWithoutDetaching([$user->id]);

        return back()->with('success', 'Followed.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $me = auth()->user();

        abort_if($me->id === $user->id, 403);

        $me->following()->detach($user->id);

        return back()->with('success', 'Unfollowed.');
    }
}



