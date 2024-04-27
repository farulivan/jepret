<?php

namespace App\Providers;

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
        $this->app->singleton(UserServiceInterface::class, UserService::class);
    }
}
