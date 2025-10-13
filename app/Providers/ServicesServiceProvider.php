<?php

namespace App\Providers;

use App\Services\Auth\AuthService;
use App\Services\Contracts\AuthServiceContract;
use App\Services\Contracts\PasswordServiceContract;
use App\Services\Contracts\PermissionServiceContract;
use App\Services\Contracts\RoleServiceContract;
use App\Services\Password\PasswordService;
use App\Services\Permission\PermissionService;
use App\Services\Permission\RoleService;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[\Override]
    public function register(): void
    {
        $this->app->bind(AuthServiceContract::class, AuthService::class);
        $this->app->bind(PermissionServiceContract::class, PermissionService::class);
        $this->app->bind(RoleServiceContract::class, RoleService::class);
        $this->app->bind(PasswordServiceContract::class, PasswordService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
