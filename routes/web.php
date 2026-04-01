<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\PengingatController;

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Tanpa Login)
|--------------------------------------------------------------------------
*/

Route::get('/', [PenggunaController::class, 'showHalamanAwal'])->name('halaman.awal');
Route::get('/login', [PenggunaController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PenggunaController::class, 'authenticate']);
Route::get('/register', [PenggunaController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [PenggunaController::class, 'register']);
Route::get('/landing-page', [PenggunaController::class, 'showLandingPage'])->name('landing.page');

/*
|--------------------------------------------------------------------------
| Protected Routes (Akses Setelah Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda.index');
    Route::post('/logout', [PenggunaController::class, 'logout'])->name('logout');

    // Rute untuk Rekening
    Route::get('/rekening', [RekeningController::class, 'index'])->name('rekening.index');
    Route::get('/rekening-data', [RekeningController::class, 'getRekeningData'])->name('rekening.data');
    Route::post('/rekening', [RekeningController::class, 'store'])->name('rekening.store');
    Route::get('/rekening/{rekening}', [RekeningController::class, 'show'])->name('rekening.show');
    Route::put('/rekening/{rekening}', [RekeningController::class, 'update'])->name('rekening.update');
    Route::delete('/rekening/{rekening}', [RekeningController::class, 'destroy'])->name('rekening.destroy');

    Route::get('/riwayat-transfer', [TransferController::class, 'index'])->name('riwayat.transfer');

// Grouping route untuk kategori agar lebih rapi
Route::prefix('kategori')->name('kategori.')->group(function () {
    
    // Menampilkan halaman daftar kategori (tabel)
    Route::get('/', [KategoriController::class, 'index'])->name('index');

    // Proses Tambah Kategori (POST)
    // Sesuai dengan form di tambah_kategori.blade.php
    Route::post('/store', [KategoriController::class, 'store'])->name('store');

    // Proses Update/Edit Kategori (PUT)
    // Sesuai dengan @method('PUT') di edit_kategori.blade.php
    Route::put('/update/{id}', [KategoriController::class, 'update'])->name('update');

    // Proses Hapus Kategori (DELETE)
    Route::delete('/destroy/{id}', [KategoriController::class, 'destroy'])->name('destroy');
});
    // Rute untuk Pembayaran Reguler
    Route::get('/pembayaran-reguler', [PengingatController::class, 'showRegularPayments'])->name('pembayaran.reguler');

    // Rute untuk Pengingat
    Route::get('/pengingat', [PengingatController::class, 'index'])->name('pengingat.index');

    // Resource routes
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('transfer', TransferController::class);
});
