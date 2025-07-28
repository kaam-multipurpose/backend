<?php

use App\Enum\PermissionsEnum;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'category',
    'controller' => CategoryController::class,
], function () {
    Route::post('/', 'createCategory')
        ->middleware('can:' . PermissionsEnum::ADD_CATEGORY->value);
    Route::get('/', 'getAllCategories')
        ->middleware('can:' . PermissionsEnum::ADD_CATEGORY->value);
    Route::get('/paginated', 'getAllCategoriesPaginated')
        ->middleware('can:' . PermissionsEnum::VIEW_CATEGORY->value);

    Route::group([
        "prefix" => "{category}",
    ], function () {
        Route::patch('/', 'updateCategory')
            ->middleware('can:' . PermissionsEnum::EDIT_CATEGORY->value);
        Route::delete('/', 'deleteCategory')
            ->middleware('can:' . PermissionsEnum::DELETE_CATEGORY->value);
    });
});
