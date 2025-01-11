<?php

use App\Http\Controllers\Admin\ActivitiesController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Batteries\BatteriesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NUR\NUR2GController;
use App\Http\Controllers\NUR\NUR3GController;
use App\Http\Controllers\NUR\NUR4GController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\NUR\ShowNURController;
use App\Http\Controllers\User\LogoutController;
use App\Http\Controllers\NUR\NurIndexController;
use App\Http\Controllers\Sites\NodalsController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\Sites\CascadesController;
use App\Http\Controllers\NUR\DownloadNURController;
use App\Http\Controllers\Transmission\XPICController;
use App\Http\Controllers\EnergySheet\EnergyController;
use App\Http\Controllers\User\ResetPasswordController;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use App\Http\Controllers\Sites\SuperAdminSitesController;
use App\Http\Controllers\Sites\NormalUsersSitesController;
use App\Http\Controllers\Instruments\InstrumentsController;
use App\Http\Controllers\Modifications\ModificationsController;
use App\Http\Controllers\EnergySheet\EnergyStatesticsController;
use App\Http\Controllers\EnergySheet\EnergySiteStatesticsController;
use App\Http\Controllers\EnergySheet\EnergyZoneStatesticsController;
use App\Http\Controllers\Prices\PriceController;
use App\Http\Controllers\Quotations\QuotationController;
use App\Http\Controllers\Transmission\All_TX_ActionsController;
use App\Http\Controllers\Transmission\WANController;
use App\Http\Controllers\Transmission\IP_traffic_Controller;
use Maatwebsite\Excel\Row;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("postman")->group(function () {
    Route::get("/getPostman", [ModificationsController::class, "testPostMan"]);
});
Route::prefix("user")->middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [LogoutController::class, "logout"]);
});
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/abilities', [AdminController::class, "userAbilities"]);
});

Route::prefix('admin')->group(function () {
    Route::post('/signUp', [AdminController::class, "adminLogin"]);
    // Route::get('/abilities',[AdminController::class,"userAbilities"]);

});
Route::prefix('activities')->middleware(['auth:sanctum', "role:super-admin"])->group(function () {
    Route::get('/modifications', [ActivitiesController::class, "modificationsActivities"]);
    Route::get('/modifications/{id}', [ActivitiesController::class, "modificationActivityData"]);
    Route::get('/wans', [ActivitiesController::class, "wanActivities"]);
    Route::get('/XPICS', [ActivitiesController::class, "XPICActivities"]);
    Route::get('/IPS', [ActivitiesController::class, "IPActivities"]);
    Route::get('/transmissions/{id}', [ActivitiesController::class, "transmissionActivityData"]);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin|super-admin'])->group(function () {
    Route::post('/logout', [AdminController::class, "logout"]);

    Route::get('/users', [AdminController::class, "users"]);
    Route::get('/user/{id}', [AdminController::class, "user"]);
    Route::get('/userwithrolesandpermissions/{id}', [AdminController::class, "userDataWithRolesAndPermissions"]);
    Route::get('/roles', [AdminController::class, "roles"]);
    Route::get('/role/{id}', [AdminController::class, "viewRole"]);
    Route::get('/role/edit/{id}', [AdminController::class, "editRole"]);
    Route::post('/role/update', [AdminController::class, "updateRole"]);
    Route::get('/permissions', [AdminController::class, "permissions"]);
    Route::get('/permissions/delete/{id}', [AdminController::class, "deletePermission"]);
    Route::post('/role/create', [AdminController::class, "createRole"]);
    Route::post('/roles/permissions', [AdminController::class, "getRolesPermissionsByRoleName"]);
    Route::post('/permissions/create', [AdminController::class, "createPermission"]);
    Route::post('/user/role/update', [AdminController::class, "updateUserRoles"]);
});

Route::prefix("user")->group(function () {
    Route::post("/register", [RegisterController::class, "register"]);
    Route::get("/signUp/{code}", [RegisterController::class, "validateSignUpCode"]);
    Route::post("/activateUserAccount", [RegisterController::class, "activateUserAccount"]);
    Route::post("/login", [LoginController::class, "login"]);
    Route::post("/sendToken", [ResetPasswordController::class, "sendToken"]);
    Route::post("/validateToken", [ResetPasswordController::class, "validateToken"]);
    Route::post("/resetPassword", [ResetPasswordController::class, "resetPassword"]);
    // Route::post("refreshtoken",[LoginController::class,"refresh_token"]);
});


Route::prefix("energysheet")->middleware(['auth:sanctum', "role:admin|super-admin"])->group(function () {
    Route::get('/index', [EnergyController::class, "index"])->name("energysheet_index");
    Route::post('/index', [EnergyController::class, "store_alarms"])->name("energysheet_store_alarms");
});
// ,'role:Modification_Admin|Modification_Viewer|admin|super-admin'
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
    Route::post("/report", [ModificationsController::class, "reportModifications"]);
});

Route::prefix("priceList")->middleware(["auth:sanctum"])->group(function () {
    Route::post('search', [PriceController::class, "search"]);
});

