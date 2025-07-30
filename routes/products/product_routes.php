<?php

use App\Enum\PermissionsEnum;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'product',
    'controller' => ProductController::class,
], function () {
    Route::post('/', 'createProduct')
        ->middleware('can:' . PermissionsEnum::ADD_PRODUCT->value);

});
