<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('feed.index')
        : redirect()->route('login');
});

// TEMP logout helper (optional – can delete later)
Route::get('/force-logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------
    | Feed
    |--------------------
    */
    Route::get('/feed', [FeedController::class, 'index'])
        ->name('feed.index');

    /*
    |--------------------
    | Public user profile
    |--------------------
    */
    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('users.show');

    /*
    |--------------------
    | Follow / Unfollow
    |--------------------
    */
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])
        ->name('follow.store');

    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])
        ->name('follow.destroy');

    /*
    |--------------------
    | Posts
    |--------------------
    */
    Route::resource('posts', PostController::class)
        ->only(['store', 'show', 'edit', 'update', 'destroy']);

    /*
    |--------------------
    | Comments
    |--------------------
    */
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    /*
    |--------------------
    | Profile (own account page)
    |--------------------
    */
    Route::get('/profile', function (Request $request) {
        $user = $request->user();

        $followersCount = method_exists($user, 'followers')
            ? $user->followers()->count()
            : 0;

        $followingCount = method_exists($user, 'following')
            ? $user->following()->count()
            : 0;

        $posts = method_exists($user, 'posts')
            ? $user->posts()->latest()->get()
            : \App\Models\Post::where('user_id', $user->id)->latest()->get();

        return view('profile.edit', compact(
            'user',
            'followersCount',
            'followingCount',
            'posts'
        ));
    })->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';







