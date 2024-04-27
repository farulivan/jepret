<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Services\AuthServices\AuthService;
use App\Services\AuthServices\AuthServiceInterface;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
    }
}
