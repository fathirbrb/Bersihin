<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $t) {
            $t->unsignedTinyInteger('durasi_jam')->nullable()->after('jam_mulai');
            $t->index('pelanggan_id');
            $t->index('petugas_id');
            $t->index('status');
            $t->index('tanggal');
        });

        DB::statement('UPDATE pesanan SET durasi_jam = (SELECT estimasi_durasi_jam FROM paket_layanan WHERE paket_layanan.id = pesanan.paket_layanan_id)');

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE pesanan ALTER COLUMN durasi_jam SET NOT NULL');
        }

        Schema::table('pembayaran', fn (Blueprint $t) => $t->index('status'));

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE ulasan ADD CONSTRAINT ulasan_rating_check CHECK (rating BETWEEN 1 AND 5)');
            DB::statement('ALTER TABLE paket_layanan ADD CONSTRAINT paket_harga_check CHECK (harga >= 0)');
            DB::statement('ALTER TABLE paket_layanan ADD CONSTRAINT paket_nama_unique UNIQUE (nama_paket)');
        } else {
            Schema::table('paket_layanan', fn (Blueprint $t) => $t->unique('nama_paket', 'paket_nama_unique'));
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE paket_layanan DROP CONSTRAINT IF EXISTS paket_nama_unique');
            DB::statement('ALTER TABLE paket_layanan DROP CONSTRAINT IF EXISTS paket_harga_check');
            DB::statement('ALTER TABLE ulasan DROP CONSTRAINT IF EXISTS ulasan_rating_check');
        } else {
            Schema::table('paket_layanan', fn (Blueprint $t) => $t->dropUnique('paket_nama_unique'));
        }

        Schema::table('pembayaran', fn (Blueprint $t) => $t->dropIndex(['status']));

        Schema::table('pesanan', function (Blueprint $t) {
            $t->dropIndex(['pelanggan_id']);
            $t->dropIndex(['petugas_id']);
            $t->dropIndex(['status']);
            $t->dropIndex(['tanggal']);
            $t->dropColumn('durasi_jam');
        });
    }
};
