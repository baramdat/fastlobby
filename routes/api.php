<?php



use Illuminate\Http\Request;

// use App\Http\Controllers\QrCodeType;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VideoContent;

use App\Http\Controllers\authController;

use App\Http\Controllers\chatController;

use App\Http\Controllers\roleController;

use App\Http\Controllers\ScanController;

use App\Http\Controllers\SiteController;

use App\Http\Controllers\userController;

use App\Http\Controllers\LockerController;

use App\Http\Controllers\PickupController;

use App\Http\Controllers\ScreenController;

use App\Http\Controllers\WayfinderController;

use App\Http\Controllers\QrCodeTypeController;

use App\Http\Controllers\VideoRoomsController;

use App\Http\Controllers\AppointmentController;

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\tenantEmployeeController;
use App\Http\Controllers\CameraAuthenticationController;
use App\Http\Controllers\Integrator\BuildingAdminController;
use App\Http\Controllers\Integrator\IntegratorDoorController;
use App\Http\Controllers\BuildingAdmin\buildingDoorController;
use App\Http\Controllers\Integrator\SiteController as IntegratorSiteController;
use App\Http\Controllers\BuildingAdmin\userController as buildingUserController;
use App\Http\Controllers\BuildingAdmin\AppointmentController as BuildingAppointment;

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

// qr code add
Route::post('/qr/code/type/add', [QrCodeTypeController::class,'add']);
Route::get('/qr/code/type/count', [QrCodeTypeController::class,'qrCodeCount']); 
Route::get('/qr/code/list', [QrCodeTypeController::class,'list']);
Route::delete('/delete/qr/code/type/{id}', [QrCodeTypeController::class,'deleteQrCode']);

Route::get('/site/qr/count', [QrCodeTypeController::class,'siteQrCount']);
Route::get('/qr/code/genrate/list', [QrCodeTypeController::class, 'generateList']);
Route::delete('/site/qr/delete/{id}', [QrCodeTypeController::class,'deleteSiteQrCode']);
Route::post('/generate/site/qr/{id}', [QrCodeTypeController::class,'regenerateQrCode']);
Route::post('/add/site/qr/code', [QrCodeTypeController::class,'addSiteQrCode']);


// add screen
Route::post('/add/screens', [ScreenController::class,'add']);
Route::get('/screen/count', [ScreenController::class,'screenCount']);
Route::get('/screen/list', [ScreenController::class,'screenList']);
Route::delete('/screen/delete/{id}', [ScreenController::class,'deleteScreen']);
Route::post('/update/screens', [ScreenController::class,'updateScreen']);

// way finder
Route::post('/add/wayfinder/picture', [WayfinderController::class,'index']);








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

//dshboard building appointment routes 

Route::get('/building/appointments/list', [BuildingAppointment::class,'dashbaordAppointmentList']); 

Route::post('/building/door/add',[buildingDoorController::class,'add']);

Route::get('/building/door/count', [buildingDoorController::class,'count']);

Route::get('/building/door/list', [buildingDoorController::class,'list']); 

Route::delete('/building/door/delete/{id}', [buildingDoorController::class,'delete']);
Route::post('/integrator/relay/state/update',[IntegratorDoorController::class,'relayStateUpdate']);
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

Route::post('/integrator/site/update', [IntegratorSiteController::class,'update']);

Route::delete('integrator/site/delete/{id}', [IntegratorSiteController::class,'delete']);

Route::get('/admin/site/integrator/list', [IntegratorSiteController::class,'adminIntegratorList']);

Route::delete('/admin/integrator/delete/{id}', [IntegratorSiteController::class,'adminIntegratorDelete']);

//integrator sites at dashbaord

Route::get('/integrator/sites/list', [IntegratorSiteController::class,'integratorDashbaordSitesList']);

//integrator door routes

Route::post('/integrator/door/add', [IntegratorDoorController::class,'add']);

Route::get('/integrator/door/count', [IntegratorDoorController::class,'count']);

