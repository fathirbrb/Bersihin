@extends('layouts.auth')

@section('title', 'Daftar Akun')
@section('meta-deskripsi', 'Buat akun Bersihin untuk memesan layanan kebersihan.')

@section('content')
    <h1 class="auth-judul">Buat akun baru</h1>
    <p class="auth-subjudul">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>

    <form action="{{ route('register') }}" method="POST" novalidate>
        @csrf

        {{-- Pilih Peran --}}
        <div class="form-grup">
            <span class="form-label">Kamu daftar sebagai</span>
            <div class="role-grup">
                <div class="role-opsi">
                    <input
                        type="radio"
                        id="role_pelanggan"
                        name="role"
                        value="pelanggan"
                        {{ old('role', 'pelanggan') === 'pelanggan' ? 'checked' : '' }}
                    >
                    <label for="role_pelanggan" class="role-label">
                        <span class="ikon">🏠</span>
                        Pelanggan
                        <span style="font-size:0.75rem;font-weight:400;">Pesan layanan</span>
                    </label>
                </div>
                <div class="role-opsi">
                    <input
                        type="radio"
                        id="role_petugas"
                        name="role"
                        value="petugas"
                        {{ old('role') === 'petugas' ? 'checked' : '' }}
                    >
                    <label for="role_petugas" class="role-label">
                        <span class="ikon">🧹</span>
                        Petugas
                        <span style="font-size:0.75rem;font-weight:400;">Kerjakan pesanan</span>
                    </label>
                </div>
            </div>
            @error('role')
                <p class="pesan-error" role="alert">{{ $message }}</p>
            @enderror

            {{-- Info petugas menunggu --}}
            <div id="info-petugas" class="alert alert-info" style="display:none; margin-top:8px; font-size:0.85rem;">
                ⏳ Akun petugas perlu diverifikasi admin sebelum bisa menerima pesanan. Kamu masih bisa masuk, tapi fitur pesanan baru aktif setelah diverifikasi.
            </div>
        </div>

        {{-- Nama Lengkap --}}
        <div class="form-grup">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input
                id="nama"
                type="text"
                name="nama"
                value="{{ old('nama') }}"
                class="form-kontrol @error('nama') is-error @enderror"
                placeholder="Nama lengkap kamu"
                autocomplete="name"
                autofocus
            >
            @error('nama')
                <p class="pesan-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-grup">
            <label for="email" class="form-label">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-kontrol @error('email') is-error @enderror"
                placeholder="kamu@email.com"
                autocomplete="email"
            >
            @error('email')
                <p class="pesan-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor HP --}}
        <div class="form-grup">
            <label for="no_hp" class="form-label">Nomor HP</label>
            <input
                id="no_hp"
                type="tel"
                name="no_hp"
                value="{{ old('no_hp') }}"
                class="form-kontrol @error('no_hp') is-error @enderror"
                placeholder="08xx xxxx xxxx"
                autocomplete="tel"
            >
            @error('no_hp')
                <p class="pesan-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kata Sandi --}}
        <div class="form-grup">
            <label for="password" class="form-label">Kata Sandi</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-kontrol @error('password') is-error @enderror"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
            >
            @error('password')
                <p class="pesan-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Kata Sandi --}}
        <div class="form-grup">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-kontrol"
                placeholder="Ulangi kata sandi"
                autocomplete="new-password"
            >
        </div>

        <button type="submit" class="btn btn-utama" id="btn-daftar">
            Buat Akun
        </button>
    </form>
@endsection

@push('scripts')
<script>
    // Tampilkan info verifikasi saat role petugas dipilih
    const radioPeran = document.querySelectorAll('input[name="role"]');
    const infoPetugas = document.getElementById('info-petugas');

    function perbaruiInfoPeran() {
        const dipilih = document.querySelector('input[name="role"]:checked');
        infoPetugas.style.display = dipilih?.value === 'petugas' ? 'block' : 'none';
    }

    radioPeran.forEach(r => r.addEventListener('change', perbaruiInfoPeran));
    perbaruiInfoPeran(); // jalankan saat halaman load
</script>
@endpush
