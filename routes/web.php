<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/login',                    [AuthController::class, 'loginPage'])->name('login');
Route::post('/login',                   [AuthController::class, 'auth'])->name('login.auth');
Route::get('/register',                    [AuthController::class, 'registerPage'])->name('register');
Route::post('/register',                   [AuthController::class, 'register'])->name('register.insert');
Route::get('/logout',                   [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/',                     [DashboardController::class, 'index']);
    Route::resource('barang-masuk',     BarangMasukController::class);
    Route::resource('barang-keluar',    BarangkeluarController::class);
    Route::resource('supplier',         SupplierController::class);
});
