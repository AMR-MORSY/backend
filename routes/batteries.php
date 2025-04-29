<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Batteries\BatteriesController;

Route::prefix("batteries")->middleware(["auth:sanctum",'role:instrument_Admin'])->group(function () {
   
    Route::post("/store", [BatteriesController::class, "store"]);
    Route::put("/{battery}", [BatteriesController::class, "update"]);
    Route::delete("/{battery}", [BatteriesController::class, "destroy"]);
});


Route::prefix("batteries")->middleware(["auth:sanctum",'permission:read_Batteries_data'])->group(function () {
    Route::get("/{site}", [BatteriesController::class, "show"]);
   
});

