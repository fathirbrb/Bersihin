<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganPembayaranController;
use App\Http\Controllers\PelangganPesananController;
use App\Http\Controllers\PelangganUlasanController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Redirect root ke login atau dashboard sesuai role
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return match (auth()->user()->role) {
        'admin'     => redirect()->route('admin.paket.index'),
        'petugas'   => redirect()->route('petugas.dashboard'),
        'pelanggan' => redirect()->route('pelanggan.pesanan.index'),
        default     => redirect()->route('login'),
    };
});

/*
|--------------------------------------------------------------------------
| Auth: Login, Registrasi, Logout  (F-01)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Petugas — auth + role:petugas
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/dashboard',             [PetugasController::class, 'dashboard'])->name('dashboard');
        Route::get('/pesanan-masuk',         [PetugasController::class, 'pesananMasuk'])->name('pesanan-masuk');
        Route::get('/pesanan-saya',          [PetugasController::class, 'pesananSaya'])->name('pesanan-saya');
        Route::get('/pesanan/{id}',          [PetugasController::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{id}/terima',  [PetugasController::class, 'terima'])->name('pesanan.terima');
        Route::post('/pesanan/{id}/kerjakan',[PetugasController::class, 'kerjakan'])->name('pesanan.kerjakan');
        Route::post('/pesanan/{id}/selesai', [PetugasController::class, 'selesai'])->name('pesanan.selesai');
        Route::get('/ulasan',                [PetugasController::class, 'ulasan'])->name('ulasan');
    });

/*
|--------------------------------------------------------------------------
| Pelanggan — auth + role:pelanggan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {
        // Pesanan
        Route::get('/pesanan',                              [PelangganPesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/buat',                         [PelangganPesananController::class, 'create'])->name('pesanan.create');
        Route::post('/pesanan',                             [PelangganPesananController::class, 'store'])->name('pesanan.store');
        Route::get('/pesanan/{id}',                         [PelangganPesananController::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{id}/batalkan',               [PelangganPesananController::class, 'batalkan'])->name('pesanan.batalkan');

        // Pembayaran (dari branch main — Orang 3)
        Route::post('/pesanan/{pesananId}/pembayaran',      [PelangganPembayaranController::class, 'store'])->name('pembayaran.store');

        // Ulasan
        Route::get('/ulasan',                               [PelangganUlasanController::class, 'index'])->name('ulasan.index');
        Route::get('/pesanan/{pesananId}/ulasan/buat',      [PelangganUlasanController::class, 'create'])->name('ulasan.create');
        Route::post('/pesanan/{pesananId}/ulasan',          [PelangganUlasanController::class, 'store'])->name('ulasan.store');
        Route::delete('/ulasan/{id}',                       [PelangganUlasanController::class, 'destroy'])->name('ulasan.destroy');
    });

/*
|--------------------------------------------------------------------------
| Admin — auth + role:admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Manajemen petugas (dari branch main — Orang 2)
        Route::get('/petugas',                         [AdminController::class, 'indexPetugas'])->name('petugas.index');
        Route::put('/petugas/{id}/verifikasi',         [AdminController::class, 'updateVerifikasiPetugas'])->name('petugas.verifikasi');

        // Paket layanan
        Route::get('/paket',                           [AdminController::class, 'indexPaket'])->name('paket.index');
        Route::put('/paket/{paketLayanan}',            [AdminController::class, 'updatePaket'])->name('paket.update');

        // Pesanan & pembayaran
        Route::get('/pesanan',                         [AdminController::class, 'indexPesanan'])->name('pesanan.index');
        Route::get('/pembayaran',                      [AdminController::class, 'indexPembayaran'])->name('pembayaran.index');
        Route::put('/pembayaran/{id}/status',          [AdminController::class, 'updateStatusPembayaran'])->name('pembayaran.status');
    });
