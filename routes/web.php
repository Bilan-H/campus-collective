<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\HashtagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('feed.index')
        : redirect()->route('login');
});

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
    // Profile 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Likes
    Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/posts/{post}/like', [LikeController::class, 'destroy'])->name('likes.destroy');

   Route::get('/notifications', function () {
    return view('notifications.index', [
        'notifications' => auth()->user()->notifications()->latest()->get(),
    ]);
})->name('notifications.index');

Route::post('/notifications/{id}/read', function ($id) {
    $n = auth()->user()->notifications()->where('id', $id)->firstOrFail();
    $n->markAsRead();
    return back();
})->name('notifications.read');

Route::post('/notifications/read-all', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.readAll');
 
Route::get('/tags/{slug}', [HashtagController::class, 'show'])->name('tags.show');

});

require __DIR__.'/auth.php';







