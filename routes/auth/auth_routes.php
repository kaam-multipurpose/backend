<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'controller' => AuthController::class,
], function () {
    Route::post('/login', 'login')->name('login');
});
