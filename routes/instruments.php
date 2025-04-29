<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Instruments\InstrumentsController;

Route::prefix("instruments")->middleware(['auth:sanctum', 'permission:read_Instrument_data'])->group(function () {
    // Route::post('/siteBatteriesData', [InstrumentsController::class, "siteBatteriesData"]);

    Route::post('/siteRectifierData', [InstrumentsController::class, "siteRectifierData"]);
    Route::post('/siteDeepData', [InstrumentsController::class, "siteDeepData"]);
    Route::post('/siteMWData', [InstrumentsController::class, "siteMWData"]);
    Route::post('/siteBTSData', [InstrumentsController::class, "siteBTSData"]);
    Route::post('/sitePowerData', [InstrumentsController::class, "sitePowerData"]);
});

Route::prefix("instruments")->middleware((["auth:sanctum", 'permission:create_Instrument_data|update_Instrument_data|']))->group(function () {
    Route::post('/updateMWData', [InstrumentsController::class, "updateMWData"]);
    Route::post('/insertMWData', [InstrumentsController::class, "insertMWData"]);
    // Route::post('/updateBatteriesData', [InstrumentsController::class, "updateSiteBatteriesData"]);
    Route::post('/updateRectifierData', [InstrumentsController::class, "updateRectifierData"]);
    Route::post('/insertRectifierData', [InstrumentsController::class, "insertRectifierData"]);
    Route::post('/updateSiteDeepData', [InstrumentsController::class, "updateSiteDeepData"]);
    Route::post('/insertSiteDeepData', [InstrumentsController::class, "insertSiteDeepData"]);
    Route::post('/updateSiteBTSData', [InstrumentsController::class, "updateSiteBTSData"]);
    Route::post('/insertSiteBTSData', [InstrumentsController::class, "insertSiteBTSData"]);
    Route::post('/updateSitePowerData', [InstrumentsController::class, "updateSitePowerData"]);
    Route::post('/insertSitePowerData', [InstrumentsController::class, "insertSitePowerData"]);
});