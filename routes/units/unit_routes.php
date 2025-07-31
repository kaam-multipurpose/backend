<?php

use App\Enum\PermissionsEnum;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'unit',
    "controller" => UnitController::class,
], function () {
    Route::post("/", "createUnit")
        ->middleware("can:" . PermissionsEnum::ADD_UNIT->value);
});
