<?php

use App\Http\Middleware\CheckLogin;
// use App\Http\Controllers\QrCodeType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoContent;
use App\Http\Controllers\authController;
use App\Http\Controllers\chatController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\userController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\StreamingController;
use App\Http\Controllers\QrCodeTypeController;
use App\Http\Controllers\VideoRoomsController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\tenantEmployeeController;
use App\Http\Controllers\Integrator\BuildingAdminController;
use App\Http\Controllers\Integrator\IntegratorDoorController;
use App\Http\Controllers\BuildingAdmin\buildingDoorController;
use App\Http\Controllers\Integrator\SiteController as IntegratorSiteController;
use App\Http\Controllers\BuildingAdmin\userController as buildingUserController;
use App\Http\Controllers\ScreenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('optimize:clear');

    return 'DONE'; //Return anything
});

Route::get('reset-password/{token}', [authController::class, 'showResetPasswordForm'])->name('reset.password.get');

Route::group(['middleware' => ['web']], function () {
    
    //video chat routes
    Route::get('/room', [VideoRoomsController::class, 'index'])->middleware('isLogin');
    Route::post('room/create', [VideoRoomsController::class, 'createRoom'])->middleware('isLogin');
    Route::get('room/join/{roomName}', [VideoRoomsController::class, 'joinRoom'])->middleware('isLogin');
    //message mark as read
    Route::get('/mark/read/{id}/{data}', [NotificationController::class, 'messageMarkRead'])->middleware('isLogin');
    Route::get('video/chat/compose',[VideoRoomsController::class, 'videoChatCompose'])->middleware('isLogin');


    Route::post('logout', [authController::class, 'logout'])->name('logout')->middleware('isLogin');
    Route::view('/dashboard', 'templates.dashboard')->name('dashboard')->middleware('isLogin');
    // user
    Route::get('/user/add', [userController::class, 'viewAdd'])->middleware('Admin');
    Route::view('user/list', 'templates.users.list')->name('user/list')->middleware('Admin');
    Route::view('user/role', 'templates.users.role')->name('role/list')->middleware('Admin');
    Route::get('/user/edit/{id}', [userController::class, 'editUser'])->middleware('Admin');
    Route::get('/user/detail/{id}', [UserController::class, 'userDetail'])->middleware('Admin');
     // qr code type
     Route::get('/qr/code/type/add', [QrCodeTypeController::class, 'index'])->middleware('Admin');
     Route::view('qr/code/type/list', 'templates.qr_codes_types.list')->name('qr/code/list')->middleware('Admin');
    Route::view('/view/site/qr/list', 'templates.qr_codes_types.site_qr_list');
    Route::get('/add/site/qr/code', [QrCodeTypeController::class, 'addSiteQr']);
     // screen route
     Route::get('/add/screen', [ScreenController::class, 'index']);
     Route::view('screen/list', 'templates.screens.list');
     Route::get('/view/screen/data/{id}', [ScreenController::class, 'screenView']);
     Route::get('/screen/edit/{id}', [ScreenController::class, 'editScreen']);
     

    //integrator for building admins routes
    Route::get('integrator/user/add', [BuildingAdminController::class, 'viewAdd'])->middleware('integrator');
    Route::view('integrator/user/list', 'templates.Integrator.building_admin.list')->middleware('integrator');
    Route::view('integrator/user/role', 'templates.Integrator.building_admin.role')->middleware('integrator');
    Route::get('integrator/user/edit/{id}', [BuildingAdminController::class, 'editUser'])->middleware('integrator');
    Route::get('integrator/user/detail/{id}', [BuildingAdminController::class, 'userDetail'])->middleware('integrator');

    //building admin for building admins routes
    Route::get('building/user/add', [buildingUserController::class, 'viewAdd'])->middleware('buildingAdmin');
    Route::view('building/user/list', 'templates.building_admin.user.list')->middleware('buildingAdmin');
    Route::view('building/user/role', 'templates.integrator.building_admin.role')->middleware('buildingAdmin');
    Route::view('building/appointment/add', 'templates.building_admin.appointments.ad')->middleware('buildingAdmin');
    Route::view('building/appointment/list', 'templates.building_admin.appointments.list')->middleware('buildingAdmin');
    Route::get('building/user/edit/{id}', [buildingUserController::class, 'editUser'])->middleware('buildingAdmin');
    Route::get('building/user/detail/{id}', [buildingUserController::class, 'userDetail'])->middleware('buildingAdmin');
    Route::view('building/door/add', 'templates.building_admin.door.add')->middleware('buildingAdmin');
    Route::view('building/door/list', 'templates.building_admin.door.list')->middleware('buildingAdmin');
    Route::get('/building/door/edit/{id}', [buildingDoorController::class, 'edit'])->middleware('buildingAdmin');
    Route::view('building/video/content', 'templates.video_content.add')->middleware('buildingAdmin');
    Route::view('building/video/list', 'templates.video_content.list')->middleware('buildingAdmin');
    Route::get('/building/video/edit/{id}', [VideoContent::class, 'edit'])->middleware('buildingAdmin');
    
    

    // site
    Route::view('site/add', 'templates.site.add')->name('add-site')->middleware('adminsForSite');
    Route::view('site/list', 'templates.site.list')->name('site/list')->middleware('adminsForSite');
    Route::get('/site/edit/{id}', [SiteController::class, 'viewEdit'])->middleware('adminsForSite');
    Route::get('/site/detail/{id}', [SiteController::class, 'viewDetails'])->middleware('adminsForSite');


    //  Integrator sites
    Route::view('integrator/site/add', 'templates.Integrator.site.add')->middleware('integrator');
    Route::view('integrator/site/list', 'templates.Integrator.site.list')->middleware('integrator');
    Route::get('integrator/site/edit/{id}', [IntegratorSiteController::class, 'viewEdit'])->middleware('integrator');
    Route::get('integrator/site/detail/{id}', [IntegratorSiteController::class, 'viewDetails'])->middleware('integrator');
    Route::view('integrator/door/add', 'templates.Integrator.door.add')->middleware('integrator');
    Route::view('integrator/door/list', 'templates.Integrator.door.list')->middleware('integrator');
    Route::get('integrator/door/edit/{id}',[IntegratorDoorController::class, 'edit'])->middleware('integrator');
    Route::get('integrator/door/access/{site}/{door}',[IntegratorDoorController::class, 'doorAccess']);


    

    //Employee routes
    Route::view('employee/visitor/list', 'templates.employee.list')->middleware('employee');
    Route::view('dumi', 'templates.external.dumi_page')->middleware('employee');

    // profile
    Route::get('/profile', [userController::class, 'profile'])->middleware('isLogin');
    Route::view('/profile/edit', 'templates.profile.profile_edit')->name('profile-edit')->middleware('isLogin');

    Route::view('/contact', 'templates.contact')->name('contact')->middleware('isLogin');

    // Appointment
    Route::view('/appointment/list', 'templates.appointment.list')->name('list')->middleware('Tenant');
    Route::view('/appointment/add', 'templates.appointment.add')->name('add')->middleware('Tenant');
    // tenant routes for employees
    Route::get('/tenant/user/add', [tenantEmployeeController::class, 'viewAdd'])->middleware('Tenant');
    Route::view('/tenant/user/list', 'templates.tenant.employee.list')->middleware('Tenant');
    Route::get('/tenant/user/edit/{id}', [tenantEmployeeController::class, 'editUser'])->middleware('Tenant');
    Route::get('/tenant/user/detail/{id}', [tenantEmployeeController::class, 'userDetail'])->middleware('Tenant');
    Route::get('appointment/detail/page/{id}', [AppointmentController::class, 'detailPage'])->name('detailPage');

    Route::get('appointment/handling/{id}', [AppointmentController::class, 'AppointmentHandling'])->name('appointment/handling')->middleware('isLogin');
    Route::get('/walk-in-visitors/detail/{id}', [AppointmentController::class, 'walikInVisitorDetails'])->name('walk-in-visitors/detail')->middleware('isLogin');
    Route::view('/walk-in-visitor', 'templates.appointment.walkin_visitor')->middleware('Tenant');

    Route::view('document/scan', 'templates.guard.scan')->name('scan')->middleware('Guard');


        //streaming routes
     Route::get('/live-streaming', [StreamingController::class, 'index'])->middleware('isLogin');
    Route::get('/streaming/{streamId}', [StreamingController::class, 'consumer'])->middleware('isLogin');
    Route::post('/stream-offer', [StreamingController::class, 'makeStreamOffer'])->middleware('isLogin');
    Route::post('/stream-answer', [StreamingController::class, 'makeStreamAnswer'])->middleware('isLogin');

});

