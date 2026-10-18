<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Bersihin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            line-height: 1.6;
        }
        .navbar {
            background: linear-gradient(135deg, #1e3a8a, #172554);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }
        .navbar-brand { color: #fff; font-size: 1.4rem; font-weight: 700; text-decoration: none; }
        .navbar-brand small { font-weight: 400; opacity: .8; font-size: .8rem; margin-left: .35rem; }
        .navbar-nav { display: flex; gap: 1.5rem; list-style: none; align-items: center; }
        .navbar-nav a {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
        }
        .navbar-nav a:hover, .navbar-nav a.active { color: #fff; }
        .navbar-nav a.active { border-bottom: 2px solid #f59e0b; padding-bottom: .15rem; }
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .page-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 1.25rem; }
        .section-title { font-size: 1.1rem; font-weight: 700; margin-bottom: .75rem; }
        .btn {
            display: inline-block;
            padding: .55rem 1.2rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .2s;
        }
        .btn:hover { opacity: .85; }
        .btn-primary { background: #1e3a8a; color: #fff; }
        .btn-success  { background: #0f9b58; color: #fff; }
        .btn-danger   { background: #dc3545; color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .badge {
            display: inline-block;
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .badge-menunggu      { background: #fef3c7; color: #92400e; }
        .badge-terverifikasi { background: #d1fae5; color: #065f46; }
        .badge-diterima      { background: #dbeafe; color: #1e40af; }
        .badge-dikerjakan    { background: #e0e7ff; color: #3730a3; }
        .badge-selesai       { background: #d1fae5; color: #065f46; }
        .badge-lunas         { background: #d1fae5; color: #065f46; }
        .badge-ditolak       { background: #fee2e2; color: #991b1b; }
        .badge-dibatalkan    { background: #fee2e2; color: #991b1b; }
        .alert {
            padding: .75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error   { background: #fee2e2; color: #991b1b; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; margin-bottom: .3rem; }
        .form-control {
            width: 100%;
            padding: .55rem .75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: .95rem;
            transition: border-color .2s;
        }
        .form-control:focus { outline: none; border-color: #1e3a8a; box-shadow: 0 0 0 3px rgba(30,58,138,.15); }
        select.form-control { appearance: auto; }
        textarea.form-control { resize: vertical; min-height: 80px; }
        .text-danger { color: #dc3545; font-size: .8rem; margin-top: .2rem; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: .65rem .75rem; text-align: left; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
        th { font-weight: 600; font-size: .8rem; text-transform: uppercase; color: #6b7280; }
        .star { color: #f59e0b; }
        .empty-state { text-align: center; padding: 3rem 1rem; color: #6b7280; }
        .muted { color: #6b7280; font-size: .85rem; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('admin.paket.index') }}" class="navbar-brand">🧹 Bersihin<small>Admin</small></a>
        <ul class="navbar-nav">
            <li><a href="{{ route('admin.petugas.index') }}" class="{{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}">Verifikasi Petugas</a></li>
            <li><a href="{{ route('admin.paket.index') }}" class="{{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">Paket Layanan</a></li>
            <li><a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">Pesanan</a></li>
            <li><a href="{{ route('admin.pembayaran.index') }}" class="{{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">Pembayaran</a></li>
        </ul>
    </nav>

    <div class="container">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>