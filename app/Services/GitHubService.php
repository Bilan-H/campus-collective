<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService
{
    public function getUser(string $username)
    {
        return Http::get("https://api.github.com/users/{$username}")->json();
    }

    public function getRepo(string $fullName)
    {
        return Http::get("https://api.github.com/repos/{$fullName}")->json();
    }
}
