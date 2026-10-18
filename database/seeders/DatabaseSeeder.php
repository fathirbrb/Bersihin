<?php

namespace Database\Seeders;

use App\Models\PaketLayanan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Akun Admin default ───────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@bersihin.id'],
            [
                'nama'              => 'Admin Bersihin',
                'password'          => Hash::make('password'),
                'no_hp'             => '081200000000',
                'role'              => 'admin',
                'status_verifikasi' => 'terverifikasi',
            ]
        );

        // ─── Demo pelanggan ───────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'pelanggan@bersihin.id'],
            [
                'nama'              => 'Budi Pelanggan',
                'password'          => Hash::make('password'),
                'no_hp'             => '081200000001',
                'role'              => 'pelanggan',
                'status_verifikasi' => 'terverifikasi',
            ]
        );

        // ─── Demo petugas terverifikasi ───────────────────────────────────
        User::updateOrCreate(
            ['email' => 'petugas@bersihin.id'],
            [
                'nama'              => 'Siti Petugas',
                'password'          => Hash::make('password'),
                'no_hp'             => '081200000002',
                'role'              => 'petugas',
                'status_verifikasi' => 'terverifikasi',
            ]
        );

        // ─── 4 Paket Layanan sesuai SRS ───────────────────────────────────
        // CATATAN: Harga dan estimasi_durasi_jam adalah nilai SEMENTARA — konfirmasi ke tim sebelum production.
        $pakets = [
            [
                'nama_paket'          => 'Pembersihan Rutin',
                'deskripsi'           => 'Layanan kebersihan harian atau mingguan untuk menjaga rumah tetap bersih. Meliputi menyapu, mengepel, membersihkan debu, dan merapikan ruangan.',
                'harga'               => 150000.00,
                'estimasi_durasi_jam' => 2,
            ],
            [
                'nama_paket'          => 'Deep Cleaning',
                'deskripsi'           => 'Pembersihan menyeluruh dan mendalam untuk seluruh area rumah. Termasuk pembersihan sudut tersembunyi, kamar mandi, dapur, dan desinfeksi permukaan.',
                'harga'               => 350000.00,
                'estimasi_durasi_jam' => 5,
            ],
            [
                'nama_paket'          => 'Cuci Sofa dan Kasur',
                'deskripsi'           => 'Layanan cuci dan pembersihan sofa serta kasur menggunakan mesin ekstraksi dan cairan pembersih khusus bebas bahan kimia berbahaya.',
                'harga'               => 250000.00,
                'estimasi_durasi_jam' => 3,
            ],
            [
                'nama_paket'          => 'Pembersihan Pascarenovasi',
                'deskripsi'           => 'Pembersihan intensif setelah renovasi atau konstruksi. Meliputi pembersihan sisa semen, cat, debu konstruksi, dan poles lantai.',
                'harga'               => 500000.00,
                'estimasi_durasi_jam' => 7,
            ],
        ];

        foreach ($pakets as $paket) {
            PaketLayanan::updateOrCreate(
                ['nama_paket' => $paket['nama_paket']],
                $paket
            );
        }
    }
}
