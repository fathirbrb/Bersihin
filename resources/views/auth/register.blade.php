@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
<h1>Bikin akun</h1>
<p class="lead">Sekali daftar, langsung bisa pesan.</p>

<form action="{{ route('register') }}" method="POST">
    @csrf

    <fieldset class="pilihan isian">
        <legend>Daftar sebagai</legend>
        <div class="segmen">
            <label class="opsi">
                <input type="radio" name="role" value="pelanggan" {{ old('role', 'pelanggan') === 'pelanggan' ? 'checked' : '' }} onchange="toggleRole(false)"> Pelanggan
            </label>
            <label class="opsi">
                <input type="radio" name="role" value="petugas" id="peran-petugas" {{ old('role') === 'petugas' ? 'checked' : '' }} onchange="toggleRole(true)"> Petugas
            </label>
        </div>
    </fieldset>

    <div class="pesan pesan-tunggu" id="catatan-petugas" {{ old('role') === 'petugas' ? '' : 'hidden' }}>
        Akun petugas perlu diverifikasi admin dulu sebelum bisa menerima pesanan.
    </div>

    <div class="isian {{ $errors->has('nama') ? 'isian-salah' : '' }}">
        <label for="nama">Nama lengkap</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" autocomplete="name" required autofocus>
        @error('nama')
            <p class="galat">{{ $message }}</p>
        @enderror
    </div>

    <div class="isian {{ $errors->has('email') ? 'isian-salah' : '' }}">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@email.com" required>
        @error('email')
            <p class="galat" id="galat-email">{{ $message }}</p>
        @enderror
    </div>

    <div class="isian {{ $errors->has('no_hp') ? 'isian-salah' : '' }}">
        <label for="no_hp">Nomor HP</label>
        <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" autocomplete="tel" inputmode="tel" placeholder="08xxxxxxxxxx" required>
        @error('no_hp')
            <p class="galat">{{ $message }}</p>
        @enderror
    </div>

    <div class="isian {{ $errors->has('password') ? 'isian-salah' : '' }}">
        <label for="password">Kata sandi</label>
        <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required>
        <p class="bantu">Minimal 8 karakter.</p>
        @error('password')
            <p class="galat">{{ $message }}</p>
        @enderror
    </div>

    <div class="isian">
        <label for="password_confirmation">Ulangi kata sandi</label>
        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
    </div>

    <button type="submit" class="btn btn-utama btn-penuh">Daftar</button>
</form>

<p class="bawah">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>

@push('scripts')
<script>
    function toggleRole(isPetugas) {
        const info = document.getElementById('catatan-petugas');
        if (info) {
            info.hidden = !isPetugas;
        }
    }
</script>
@endpush
@endsection
