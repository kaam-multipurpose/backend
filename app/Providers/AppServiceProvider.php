<?php

namespace App\Providers;

use App\Enum\UserRolesEnum;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Logger\Logger;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoggerContract::class, Logger::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user) {
            return $user->hasRole(UserRolesEnum::SUPER_ADMIN->value) ? true : null;
        });
    }
}
