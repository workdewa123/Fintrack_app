<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PengingatController;

/*
|--------------------------------------------------------------------------
| Rute Publik
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
| Rute Terproteksi (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // --- Beranda & API Dashboard ---
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda.index');
    Route::get('/api/beranda-data', [BerandaController::class, 'getApiData']);
    Route::post('/api/transaksi-simpan', [BerandaController::class, 'storeTransaksi']);

    // --- Profil & Autentikasi ---
    Route::post('/logout', [PenggunaController::class, 'logout'])->name('logout');
    Route::post('/profil', [PenggunaController::class, 'updateProfil'])->name('profil');

    // --- Manajemen Rekening ---
    Route::prefix('rekening')->name('rekening.')->group(function () {
        Route::get('/', [RekeningController::class, 'index'])->name('index');
        Route::get('/data', [RekeningController::class, 'getRekeningData'])->name('data');
        Route::post('/', [RekeningController::class, 'store'])->name('store');
        Route::get('/{rekening}', [RekeningController::class, 'show'])->name('show');
        Route::put('/{rekening}', [RekeningController::class, 'update'])->name('update');
        Route::delete('/{rekening}', [RekeningController::class, 'destroy'])->name('destroy');
    });

    // --- Manajemen Kategori ---
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::get('/kategori-data', [KategoriController::class, 'getKategoriData'])->name('data');
        Route::post('/store', [KategoriController::class, 'store'])->name('store');
        Route::get('/show/{id}', [KategoriController::class, 'show'])->name('show');
        Route::put('/update/{id}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [KategoriController::class, 'destroy'])->name('destroy');
    });

    // --- Transaksi & Laporan ---
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/riwayat', [TransaksiController::class, 'halamanRiwayat'])->name('riwayat');
        Route::get('/data', [TransaksiController::class, 'getTransaksiData'])->name('data');
        Route::get('/laporan/pdf', [TransaksiController::class, 'exportPDF'])->name('laporan.pdf');
    });
    // CRUD Transaksi menggunakan Resource
    Route::resource('transaksi', TransaksiController::class)->except(['index']);

    // --- Pembayaran Reguler / Pengingat ---
    Route::prefix('pembayaran-reguler')->name('pembayaran.')->group(function () {
        Route::get('/', [PengingatController::class, 'index'])->name('index');
        Route::get('/data', [PengingatController::class, 'getPengingatData'])->name('data.api');
        Route::get('/tambah', [PengingatController::class, 'create'])->name('create');
        Route::post('/simpan', [PengingatController::class, 'store'])->name('store');
        Route::get('/{id}', [PengingatController::class, 'show'])->name('show');
        Route::put('/{id}', [PengingatController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengingatController::class, 'destroy'])->name('destroy');
    });
    // Konfirmasi Pembayaran Tagihan
    Route::post('/pengingat/konfirmasi-bayar/{id}', [PengingatController::class, 'konfirmasiBayar'])->name('pengingat.bayar');

    // --- API Helper (Global Dropdown) ---
    Route::get('/api/rekening-all', [RekeningController::class, 'allRekening'])->name('api.rekening.all');
    Route::get('/api/kategori-all', [KategoriController::class, 'allKategori'])->name('api.kategori.all');

});
