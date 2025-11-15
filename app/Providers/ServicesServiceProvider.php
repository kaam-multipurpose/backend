<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Auth\AuthService;
use App\Services\Category\CategoryService;
use App\Services\Contracts\AuthServiceContract;
use App\Services\Contracts\CategoryServiceContract;
use App\Services\Contracts\PasswordServiceContract;
use App\Services\Contracts\PermissionServiceContract;
use App\Services\Contracts\RoleServiceContract;
use App\Services\Contracts\UnitServiceContract;
use App\Services\Contracts\VariantTypeServiceContract;
use App\Services\Password\PasswordService;
use App\Services\Permission\PermissionService;
use App\Services\Permission\RoleService;
use App\Services\Unit\UnitService;
use App\Services\VariantType\VariantTypeService;
use Illuminate\Support\ServiceProvider;
use Override;

final class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->bind(AuthServiceContract::class, AuthService::class);
        $this->app->bind(PermissionServiceContract::class, PermissionService::class);
        $this->app->bind(RoleServiceContract::class, RoleService::class);
        $this->app->bind(PasswordServiceContract::class, PasswordService::class);
        $this->app->bind(VariantTypeServiceContract::class, VariantTypeService::class);
        $this->app->bind(CategoryServiceContract::class, CategoryService::class);
        $this->app->bind(UnitServiceContract::class, UnitService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
