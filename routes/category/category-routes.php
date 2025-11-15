<?php

declare(strict_types=1);

use App\Enums\PermissionsEnum;
use App\Http\Controllers\Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group([
    'controller' => CategoryController::class,
    'prefix' => 'categories',
], function (): void {
    Route::post('/', 'addCategory')
        ->can(PermissionsEnum::ADD_CATEGORY);
    Route::get('/', 'getCategories')
        ->can(PermissionsEnum::VIEW_CATEGORY);

    Route::group([
        'prefix' => '{category:slug}',
    ], function (): void {
        Route::get('/', 'getCategory')
            ->can(PermissionsEnum::VIEW_CATEGORY);
        Route::post('/', 'addSubCategory')
            ->can(PermissionsEnum::ADD_CATEGORY);
    });
});
