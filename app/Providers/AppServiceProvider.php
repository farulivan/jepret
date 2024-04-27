<?php

namespace App\Providers;

use App\Repositories\Posts\Eloquent\PostRepository;
use App\Repositories\Posts\PostRepositoryInterface;
use App\Services\PostServices\PostService;
use App\Services\PostServices\PostServiceInterface;
use App\Services\UserServices\UserService;
use App\Services\UserServices\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->singleton(UserServiceInterface::class, UserService::class);
    }
}
