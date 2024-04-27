<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\User\Eloquent\UserRepository;
use App\Repositories\User\Eloquent\UserRepositoryInterface;
use App\Repositories\Token\Sanctum\TokenRepository;
use App\Repositories\Token\TokenRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TokenRepositoryInterface::class, TokenRepository::class);
    }
}
