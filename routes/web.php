<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HashtagController;

Route::get('/', fn () => redirect()->route('feed'));

Route::middleware('auth')->group(function () {
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');

    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/users/{user}', [ProfileController::class, 'show'])->name('users.show');

    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('follow.destroy');

    Route::get('/hashtags/{hashtag:slug}', [HashtagController::class, 'show'])->name('hashtags.show');
});

