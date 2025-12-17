<?php

namespace App\Http\Controllers;

use App\Models\User;

class FollowController extends Controller
{
    public function store(User $user)
    {
        abort_if($user->id === auth()->id(), 400);

        auth()->user()->following()->syncWithoutDetaching([$user->id]);

        return back();
    }

    public function destroy(User $user)
    {
        auth()->user()->following()->detach($user->id);

        return back();
    }
}


