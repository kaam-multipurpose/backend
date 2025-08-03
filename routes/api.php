<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/./auth/auth_routes.php';

Route::group([
    'middleware' => 'auth:sanctum',
], function () {
});
