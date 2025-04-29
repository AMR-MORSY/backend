<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\LogoutController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\Admin\ActivitiesController;
use App\Http\Controllers\Transmission\WANController;
use App\Http\Controllers\Transmission\XPICController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\Transmission\IP_traffic_Controller;
use App\Http\Controllers\Modifications\ModificationsController;
use App\Http\Controllers\Transmission\All_TX_ActionsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
// */

require __DIR__ . "/modifications.php";
require __DIR__ . "/sites.php";
require __DIR__ . "/NUR.php";
require __DIR__ ."/energy.php";
require __DIR__ ."/batteries.php";
require __DIR__ ."/instruments.php";

Route::prefix("postman")->group(function () {
    Route::get("/getPostman", [ModificationsController::class, "testPostMan"]);
});
Route::prefix("user")->middleware(['auth:sanctum'])->group(function () {
    Route::get('/notifications/read/all', [LoginController::class, 'allNotificationsAsRead']);
    Route::get('/notifications/delete/all', [LoginController::class, 'deleteAllNotification']);
    Route::post('/logout', [LogoutController::class, "logout"]);
    Route::get('/notifications', [LoginController::class, 'notifications']);
    Route::get('/notifications/read/{notification}', [LoginController::class, 'markNotificationAsRead']);

    Route::get('/notifications/delete/{notification}', [LoginController::class, 'deleteNotification']);
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
    Route::post('/public/notification', [AdminController::class, 'sendPublicNotification']);
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
