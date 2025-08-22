<?php

namespace App\Providers;

use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Service\IAuthService;
use App\Interfaces\Service\IUserService;
use App\Repositories\UserRepository;
use App\Service\AuthService;
use App\Service\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IUserService::class, UserService::class);

        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
