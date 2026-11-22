@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<h1>Masuk</h1>
<p class="lead">Pakai email yang terdaftar.</p>

<form action="{{ route('login') }}" method="POST">
    @csrf

    <div class="isian {{ $errors->has('email') ? 'isian-salah' : '' }}">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@email.com" required autofocus>
        @error('email')
            <p class="galat" id="galat-email">{{ $message }}</p>
        @enderror
    </div>

    <div class="isian {{ $errors->has('password') ? 'isian-salah' : '' }}">
        <label for="password">Kata sandi</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>
        @error('password')
            <p class="galat" id="galat-password">{{ $message }}</p>
        @enderror
    </div>

    <div class="baris-tipis">
        <label class="centang">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Ingat aku
        </label>
    </div>

    <button type="submit" class="btn btn-utama btn-penuh">Masuk</button>
</form>

<p class="bawah">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
@endsection
