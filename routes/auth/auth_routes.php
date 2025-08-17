<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'controller' => AuthController::class,
], function () {
    Route::post('/login', 'login')->name('login');
    Route::delete('/logout', 'logout')->name('logout')
        ->middleware('auth:sanctum');
    Route::get('/refresh-token', 'refreshToken')->middleware('throttle:5,2');
});
