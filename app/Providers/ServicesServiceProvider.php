<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\CategoryService;
use App\Services\Contracts\AuthServiceContract;
use App\Services\Contracts\CategoryServiceContract;
use App\Services\Contracts\ProductServiceContract;
use App\Services\Contracts\ProductUnitServiceContract;
use App\Services\Contracts\VariantServiceContract;
use App\Services\ProductService;
use App\Services\ProductUnitService;
use App\Services\VariantService;
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
        $this->app->bind(ProductServiceContract::class, ProductService::class);
        $this->app->bind(VariantServiceContract::class, VariantService::class);
        $this->app->bind(ProductUnitServiceContract::class, ProductUnitService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
