<?php

use App\Http\Controllers\Sites\MuxPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sites\NodalsController;
use App\Http\Controllers\Sites\CascadesController;
use App\Http\Controllers\Sites\SuperAdminSitesController;
use App\Http\Controllers\Sites\NormalUsersSitesController;
use App\Http\Controllers\Sites\NoticeController;

Route::prefix('sites')->middleware(['auth:sanctum', "role:super-admin"])->group(function () {
    // Route::get('/newsitesinsert',[SitesController::class,"index"])->name("sites");
    Route::post('/create', [SuperAdminSitesController::class, "siteCreate"])->name("create_site");
    Route::post('/store', [SuperAdminSitesController::class, "store"])->name("store_sites");
    Route::post('/downloadAll', [SuperAdminSitesController::class, "export_all"])->name("export_all");
    Route::get('/cascades', [CascadesController::class, "exportAllCascades"])->name("all_cascades");
    Route::post('/cascades', [CascadesController::class, "importCascades"])->name("import_cascades");
    Route::post('/nodals', [NodalsController::class, "importNodals"])->name("import_nodals");

    Route::post('/updateCascades', [CascadesController::class, "updateCascades"])->name("updateCascades");
    Route::put('/update/{site:site_code}', [SuperAdminSitesController::class, "siteUpdate"])->name("siteUpdate");
});
Route::prefix('sites')->middleware((['auth:sanctum', "role:admin|super-admin"]))->group(function () {
    Route::post('/nodals/download', [NodalsController::class, "exportNodals"]);
});
Route::prefix('sites')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/search/{search}', [NormalUsersSitesController::class, "search"])->name("search_sites");
    Route::get('/details/{siteCode}', [NormalUsersSitesController::class, "siteDetails"])->name("site_details");
    Route::get('/index', [NormalUsersSitesController::class, "index"]);
   
});

Route::prefix('sites')->middleware(['auth:sanctum','permission:view_mux_plan'])->group(function () {
   
    Route::get('/muxPlans',[MuxPlanController::class,'siteMuxPlans']);
});

Route::prefix('site')->middleware(['auth:sanctum','permission:view_site_notices'])->group(function () {
     Route::get('/notices/all',[NoticeController::class,'notices']);
    Route::get('/notices',[NoticeController::class,'siteNotices']);
    Route::get('/noticeTypes',[NoticeController::class,'noticeTypes']);

});

Route::prefix('site')->middleware(['auth:sanctum','permission:create_site_notices'])->group(function () {
   
    Route::post('/notice/create',[NoticeController::class,'create']);
    Route::put('/notice/update',[NoticeController::class,'update']);

});

