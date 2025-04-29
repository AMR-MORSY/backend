<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnergySheet\EnergyController;
use App\Http\Controllers\EnergySheet\EnergyStatesticsController;
use App\Http\Controllers\EnergySheet\EnergySiteStatesticsController;
use App\Http\Controllers\EnergySheet\EnergyZoneStatesticsController;


Route::prefix("energysheet")->middleware(['auth:sanctum', "role:admin|super-admin"])->group(function () {

    Route::post('/index', [EnergyController::class, "store_alarms"])->name("energysheet_store_alarms");
});


Route::prefix("energysheet")->middleware(['auth:sanctum'])->group(function () {

    Route::post("/alarms", [EnergyStatesticsController::class, "siteAlarms"]);

    Route::get('/statestics/{week}/{year}', [EnergyStatesticsController::class, "statestics"]);
    Route::get('/zoneSitesReportedDownAlarms/{zone}/{week}/{year}', [EnergyZoneStatesticsController::class, "zonesSitesReportedDownAlarms"]);
    Route::get('/zoneDownSitesAfterPowerAlarm/{zone}/{week}/{year}', [EnergyZoneStatesticsController::class, "zoneDownSitesAfterPowerAlarm"]);
    Route::get('/zoneSitesDownWithoutPowerAlarms/{zone}/{week}/{year}', [EnergyZoneStatesticsController::class, "zoneSitesDownWithoutPowerAlarms"]);
    Route::post("/sitePowerAlarms", [EnergySiteStatesticsController::class, "sitePowerAlarms"]);
    Route::post("/siteHighTempAlarms", [EnergySiteStatesticsController::class, "siteHighTempAlarms"]);
    Route::post("/siteBatteriesHealth", [EnergySiteStatesticsController::class, "siteBatteriesHealth"]);
    Route::post("/siteDownAlarmsGroupedByWeek", [EnergySiteStatesticsController::class, "siteDownAlarmsGroupedByWeek"]);
    Route::post("/siteGenAlarms", [EnergySiteStatesticsController::class, "siteGenAlarms"]);
    Route::post("/downloadSitePowerAlarms", [EnergySiteStatesticsController::class, "downloadSitePowerAlarms"]);
    Route::post("/downloadSiteHighTempAlarms", [EnergySiteStatesticsController::class, "downloadSiteHighTempAlarms"]);
    Route::post("/downloadSiteGenAlarms", [EnergySiteStatesticsController::class, "downloadSiteGenAlarms"]);
    Route::post("/downloadZoneHTAlarms", [EnergyZoneStatesticsController::class, "downloadZoneHTAlarms"]);
});

