# Bersihin: Layanan Kebersihan Rumah

<p align="center">
  <b>Platform pemesanan jasa kebersihan rumah dengan harga pasti per paket, pantauan status pesanan, serta pembayaran transfer dan tunai.</b>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PostgreSQL-14%2B-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Design_System-bersihin.css-2453D8?style=for-the-badge" alt="bersihin.css">
</p>

---

## Daftar Isi
- [Gambaran Umum](#gambaran-umum)
- [Peran Pengguna dan Fitur](#peran-pengguna-dan-fitur)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Panduan Instalasi dan Menjalankan](#panduan-instalasi-dan-menjalankan)
- [Struktur Database](#struktur-database)
- [Akun Demo / Testing](#akun-demo--testing)
- [Menjalankan Automated Tests](#menjalankan-automated-tests)
- [Struktur Proyek Kunci](#struktur-proyek-kunci)

---

## Gambaran Umum

**Bersihin** membantu pelanggan memesan layanan kebersihan rumah dengan alur yang jelas. Fitur utama aplikasi ini:
- **Harga Pasti**: Tarif tetap per paket tanpa tawar-menawar di lokasi.
- **Verifikasi Petugas**: Pendaftaran mitra petugas baru diperiksa dan diverifikasi Admin sebelum dapat menerima pesanan.
- **Pantau Status Pesanan (Mobile-First)**: Pelanggan memantau status pesanan (Menunggu, Diterima, Dikerjakan, hingga Selesai) dari halaman pesanan dengan navigasi bawah (*bottom navbar*).
- **Pilihan Pembayaran**: Mendukung transfer bank (dengan unggah bukti bayar) serta tunai di lokasi.

---

## Peran Pengguna dan Fitur

### 1. Pelanggan (Mobile-First)
- **Beranda**: Menampilkan kartu pesanan aktif ("Lagi jalan") dengan indikator progres 4 tahap, tombol bayar cepat, dan daftar paket untuk pemesanan ulang.
- **Pemesanan Layanan**: Form pemilihan paket layanan, tanggal, jam mulai, alamat, dan catatan khusus.
- **Pesananku**: Daftar pesanan aktif dan riwayat pesanan dengan indikator status.
- **Detail dan Pembayaran**: Unggah bukti bayar transfer bank (JPG/PNG maks 2 MB) atau pilih bayar tunai. Pembayaran yang ditolak bisa dikirim ulang. Pesanan bisa dibatalkan selama masih menunggu dan belum lunas.
- **Ulasan dan Rating**: Penilaian bintang 1-5 dan komentar untuk pesanan yang telah selesai.
- **Kelola Profil**: Ganti atau hapus foto profil, pembaruan kontak, dan pergantian kata sandi.

### 2. Mitra Petugas
- **Dashboard Kerja**: Ringkasan tugas dan status verifikasi akun.
- **Pesanan Masuk**: Mengambil pesanan yang tersedia dengan deteksi bentrok jadwal otomatis.
- **Alur Pengerjaan**: Mengubah status pengerjaan secara bertahap (`Terima` -> `Kerjakan` -> `Selesai`).
- **Ulasan Saya**: Melihat rating dan komentar yang diberikan pelanggan.
- **Profil**: Pengaturan data diri, foto profil, dan kata sandi.

### 3. Administrator (Desktop View)
- **Beranda**: Ringkasan antrean pembayaran dan verifikasi petugas beserta pesanan terbaru.
- **Verifikasi Petugas**: Memverifikasi atau menolak pendaftaran mitra petugas baru.
- **Kelola Paket Layanan**: Mengatur nama paket, estimasi durasi jam, deskripsi, dan tarif harga.
- **Konfirmasi Pembayaran**: Memeriksa bukti transfer pelanggan dan mengubah status menjadi `Lunas` atau `Ditolak`.
- **Monitoring Pesanan**: Rekapitulasi seluruh pesanan dengan penyaring status.

---

## Persyaratan Sistem

Pastikan perangkat sudah terpasang:
- **PHP** >= 8.2 (ekstensi `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`)
- **Composer** >= 2.x
- **Node.js** >= 18.x dan **NPM** (bila membangun aset Vite)
- **PostgreSQL** >= 14

---

## Panduan Instalasi dan Menjalankan

### 1. Clone Repositori
```bash
git clone https://github.com/fathirbrb/Bersihin.git
cd Bersihin
```

### 2. Pasang Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin `.env.example` ke `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan koneksi PostgreSQL dan rekening transfer di `.env`:
```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bersihin
DB_USERNAME=postgres
DB_PASSWORD=secret

BANK_NAMA=
BANK_NOMOR=
BANK_ATAS_NAMA=
```

Selama ketiga isian rekening kosong, halaman pembayaran meminta pelanggan menghubungi admin.

### 4. Migrasi dan Seeder Database
Pastikan database `bersihin` sudah dibuat di PostgreSQL, lalu jalankan:
```bash
php artisan migrate:fresh --seed
```

### 5. Hubungkan Direktori Storage
Jalankan perintah berikut agar foto profil dapat diakses melalui `public/storage`. Bukti bayar disimpan privat dan hanya bisa dibuka admin serta pemilik pesanan.
```bash
php artisan storage:link
```

### 6. Jalankan Server Pengembangan
```bash
php artisan serve
```
*(Jika perlu membangun atau memantau aset `resources/css/app.css`, jalankan `npm run dev` di terminal terpisah. Sebagian besar tampilan utama menggunakan berkas statis `public/css/bersihin.css`.)*

Buka browser dan akses: **[http://localhost:8000](http://localhost:8000)**

---

## Struktur Database

Skema relasional di PostgreSQL terdiri dari tabel utama berikut:
- **`users`**: Data akun pengguna, peran (`admin`, `petugas`, `pelanggan`), status verifikasi petugas, dan path foto profil.
- **`paket_layanan`**: Daftar katalog paket kebersihan, deskripsi, estimasi durasi, dan harga.
- **`pesanan`**: Transaksi pemesanan dengan relasi ke pelanggan, petugas, paket, alamat, waktu mulai, durasi, dan status kerja.
- **`pembayaran`**: Metode pembayaran (`transfer`/`tunai`), path bukti bayar, dan status konfirmasi.
- **`ulasan`**: Rating bintang 1-5 dan ulasan teks dari pelanggan untuk pesanan yang telah selesai.

---

## Akun Demo / Testing

Setelah menjalankan `php artisan migrate:fresh --seed`, akun berikut siap dipakai:

| Role | Email | Kata Sandi | Keterangan |
|---|---|---|---|
| **Admin** | `admin@bersihin.id` | `password` | Akses penuh dashboard admin |
| **Petugas** | `petugas@bersihin.id` | `password` | Status telah diverifikasi |
| **Pelanggan** | `pelanggan@bersihin.id` | `password` | Akun pelanggan aktif |

---

## Menjalankan Automated Tests

Jalankan suite pengujian Laravel dengan:

```bash
php artisan test
```

---

## Struktur Proyek Kunci

```text
Bersihin/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Controller Admin, Petugas, Pelanggan, Auth, Landing
│   │   ├── Middleware/         # EnsureRole (proteksi role berlapis)
│   │   └── Requests/           # Form Requests validasi terpusat
│   └── Models/                 # Eloquent Models (User, Pesanan, PaketLayanan, Pembayaran, Ulasan)
├── config/
│   └── bersihin.php            # Konfigurasi rekening bank aplikasi
├── database/
│   ├── migrations/             # Skema dan constraint tabel database
│   └── seeders/                # Data awal pengguna demo dan paket layanan
├── lang/id/                    # Pesan validasi bahasa Indonesia
├── public/
│   ├── css/bersihin.css        # Token desain utama aplikasi
│   ├── img/                    # Gambar pratinjau landing page
│   └── storage/                # Symlink foto profil
├── resources/
│   └── views/
│       ├── layouts/            # Layout responsif (pelanggan, petugas, admin, auth)
│       ├── pelanggan/          # Beranda, Pesanan, Ulasan, Profil
│       ├── petugas/            # Dashboard, Pesanan masuk, Pesanan saya, Profil
│       ├── admin/              # Beranda, Paket, Verifikasi petugas, Pembayaran, Pesanan, Profil
│       ├── auth/               # Login dan Register
│       └── welcome.blade.php   # Halaman depan (landing page)
└── routes/
    └── web.php                 # Rute web terorganisir per role
```
