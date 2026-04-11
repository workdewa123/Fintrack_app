<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\PengingatController;
use App\Http\Controllers\PengaturanController;

// --- PUBLIC ROUTES ---
Route::get('/', [PenggunaController::class, 'showHalamanAwal'])->name('halaman.awal');
Route::get('/login', [PenggunaController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PenggunaController::class, 'authenticate']);
Route::get('/register', [PenggunaController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [PenggunaController::class, 'register']);
Route::get('/landing-page', [PenggunaController::class, 'showLandingPage'])->name('landing.page');

// --- PROTECTED ROUTES ---
Route::middleware('auth')->group(function () {
    // Beranda & API Dashboard
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda.index');
    Route::get('/api/beranda-data', [BerandaController::class, 'getApiData']); // <-- Tambahkan ini
    Route::post('/api/transaksi-simpan', [BerandaController::class, 'storeTransaksi']); // <-- Tambahkan ini

    // Tambahkan di dalam group middleware auth di web.php
    Route::get('/api/rekening-all', [RekeningController::class, 'allRekening'])->name('rekening.all');
    Route::get('/api/kategori-all', [KategoriController::class, 'allKategori'])->name('kategori.all');

    Route::post('/logout', [PenggunaController::class, 'logout'])->name('logout');

    // Rekening
    Route::get('/rekening', [RekeningController::class, 'index'])->name('rekening.index');
    Route::get('/rekening-data', [RekeningController::class, 'getRekeningData'])->name('rekening.data');
    Route::post('/rekening', [RekeningController::class, 'store'])->name('rekening.store');
    Route::get('/rekening/{rekening}', [RekeningController::class, 'show'])->name('show');
    Route::put('/rekening/{rekening}', [RekeningController::class, 'update'])->name('rekening.update');
    Route::delete('/rekening/{rekening}', [RekeningController::class, 'destroy'])->name('rekening.destroy');

    Route::get('/riwayat-transfer', [TransferController::class, 'index'])->name('riwayat.transfer');

    // Kategori
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::post('/store', [KategoriController::class, 'store'])->name('store');
        Route::delete('/destroy/{id}', [KategoriController::class, 'destroy'])->name('destroy');
        Route::get('/kategori-data', [KategoriController::class, 'getKategoriData']);
        Route::get('/show/{id}', [KategoriController::class, 'show']);
        Route::put('/update/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    });

    Route::post('/pengingat/konfirmasi-bayar/{id}', [PengingatController::class, 'konfirmasiBayar'])->name('pengingat.bayar');


    // --- RANGKAIAN PEMBAYARAN REGULER ---
    Route::middleware('auth')->group(function () {
        Route::prefix('pembayaran-reguler')->group(function () {
            // Halaman Utama (Daftar)
            Route::get('/', [PengingatController::class, 'index'])->name('pembayaran.index');

            // Halaman Form Tambah
            Route::get('/tambah', [PengingatController::class, 'create'])->name('pembayaran.create');

            // Aksi Simpan & Hapus
            Route::post('/simpan', [PengingatController::class, 'store'])->name('pembayaran.store');
            Route::delete('/{id}', [PengingatController::class, 'destroy'])->name('pembayaran.destroy');

            Route::get('/data', [PengingatController::class, 'getPengingatData'])->name('pembayaran.data.api');

            // Route untuk mengambil detail data (Show)
            Route::get('/{id}', [PengingatController::class, 'show'])->name('pembayaran.show');

            // Route untuk memproses update data
            Route::put('/{id}', [PengingatController::class, 'update'])->name('pembayaran.update');
        });
    });
    // Resource
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('transfer', TransferController::class);
    // web.php
// web.php
Route::middleware('auth')->group(function () {
    // Rute untuk halaman riwayat transaksi (tampilan)
    Route::get('/riwayat-transaksi', [TransaksiController::class, 'halamanRiwayat'])->name('transaksi.riwayat');
    
    // API Data untuk tabel
    Route::get('/transaksi-data', [TransaksiController::class, 'getTransaksiData']);
    
    // Cetak PDF
    Route::get('/laporan/pdf', [TransaksiController::class, 'exportPDF'])->name('laporan.pdf');

    // Resource CRUD (sudah ada)
    Route::resource('transaksi', TransaksiController::class);
});

    // Pengaturan
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('index');
        Route::post('/profil', [PengaturanController::class, 'updateProfil'])->name('profil');
        Route::post('/preferensi', [PengaturanController::class, 'updatePreferensi'])->name('preferensi');
        Route::post('/pin', [PengaturanController::class, 'updatePin'])->name('pin');
    });
});
