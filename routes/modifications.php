<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Prices\PriceController;
use App\Http\Controllers\Invoices\InvoicesController;
use App\Http\Controllers\Quotations\QuotationController;
use App\Http\Controllers\Modifications\ModificationsController;
use App\Http\Controllers\Modifications\ModificationsDashboardController;

/////////////////////////////////these routes are handled by policies///////////////////////////////////////////////////
Route::prefix('modifications')->middleware(['auth:sanctum'])->group(function () {
    Route::get("/analysis", [ModificationsController::class, "analysis"])->name("analysis");
    Route::get("/index/{columnName}/{columnValue}", [ModificationsController::class, "index"])->name("index");

    Route::get("/siteModifications/{site_code}", [ModificationsController::class, "siteModifications"])->name("siteModifications");
    Route::get("/details/{modification}", [ModificationsController::class, "modificationDetails"])->name("details");


    Route::post("/download", [ModificationsController::class, "download"])->name("download_modification");

    Route::post("/delete", [ModificationsController::class, "deleteModification"])->name("delete_modification");
    Route::put("/update/{modification}", [ModificationsController::class, "modificationUpdate"])->name("modification_update");
    Route::post("/new", [ModificationsController::class, "newModification"])->name("new_modification");
    Route::get("/filterdates/{date_type}/{from_date?}/{to_date?}", [ModificationsController::class, "modificationsFilteredByDate"]);
    Route::get("/wo/{wo_code}", [ModificationsController::class, "searchModificationsByWO"]);
    Route::post("/report", [ModificationsController::class, "reportModifications"]);
    Route::post('/invoice/store', [InvoicesController::class, 'store']);
    Route::get('/invoice/{invoice}', [InvoicesController::class, 'view']);
    Route::get('/years', [ModificationsDashboardController::class, 'years']);
    Route::get('/dashboard/{year}',[ModificationsDashboardController::class,"dashboard"]);

    Route::get('/without-quotation',[ModificationsController::class,"modificationsWithoutQuotation"]);
    Route::get('/unreported',[ModificationsController::class,"unreportedModifications"]);
    Route::get('/check',[ModificationsController::class,"checkModificationQuotation"]);

});

Route::prefix("priceList")->middleware(["auth:sanctum"])->group(function () {
    Route::post('search', [PriceController::class, "search"]);
});

Route::prefix("quotation")->middleware(["auth:sanctum"])->group(function () {

    Route::get("/modification/{modification}", [QuotationController::class, "findQuotationBelongsToModification"]);
    Route::post("/upload", [QuotationController::class, 'uploadQuotation']);
    // Route::put('/modification/newpricelistitems/{modification}',[QuotationController::class,"addNewQuotationPriceListItems"]);
    // Route::put('/modification/newmailitems/{modification}',[QuotationController::class,"addNewQuotationMailItems"]);
    Route::get("/mailprices/index", [QuotationController::class, "mailPricesIndex"]);
    Route::put('/mailprices/{modification}/{quotation_id?}', [QuotationController::class, "insertMailPricesItems"]);
    Route::put('/priceList/{modification}/{quotation_id?}', [QuotationController::class, "insertPriceListItems"]);
    Route::put('/priceList/delete/items/{modification}/{quotation}', [QuotationController::class, "deletePriceListItems"]);
    Route::put('/mailList/delete/items/{modification}/{quotation}', [QuotationController::class, "deleteMailListItems"]);
});

