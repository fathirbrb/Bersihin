<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/paket', [AdminController::class, 'indexPaket'])->name('paket.index');
    Route::put('/paket/{paketLayanan}', [AdminController::class, 'updatePaket'])->name('paket.update');
    Route::get('/pesanan', [AdminController::class, 'indexPesanan'])->name('pesanan.index');
    Route::get('/pembayaran', [AdminController::class, 'indexPembayaran'])->name('pembayaran.index');
    Route::put('/pembayaran/{id}/status', [AdminController::class, 'updateStatusPembayaran'])->name('pembayaran.status');
});