// promotional videos
Route::get('/all/promotional/videos/{id}', [VideoContent::class, 'allVideos']);

//external incoming visitor
Route::get('/external/scan/{id}', [SiteController::class, 'externalScanPage']);
Route::get('external/new/appointment/{id}', [AppointmentController::class, 'externalAppointmentForm']);
Route::post('qr/add/', [AppointmentController::class, 'addQr']);
Route::get('/external/visitor/detail/{id}', [AppointmentController::class, 'externalVisitorDetailPage']);
Route::view('keyboard/wedge-one','templates.external.keyboard_wedge_one');
Route::view('keyboard/wedge-three','templates.external.keyboard_wedge_three');
Route::view('keyboard/wedge-two','templates.external.keyboard_wedge_two');
Route::get('/external/pages/{id}', [VideoContent::class, 'external_pages']);
Route::get('/contact/tenant/{id}', [VideoContent::class, 'contact_tenant']);

Route::view('sample', 'templates.guard.camera');

//camera routes
Route::view('camera/authentications', 'templates.tenant.camera.auth')->middleware('Tenant');
Route::view('tenant/channel/list', 'templates.tenant.camera.channel_list')->middleware('Tenant');
Route::view('tenant/all/camera/streaming', 'templates.tenant.camera.camera_list')->middleware('Tenant');


