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
        .logo { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; font-weight: 700; font-size: 19px; color: var(--ember); text-decoration: none; letter-spacing: -.01em; }
        .mode { font-size: 12px; font-weight: 500; background: var(--ember-muda); color: var(--ember-tua); padding: 4px 10px; border-radius: 999px; }
        .bar-isi > nav { margin-left: auto; }
        .tab { display: flex; gap: 2px; list-style: none; }
        .tab a { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; padding: 0 12px; border-radius: 12px; color: var(--tinta-2); text-decoration: none; font-size: 15px; }
        .tab a:hover { background: var(--lantai); color: var(--tinta); }
        .tab a.aktif { background: var(--ember-muda); color: var(--ember-tua); font-weight: 500; }
        .tab svg { width: 20px; height: 20px; flex: none; }
        .tab .ikon { display: inline-grid; place-items: center; }
        .avatar { display: inline-grid; place-items: center; flex: none; width: 44px; height: 44px; border-radius: 50%; background: var(--ember-muda); color: var(--ember-tua); overflow: hidden; }
        .avatar svg { width: 24px; height: 24px; }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        a.avatar:hover { box-shadow: 0 0 0 2px var(--keramik), 0 0 0 4px var(--ember); }

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

        /* Di HP tab pindah ke bawah */
        @media (max-width: 859px) {
            .bar-isi { padding: 10px 16px; flex-wrap: nowrap; }
            .bar-isi > nav { margin-left: 0; }
            .bar-isi > .avatar { margin-left: auto; }
            .tab { position: fixed; left: 0; right: 0; bottom: 0; z-index: 20; display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; padding: 6px 4px calc(6px + env(safe-area-inset-bottom)); background: var(--keramik); border-top: 1px solid var(--garis); }
            .tab a { flex-direction: column; justify-content: center; gap: 2px; min-height: 56px; padding: 0 4px; font-size: 12px; line-height: 1.2; text-align: center; }
            .tab a:hover, .tab a.aktif { background: transparent; }
            .tab .ikon { width: 56px; height: 30px; border-radius: 10px; transition: background-color 140ms var(--keluar); }
            .tab a.aktif .ikon { background: var(--ember-muda); }
            .tab svg { width: 22px; height: 22px; }
            main { padding-bottom: calc(96px + env(safe-area-inset-bottom)); }
        }
        @media (max-width: 640px) {
            .bar-isi { padding: 10px 16px; }
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
            @php
                $tab = [
                    ['petugas.dashboard', ['petugas.dashboard'], 'Beranda', '<path d="M4 10.5 12 4l8 6.5V19a1 1 0 0 1-1 1h-4v-5h-6v5H5a1 1 0 0 1-1-1z"/>'],
                    ['petugas.pesanan-masuk', ['petugas.pesanan-masuk'], 'Pesanan', '<path d="M3 13h5l1.5 3h5l1.5-3h5"/><path d="M5.5 5h13L21 13v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5z"/>'],
                    ['petugas.pesanan-saya', ['petugas.pesanan-saya', 'petugas.pesanan.show'], 'Kerjaanku', '<rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10h16M8 3v4M16 3v4"/>'],
                    ['petugas.ulasan', ['petugas.ulasan'], 'Ulasan', '<path d="m12 4 2.4 4.9 5.4.8-3.9 3.8.9 5.4L12 16.4l-4.8 2.5.9-5.4-3.9-3.8 5.4-.8z"/>'],
                ];
            @endphp
            <nav aria-label="Menu petugas">
                <ul class="tab">
                    @foreach($tab as [$route, $aktif, $label, $ikon])
                        <li>
                            <a href="{{ route($route) }}" @if(request()->routeIs(...$aktif)) class="aktif" aria-current="page" @endif>
                                <span class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $ikon !!}</svg></span>{{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
            {{-- Ikon profil, jadi link setelah route profil ada --}}
            @php $ikonProfil = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8.5" r="3.5"/><path d="M5 19.5a7 7 0 0 1 14 0"/></svg>'; @endphp
            @if(Route::has('petugas.profil'))
                <a href="{{ route('petugas.profil') }}" class="avatar" aria-label="Profil {{ Auth::user()->nama }}" @if(request()->routeIs('petugas.profil')) aria-current="page" @endif>{!! $ikonProfil !!}</a>
            @else
                <span class="avatar" role="img" aria-label="{{ Auth::user()->nama }}">{!! $ikonProfil !!}</span>
            @endif
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
