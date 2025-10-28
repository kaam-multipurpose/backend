<?php

use App\Enum\PermissionsEnum;
use App\Http\Controllers\VariantType\VariantTypeController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'variant-types',
    'controller' => VariantTypeController::class,
], function (): void {
    Route::post('/', 'addVariantType')->can(PermissionsEnum::ADD_PRODUCT);

    Route::get('/', 'getVariantTypes')->can(PermissionsEnum::ADD_PRODUCT);

    Route::group([
        'prefix' => '/{variantType:slug}',
    ], function (): void {
        Route::post('/', 'addValuesToVariantType')->can(PermissionsEnum::ADD_PRODUCT);

        Route::delete('/', 'deleteVariantType')->can(PermissionsEnum::DELETE_PRODUCT);

        Route::delete('/{variantTypeValue:slug}', 'deleteVariantTypeValue')->can(PermissionsEnum::DELETE_PRODUCT);
    });

});
