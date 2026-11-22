# 📋 Progress Report — Proyek "Bersihin" (Laravel)

> Terakhir diperbarui pada: **22 November 2026**
> Framework: **Laravel 11** | Database: **PostgreSQL** | Frontend: **Blade + bersihin.css (Design System)**

---

## 🧩 Gambaran Umum

**Bersihin** adalah aplikasi manajemen layanan kebersihan rumah berbasis web. Terdapat tiga peran pengguna:

| Role | Deskripsi |
|------|-----------|
| **Admin** | Mengelola petugas, paket layanan, pesanan, dan konfirmasi pembayaran |
| **Petugas** | Melihat & mengambil pesanan, mengubah status pengerjaan, melihat ulasan |
| **Pelanggan** | Melihat beranda, memesan layanan, membayar, melacak progres, mengelola profil, memberikan ulasan |

---

## ✅ Fitur yang Sudah Selesai & Disesuaikan dengan Mockup

### 🌟 Landing Page Publik (`welcome.blade.php`)
- [x] Dioptimalkan menggunakan skill `landing-page` & `no-ai-design-slop`
- [x] Hero section dengan value proposition jelas & sinyal kepercayaan
- [x] Kartu pratinjau antarmuka aplikasi interaktif (progres pembersihan & estimasi biaya)
- [x] 3 Nilai keunggulan: Harga pasti & transparan, petugas terverifikasi, pelacakan HP real-time
- [x] Daftar paket layanan dinamis dari database
- [x] Visualisasi 4 tahap pengerjaan (Menunggu → Diterima → Dikerjakan → Beres) & metode bayar
- [x] Section Tanya Jawab (FAQ) interaktif native `<details>` tanpa library berat
- [x] Ajakan bergabung mitra petugas
- [x] Header & Footer navigasi responsif

### 🔐 Autentikasi (F-01) & Desain Auth Baru
- [x] Halaman Login modern (`resources/views/auth/login.blade.php`) dengan `bersihin.css`
- [x] Halaman Registrasi (`resources/views/auth/register.blade.php`) dengan seleksi peran (Pelanggan / Petugas) dan catatan verifikasi petugas
- [x] Layout Auth terpusat (`resources/views/layouts/auth.blade.php`)
- [x] Middleware terpusat `EnsureRole` (duplikat `EnsureUserHasRole` telah dibersihkan)
- [x] Redirect otomatis sesuai role (`pelanggan.beranda`, `petugas.dashboard`, `admin.paket.index`)

---

### 👤 Modul Pelanggan (100% Sesuai Mockup Mobile-First)

#### 1. Beranda Pelanggan (`pelanggan/beranda.blade.php`)
- [x] Kartu pesanan aktif ("Lagi jalan") dengan indikator level langkah 1 s/d 4 (25%, 50%, 75%, 100%)
- [x] Tombol cepat Bayar (bila belum bayar) dan Detail pesanan
- [x] Tampilan state kosong (`.kosong` dengan ikon ember khas Bersihin)
- [x] Daftar paket untuk pemesanan ulang cepat ("Pesan lagi")

#### 2. Buat Pesanan (`pelanggan/pesanan/create.blade.php`)
- [x] Pilihan paket radio button interaktif lengkap dengan durasi dan harga
- [x] Mendukung query parameter `?paket=ID` dari kartu beranda
- [x] Input alamat, tanggal, jam mulai, dan catatan opsional
- [x] Penanganan pesan galat validasi terstruktur

#### 3. Pesananku / Daftar Pesanan (`pelanggan/pesanan/index.blade.php`)
- [x] Pemisahan daftar "Lagi jalan" vs "Riwayat" (Selesai / Dibatalkan)
- [x] Status badge dengan titik matriks progres (`.status.s-jalan`, `.status.s-beres`, `.status.s-tunggu`, `.status.s-batal`)
- [x] State kosong saat belum pernah memesan

#### 4. Detail Pesanan (`pelanggan/pesanan/show.blade.php`)
- [x] Info lengkap jadwal, durasi pengerjaan, alamat, dan nomor kontak petugas
- [x] Level progres visual interaktif
- [x] Formulir pembayaran transfer (unggah bukti bayar JPG/PNG maks 2MB) dan tunai
- [x] Status pembayaran (`menunggu`, `lunas`, `ditolak`)
- [x] Bagian ulasan setelah pesanan selesai
- [x] Modal native `<dialog>` untuk pembatalan pesanan jika status masih menunggu

#### 5. Ulasan (`pelanggan/ulasan/index.blade.php` & `create.blade.php`)
- [x] Tab pesanan "Belum diulas" dan "Sudah diulas"
- [x] Formulir rating bintang interaktif 1–5 dengan label teks dinamis (`Kecewa` s/d `Mantap`)
- [x] Modal native `<dialog>` untuk konfirmasi hapus ulasan

#### 6. Profil Pelanggan (`pelanggan/profil.blade.php`)
- [x] Pratinjau langsung avatar foto profil
- [x] Unggah foto profil baru dan tombol hapus foto
- [x] Perubahan data diri (nama, email, no HP)
- [x] Formulir penggantian kata sandi dengan verifikasi kata sandi lama
- [x] Tombol keluar (logout)

---

### 👑 Admin & 👷 Petugas
- [x] Verifikasi petugas (`admin/petugas/index.blade.php`)
- [x] Kelola paket layanan (`admin/paket_layanan.blade.php`)
- [x] Konfirmasi pembayaran (`admin/pembayaran.blade.php`)
- [x] Daftar seluruh pesanan (`admin/pesanan.blade.php`)
- [x] Dashboard dan alur pengerjaan petugas (Terima → Kerjakan → Selesai)

---

## 🗄️ Database & Model

- **PostgreSQL Connection**: `127.0.0.1:5432`, database `bersihin`
- **Migrasi Tambahan**: `add_foto_to_users_table` menambahkan kolom `foto` nullable pada tabel `users`
- **Storage Symlink**: `php artisan storage:link` aktif menghubungkan `public/storage` ke `storage/app/public`

---

## 🧪 Hasil Pengujian (Test Suite)

```bash
php artisan test
```
```text
PASS  Tests\Unit\ExampleTest
PASS  Tests\Feature\ExampleTest
PASS  Tests\Feature\PembayaranTest (11 test case)

Tests: 13 passed (39 assertions)
Duration: 0.63s
```
Semua test case lulus 100%!