Route::get('/login', [authController::class, 'LoginPageView'])->name('login');

Route::get('/', [authController::class, 'LoginPageView']);

Route::view('detail/page', 'templates.appointment.dumi');

Route::get('/register', function () {
    return view('templates.auth.register');
});

// register as a guest
Route::get('/guest/check/{id}', [authController::class, 'GuestCheck']);
Route::get('/guest/register/{id}', [authController::class, 'GuestRegister'])->name('guest/register');
Route::get('/guest/login/{id}', [authController::class, 'GuestLogin'])->name('guest/login');
Route::view('/scan', 'templates.appointment.scan');

Route::get('/forgot/password', function () {

    return view('templates.auth.forgot_password');
});

//privacy
Route::view('/privacy','templates.privacy');

//chat route
Route::get('/chat/box',[chatController::class,'chatPage'])->middleware('isLogin');
Route::get('/message/compose',[chatController::class,'chatPage'])->middleware('isLogin');
Route::get('/message/inbox',[chatController::class,'messageInbox'])->middleware('isLogin');
Route::get('/inbox/{id}',[chatController::class,'inbox'])->middleware('isLogin');
Route::post('/file/upload',[chatController::class,'fileUpload'])->middleware('isLogin');
Route::get('/file/delete',[chatController::class,'fileDelete'])->middleware('isLogin');

Route::get('/message/sent',[chatController::class,'sentList'])->middleware('isLogin');
Route::get('/sent/{id}',[chatController::class,'sent'])->middleware('isLogin');


Route::get('/qr-scan', function () {
    return view('templates.qr.scan');
})->middleware('Guard');
Route::view('/contact', 'templates.contact');



Route::get('/notification', function () {
    return view('templates.notifications.notification');
});

Route::get('/transaction/pending', function () {
    return view('templates.transactions.pending_transaction');
});

Route::get('/transaction/completed', function () {
    return view('templates.transactions.completed_transaction');
});

Route::get('/transaction/edit', function () {
    return view('templates.transactions.edit_transaction');
});

Route::get('/transaction/detail', function () {
    return view('templates.transactions.transaction_detail');
});

Route::get('/transaction/new', function () {
    return view('templates.transactions.new_transaction');
});

Route::get('change/password', function () {
    return view('templates.auth.change_password');
});
Route::get('/stream', function () {
    return view('templates.stream');
});
Route::get('/dumi', function () {
    return view('templates.users.dumi_user');
});


Route::get('appointment/detail/{id}', [AppointmentController::class, 'detail'])->name('detail');
Route::get('request/new/qr/{id}', [AppointmentController::class, 'requestNewQr']);
Route::get('external/visitor/qr/request/{id}', [AppointmentController::class, 'externalVistorRequest']);


// external menus
// Route::view('external/pages', 'templates.external_design.menu');

