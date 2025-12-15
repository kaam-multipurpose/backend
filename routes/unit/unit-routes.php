<?php

use App\Enums\PermissionsEnum;
use App\Http\Controllers\Unit\UnitController;
use Illuminate\Support\Facades\Route;

Route::group([
    "prefix" => "/units",
    "controller" => UnitController::class,
], function () {
    Route::post("/", "addUnit")
        ->can(PermissionsEnum::ADD_UNIT);

    Route::get("/", "getUnits")
        ->can(PermissionsEnum::VIEW_UNIT);
    Route::group([
        "prefix" => "/{unit}",
    ], function () {
        Route::patch("/", "updateUnit")->can(PermissionsEnum::EDIT_UNIT);

        Route::delete("/", "deleteUnit")->can(PermissionsEnum::DELETE_UNIT);
    });
});