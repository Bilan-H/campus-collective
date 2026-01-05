<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GitHubService;

class ApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GitHubService::class, function () {
            return new GitHubService();
        });
    }

    public function boot(): void
    {
        //
    }
}
