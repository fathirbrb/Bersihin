<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') · Bersihin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
/* Token warna dan ukuran, sama dengan layout petugas */
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

/* Dasar */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { -webkit-font-smoothing: antialiased; }
body { font-family: 'Lexend', system-ui, sans-serif; background: var(--keramik); color: var(--tinta); font-size: 16px; line-height: 1.6; }
h1, h2, h3 { font-weight: 600; text-wrap: balance; }
a { color: var(--ember); text-underline-offset: 3px; }
:focus-visible { outline: 3px solid var(--ember); outline-offset: 2px; }
.angka { font-variant-numeric: tabular-nums; }
.sr { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }
[hidden] { display: none !important; }

/* Bar atas */
.bar { border-bottom: 1px solid var(--garis); background: var(--keramik); }
.bar-isi { max-width: 960px; margin: 0 auto; padding: 12px 20px; display: flex; flex-wrap: wrap; align-items: center; gap: 8px 24px; }
.logo { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; font-weight: 700; font-size: 19px; color: var(--ember); text-decoration: none; letter-spacing: -.01em; }
.mode { font-size: 12px; font-weight: 500; background: var(--ember-muda); color: var(--ember-tua); padding: 4px 10px; border-radius: 999px; }
.menu { display: flex; flex-wrap: wrap; gap: 2px; list-style: none; margin-left: auto; }
.menu a { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; padding: 0 12px; border-radius: 12px; color: var(--tinta-2); text-decoration: none; font-size: 15px; }
.menu a:hover { background: var(--lantai); color: var(--tinta); }
.menu a.aktif { background: var(--ember-muda); color: var(--ember-tua); font-weight: 500; }
.nama { font-size: 14px; color: var(--tinta-2); }

/* Ikon profil di bar atas */
.avatar { display: inline-grid; place-items: center; flex: none; width: 44px; height: 44px; border-radius: 50%; background: var(--ember-muda); color: var(--ember-tua); overflow: hidden; transition: box-shadow 140ms var(--keluar); text-decoration: none; }
.avatar svg { width: 24px; height: 24px; }
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.avatar:hover, .avatar[aria-current=page] { box-shadow: 0 0 0 2px var(--keramik), 0 0 0 4px var(--ember); }
.avatar-besar { width: 96px; height: 96px; }
.avatar-besar svg { width: 48px; height: 48px; }

