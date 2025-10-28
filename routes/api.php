<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/./auth/auth-routes.php';
require __DIR__.'/./password/password-routes.php';
Route::group([
    'middleware' => 'auth:sanctum',
], function (): void {
    require __DIR__.'/./permission/permission-routes.php';
    require __DIR__.'/./permission/role-routes.php';
    require __DIR__.'/./variant-type/variant-type-routes.php';
});
