<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\CategoryService;
use App\Services\Contracts\AuthServiceContract;
use App\Services\Contracts\CategoryServiceContract;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceContract::class, AuthService::class);
        $this->app->bind(CategoryServiceContract::class, CategoryService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
