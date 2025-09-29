<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/./auth/auth-routes.php';

Route::group([
    'middleware' => 'auth:sanctum',
], function (): void {
    require __DIR__.'/./permission/permission-routes.php';
});
