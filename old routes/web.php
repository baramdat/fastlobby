<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\userController;
use App\Http\Controllers\LockerController;
use App\Http\Middleware\CheckLogin;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Integrator\SiteController as IntegratorSiteController;
use App\Http\Controllers\Integrator\BuildingAdminController;
use App\Http\Controllers\BuildingAdmin\userController as buildingUserController;
use App\Http\Controllers\tenant\tenantEmployeeController;

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

    Route::post('logout', [authController::class, 'logout'])->name('logout')->middleware('isLogin');
    Route::view('/dashboard', 'templates.dashboard')->name('dashboard')->middleware('isLogin');
    // user
    Route::get('/user/add', [userController::class, 'viewAdd'])->middleware('Admin');
    Route::view('user/list', 'templates.users.list')->name('user/list')->middleware('Admin');
    Route::view('user/role', 'templates.users.role')->name('role/list')->middleware('Admin');
    Route::get('/user/edit/{id}', [userController::class, 'editUser'])->middleware('Admin');
    Route::get('/user/detail/{id}', [UserController::class, 'userDetail'])->middleware('Admin');

    //integrator for building admins routes
    Route::get('integrator/user/add', [BuildingAdminController::class, 'viewAdd'])->middleware('integrator');
    Route::view('integrator/user/list', 'templates.integrator.building_admin.list')->middleware('integrator');
    Route::view('integrator/user/role', 'templates.integrator.building_admin.role')->middleware('integrator');
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
    Route::get('appointment/detail/{id}/{data?}', [AppointmentController::class, 'detail'])->name('detail');
    // tenant routes for employees
    Route::get('/tenant/user/add', [tenantEmployeeController::class, 'viewAdd'])->middleware('Tenant');
    Route::view('/tenant/user/list', 'templates.tenant.employee.list')->middleware('Tenant');
    Route::get('/tenant/user/edit/{id}', [tenantEmployeeController::class, 'editUser'])->middleware('Tenant');
    Route::get('/tenant/user/detail/{id}', [tenantEmployeeController::class, 'userDetail'])->middleware('Tenant');

    Route::get('appointment/handling/{id}', [AppointmentController::class, 'AppointmentHandling'])->name('appointment/handling')->middleware('isLogin');
    Route::get('/walk-in-visitors/detail/{id}', [AppointmentController::class, 'walikInVisitorDetails'])->name('walk-in-visitors/detail')->middleware('isLogin');
    Route::view('/walk-in-visitor', 'templates.appointment.walkin_visitor')->middleware('Tenant');
    
    Route::view('document/scan', 'templates.guard.scan')->name('scan')->middleware('Guard');
    
});
    //external incoming visitor
    Route::get('/external/scan/{id}', [SiteController::class, 'externalScanPage']);
    Route::get('external/new/appointment/{id}', [AppointmentController::class, 'externalAppointmentForm']);
    Route::post('qr/add/', [AppointmentController::class, 'addQr']);
    Route::get('/external/visitor/detail/{id}', [AppointmentController::class, 'externalVisitorDetailPage'])->middleware('Tenant');
        Route::view('keyboard/wedge-one','templates.external.keyboard_wedge_one');

    
    Route::view('live-streaming', 'templates.external.live_streaming');

Route::view('sample', 'templates.guard.camera');

//camera routes
Route::view('camera/authentications', 'templates.tenant.camera.auth');
Route::view('tenant/channel/list', 'templates.tenant.camera.channel_list');



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







Route::get('/qr-scan', function () {
    return view('templates.qr.scan');
});



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