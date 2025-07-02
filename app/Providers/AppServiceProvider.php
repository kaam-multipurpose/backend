<?php

namespace App\Providers;

use App\Enum\UserRolesEnum;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\Contracts\AuthServiceContract;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(AuthServiceContract::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
           return $user->hasRole(UserRolesEnum::SUPER_ADMIN->value)?true:null;
        });
    }
}