/* Halaman profil */
.profil-foto { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; margin-bottom: 20px; }
.profil-foto > div { flex: 1 1 160px; min-width: 0; }
.profil-foto .aksi { gap: 4px 8px; }
.profil-foto label.btn { cursor: pointer; }
/* Tombol pilih file, ganti input bawaan browser */
.pilih-berkas { position: relative; cursor: pointer; }
.pilih-berkas input[type=file] { position: absolute; opacity: 0; width: 1px; height: 1px; }
.pilih-berkas:has(input:focus-visible) { outline: 3px solid var(--ember); outline-offset: 2px; }
.berkas { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.berkas .sub-baris { min-width: 0; overflow-wrap: anywhere; }
.peran { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; font-size: 15px; color: var(--tinta-2); }

/* Tab bawah pelanggan & petugas */
.bar-isi > nav { margin-left: auto; }
.tab { display: flex; gap: 2px; list-style: none; }
.tab a { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; padding: 0 12px; border-radius: 12px; color: var(--tinta-2); text-decoration: none; font-size: 15px; }
.tab a:hover { background: var(--lantai); color: var(--tinta); }
.tab a.aktif { background: var(--ember-muda); color: var(--ember-tua); font-weight: 500; }
.tab svg { width: 20px; height: 20px; flex: none; }
.tab .ikon { display: inline-grid; place-items: center; }

/* Isi halaman */
main { max-width: 960px; margin: 0 auto; padding: 28px 20px 72px; }
main.lebar { max-width: 1120px; }
/* Form tetap rata kiri, sejajar header */
main.sempit > * { max-width: 640px; }

/* Dua kolom di desktop, bertumpuk di HP */
.dua-kolom { display: grid; gap: 0 32px; align-items: start; }
.dua-kolom > :first-child > .bagian:first-child { margin-top: 8px; }
@media (min-width: 860px) {
    .dua-kolom { grid-template-columns: minmax(0, 1.35fr) minmax(0, 1fr); }
    .dua-kolom > * > .bagian:first-child { margin-top: 0; }
}
.kepala { margin-bottom: 20px; }
.kepala h1 { font-size: 33px; line-height: 1.15; letter-spacing: -.02em; }
.kepala p { color: var(--tinta-2); margin-top: 4px; }
.kepala-baris { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 12px; }
.kembali { display: inline-flex; align-items: center; gap: 6px; min-height: 44px; margin: -8px 0 4px -6px; padding: 0 6px; font-size: 15px; color: var(--tinta-2); text-decoration: none; border-radius: 10px; }
.kembali:hover { color: var(--tinta); background: var(--lantai); }
h2.bagian { font-size: 19px; letter-spacing: -.01em; margin: 32px 0 12px; }

/* Pesan kilat */
.pesan { display: grid; gap: 2px; padding: 14px 16px; border-radius: var(--r-kontrol); font-size: 15px; margin-bottom: 20px; }
.pesan-ok { background: var(--ember-muda); color: var(--tinta); }
.pesan-tunggu { background: var(--tunggu-muda); color: var(--tunggu); }
.pesan-salah { background: var(--bahaya-muda); color: var(--bahaya); }
.pesan .aksi { margin-top: 8px; }

/* Tombol */
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 52px; padding: 0 22px; border: none; border-radius: var(--r-kontrol); font: inherit; font-size: 16px; font-weight: 600; cursor: pointer; text-decoration: none; transition: transform 140ms var(--keluar), background-color 140ms var(--keluar); }
.btn:active { transform: scale(.97); }
.btn:disabled { opacity: .6; cursor: progress; }
.btn-utama { background: var(--ember); color: #fff; }
.btn-utama:hover { background: var(--ember-tua); }
.btn-kedua { background: var(--ember-muda); color: var(--ember-tua); }
.btn-kedua:hover { background: #DCE6FF; }
.btn-teks { background: transparent; color: var(--ember); min-height: 44px; padding: 0 10px; }
.btn-teks:hover { text-decoration: underline; }
.btn-bahaya { background: var(--bahaya-muda); color: var(--bahaya); }
.btn-bahaya:hover { background: #FAD9D5; }
.btn-teks.bahaya { color: var(--bahaya); }
.btn-penuh { width: 100%; }
.btn-kecil { min-height: 44px; padding: 0 16px; font-size: 15px; border-radius: 12px; }

/* Kartu dan daftar */
.kartu { background: var(--lantai); border-radius: var(--r-kartu); padding: 20px; }
.kartu + .kartu { margin-top: 12px; }
.kartu > :last-child { margin-bottom: 0; }
.kartu-fokus { background: var(--ember-muda); }
.kartu-fokus .btn-kedua { background: var(--keramik); }
.kartu-fokus .btn-kedua:hover { background: #F7F9FF; }
.aksi-dua { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 16px; }
.kartu .paket { font-size: 14px; font-weight: 500; color: var(--ember-tua); }
.kartu .atas { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
.jam { font-size: 32px; font-weight: 600; letter-spacing: -.02em; line-height: 1.1; margin-top: 4px; font-variant-numeric: tabular-nums; }
.jam-kecil { font-size: 22px; font-weight: 600; letter-spacing: -.01em; line-height: 1.2; font-variant-numeric: tabular-nums; }
.tgl { font-size: 15px; }
.info { display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 6px 16px; font-size: 15px; margin: 16px 0; }
.info dt { color: var(--tinta-2); }
.info dd { font-weight: 500; overflow-wrap: anywhere; }
.aksi { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

.daftar { background: var(--lantai); border-radius: var(--r-kartu); padding: 4px 20px; }
.baris { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 16px 0; text-decoration: none; color: inherit; }
.baris + .baris { border-top: 1px solid var(--garis); }
a.baris:hover .judul-baris { color: var(--ember); }
.judul-baris { display: block; font-weight: 500; }
.sub-baris { display: block; font-size: 14px; color: var(--tinta-2); }
.harga { font-weight: 600; font-variant-numeric: tabular-nums; white-space: nowrap; }

.kosong { background: var(--lantai); border-radius: var(--r-kartu); padding: 40px 20px; text-align: center; }
.kosong svg { display: block; margin: 0 auto 12px; }
.kosong b { display: block; font-size: 18px; font-weight: 600; }
.kosong span { display: block; color: var(--tinta-2); margin: 4px auto 18px; max-width: 36ch; }

.ringkas { display: flex; flex-wrap: wrap; gap: 8px 24px; font-size: 15px; color: var(--tinta-2); margin-top: 16px; }
.ringkas b { color: var(--tinta); font-weight: 600; font-variant-numeric: tabular-nums; }
.bintang { color: var(--ember); letter-spacing: 1px; }

/* Chip status bermotif batang ember */
.status { display: inline-flex; align-items: center; gap: 8px; padding: 5px 10px; border-radius: var(--r-chip); font-size: 13px; font-weight: 500; white-space: nowrap; }
.status .m { display: inline-grid; grid-template-columns: repeat(4, 4px); gap: 2px; }
.status .m i { height: 10px; border-radius: 2px; background: currentColor; opacity: .22; }
.status .m i.on { opacity: 1; }
.s-tunggu { background: var(--tunggu-muda); color: var(--tunggu); }
.s-jalan { background: var(--ember-muda); color: var(--ember-tua); }
.s-beres { background: var(--ember); color: #fff; }
.s-batal { background: var(--bahaya-muda); color: var(--bahaya); }
.s-netral { background: var(--isian); color: var(--tinta-2); }

/* Progres pesanan seperti air di ember */
.level .tabung { height: 12px; border-radius: 6px; background: var(--keramik); overflow: hidden; }
.level .tabung span { display: block; height: 100%; border-radius: 6px; background: var(--ember); }
.level .label { display: flex; justify-content: space-between; font-size: 13px; color: var(--tinta-2); margin-top: 8px; }
.level .label b { color: var(--tinta); font-weight: 500; }

/* Formulir */
.isian { display: grid; gap: 6px; margin-bottom: 18px; }
.isian > label, .judul-isian { font-size: 15px; font-weight: 500; }
.isian .bantu { font-size: 13px; color: var(--tinta-2); }
.isian .galat { font-size: 14px; color: var(--bahaya); }
.isian-dua { display: grid; grid-template-columns: 1fr 1fr; gap: 0 12px; }
input[type=text], input[type=email], input[type=password], input[type=tel], input[type=date], input[type=time], input[type=number], input[type=file], textarea, select {
    width: 100%; min-height: 52px; padding: 12px 16px; border: 1.5px solid transparent; border-radius: var(--r-kontrol);
    background: var(--isian); color: var(--tinta); font: inherit; font-size: 16px;
}
textarea { min-height: 104px; resize: vertical; }
input::placeholder, textarea::placeholder { color: var(--tinta-2); }
input:hover, textarea:hover, select:hover { border-color: rgba(16, 24, 58, .18); }
input:focus, textarea:focus, select:focus { border-color: var(--ember); background: var(--keramik); }
.isian-salah input, .isian-salah textarea { border-color: var(--bahaya); background: var(--bahaya-muda); }
input[type=file] { padding: 12px; font-size: 15px; }
input[type=file]::file-selector-button { font: inherit; font-weight: 500; border: none; border-radius: 10px; background: var(--ember-muda); color: var(--ember-tua); padding: 6px 12px; margin-right: 12px; cursor: pointer; }
.centang { display: inline-flex; align-items: center; gap: 10px; min-height: 44px; font-size: 15px; cursor: pointer; }
.centang input { width: 20px; height: 20px; accent-color: var(--ember); }

/* Pilihan berbentuk kartu (radio) */
.pilihan { display: grid; gap: 8px; border: none; }
.pilihan legend { font-size: 15px; font-weight: 500; margin-bottom: 6px; }
.opsi { position: relative; display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 14px 16px; border-radius: var(--r-kontrol); background: var(--lantai); border: 1.5px solid transparent; cursor: pointer; transition: border-color 140ms var(--keluar), background-color 140ms var(--keluar); }
.opsi input { position: absolute; opacity: 0; width: 1px; height: 1px; }
.opsi:hover { border-color: rgba(16, 24, 58, .18); }
.opsi:has(input:checked) { background: var(--ember-muda); border-color: var(--ember); }
.opsi:has(input:focus-visible) { outline: 3px solid var(--ember); outline-offset: 2px; }
.kartu .opsi { background: var(--keramik); }
.kartu .opsi:has(input:checked) { background: var(--ember-muda); }
.segmen { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.segmen .opsi { justify-content: center; text-align: center; min-height: 52px; padding: 10px 12px; font-weight: 500; }

/* Bintang rating */
.rating { display: flex; gap: 4px; border: none; }
.rating label { display: grid; place-items: center; width: 52px; height: 52px; border-radius: 12px; cursor: pointer; color: #878EA1; }
.rating label svg { width: 36px; height: 36px; fill: transparent; stroke: currentColor; stroke-width: 1.6; stroke-linejoin: round; }
.rating label:hover { background: var(--lantai); }
.rating input { position: absolute; opacity: 0; width: 1px; height: 1px; }
.rating input:focus-visible + label { outline: 3px solid var(--ember); outline-offset: 2px; }
.rating:has(#r1:checked) label:nth-of-type(-n+1),
.rating:has(#r2:checked) label:nth-of-type(-n+2),
.rating:has(#r3:checked) label:nth-of-type(-n+3),
.rating:has(#r4:checked) label:nth-of-type(-n+4),
.rating:has(#r5:checked) label:nth-of-type(-n+5) { color: var(--ember); }
.rating:has(#r1:checked) label:nth-of-type(-n+1) svg,
.rating:has(#r2:checked) label:nth-of-type(-n+2) svg,
.rating:has(#r3:checked) label:nth-of-type(-n+3) svg,
.rating:has(#r4:checked) label:nth-of-type(-n+4) svg,
.rating:has(#r5:checked) label:nth-of-type(-n+5) svg { fill: currentColor; }
.rating-label { font-size: 15px; color: var(--tinta-2); min-height: 24px; margin-top: 4px; }

/* Tabel admin */
.tabel-wadah { background: var(--lantai); border-radius: var(--r-kartu); overflow-x: auto; }
.tabel { width: 100%; border-collapse: collapse; font-size: 15px; }
.tabel th { text-align: left; font-size: 13px; font-weight: 500; color: var(--tinta-2); padding: 14px 16px 10px; white-space: nowrap; }
.tabel td { padding: 14px 16px; border-top: 1px solid var(--garis); vertical-align: middle; }
.tabel th:first-child, .tabel td:first-child { padding-left: 20px; }
.tabel th:last-child, .tabel td:last-child { padding-right: 20px; }
.tabel .kanan { text-align: right; }
.tabel .aksi { flex-wrap: nowrap; justify-content: flex-end; }
.tabel tr.sorot td { background: rgba(255, 244, 214, .55); }

/* Layar sempit: tiap baris tabel jadi kartu */
@media (max-width: 900px) {
    .tabel-wadah { overflow: visible; }
    .tabel thead { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); }
    .tabel, .tabel tbody, .tabel tr, .tabel td { display: block; width: 100%; }
    .tabel tr { padding: 14px 20px; }
    .tabel tr + tr { border-top: 1px solid var(--garis); }
    .tabel tr.sorot { background: rgba(255, 244, 214, .55); }
    .tabel tr.sorot td { background: transparent; }
    .tabel td, .tabel td:first-child, .tabel td:last-child { display: grid; grid-template-columns: auto minmax(0, 1fr); align-items: center; column-gap: 16px; padding: 5px 0; border: 0; text-align: right; overflow-wrap: anywhere; }
    .tabel td::before { content: attr(data-label); grid-row: 1 / span 3; font-size: 14px; font-weight: 400; color: var(--tinta-2); text-align: left; }
    .tabel td > * { grid-column: 2; justify-self: end; }
    .tabel td:empty { display: none; }
    .tabel .aksi { justify-content: flex-end; flex-wrap: wrap; }
}

/* Chip filter */
.saring { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
.saring button { min-height: 44px; padding: 0 14px; border-radius: 12px; border: 1.5px solid var(--garis); background: var(--keramik); color: var(--tinta-2); font: inherit; font-size: 15px; cursor: pointer; }
.saring button:hover { color: var(--tinta); border-color: rgba(16, 24, 58, .22); }
.saring button[aria-pressed=true] { background: var(--ember-muda); color: var(--ember-tua); border-color: transparent; font-weight: 500; }
.saring b { font-weight: 500; font-variant-numeric: tabular-nums; margin-left: 4px; }

/* Dialog */
dialog { margin: auto; width: min(480px, calc(100% - 32px)); border: none; border-radius: var(--r-kartu); padding: 24px; background: var(--keramik); color: var(--tinta); box-shadow: 0 24px 64px rgba(16, 24, 58, .22); }
dialog::backdrop { background: rgba(16, 24, 58, .45); }
dialog h2 { font-size: 21px; letter-spacing: -.01em; margin-bottom: 4px; }
dialog p { color: var(--tinta-2); margin-bottom: 18px; }
dialog .aksi { justify-content: flex-end; margin-top: 8px; }

/* Halaman masuk & daftar */
.auth { max-width: none; margin: 0; min-height: 100dvh; display: grid; align-content: center; padding: 32px 20px; background: var(--lantai); }
.auth-kotak { width: 100%; max-width: 440px; margin: 0 auto; background: var(--keramik); border-radius: var(--r-kartu); padding: 32px 28px; }
.auth .logo { margin-bottom: 24px; }
.auth h1 { font-size: 27px; line-height: 1.2; letter-spacing: -.02em; }
.auth .lead { color: var(--tinta-2); margin: 4px 0 24px; }
.auth .bawah { text-align: center; font-size: 15px; color: var(--tinta-2); margin-top: 20px; }
.baris-tipis { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin: -4px 0 18px; font-size: 15px; }

/* Layar ponsel: tab pindah ke bawah */
@media (max-width: 859px) {
    .app .bar-isi { padding: 10px 16px; flex-wrap: nowrap; }
    .app .bar-isi > nav { margin-left: 0; }
    .app .bar-isi > .avatar { margin-left: auto; }
    .tab { position: fixed; left: 0; right: 0; bottom: 0; z-index: 20; margin: 0; display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; padding: 6px 4px calc(6px + env(safe-area-inset-bottom)); background: var(--keramik); border-top: 1px solid var(--garis); }
    .tab a { flex-direction: column; justify-content: center; gap: 2px; min-height: 56px; padding: 0 4px; font-size: 12px; line-height: 1.2; border-radius: 12px; text-align: center; }
    .tab a:hover { background: transparent; }
    .tab a.aktif { background: transparent; }
    .tab .ikon { width: 56px; height: 30px; border-radius: 10px; transition: background-color 140ms var(--keluar); }
    .tab a.aktif .ikon { background: var(--ember-muda); }
    .tab svg { width: 22px; height: 22px; }
    .app main { padding-bottom: calc(96px + env(safe-area-inset-bottom)); }
}

@media (max-width: 640px) {
    .bar-isi { padding: 10px 16px; }
    .menu { margin-left: 0; width: 100%; }

    main { padding: 20px 16px 56px; }
    .kepala h1 { font-size: 27px; }
    .jam { font-size: 30px; }
    .kartu { padding: 18px; }
    .daftar { padding: 4px 16px; }
    .auth { padding: 0; align-content: stretch; background: var(--keramik); }
    .auth-kotak { padding: 28px 16px 40px; border-radius: 0; }
}

@media (max-width: 380px) {
    .isian-dua { grid-template-columns: 1fr; }
    .rating label { width: 46px; height: 46px; }
}

@media (prefers-reduced-motion: reduce) {
    .btn, .opsi, .tab .ikon { transition: none; }
    .btn:active { transform: none; }
}

/* Admin berbasis desktop, isi lebih lebar */
.admin .bar-isi { max-width: 1120px; }
@media (max-width: 900px) {
    .admin .bar-isi > nav { margin-left: 0; width: 100%; order: 3; }
    .admin .bar-isi > .avatar { margin-left: auto; }
}
    </style>
    @stack('styles')
</head>
<body class="admin">
    <header class="bar">
        <div class="bar-isi">
            <a href="{{ route('admin.beranda') }}" class="logo">
                <svg width="24" height="24" viewBox="0 0 26 26" aria-hidden="true"><path d="M6 6.5a7 5 0 0 1 14 0" fill="none" stroke="#2453D8" stroke-width="2"/><path d="M3.5 9h19l-2.2 13.2a2 2 0 0 1-2 1.8H7.7a2 2 0 0 1-2-1.8z" fill="#EAF0FF" stroke="#2453D8" stroke-width="2" stroke-linejoin="round"/><path d="M5.2 15.5h15.6l-1.1 6.6a1 1 0 0 1-1 .9H7.3a1 1 0 0 1-1-.9z" fill="#2453D8"/></svg>
                bersihin
            </a>
            <span class="mode">Admin</span>
            <nav aria-label="Menu admin">
                <ul class="menu">
                    <li><a href="{{ route('admin.beranda') }}" class="{{ request()->routeIs('admin.beranda') ? 'aktif' : '' }}" @if(request()->routeIs('admin.beranda')) aria-current="page" @endif>Beranda</a></li>
                    <li><a href="{{ route('admin.petugas.index') }}" class="{{ request()->routeIs('admin.petugas.*') ? 'aktif' : '' }}" @if(request()->routeIs('admin.petugas.*')) aria-current="page" @endif>Petugas</a></li>
                    <li><a href="{{ route('admin.paket.index') }}" class="{{ request()->routeIs('admin.paket.*') ? 'aktif' : '' }}" @if(request()->routeIs('admin.paket.*')) aria-current="page" @endif>Paket</a></li>
                    <li><a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.*') ? 'aktif' : '' }}" @if(request()->routeIs('admin.pesanan.*')) aria-current="page" @endif>Pesanan</a></li>
                    <li><a href="{{ route('admin.pembayaran.index') }}" class="{{ request()->routeIs('admin.pembayaran.*') ? 'aktif' : '' }}" @if(request()->routeIs('admin.pembayaran.*')) aria-current="page" @endif>Pembayaran</a></li>
                </ul>
            </nav>
            <a href="{{ route('admin.profil') }}" class="avatar" @if(request()->routeIs('admin.profil*')) aria-current="page" @endif aria-label="Profil {{ Auth::user()->nama ?? 'Admin' }}">
                @if(Auth::user()?->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto {{ Auth::user()->nama }}">
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8.5" r="3.5"/><path d="M5 19.5a7 7 0 0 1 14 0"/></svg>
                @endif
            </a>
        </div>
    </header>

    <main class="lebar">
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