Route::get('/integrator/door/list', [IntegratorDoorController::class,'list']);

Route::post('/integrator/door/update', [IntegratorDoorController::class,'update']);

Route::delete('/integrator/door/delete/{id}', [IntegratorDoorController::class,'delete']);

Route::get('integrator/site/urls/show', [IntegratorDoorController::class,'SiteUrls']);
Route::get('door/access/update/{user}', [IntegratorDoorController::class,'doorAccessUpdate']);


// Appointment

Route::post('/appointment/add',[AppointmentController::class,'add']);

Route::get('/appointment/count', [AppointmentController::class,'count']); 

Route::get('/appointment/list', [AppointmentController::class,'list']); 

Route::get('/appointment/status', [AppointmentController::class,'statusList']); 
Route::get('/appointment/checked_in/history/{id}', [AppointmentController::class,'checkInHistory']); 


Route::get('guard/recent/appointments', [AppointmentController::class,'guardRecentAppointment']); 



//external walkin appointment

Route::post('/external/walkin/appointment/add',[AppointmentController::class,'externalAppointmentCreate']);
Route::get('/get/external/appointment/form',[AppointmentController::class,'externalAppointmentPage']);



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
Route::post('visitor/qr/request/update',[AppointmentController::class,'ApproveQrRequest']);


Route::post('webservice/image-scan',[ScanController::class,'imageScan']);

Route::post('apppointment/create',[ScanController::class,'createAppointment']);





//Message

Route::post('send/message',[ScanController::class,'sendSMS']);





//chat routes

Route::post('/message/send',[chatController::class,'messageSend']);

Route::get('/messages/get',[chatController::class,'MessagesGet']);
Route::post('message/reply',[chatController::class,'messageReply']);
//video chat room
Route::post('/create/specific/room/{id}', [VideoRoomsController::class, 'RoomBuilt']);
Route::get('/video/chat/notifications', [NotificationController::class, 'videoNotificationManage']);
Route::delete('/notification/delete/{id}', [NotificationController::class, 'notificationDelete']);
Route::get('/single/notification/read/{id}', [NotificationController::class, 'videoNotificationRead']);
Route::post('/chat_status/change', [VideoRoomsController::class, 'ChatStatusChange']);
Route::post('/video/chat/room/user/status', [VideoRoomsController::class, 'updateUserRoomStatus']);
Route::post('single/video/read', [NotificationController::class, 'videoMarkRead']);


//Camera's list

Route::post('/ChannelList',[CameraAuthenticationController::class,'getChannelList']);

Route::post('camera/video/stream',[CameraAuthenticationController::class,'getCameraVideoStream']);

Route::post('/CameraList',[CameraAuthenticationController::class,'getCameraList']);

//Message notification
Route::get('/message/notifications',[NotificationController::class,'messageNotificationManage']);

// Appointment detail through unique code.

Route::get('get/appointment/details/{id}',[AppointmentController::class,'AppointmentDetailThroughCode']);
// bar code scanner
Route::get('get/appointment/barcode/details/{id}',[AppointmentController::class,'BarcodeScanner']);



// add qrcode to users

Route::get('user/qr/add',[userController::class,'addQrCodeToUser']);
//add unique code
Route::get('add/site/uniquecode', [SiteController::class, 'addSiteUniqueCode']);

//dumi routes for count
Route::get('/user/count/for/chat', [userController::class, 'userCountForChat']);
Route::get('/users/for/chat', [userController::class, 'usersForChat']);

//

Route::post('/video/add', [VideoContent::class, 'addVideo']);
Route::post('/video/update', [VideoContent::class, 'updateVideo']);
Route::get('/building/videos/count', [VideoContent::class, 'videoCount']);
Route::get('/building/videos/list', [VideoContent::class, 'list']);
Route::delete('/video/delete/{id}', [VideoContent::class, 'delete']);
Route::post('/external/contact/tenant', [VideoContent::class, 'send_email']);
