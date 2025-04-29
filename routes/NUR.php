<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NUR\NUR2GController;
use App\Http\Controllers\NUR\NUR3GController;
use App\Http\Controllers\NUR\NUR4GController;
use App\Http\Controllers\NUR\ShowNURController;
use App\Http\Controllers\NUR\NurIndexController;



Route::prefix('Nur')->middleware(['auth:sanctum', 'role:admin|super-admin|NUR_Viewer'])->group(function () {
    // Route::get('/newsitesinsert',[SitesController::class,"index"])->name("sites");
    Route::get('/index', [NurIndexController::class, "index"])->name("Nur_index");
    Route::post('/2G', [NUR2GController::class, "store"])->name("store_2G");
    Route::post('/3G', [NUR3GController::class, "store"])->name("store_3G");
    Route::post('/4G', [NUR4GController::class, "store"])->name("store_4G");
});
// Route::prefix('Nur')->middleware(['auth:sanctum', 'role:NUR_Viewer|admin|super-admin'])->group(function () {
//     Route::post('/downloadNUR2G', [DownloadNURController::class, "NUR2G"])->name("site2GNUR");
//     Route::post('/downloadNUR3G', [DownloadNURController::class, "NUR3G"])->name("site3GNUR");
//     Route::post('/downloadNUR4G', [DownloadNURController::class, "NUR4G"])->name("site4GNUR");
// });
Route::prefix('Nur')->middleware(['auth:sanctum', 'role:admin|super-admin|NUR_Viewer'])->group(function () {
    Route::post('/siteNUR', [ShowNURController::class, "SiteNUR"])->name("siteNUR");
    Route::get('/show/{week}/{year}/{NUR_Type}', [ShowNURController::class, "show_nur"])->name("show_nur");

    Route::get('/vip/week/{zone}/{week}/{year}', [ShowNURController::class, "vipSitesWeeklyNUR"]);
    Route::get('/nodal/week/{zone}/{week}/{year}', [ShowNURController::class, "nodalSitesWeeklyNUR"]);
    Route::get('/cairo/weekly/MWNUR/{week}/{year}', [ShowNURController::class, "cairoMWweeklyNUR"]);
    Route::get('/cairo/weekly/GenNUR/{week}/{year}', [ShowNURController::class, "cairoGenweeklyNUR"]);
    Route::get('/cairo/weekly/PowerNUR/{week}/{year}', [ShowNURController::class, "cairoPowerWeeklyNUR"]);
    Route::get('/cairo/weekly/NodeBNUR/{week}/{year}', [ShowNURController::class, "cairoNodeBWeeklyNUR"]);
    Route::get('/cairo/weekly/ModificationNUR/{week}/{year}', [ShowNURController::class, "cairoModificationWeeklyNUR"]);
    Route::get('/cairo/yearly/NUR_C/{year}', [ShowNURController::class, "cairoYearlyNUR_C"]);
    Route::get('/cairo/yearly/GenNUR/{year}', [ShowNURController::class, "cairoGenYearlyNUR"]);
    Route::get('/cairo/yearly/TXNUR/{year}', [ShowNURController::class, "cairoMWYearlyNUR"]);
    Route::get('/cairo/yearly/NodeBNUR/{year}', [ShowNURController::class, "cairoNodeBYearlyNUR"]);
    Route::get('/cairo/yearly/PowerNUR/{year}', [ShowNURController::class, "cairoPowerYearlyNUR"]);
    Route::get('/cairo/yearly/ModificationNUR/{year}', [ShowNURController::class, "cairoModificationYearlyNUR"]);
});

