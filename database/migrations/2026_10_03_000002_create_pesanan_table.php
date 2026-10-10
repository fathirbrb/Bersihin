<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('users')->cascadeOnDelete();
            // Kosong sampai ada petugas yang menerima
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('paket_layanan_id')->constrained('paket_layanan')->restrictOnDelete();
            $table->text('alamat');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->text('catatan')->nullable();
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['menunggu', 'diterima', 'dikerjakan', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
