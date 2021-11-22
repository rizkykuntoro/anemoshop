<?php
session_start();
date_default_timezone_set("Asia/Jakarta");

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\AdminController;
use App\Helpers\Helper;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/',[PortalController::class, 'index']);
Route::get('/test-email',[PortalController::class, 'test_email']);
Route::get('/simulasi/{jmlh}',[PortalController::class, 'simulasi']);

Route::group(['prefix' => '/'], function(){
    Route::get('/kontak-kami',[PortalController::class, 'kontak_kami']);
    Route::get('/tentang-kami',[PortalController::class, 'tentang_kami']);
    Route::post('/daftar/',[PortalController::class, 'daftar']);
    Route::post('/promobuy/',[PortalController::class, 'promobuy']);
});

Route::group(['prefix' => 'webinar'], function(){
    Route::get('/view/{id}',[PortalController::class, 'viewWebinar']);
    Route::get('/list/',[PortalController::class, 'listwebinar']);
});


Route::group(['prefix' => 'admin', 'middleware' => ['UserCheck']], function(){
    Route::get('/',[AdminController::class, 'index']);
    Route::get('/daftar-peserta',[AdminController::class, 'daftarpeserta']);
    Route::get('/create-peserta',[AdminController::class, 'createpeserta']);
    Route::post('/create-peserta',[AdminController::class, 'createpeserta']);
    Route::get('/user',[AdminController::class, 'listuser']);
    Route::get('/user/create',[AdminController::class, 'createuser']);
    Route::post('/user/create',[AdminController::class, 'createuser']);
    Route::get('/user/edit/{id}',[AdminController::class, 'edituser']);
    Route::post('/user/edit/{id}',[AdminController::class, 'edituser']);
    Route::get('/webinar',[AdminController::class, 'listwebinar']);
    Route::get('/webinar/create',[AdminController::class, 'createwebinar']);
    Route::post('/webinar/create',[AdminController::class, 'createwebinar']);

    Route::get('/webinar/edit/{id}',[AdminController::class, 'editwebinar']);
    Route::post('/webinar/edit/{id}',[AdminController::class, 'editwebinar']);


    Route::get('/webinar/delete/{id}',[AdminController::class, 'deletewebinar']);
    
    Route::get('/login', function () { echo "<pre>";print_r('this'); echo "</pre>";die(); return view('admin.login'); });
    // Route::post('/login',[LoginController::class, 'login']);
    Route::get('/logout',[AdminController::class, 'logout']);
});


Route::group(['prefix' => 'admin'], function(){
    
    Route::get('/login', function () { return view('admin.login'); });
    Route::post('/login',[LoginController::class, 'login']);
    Route::get('/logout',[AdminController::class, 'logout']);
});


Route::get('/callback',[PaymentGatewayController::class, 'callback']);
Route::post('/callback',[PaymentGatewayController::class, 'callback']);