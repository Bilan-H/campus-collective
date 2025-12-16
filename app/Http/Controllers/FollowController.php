<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(Request $request, User $user)
    {
        $auth = $request->user();
        if ($auth->id === $user->id) return back();

        $auth->following()->syncWithoutDetaching([$user->id]);
        return back();
    }

    public function destroy(Request $request, User $user)
    {
        $auth = $request->user();
        $auth->following()->detach($user->id);
        return back();
    }
}

