<?php

declare(strict_types=1);

use App\Http\Controllers\Password\PasswordController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'password',
    'controller' => PasswordController::class,
], function (): void {
    Route::post('/forgot', [PasswordController::class, 'forgetPassword'])
        ->middleware('throttle:3,10');

    Route::post('/reset/{email}/{token}', [PasswordController::class, 'resetPassword']);

    Route::patch('/change/{user}', [PasswordController::class, 'changePassword'])
        ->middleware('auth:sanctum')
        ->middleware('throttle:8,10');
});
