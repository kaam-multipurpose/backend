<?php

namespace App\Providers;

use App\Enum\UserRolesEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();
        Gate::before(fn ($user) => $user->hasRole(UserRolesEnum::SUPER_ADMIN->value) ? true : null);
    }
}
