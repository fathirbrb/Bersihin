<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganPesananController;
use App\Http\Controllers\PelangganUlasanController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/pesanan-masuk', [PetugasController::class, 'pesananMasuk'])->name('pesanan-masuk');
    Route::get('/pesanan-saya', [PetugasController::class, 'pesananSaya'])->name('pesanan-saya');
    Route::get('/pesanan/{id}', [PetugasController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{id}/terima', [PetugasController::class, 'terima'])->name('pesanan.terima');
    Route::post('/pesanan/{id}/kerjakan', [PetugasController::class, 'kerjakan'])->name('pesanan.kerjakan');
    Route::post('/pesanan/{id}/selesai', [PetugasController::class, 'selesai'])->name('pesanan.selesai');
    Route::get('/ulasan', [PetugasController::class, 'ulasan'])->name('ulasan');
});

// Halaman khusus role pelanggan
Route::middleware('auth')->prefix('pelanggan')->name('pelanggan.')->group(function () {

    // Pesanan
    Route::get('/pesanan', [PelangganPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/buat', [PelangganPesananController::class, 'create'])->name('pesanan.create');
    Route::post('/pesanan', [PelangganPesananController::class, 'store'])->name('pesanan.store');
    Route::get('/pesanan/{id}', [PelangganPesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{id}/batalkan', [PelangganPesananController::class, 'batalkan'])->name('pesanan.batalkan');

    // Ulasan
    Route::get('/ulasan', [PelangganUlasanController::class, 'index'])->name('ulasan.index');
    Route::get('/pesanan/{pesananId}/ulasan/buat', [PelangganUlasanController::class, 'create'])->name('ulasan.create');
    Route::post('/pesanan/{pesananId}/ulasan', [PelangganUlasanController::class, 'store'])->name('ulasan.store');
    Route::delete('/ulasan/{id}', [PelangganUlasanController::class, 'destroy'])->name('ulasan.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/petugas', [AdminController::class, 'indexPetugas'])->name('petugas.index');
    Route::put('/petugas/{id}/verifikasi', [AdminController::class, 'updateVerifikasiPetugas'])->name('petugas.verifikasi');
    Route::get('/paket', [AdminController::class, 'indexPaket'])->name('paket.index');
    Route::put('/paket/{paketLayanan}', [AdminController::class, 'updatePaket'])->name('paket.update');
    Route::get('/pesanan', [AdminController::class, 'indexPesanan'])->name('pesanan.index');
    Route::get('/pembayaran', [AdminController::class, 'indexPembayaran'])->name('pembayaran.index');
    Route::put('/pembayaran/{id}/status', [AdminController::class, 'updateStatusPembayaran'])->name('pembayaran.status');
});
