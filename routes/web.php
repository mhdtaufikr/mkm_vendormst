<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VendorMstController;
use App\Http\Controllers\CustomerMstController;



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

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/auth/login', [AuthController::class, 'postLogin']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    //Home Controller
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::post('/mst/form', [HomeController::class, 'form']);

    //Vendor Master
    Route::get('/mst/vendor', [VendorMstController::class, 'index'])->name('mst.vendor');
    Route::get('/mst/vendor/form', [VendorMstController::class, 'form']);
    Route::post('/vendor/store', [VendorMstController::class, 'store']);
    Route::get('/vendor/detail/{id}', [VendorMstController::class, 'detail']);
    Route::get('/vendor/update/{id}', [VendorMstController::class, 'update']);
    Route::post('/vendor/update/store', [VendorMstController::class, 'storeUpdate']);
    Route::get('/vendor/checked/{id}', [VendorMstController::class, 'checked']);
    Route::post('/vendor/approval', [VendorMstController::class, 'approval']);
    Route::get('/vendor/template', [VendorMstController::class, 'excelFormat']);
    Route::post('/vendor/upload', [VendorMstController::class, 'excelUpload']);


    //Customer Master
    Route::get('/mst/customer', [CustomerMstController::class, 'index'])->name('mst.customer');
    Route::get('/mst/customer/form', [CustomerMstController::class, 'form']);
    Route::post('/customer/store', [CustomerMstController::class, 'store']);
    Route::get('/customer/checked/{id}', [CustomerMstController::class, 'checked']);
    Route::post('/customer/approval', [CustomerMstController::class, 'approval']);
    Route::get('/customer/update/{id}', [CustomerMstController::class, 'update']);
    Route::post('/customer/update/store', [CustomerMstController::class, 'storeUpdate']);
    Route::get('/customer/detail/{id}', [CustomerMstController::class, 'detail']);

    //Dropdown Controller
     Route::get('/dropdown', [DropdownController::class, 'index'])->middleware(['checkRole:IT']);
     Route::post('/dropdown/store', [DropdownController::class, 'store'])->middleware(['checkRole:IT']);
     Route::patch('/dropdown/update/{id}', [DropdownController::class, 'update'])->middleware(['checkRole:IT']);
     Route::delete('/dropdown/delete/{id}', [DropdownController::class, 'delete'])->middleware(['checkRole:IT']);

     //Rules Controller
     Route::get('/rule', [RulesController::class, 'index'])->middleware(['checkRole:IT']);
     Route::post('/rule/store', [RulesController::class, 'store'])->middleware(['checkRole:IT']);
     Route::patch('/rule/update/{id}', [RulesController::class, 'update'])->middleware(['checkRole:IT']);
     Route::delete('/rule/delete/{id}', [RulesController::class, 'delete'])->middleware(['checkRole:IT']);

     //User Controller
     Route::get('/user', [UserController::class, 'index'])->middleware(['checkRole:IT']);
     Route::post('/user/store', [UserController::class, 'store'])->middleware(['checkRole:IT']);
     Route::post('/user/store-partner', [UserController::class, 'storePartner'])->middleware(['checkRole:IT']);
     Route::patch('/user/update/{user}', [UserController::class, 'update'])->middleware(['checkRole:IT']);
     Route::get('/user/revoke/{user}', [UserController::class, 'revoke'])->middleware(['checkRole:IT']);
     Route::get('/user/access/{user}', [UserController::class, 'access'])->middleware(['checkRole:IT']);


});
