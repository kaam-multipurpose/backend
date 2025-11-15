<?php

declare(strict_types=1);

use App\Enums\PermissionsEnum;
use App\Http\Controllers\Permission\RoleController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/roles',
    'controller' => RoleController::class,
], function (): void {
    Route::post('/', 'addRole')
        ->middleware('can:'.PermissionsEnum::ADD_ROLE->value);
    Route::patch('/{role:slug}', 'editRolePermission')
        ->middleware('can:'.PermissionsEnum::EDIT_ROLE->value);
    Route::delete('/{role:slug}', 'deleteRole')
        ->middleware('can:'.PermissionsEnum::DELETE_ROLE->value);
    Route::get('/', 'getRoles')
        ->middleware('can:'.PermissionsEnum::VIEW_ROLE->value);
    Route::get('/{role:slug}', 'getRole')
        ->middleware('can:'.PermissionsEnum::VIEW_ROLE->value);
});
