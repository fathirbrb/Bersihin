# 🧹 Bersihin — Layanan Kebersihan Rumah Profesional

<p align="center">
  <img src="https://raw.githubusercontent.com/fathirbrb/Bersihin/main/public/mockup/assets/logo.svg" width="64" height="64" alt="Bersihin Logo" onerror="this.style.display='none'">
</p>

<p align="center">
  <b>Platform digital on-demand pemesanan jasa kebersihan rumah yang transparan, mudah, dan terpercaya.</b>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PostgreSQL-16%2B-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Design_System-bersihin.css-2453D8?style=for-the-badge" alt="bersihin.css">
</p>

---

## 📌 Daftar Isi
- [Gambaran Umum](#-gambaran-umum)
- [Peran Pengguna & Fitur](#-peran-pengguna--fitur)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Panduan Instalasi & Menjalankan](#-panduan-instalasi--menjalankan)
- [Struktur Database](#-struktur-database)
- [Akun Demo / Testing](#-akun-demo--testing)
- [Menjalankan Automated Tests](#-menjalankan-automated-tests)
- [Struktur Proyek](#-struktur-proyek)

---

## 💡 Gambaran Umum

**Bersihin** hadir untuk mengatasi masalah ketidakpastian harga, rumitnya proses pencarian petugas kebersihan, serta ketiadaan pelacakan status kerja. Dengan Bersihin:
- **Harga Pasti**: Tarif tetap per paket tanpa tawar-menawar di tempat.
- **Petugas Terverifikasi**: Setiap mitra petugas melewati proses verifikasi identitas oleh Admin sebelum bertugas.
- **Pelacakan Real-time (Mobile-First)**: Pelanggan memantau status pesanan (Menunggu → Diterima → Dikerjakan → Selesai) langsung dari browser HP dengan antarmuka navigasi jempol (*bottom navbar*).
- **Fleksibilitas Pembayaran**: Mendukung transfer bank (dengan unggah bukti bayar) serta tunai di lokasi.

---

## 👥 Peran Pengguna & Fitur

### 1. 👤 Pelanggan (Mobile-First)
- **Beranda Interaktif**: Menampilkan kartu pesanan aktif ("Lagi jalan") dengan indikator progres air 4 tahap, tombol bayar cepat, dan riwayat pemesanan ulang.
- **Pemesanan Layanan**: Form pemilihan paket layanan dinamis, penentuan tanggal, jam mulai, alamat, dan catatan khusus.
- **Pesananku**: Daftar riwayat pesanan aktif dan lampau dengan indikator status dot matriks.
- **Detail & Pembayaran**: Unggah bukti bayar transfer bank (JPG/PNG maks 2 MB) atau pilih bayar tunai, serta opsi pembatalan pesanan mandiri selama status masih menunggu.
- **Ulasan & Rating**: Penilaian bintang 1–5 dan komentar untuk pesanan yang telah tuntas.
- **Kelola Profil**: Ganti/hapus foto profil avatar, pembaruan kontak, dan pergantian kata sandi aman.

### 2. 👷 Mitra Petugas
- **Dashboard Kerja**: Ringkasan tugas dan status verifikasi akun.
- **Pesanan Masuk**: Mengambil pesanan yang tersedia dengan proteksi deteksi bentrok jadwal otomatis.
- **Alur Pengerjaan**: Mengubah status pengerjaan secara bertahap (`Terima` → `Kerjakan` → `Selesai`).
- **Ulasan Saya**: Memantau kepuasan dan rating yang diberikan oleh pelanggan.

### 3. 👑 Administrator (Desktop View)
- **Verifikasi Petugas**: Memvalidasi atau menolak pendaftaran mitra petugas baru.
- **Kelola Paket Layanan**: Mengatur nama paket, estimasi durasi jam, deskripsi, dan tarif harga.
- **Konfirmasi Pembayaran**: Memeriksa bukti transfer pelanggan dan mengubah status menjadi `Lunas` atau `Ditolak`.
- **Monitoring Pesanan**: Rekapitulasi seluruh pesanan yang berjalan di sistem.

---

## ⚙️ Persyaratan Sistem

Pastikan perangkat Anda telah terpasang:
- **PHP** >= 8.2 (dengan ekstensi `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`)
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM**
- **PostgreSQL** >= 14 (misal: Postgres.app / Homebrew / Docker)

---

## 🚀 Panduan Instalasi & Menjalankan

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal:

### 1. Clone Repositori
```bash
git clone https://github.com/fathirbrb/Bersihin.git
cd Bersihin
```

### 2. Pasang Dependensi
```bash
# Dependensi PHP
composer install

# Dependensi Frontend
npm install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database PostgreSQL di dalam file `.env`:
```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bersihin
DB_USERNAME=postgres
DB_PASSWORD=secret
```

### 4. Migrasi & Seeder Database
Pastikan database dengan nama `bersihin` sudah dibuat di PostgreSQL Anda, lalu jalankan:
```bash
php artisan migrate --seed
```

### 5. Hubungkan Direktori Storage
Perintah ini wajib dijalankan agar berkas unggahan bukti transfer dan foto profil dapat diakses:
```bash
php artisan storage:link
```

### 6. Jalankan Server Pengembangan
Buka dua terminal terpisah:
```bash
# Terminal 1: Laravel Backend
php artisan serve

# Terminal 2: Vite Asset Bundler
npm run dev
```

Buka peramban dan akses: **[http://localhost:8000](http://localhost:8000)**

---

## 🗄️ Struktur Database

Aplikasi menggunakan skema relasional di PostgreSQL:
- **`users`**: Menyimpan akun pengguna, peran (`admin`, `petugas`, `pelanggan`), status verifikasi petugas, dan path foto profil.
- **`paket_layanan`**: Daftar katalog paket kebersihan, deskripsi, estimasi durasi, dan harga.
- **`pesanan`**: Transaksi pemesanan dengan relasi ke pelanggan, petugas, paket, alamat, waktu mulai, dan status kerja.
- **`pembayaran`**: Metode pembayaran (transfer/tunai), file bukti transfer, dan status konfirmasi.
- **`ulasan`**: Rating bintang 1–5 dan ulasan teks dari pelanggan terhadap pesanan yang telah selesai.

---

## 🔑 Akun Demo / Testing

Setelah menjalankan `php artisan migrate --seed`, akun demo siap pakai:

| Role | Email | Kata Sandi | Keterangan |
|------|-------|------------|------------|
| **Admin** | `admin@bersihin.test` | `password` | Akses penuh dashboard admin |
| **Petugas** | `petugas@bersihin.test` | `password` | Status telah diverifikasi |
| **Pelanggan** | `pelanggan@bersihin.test` | `password` | Akun pelanggan aktif |

---

## 🧪 Menjalankan Automated Tests

Aplikasi dilengkapi pengujian fitur otomatis (Feature & Unit Tests) yang mencakup alur pembayaran, validasi berkas, autentikasi, dan responsivitas endpoint:

```bash
php artisan test
```

Contoh output pengujian:
```text
PASS  Tests\Unit\ExampleTest
PASS  Tests\Feature\ExampleTest
PASS  Tests\Feature\PembayaranTest (11 test case)

Tests: 13 passed (39 assertions)
Duration: ~0.60s
```

---

## 📁 Struktur Proyek Kunci

```
Bersihin/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Controller Admin, Petugas, Pelanggan, Auth, Profil
│   │   ├── Middleware/         # EnsureRole (Proteksi role berlapis)
│   │   └── Requests/           # Form Requests validasi terpusat
│   └── Models/                 # Eloquent Models (User, Pesanan, Paket, Pembayaran, Ulasan)
├── database/
│   ├── migrations/             # Skema tabel database PostgreSQL
│   └── seeders/                # Data awal & dummy
├── public/
│   ├── css/bersihin.css        # Token desain resmi aplikasi
│   └── storage/                # Symlink berkas upload (foto & bukti transfer)
├── resources/
│   └── views/
│       ├── layouts/            # Layout responsif (pelanggan, petugas, admin, auth)
│       ├── pelanggan/          # Beranda, Pesanan, Ulasan, Profil
│       ├── petugas/            # Dashboard, Pesanan masuk, Pesanan saya
│       ├── admin/              # Paket, Verifikasi petugas, Pembayaran, Pesanan
│       ├── auth/               # Login & Register
│       └── welcome.blade.php   # Landing page konversi tinggi
└── routes/
    └── web.php                 # Rute web terorganisir per role
```

---

<p align="center">Dibuat dengan ❤️ oleh <b>Kelompok 4 — Proyek Bersihin</b></p>
