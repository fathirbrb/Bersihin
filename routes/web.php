<?php

use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Halaman khusus role petugas
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
