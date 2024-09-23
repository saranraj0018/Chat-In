<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dummy', [ProductController::class, 'dummy']);
Route::group(['prefix'=> 'admin'],function (){
        Route::view('/login',  'admin.login')->name('admin.login');
//        Route::view('/register',  'admin.register')->name('admin.register');
        Route::post('/authenticate', [AuthController::class, 'adminAuthenticate'])->name('admin.authenticate');
//        Route::post('/register/update', [AdminAuthController::class, 'registerUpdate'])->name('admin.register.update');

    Route::group(['middleware'=> 'admin.auth'],function (){
        Route::get('/dashboard', [DashboardController::class, 'view'])->name('admin.dashboard');
        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');


        //Coupon Section
        Route::get('/product/list', [ProductController::class, 'list'])->name('product.list');
        Route::post('/coupon/save', [ProductController::class, 'save'])->name('product.save');
        Route::delete('/coupon/{id?}/delete', [ProductController::class, 'delete'])->name('product.delete');
    });
});
