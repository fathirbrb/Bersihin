<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Pelanggan') · Bersihin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bersihin.css') }}">
    @stack('styles')
</head>
<body class="app">
    <header class="bar">
        <div class="bar-isi">
            <a href="{{ route('pelanggan.beranda') }}" class="logo">
                <svg width="24" height="24" viewBox="0 0 26 26" aria-hidden="true"><path d="M6 6.5a7 5 0 0 1 14 0" fill="none" stroke="#2453D8" stroke-width="2"/><path d="M3.5 9h19l-2.2 13.2a2 2 0 0 1-2 1.8H7.7a2 2 0 0 1-2-1.8z" fill="#EAF0FF" stroke="#2453D8" stroke-width="2" stroke-linejoin="round"/><path d="M5.2 15.5h15.6l-1.1 6.6a1 1 0 0 1-1 .9H7.3a1 1 0 0 1-1-.9z" fill="#2453D8"/></svg>
                bersihin
            </a>
            <nav aria-label="Menu pelanggan">
                <ul class="tab">
                    <li>
                        <a href="{{ route('pelanggan.beranda') }}" class="{{ request()->routeIs('pelanggan.beranda') ? 'aktif' : '' }}" {!! request()->routeIs('pelanggan.beranda') ? 'aria-current="page"' : '' !!}>
                            <span class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 10.5 12 4l8 6.5V19a1 1 0 0 1-1 1h-4v-5h-6v5H5a1 1 0 0 1-1-1z"/></svg></span>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pelanggan.pesanan.create') }}" class="{{ request()->routeIs('pelanggan.pesanan.create') ? 'aktif' : '' }}" {!! request()->routeIs('pelanggan.pesanan.create') ? 'aria-current="page"' : '' !!}>
                            <span class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 8a5 4 0 0 1 10 0"/><path d="M4 8h16l-1.8 11.2a1.5 1.5 0 0 1-1.5 1.3H7.3a1.5 1.5 0 0 1-1.5-1.3z"/><path d="M12 11v6M9 14h6"/></svg></span>
                            Pesan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pelanggan.pesanan.index') }}" class="{{ request()->routeIs('pelanggan.pesanan.index', 'pelanggan.pesanan.show') ? 'aktif' : '' }}" {!! request()->routeIs('pelanggan.pesanan.index', 'pelanggan.pesanan.show') ? 'aria-current="page"' : '' !!}>
                            <span class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="4.5" width="14" height="16" rx="2"/><path d="M9 3.5h6v3H9zM9 11h6M9 15h4"/></svg></span>
                            Pesananku
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pelanggan.ulasan.index') }}" class="{{ request()->routeIs('pelanggan.ulasan.*') ? 'aktif' : '' }}" {!! request()->routeIs('pelanggan.ulasan.*') ? 'aria-current="page"' : '' !!}>
                            <span class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 4 2.4 4.9 5.4.8-3.9 3.8.9 5.4L12 16.4l-4.8 2.5.9-5.4-3.9-3.8 5.4-.8z"/></svg></span>
                            Ulasan
                        </a>
                    </li>
                </ul>
            </nav>
            <a href="{{ route('pelanggan.profil') }}" class="avatar" {!! request()->routeIs('pelanggan.profil') ? 'aria-current="page"' : '' !!} aria-label="Profil {{ Auth::user()->nama }}">
                @if(Auth::user()->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="{{ Auth::user()->nama }}">
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8.5" r="3.5"/><path d="M5 19.5a7 7 0 0 1 14 0"/></svg>
                @endif
            </a>
        </div>
    </header>

    <main class="@yield('main-class', '')">
        @if(session('success'))
            <div class="pesan pesan-ok" role="status">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="pesan pesan-salah" role="alert">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="pesan pesan-salah" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
