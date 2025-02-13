<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/login',                    [AuthController::class, 'loginPage'])->name('login');
Route::post('/login',                   [AuthController::class, 'auth'])->name('login.auth');
Route::get('/register',                 [AuthController::class, 'registerPage'])->name('register');
Route::post('/register',                [AuthController::class, 'register'])->name('register.insert');
Route::get('/logout',                   [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('barang-keluar/export',  [BarangKeluarController::class, 'export']);
    Route::get('barang-masuk/export',   [BarangMasukController::class, 'export']);
    Route::get('barang-keluar/print',   [BarangKeluarController::class, 'print']);
    Route::get('barang-masuk/print',    [BarangMasukController::class, 'print']);

    Route::get('/',                     [DashboardController::class, 'index']);
    Route::resource('barang-masuk',     BarangMasukController::class);
    Route::resource('barang-keluar',    BarangkeluarController::class);
    Route::resource('supplier',         SupplierController::class);
    Route::resource('product',          ProductController::class);
    Route::get('gudang',                [ProductController::class, 'index']);

    Route::get('laporan-barang-masuk',  [BarangMasukController::class, 'report']);
    Route::get('laporan-barang-keluar', [BarangKeluarController::class, 'report']);

    Route::get('api/product/{id}',      [ApiController::class, 'getProduct']);

    Route::get('/stock-opname',         [ProductController::class, 'stockOpnamePage'])->name('stock.opname');
    Route::post('/stock-opname',        [ProductController::class, 'stockOpnameUpdate'])->name('stock.opname.store');
    Route::get('/laporan-stock-opname', [ProductController::class, 'stockOpnameReport'])->name('stock.opname.report');
    Route::get('/stock-opname/print',   [ProductController::class, 'stockOpnamePrint'])->name('stock.opname.print');
    Route::get('/stock-opname/export',  [ProductController::class, 'stockOpnameExport'])->name('stock.opname.export');
});
