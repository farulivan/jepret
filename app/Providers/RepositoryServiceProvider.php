<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\User\Eloquent\UserRepository;
use App\Repositories\User\Eloquent\UserRepositoryInterface;

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
    }
}
