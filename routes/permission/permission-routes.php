<?php

use App\Enum\PermissionsEnum;
use App\Http\Controllers\Permission\PermissionController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/permissions',
    'controller' => PermissionController::class,
], function () {
    Route::get('/', 'getAllPermissions')
        ->middleware('can:'.PermissionsEnum::VIEW_PERMISSIONS->value);
});
