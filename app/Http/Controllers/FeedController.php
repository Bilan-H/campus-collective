<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\GitHubService;

class FeedController extends Controller
{
    public function index(GitHubService $github)
{
    $posts = Post::with(['user', 'comments.user', 'likes'])
        ->latest()
        ->paginate(10);

    $githubUser = $github->getUser('laravel');
    $githubRepo = $github->getRepo('laravel/framework');

    return view('feed', compact('posts', 'githubUser', 'githubRepo'));
}

}