Route::prefix("quotation")->middleware(["auth:sanctum"])->group(function(){

Route::get("/modification/{modification}",[QuotationController::class,"findQuotationBelongsToModification"]);
Route::post ("/upload",[QuotationController::class,'uploadQuotation']);
// Route::put('/modification/newpricelistitems/{modification}',[QuotationController::class,"addNewQuotationPriceListItems"]);
// Route::put('/modification/newmailitems/{modification}',[QuotationController::class,"addNewQuotationMailItems"]);
Route::get("/mailprices/index",[QuotationController::class,"mailPricesIndex"]);
Route::put('/mailprices/{modification}/{quotation_id?}',[QuotationController::class,"insertMailPricesItems"]);
Route::put('/priceList/{modification}/{quotation_id?}',[QuotationController::class,"insertPriceListItems"]);
Route::put('/priceList/delete/items/{modification}/{quotation}',[QuotationController::class,"deletePriceListItems"]);
Route::put('/mailList/delete/items/{modification}/{quotation}',[QuotationController::class,"deleteMailListItems"]);
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
});
Route::prefix('Nur')->middleware(['auth:sanctum', 'role:admin|super-admin|NUR_Viewer'])->group(function () {
    // Route::get('/newsitesinsert',[SitesController::class,"index"])->name("sites");
    Route::get('/index', [NurIndexController::class, "index"])->name("Nur_index");
    Route::post('/2G', [NUR2GController::class, "store"])->name("store_2G");
    Route::post('/3G', [NUR3GController::class, "store"])->name("store_3G");
    Route::post('/4G', [NUR4GController::class, "store"])->name("store_4G");
});
Route::prefix('Nur')->middleware(['auth:sanctum', 'role:NUR_Viewer|admin|super-admin'])->group(function () {
    Route::post('/downloadNUR2G', [DownloadNURController::class, "NUR2G"])->name("site2GNUR");
    Route::post('/downloadNUR3G', [DownloadNURController::class, "NUR3G"])->name("site3GNUR");
    Route::post('/downloadNUR4G', [DownloadNURController::class, "NUR4G"])->name("site4GNUR");
});
Route::prefix('Nur')->middleware(['auth:sanctum', 'role:NUR_Viewer|admin|super-admin'])->group(function () {
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


Route::prefix("instruments")->middleware(['auth:sanctum', 'role:instrument_viewer|admin|super-admin'])->group(function () {
    Route::post('/siteBatteriesData', [InstrumentsController::class, "siteBatteriesData"]);

    Route::post('/siteRectifierData', [InstrumentsController::class, "siteRectifierData"]);
    Route::post('/siteDeepData', [InstrumentsController::class, "siteDeepData"]);
    Route::post('/siteMWData', [InstrumentsController::class, "siteMWData"]);
    Route::post('/siteBTSData', [InstrumentsController::class, "siteBTSData"]);
    Route::post('/sitePowerData', [InstrumentsController::class, "sitePowerData"]);
});

Route::prefix("instruments")->middleware((["auth:sanctum", "role:admin|super-admin"]))->group(function () {
    Route::post('/updateMWData', [InstrumentsController::class, "updateMWData"]);
    Route::post('/updateBatteriesData', [InstrumentsController::class, "updateSiteBatteriesData"]);
    Route::post('/updateRectifierData', [InstrumentsController::class, "updateRectifierData"]);
    Route::post('/insertRectifierData', [InstrumentsController::class, "insertRectifierData"]);
    Route::post('/updateSiteDeepData', [InstrumentsController::class, "updateSiteDeepData"]);
    Route::post('/insertSiteDeepData', [InstrumentsController::class, "insertSiteDeepData"]);
    Route::post('/updateSiteBTSData', [InstrumentsController::class, "updateSiteBTSData"]);
    Route::post('/updateSitePowerData', [InstrumentsController::class, "updateSitePowerData"]);
});
Route::prefix('sites')->group(function () {
    Route::get('/index', [NormalUsersSitesController::class, "index"]);
});

Route::prefix("Transmission")->middleware(["auth:sanctum", "role:admin|super-admin"])->group(function () {

    Route::get("/getSiteTXIssues/{site_code}", [All_TX_ActionsController::class, "getSiteTXIssues"]);
    Route::get("/searchTxIssues/{fromDate}/{toDate}/{issue}", [All_TX_ActionsController::class, "searchTxIssues"]);
});
Route::prefix("Transmission")->middleware(["auth:sanctum", "role:admin|super-admin"])->group(function () {
    Route::post("/updateSiteIP_trafics", [IP_traffic_Controller::class, "updateSiteIP_trafics"]);
    Route::post("/updateSiteXPICs", [XPICController::class, "updateSiteXPICs"]);
    Route::post("/updateSiteWAN", [WANController::class, "updateSiteWAN"]);
    Route::post("/storeSiteIP_trafic", [IP_traffic_Controller::class, "storeSiteIP_trafic"]);
    Route::post("/storeSiteWAN", [WANController::class, "storeSiteWAN"]);
    Route::post("/storeSiteXPICs", [XPICController::class, "storeSiteXPICs"]);
});

Route::prefix("batteries")->middleware(["auth:sanctum"])->group(function () {
    Route::get("/{site}", [BatteriesController::class, "show"]);
    Route::post("/store", [BatteriesController::class, "store"]);
    Route::put("/{battery}", [BatteriesController::class, "update"]);
    Route::delete("/{battery}", [BatteriesController::class, "destroy"]);
});
