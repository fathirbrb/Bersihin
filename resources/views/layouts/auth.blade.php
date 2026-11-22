<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Masuk') · Bersihin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bersihin.css') }}">
    @stack('styles')
</head>
<body>
    <main class="auth">
        <div class="auth-kotak">
            <a href="{{ route('landing') }}" class="logo">
                <svg width="28" height="28" viewBox="0 0 26 26" aria-hidden="true"><path d="M6 6.5a7 5 0 0 1 14 0" fill="none" stroke="#2453D8" stroke-width="2"/><path d="M3.5 9h19l-2.2 13.2a2 2 0 0 1-2 1.8H7.7a2 2 0 0 1-2-1.8z" fill="#EAF0FF" stroke="#2453D8" stroke-width="2" stroke-linejoin="round"/><path d="M5.2 15.5h15.6l-1.1 6.6a1 1 0 0 1-1 .9H7.3a1 1 0 0 1-1-.9z" fill="#2453D8"/></svg>
                bersihin
            </a>

            @if(session('success'))
                <div class="pesan pesan-ok" role="status">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="pesan pesan-salah" role="alert">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
