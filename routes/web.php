<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DetailPenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DiskonController;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\ActivityLogController;

// Redirect '/' ke dashboard jika sudah login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route untuk autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');





// Middleware untuk memastikan hanya pengguna yang sudah login dapat mengakses dashboard
Route::middleware('auth')->group(function () {
    // Dashboard bisa diakses oleh pemilik dan kasir
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(CheckRole::class . ':pemilik,kasir')
        ->name('dashboard');

    // Hak akses hanya untuk pemilik
    Route::middleware(CheckRole::class . ':pemilik')->group(function () {
        Route::resource('barang', BarangController::class)->except(['show']);
        

        Route::patch('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
        Route::resource('kategori', KategoriController::class);
        Route::patch('/kategori/{id}/restore', [KategoriController::class, 'restore'])->name('kategori.restore');
        Route::resource('diskon', DiskonController::class);
        Route::patch('/diskon/restore/{id}', [DiskonController::class, 'restore'])->name('diskon.restore');

        Route::get('/barang/laporan', [BarangController::class, 'laporan'])->name('barang.laporan');

        Route::get('/barang/{id}/tambah-stok', [BarangController::class, 'tambahStokForm'])->name('barang.tambahStokForm');
Route::post('/barang/{id}/tambah-stok', [BarangController::class, 'tambahStok'])->name('barang.tambahStok');

Route::get('/barang/{id}/detail', [BarangController::class, 'detail'])->name('barang.detail');



        Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('log.index');

        // Manajemen Pengguna
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    });

    // Hak akses untuk pemilik dan kasir
    Route::middleware(CheckRole::class . ':pemilik,kasir')->group(function () {

        Route::get('/laporan/export', [LaporanController::class, 'exportExcel'])->name('laporan.export');

        Route::get('/barang/export', [BarangController::class, 'export'])->name('barang.export');

       

        // Penjualan
        Route::resource('penjualan', PenjualanController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');

        // Kasir
        Route::get('/kasir', [PenjualanController::class, 'index'])->name('kasir.index');
        Route::post('/kasir/addItem', [PenjualanController::class, 'addItem']);
        Route::post('/kasir/checkout', [PenjualanController::class, 'checkout'])->name('kasir.checkout');

        // Detail Penjualan
        Route::resource('detail-penjualan', DetailPenjualanController::class)->only(['index', 'store', 'update', 'destroy']);

        // Laporan Transaksi
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
        Route::get('/laporan/{id}/cetakpdf', [LaporanController::class, 'cetakpdf'])->name('laporan.cetakpdf');
      

    });
});
