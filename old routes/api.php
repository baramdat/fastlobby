<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\roleController;
use App\Http\Controllers\authController;
use App\Http\Controllers\userController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\CameraAuthenticationController;
use App\Http\Controllers\Integrator\SiteController as IntegratorSiteController;
use App\Http\Controllers\Integrator\BuildingAdminController;
use App\Http\Controllers\BuildingAdmin\userController as buildingUserController;
use App\Http\Controllers\BuildingAdmin\AppointmentController as BuildingAppointment;
use App\Http\Controllers\tenant\tenantEmployeeController;
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



// Authentications
Route::post('/register/user', [authController::class,'registerUser']);
Route::post('login',[authController::class,'login']);
Route::post('forget/password',[authController::class,'submitForgetPasswordForm'])->name('forget.password.post');
Route::post('reset-password', [authController::class, 'submitResetPasswordForm'])->name('reset.password.post');

// profile
Route::post('profile/update',[userController::class,'updateProfile']);
Route::post('change/password', [userController::class, 'ChangePassword'])->name('change-password');
Route::post('change/photo', [userController::class, 'changePhoto']);

// Role 
Route::post('/role/add', [roleController::class,'addRole']); 
Route::get('/role/edit', [roleController::class, 'editRole']);
Route::post('/role/update', [roleController::class, 'updateRole']);
Route::get('/role/delete', [roleController::class, 'deleteRole']);

// user
Route::post('/user/add', [userController::class,'add']);
Route::get('/user/count', [userController::class,'userCount']); 
Route::get('/users', [userController::class,'users']);
Route::post('/user/update', [userController::class,'updateUser']);
Route::delete('/delete/user/{id}', [userController::class,'deleteUser']);

// integrator building admin routes
Route::post('integrator/user/add', [BuildingAdminController::class,'add']);
Route::get('integrator/user/count', [BuildingAdminController::class,'userCount']); 
Route::get('integrator/users', [BuildingAdminController::class,'users']);
Route::post('integrator/user/update', [BuildingAdminController::class,'updateUser']);
Route::delete('integrator/delete/user/{id}', [BuildingAdminController::class,'deleteUser']);

//generate qr for all sites
Route::get('sites/qr/generate', [SiteController::class,'GenerateSitesQr']);

// building admin routes
Route::post('building/user/add', [buildingUserController::class,'add']);
Route::get('building/user/count', [buildingUserController::class,'userCount']); 
Route::get('building/users', [buildingUserController::class,'users']);
Route::post('building/user/update', [buildingUserController::class,'updateUser']);
Route::delete('building/delete/user/{id}', [buildingUserController::class,'deleteUser']);
Route::get('/building/admin/tenant/list', [buildingUserController::class,'tenantsList']);

Route::post('/building/appointment/add',[BuildingAppointment::class,'add']);
Route::get('/building/appointment/count', [BuildingAppointment::class,'count']); 
Route::get('/building/appointment/list', [BuildingAppointment::class,'list']); 

//site
Route::post('/site/add', [SiteController::class,'add']);
Route::get('/site/count', [SiteController::class,'count']); 
Route::get('/site/list', [SiteController::class,'list']);
Route::get('/sites', [SiteController::class,'list']);
Route::post('/site/update', [SiteController::class,'update']);
Route::delete('/site/delete/{id}', [SiteController::class,'delete']);
Route::get('admin/site/visitors/list', [SiteController::class,'adminSiteList']);

//integrator site routes
Route::post('integrator/site/add', [IntegratorSiteController::class,'add']);
Route::get('integrator/site/count', [IntegratorSiteController::class,'count']); 
Route::get('integrator/site/list', [IntegratorSiteController::class,'list']);
Route::get('integrator/sites', [IntegratorSiteController::class,'list']);
Route::post('integrator/site/update', [IntegratorSiteController::class,'update']);
Route::delete('integrator/site/delete/{id}', [IntegratorSiteController::class,'delete']);
Route::get('/admin/site/integrator/list', [IntegratorSiteController::class,'adminIntegratorList']);
Route::delete('/admin/integrator/delete/{id}', [IntegratorSiteController::class,'adminIntegratorDelete']);

// Appointment
Route::post('/appointment/add',[AppointmentController::class,'add']);
Route::get('/appointment/count', [AppointmentController::class,'count']); 
Route::get('/appointment/list', [AppointmentController::class,'list']); 
Route::get('/appointment/status', [AppointmentController::class,'statusList']); 
Route::get('guard/recent/appointments', [AppointmentController::class,'guardRecentAppointment']); 

//external walkin appointment
Route::post('/external/walkin/appointment/add',[AppointmentController::class,'externalAppointmentCreate']);

Route::delete('/appointment/delete/{id}', [AppointmentController::class,'delete']); 
Route::get('/walkin/appointments/list', [AppointmentController::class,'walkinAppointmentList']);

//camera authentications
Route::post('camera/auth/add', [CameraAuthenticationController::class,'authAdd']);


Route::post('/tenant/user/add', [tenantEmployeeController::class,'add']);
Route::get('/tenant/user/count', [tenantEmployeeController::class,'userCount']);
Route::get('/tenant/users', [tenantEmployeeController::class,'users']);
Route::post('/tenant/user/update', [tenantEmployeeController::class,'updateUser']);
Route::delete('/tenant/delete/user/{id}', [tenantEmployeeController::class,'deleteUser']);


Route::post('inform/client',[AppointmentController::class,'informClient']);

Route::get('client/walkin-visitor/count',[AppointmentController::class,'walkinCount']);
Route::get('client/walkin-visitors',[AppointmentController::class,'walkinVisitors']);
Route::post('visitor/request/update',[AppointmentController::class,'ApproveWalkInRequest']);
Route::post('webservice/image-scan',[ScanController::class,'imageScan']);
Route::post('apppointment/create',[ScanController::class,'createAppointment']);


//Message
Route::post('send/message',[ScanController::class,'sendSMS']);

//Camera's list
Route::post('/ChannelList',[CameraAuthenticationController::class,'getChannelList']);
Route::post('camera/video/stream',[CameraAuthenticationController::class,'getCameraVideoStream']);

