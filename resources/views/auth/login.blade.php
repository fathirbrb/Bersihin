@extends('layouts.auth')

@section('title', 'Masuk')
@section('meta-deskripsi', 'Masuk ke akun Bersihin kamu.')

@section('content')
    <h1 class="auth-judul">Selamat datang kembali 👋</h1>
    <p class="auth-subjudul">Masukkan email dan kata sandi untuk melanjutkan.</p>

    <form action="{{ route('login') }}" method="POST" novalidate>
        @csrf

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
                autofocus
            >
            @error('email')
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
                placeholder="••••••••"
                autocomplete="current-password"
            >
            @error('password')
                <p class="pesan-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ingat Saya --}}
        <div class="form-grup" style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" id="ingat_saya" name="ingat_saya" style="width:18px;height:18px;cursor:pointer;">
            <label for="ingat_saya" style="font-size:0.9rem;cursor:pointer;">Ingat saya di perangkat ini</label>
        </div>

        <button type="submit" class="btn btn-utama" id="btn-masuk">
            Masuk
        </button>
    </form>

    <div class="divider">atau</div>

    <div class="auth-footer">
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar sekarang</a>
    </div>
@endsection
