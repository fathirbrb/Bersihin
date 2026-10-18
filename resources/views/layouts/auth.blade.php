<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bersihin') — Bersihin</title>
    <meta name="description" content="@yield('meta-deskripsi', 'Layanan kebersihan rumah profesional.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/bersihin.css">
    <style>
        /* Override layout pelanggan yang punya inline style — layout auth lebih simpel */
        body { background: var(--lantai); }

        .auth-wrapper {
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        .auth-topbar {
            background: var(--ember);
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 var(--gap-lg);
            box-shadow: 0 2px 8px rgba(36,83,216,.20);
        }
        .auth-topbar a {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: -0.01em;
        }

        .auth-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--gap-xl) var(--gap-md);
        }

        .auth-kartu {
            background: #fff;
            border-radius: var(--r-kartu);
            box-shadow: var(--bayangan-kartu);
            padding: var(--gap-xl) var(--gap-lg);
            width: 100%;
            max-width: 440px;
        }

        .auth-judul {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--tinta);
            margin-bottom: var(--gap-xs);
        }
        .auth-subjudul {
            font-size: 0.9rem;
            color: var(--tinta-2);
            margin-bottom: var(--gap-lg);
        }

        .auth-footer {
            margin-top: var(--gap-md);
            text-align: center;
            font-size: 0.9rem;
            color: var(--tinta-2);
        }
        .auth-footer a { font-weight: 600; }

        /* Role selector (registrasi) */
        .role-grup {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--gap-sm);
            margin-bottom: var(--gap-md);
        }
        .role-opsi { position: relative; }
        .role-opsi input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
        }
        .role-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: var(--gap-xs);
            padding: var(--gap-md) var(--gap-sm);
            border: 1.5px solid #e4e7ef;
            border-radius: var(--r-kontrol);
            cursor: pointer;
            background: var(--isian);
            transition: border-color 0.15s, background 0.15s;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--tinta-2);
            text-align: center;
        }
        .role-opsi input:checked + .role-label {
            border-color: var(--ember);
            background: var(--ember-muda);
            color: var(--ember);
        }
        .role-label .ikon { font-size: 1.5rem; }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: var(--gap-sm);
            margin: var(--gap-md) 0;
            color: var(--tinta-2);
            font-size: 0.8rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e4e7ef;
        }

        @media (max-width: 480px) {
            .auth-kartu { padding: var(--gap-lg) var(--gap-md); }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <header class="auth-topbar">
            <a href="{{ route('login') }}">🧹 Bersihin</a>
        </header>

        <main class="auth-main">
            <div class="auth-kartu">
                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-sukses" role="alert">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-bahaya" role="alert">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
