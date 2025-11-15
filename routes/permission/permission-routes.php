<?php

declare(strict_types=1);

use App\Enums\PermissionsEnum;
use App\Http\Controllers\Permission\PermissionController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/permissions',
    'controller' => PermissionController::class,
], function (): void {
    Route::get('/', 'getAllPermissions')
        ->middleware('can:'.PermissionsEnum::VIEW_PERMISSIONS->value);
});
