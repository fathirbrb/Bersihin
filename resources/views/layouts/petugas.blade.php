<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Petugas') · Bersihin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        :root {
            --ember: #2453D8;
            --ember-tua: #1A3FAE;
            --ember-muda: #EAF0FF;
            --keramik: #FFFFFF;
            --lantai: #F4F6FB;
            --isian: #ECEFF6;
            --tinta: #10183A;
            --tinta-2: #5A6175;
            --garis: rgba(16, 24, 58, .10);
            --tunggu: #7A4D00;
            --tunggu-muda: #FFF4D6;
            --bahaya: #B42318;
            --bahaya-muda: #FDECEA;
            --r-chip: 8px;
            --r-kontrol: 14px;
            --r-kartu: 20px;
            --keluar: cubic-bezier(.23, 1, .32, 1);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { -webkit-font-smoothing: antialiased; }
        body { font-family: 'Lexend', system-ui, sans-serif; background: var(--keramik); color: var(--tinta); font-size: 16px; line-height: 1.6; }
        h1, h2, h3 { font-weight: 600; text-wrap: balance; }
        a { color: var(--ember); text-underline-offset: 3px; }
        :focus-visible { outline: 3px solid var(--ember); outline-offset: 2px; }
        .angka { font-variant-numeric: tabular-nums; }

        .bar { border-bottom: 1px solid var(--garis); }
        .bar-isi { max-width: 960px; margin: 0 auto; padding: 12px 20px; display: flex; flex-wrap: wrap; align-items: center; gap: 8px 24px; }
        .logo { display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: 19px; color: var(--ember); text-decoration: none; letter-spacing: -.01em; }
        .mode { font-size: 12px; font-weight: 500; background: var(--ember-muda); color: var(--ember-tua); padding: 4px 10px; border-radius: 999px; }
        .menu { display: flex; flex-wrap: wrap; gap: 2px; list-style: none; margin-left: auto; }
        .menu a { display: inline-flex; align-items: center; min-height: 44px; padding: 0 12px; border-radius: 12px; color: var(--tinta-2); text-decoration: none; font-size: 15px; }
        .menu a:hover { background: var(--lantai); color: var(--tinta); }
        .menu a.aktif { background: var(--ember-muda); color: var(--ember-tua); font-weight: 500; }
        .nama { font-size: 14px; color: var(--tinta-2); }

        main { max-width: 960px; margin: 0 auto; padding: 28px 20px 72px; }
        .kepala { margin-bottom: 20px; }
        .kepala h1 { font-size: 33px; line-height: 1.15; letter-spacing: -.02em; }
        .kepala p { color: var(--tinta-2); margin-top: 4px; }
        .kepala-baris { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 12px; }
        h2.bagian { font-size: 19px; letter-spacing: -.01em; margin: 32px 0 12px; }

        .pesan { display: grid; gap: 2px; padding: 14px 16px; border-radius: var(--r-kontrol); font-size: 15px; margin-bottom: 20px; }
        .pesan-ok { background: var(--ember-muda); color: var(--tinta); }
        .pesan-tunggu { background: var(--tunggu-muda); color: var(--tunggu); }
        .pesan-salah { background: var(--bahaya-muda); color: var(--bahaya); }

        .btn { display: inline-flex; align-items: center; justify-content: center; min-height: 52px; padding: 0 22px; border: none; border-radius: var(--r-kontrol); font: inherit; font-size: 16px; font-weight: 600; cursor: pointer; text-decoration: none; transition: transform 140ms var(--keluar), background-color 140ms var(--keluar); }
        .btn:active { transform: scale(.97); }
        .btn-utama { background: var(--ember); color: #fff; }
        .btn-utama:hover { background: var(--ember-tua); }
        .btn-kedua { background: var(--ember-muda); color: var(--ember-tua); }
        .btn-kedua:hover { background: #DCE6FF; }
        .btn-teks { background: transparent; color: var(--ember); min-height: 44px; padding: 0 10px; }
        .btn-teks:hover { text-decoration: underline; }
        .btn-penuh { width: 100%; }
        .btn-kecil { min-height: 44px; padding: 0 16px; font-size: 15px; border-radius: 12px; }

        .kartu { background: var(--lantai); border-radius: var(--r-kartu); padding: 20px; }
        .kartu + .kartu { margin-top: 12px; }
        .kartu-fokus { background: var(--ember-muda); }
        .kartu .paket { font-size: 14px; font-weight: 500; color: var(--ember-tua); }
        .kartu .atas { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
        .jam { font-size: 32px; font-weight: 600; letter-spacing: -.02em; line-height: 1.1; margin-top: 4px; font-variant-numeric: tabular-nums; }
        .jam-kecil { font-size: 22px; font-weight: 600; letter-spacing: -.01em; line-height: 1.2; font-variant-numeric: tabular-nums; }
        .tgl { font-size: 15px; }
        .info { display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 6px 16px; font-size: 15px; margin: 16px 0; }
        .info dt { color: var(--tinta-2); }
        .info dd { font-weight: 500; overflow-wrap: anywhere; }
        .aksi { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .aksi-tumpuk { display: grid; gap: 4px; justify-items: center; }
        .aksi-tumpuk form { width: 100%; }

        .status { display: inline-flex; align-items: center; gap: 8px; padding: 5px 10px; border-radius: var(--r-chip); font-size: 13px; font-weight: 500; white-space: nowrap; }
        .status .m { display: inline-grid; grid-template-columns: repeat(4, 4px); gap: 2px; }
        .status .m i { height: 10px; border-radius: 2px; background: currentColor; opacity: .22; }
        .status .m i.on { opacity: 1; }
        .s-tunggu { background: var(--tunggu-muda); color: var(--tunggu); }
        .s-jalan { background: var(--ember-muda); color: var(--ember-tua); }
        .s-beres { background: var(--ember); color: #fff; }
        .s-batal { background: var(--bahaya-muda); color: var(--bahaya); }

        .level .tabung { height: 12px; border-radius: 6px; background: var(--keramik); overflow: hidden; }
        .level .tabung span { display: block; height: 100%; border-radius: 6px; background: var(--ember); }
        .level .label { display: flex; justify-content: space-between; font-size: 13px; color: var(--tinta-2); margin-top: 8px; }
        .level .label b { color: var(--tinta); font-weight: 500; }

        .daftar { background: var(--lantai); border-radius: var(--r-kartu); padding: 4px 20px; }
        .baris { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 16px 0; text-decoration: none; color: inherit; }
        .baris + .baris { border-top: 1px solid var(--garis); }
        a.baris:hover .judul-baris { color: var(--ember); }
        .judul-baris { display: block; font-weight: 500; }
        .sub-baris { display: block; font-size: 14px; color: var(--tinta-2); }

        .kosong { background: var(--lantai); border-radius: var(--r-kartu); padding: 40px 20px; text-align: center; }
        .kosong svg { display: block; margin: 0 auto 12px; }
        .kosong b { display: block; font-size: 18px; font-weight: 600; }
        .kosong span { display: block; color: var(--tinta-2); margin: 4px auto 18px; max-width: 36ch; }

        .ringkas { display: flex; flex-wrap: wrap; gap: 8px 24px; font-size: 15px; color: var(--tinta-2); margin-top: 16px; }
        .ringkas b { color: var(--tinta); font-weight: 600; font-variant-numeric: tabular-nums; }
        .bintang { color: var(--ember); letter-spacing: 1px; }

        @media (max-width: 640px) {
            .bar-isi { padding: 10px 16px; }
            .menu { margin-left: 0; width: 100%; }
            .nama { display: none; }
            main { padding: 20px 16px 56px; }
            .kepala h1 { font-size: 27px; }
            .jam { font-size: 30px; }
            .kartu { padding: 18px; }
            .daftar { padding: 4px 16px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .btn { transition: background-color 140ms; }
            .btn:active { transform: none; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="bar">
        <div class="bar-isi">
            <a href="{{ route('petugas.dashboard') }}" class="logo">
                @include('petugas.partials.logo', ['ukuran' => 24])
                bersihin
            </a>
            <span class="mode">Petugas</span>
            <ul class="menu">
                <li><a href="{{ route('petugas.dashboard') }}" class="{{ request()->routeIs('petugas.dashboard') ? 'aktif' : '' }}">Beranda</a></li>
                <li><a href="{{ route('petugas.pesanan-masuk') }}" class="{{ request()->routeIs('petugas.pesanan-masuk') ? 'aktif' : '' }}">Pesanan masuk</a></li>
                <li><a href="{{ route('petugas.pesanan-saya') }}" class="{{ request()->routeIs('petugas.pesanan-saya', 'petugas.pesanan.show') ? 'aktif' : '' }}">Pesanan saya</a></li>
                <li><a href="{{ route('petugas.ulasan') }}" class="{{ request()->routeIs('petugas.ulasan') ? 'aktif' : '' }}">Ulasan</a></li>
            </ul>
            <span class="nama">{{ Auth::user()->nama }}</span>
        </div>
    </header>

    <main>
        @if(session('success'))
            <div class="pesan pesan-ok" role="status">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="pesan pesan-salah" role="alert">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
