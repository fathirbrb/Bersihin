<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('paket_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->text('deskripsi');
            $table->decimal('harga', 12, 2);
            $table->unsignedTinyInteger('estimasi_durasi_jam');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_layanan');
    }
};
