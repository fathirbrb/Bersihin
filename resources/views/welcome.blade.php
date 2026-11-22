<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Bersihin · Jasa Bersih-bersih Rumah Profesional</title>
    <meta name="description" content="Pesan petugas kebersihan rumah terpercaya. Harga pasti tanpa tawar-menawar, pantau progres langsung dari HP, dan bayar setelah selesai.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bersihin.css') }}">
    <style>
        .bar-isi { max-width: 1120px; }
        .nav-landing { display: flex; align-items: center; gap: 4px; margin-left: auto; list-style: none; }
        .nav-landing a:not(.btn) { display: inline-flex; align-items: center; min-height: 44px; padding: 0 12px; border-radius: 12px; color: var(--tinta-2); text-decoration: none; font-size: 15px; }
        .nav-landing a:not(.btn):hover { background: var(--lantai); color: var(--tinta); }
        .nav-landing .btn { margin-left: 8px; }

        .isi { max-width: 1120px; margin: 0 auto; padding: 0 20px; }

        /* Pembuka */
        .pembuka { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr); gap: 56px; align-items: center; padding: 72px 0 88px; }
        .pembuka h1 { font-size: 54px; line-height: 1.05; letter-spacing: -.035em; max-width: 13ch; }
        .pembuka h1 em { font-style: normal; color: var(--ember); }
        .pembuka .lead { font-size: 19px; color: var(--tinta-2); max-width: 46ch; margin: 20px 0 28px; }
        .sinyal-bukti { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 24px; font-size: 14px; color: var(--tinta-2); }
        .sinyal-bukti span { display: inline-flex; align-items: center; gap: 6px; }

        .contoh { position: relative; }
        .contoh .kartu-fokus { padding: 24px 24px 40px; }
        .contoh .kecil { margin: -18px 24px 0; position: relative; background: var(--keramik); border: 1px solid var(--garis); border-radius: var(--r-kontrol); padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; font-size: 15px; box-shadow: 0 12px 32px rgba(16, 24, 58, .08); }

        /* Fitur / Keunggulan */
        .keunggulan { padding: 48px 0 72px; border-top: 1px solid var(--garis); }
        .keunggulan-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; margin-top: 36px; }
        .keunggulan-kartu { background: var(--lantai); border-radius: var(--r-kartu); padding: 24px; display: flex; flex-direction: column; gap: 8px; }
        .keunggulan-kartu .ikon-lingkar { width: 44px; height: 44px; border-radius: 12px; background: var(--ember-muda); color: var(--ember-tua); display: grid; place-items: center; margin-bottom: 8px; }
        .keunggulan-kartu h3 { font-size: 18px; font-weight: 600; }
        .keunggulan-kartu p { color: var(--tinta-2); font-size: 15px; line-height: 1.5; }

        /* Paket */
        .paket-bagian { display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr); gap: 56px; padding: 72px 0; border-top: 1px solid var(--garis); }
        .paket-bagian h2, .alur h2, .keunggulan h2, .faq h2 { font-size: 33px; line-height: 1.15; letter-spacing: -.02em; }
        .paket-bagian .lead { color: var(--tinta-2); margin-top: 12px; max-width: 36ch; }
        .paket-baris { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 4px 24px; padding: 20px 0; }
        .paket-baris + .paket-baris { border-top: 1px solid var(--garis); }
        .paket-baris h3 { font-size: 19px; letter-spacing: -.01em; }
        .paket-baris p { color: var(--tinta-2); font-size: 15px; }
        .paket-baris .harga { font-size: 19px; text-align: right; font-weight: 600; }
        .paket-baris .durasi { text-align: right; font-size: 14px; color: var(--tinta-2); }

        /* Alur pesanan */
        .alur { background: var(--lantai); border-radius: 28px; padding: 56px 48px; margin-bottom: 72px; }
        .alur .lead { color: var(--tinta-2); margin-top: 8px; max-width: 52ch; }
        .tahap { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 0 16px; margin-top: 40px; list-style: none; }
        .tahap li .tabung { height: 12px; border-radius: 6px; background: var(--keramik); overflow: hidden; margin-bottom: 16px; }
        .tahap li .tabung span { display: block; height: 100%; background: var(--ember); border-radius: 6px; }
        .tahap b { display: block; font-weight: 600; }
        .tahap p { color: var(--tinta-2); font-size: 15px; margin-top: 4px; }
        .bayar { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-top: 40px; }
        .bayar div { background: var(--keramik); border-radius: var(--r-kontrol); padding: 16px 20px; }
        .bayar b { display: block; }
        .bayar span { font-size: 15px; color: var(--tinta-2); }

        /* Tanya Jawab (FAQ) */
        .faq { padding: 48px 0 72px; border-top: 1px solid var(--garis); }
        .faq-daftar { margin-top: 32px; display: grid; gap: 12px; }
        .faq-item { background: var(--lantai); border-radius: var(--r-kontrol); overflow: hidden; transition: background 140ms var(--keluar); }
        .faq-item summary { padding: 18px 24px; font-weight: 600; font-size: 17px; cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center; }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item summary::after { content: '+'; font-size: 20px; font-weight: 400; color: var(--ember); transition: transform 140ms var(--keluar); }
        .faq-item[open] summary::after { content: '−'; }
        .faq-item p { padding: 0 24px 20px; color: var(--tinta-2); font-size: 15px; line-height: 1.6; }

        /* Ajakan petugas */
        .petugas { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 24px; padding: 56px 40px; background: var(--ember-muda); border-radius: var(--r-kartu); margin-bottom: 72px; }
        .petugas h2 { font-size: 27px; letter-spacing: -.02em; color: var(--ember-tua); }
        .petugas p { color: var(--tinta); max-width: 52ch; margin-top: 4px; }

        footer { border-top: 1px solid var(--garis); }
        footer .isi a { display: inline-flex; align-items: center; min-height: 44px; padding: 0 4px; }
        footer .isi { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; padding-top: 24px; padding-bottom: 32px; font-size: 14px; color: var(--tinta-2); }

        @media (max-width: 859px) {
            .nav-landing .geser { display: none; }
            .pembuka { grid-template-columns: 1fr; gap: 40px; padding: 40px 0 56px; }
            .pembuka h1 { font-size: 40px; }
            .pembuka .lead { font-size: 17px; margin: 16px 0 24px; }
            .keunggulan-grid { grid-template-columns: 1fr; gap: 16px; }
            .paket-bagian { grid-template-columns: 1fr; gap: 16px; padding: 48px 0; }
            .paket-bagian h2, .alur h2, .keunggulan h2, .faq h2 { font-size: 27px; }
            .alur { padding: 32px 20px; border-radius: var(--r-kartu); }
            .tahap { grid-template-columns: 1fr 1fr; gap: 24px 16px; }
            .bayar { grid-template-columns: 1fr; }
            .petugas { padding: 32px 20px; }
        }
        @media (max-width: 480px) {
            .isi { padding: 0 16px; }
            .nav-landing .btn { margin-left: 0; padding: 0 14px; }
            .pembuka h1 { font-size: 34px; }
            .pembuka .aksi { display: flex; flex-direction: column; gap: 8px; }
            .tahap { grid-template-columns: 1fr; }
            .contoh .kecil { margin: -14px 12px 0; }
        }
    </style>
</head>
<body>
    <header class="bar">
        <div class="bar-isi">
            <a href="{{ route('landing') }}" class="logo">
                <svg width="24" height="24" viewBox="0 0 26 26" aria-hidden="true"><path d="M6 6.5a7 5 0 0 1 14 0" fill="none" stroke="#2453D8" stroke-width="2"/><path d="M3.5 9h19l-2.2 13.2a2 2 0 0 1-2 1.8H7.7a2 2 0 0 1-2-1.8z" fill="#EAF0FF" stroke="#2453D8" stroke-width="2" stroke-linejoin="round"/><path d="M5.2 15.5h15.6l-1.1 6.6a1 1 0 0 1-1 .9H7.3a1 1 0 0 1-1-.9z" fill="#2453D8"/></svg>
                bersihin
            </a>
            <ul class="nav-landing">
                <li class="geser"><a href="#keunggulan">Keunggulan</a></li>
                <li class="geser"><a href="#paket">Paket</a></li>
                <li class="geser"><a href="#alur">Alur Kerja</a></li>
                <li class="geser"><a href="#faq">Tanya Jawab</a></li>
                @auth
                    <li><a href="{{ route('login') }}" class="btn btn-utama btn-kecil">Dashboard</a></li>
                @else
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-utama btn-kecil">Daftar</a></li>
                @endauth
            </ul>
        </div>
    </header>

    <main class="isi" style="max-width:1120px;padding-top:0;padding-bottom:0;">
        <!-- Hero Section -->
        <section class="pembuka">
            <div>
                <h1>Rumahmu dibersihin, kamu tinggal <em>santai</em>.</h1>
                <p class="lead">Pesan petugas kebersihan terpercaya dalam hitungan menit. Tanpa tawar-menawar, harga transparan, dan bayar setelah pesanan dikerjakan.</p>
                <div class="aksi">
                    <a href="{{ route('register') }}" class="btn btn-utama">Pesan petugas</a>
                    <a href="#paket" class="btn btn-kedua">Lihat paket</a>
                </div>
                <div class="sinyal-bukti">
                    <span>🛡️ 100% Petugas Terverifikasi</span>
                    <span>💵 Transfer & Tunai</span>
                    <span>⭐ Ulasan Real-Time</span>
                </div>
            </div>

            <!-- Preview Card Mockup -->
            <div class="contoh" aria-label="Contoh pesanan di aplikasi Bersihin">
                <div class="kartu kartu-fokus">
                    <div class="atas">
                        <p class="paket">Pembersihan Rutin · 3 jam</p>
                        <span class="status s-jalan"><span class="m" aria-hidden="true"><i class="on"></i><i class="on"></i><i></i><i></i></span>Diterima</span>
                    </div>
                    <p class="jam">13.00</p>
                    <p class="tgl">Rabu, 28 Oktober</p>
                    <dl class="info">
                        <dt>Alamat</dt><dd>Jl. Contoh No. 5, Rajabasa, Bandar Lampung</dd>
                        <dt>Petugas</dt><dd>Rifan (Terverifikasi)</dd>
                        <dt>Status</dt><dd><span class="status s-jalan">Petugas menuju lokasi</span></dd>
                    </dl>
                    <div class="level">
                        <div class="tabung" role="progressbar" aria-valuemin="0" aria-valuemax="4" aria-valuenow="2" aria-label="Progres pesanan"><span style="width:50%"></span></div>
                        <div class="label"><b>Diterima, belum mulai</b><span>Langkah 2 dari 4</span></div>
                    </div>
                </div>
                <div class="kecil">
                    <span>Estimasi total pengerjaan</span>
                    <span class="angka" style="font-weight:600;color:var(--ember);">Rp150.000</span>
                </div>
            </div>
        </section>

        <!-- Keunggulan Section -->
        <section class="keunggulan" id="keunggulan">
            <h2>Kenapa pesan lewat Bersihin?</h2>
            <div class="keunggulan-grid">
                <div class="keunggulan-kartu">
                    <div class="ikon-lingkar">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <h3>Harga Pasti & Tetap</h3>
                    <p>Semua biaya sudah final per paket layanan. Tidak ada biaya tersembunyi atau tawar-menawar di lokasi.</p>
                </div>

                <div class="keunggulan-kartu">
                    <div class="ikon-lingkar">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3>Petugas Terverifikasi</h3>
                    <p>Identitas dan kelayakan petugas diverifikasi ketat oleh admin sebelum diizinkan menerima pesanan.</p>
                </div>

                <div class="keunggulan-kartu">
                    <div class="ikon-lingkar">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                    </div>
                    <h3>Pantau Progres dari HP</h3>
                    <p>Lacak tahap pekerjaan mulai dari pesanan diambil, dikerjakan, hingga tuntas melalui indikator progres interaktif.</p>
                </div>
            </div>
        </section>

        <!-- Paket Section -->
        <section class="paket-bagian" id="paket">
            <div>
                <h2>Pilihan paket layanan</h2>
                <p class="lead">Pilih sesuai kebutuhan kebersihan rumahmu. Estimasi durasi disesuaikan dengan luas dan kondisi ruangan.</p>
            </div>
            <div>
                @forelse($paketLayanan as $paket)
                    <div class="paket-baris">
                        <div>
                            <h3>{{ $paket->nama_paket }}</h3>
                            <p>{{ $paket->deskripsi }}</p>
                        </div>
                        <div>
                            <p class="harga">Rp{{ number_format($paket->harga, 0, ',', '.') }}</p>
                            <p class="durasi">± {{ $paket->estimasi_durasi_jam }} jam</p>
                        </div>
                    </div>
                @empty
                    <div class="paket-baris">
                        <div>
                            <h3>Pembersihan Rutin</h3>
                            <p>Menyapu, mengepel, merapikan ruangan</p>
                        </div>
                        <div>
                            <p class="harga">Rp150.000</p>
                            <p class="durasi">± 3 jam</p>
                        </div>
                    </div>
                    <div class="paket-baris">
                        <div>
                            <h3>Deep Cleaning</h3>
                            <p>Kamar mandi, dapur, dan lantai secara menyeluruh</p>
                        </div>
                        <div>
                            <p class="harga">Rp350.000</p>
                            <p class="durasi">± 6 jam</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Alur Section -->
        <section class="alur" id="alur">
            <h2>Dari pesan sampai beres</h2>
            <p class="lead">Semua langkah transparan dan dapat dipantau langsung dari browser HP.</p>
            <ol class="tahap">
                <li>
                    <div class="tabung"><span style="width:25%"></span></div>
                    <b>1. Menunggu petugas</b>
                    <p>Pesananmu masuk dan disiapkan untuk petugas terdekat.</p>
                </li>
                <li>
                    <div class="tabung"><span style="width:50%"></span></div>
                    <b>2. Diterima</b>
                    <p>Petugas mengambil jadwal dan menuju lokasi sesuai waktu.</p>
                </li>
                <li>
                    <div class="tabung"><span style="width:75%"></span></div>
                    <b>3. Dikerjakan</b>
                    <p>Petugas membersihkan rumah sesuai rincian paket.</p>
                </li>
                <li>
                    <div class="tabung"><span style="width:100%"></span></div>
                    <b>4. Beres</b>
                    <p>Rumah bersih berkilau, konfirmasi dan beri ulasan.</p>
                </li>
            </ol>
            <div class="bayar">
                <div>
                    <b>Transfer Bank</b>
                    <span>Upload foto bukti transfer, konfirmasi kilat oleh admin.</span>
                </div>
                <div>
                    <b>Tunai di Tempat</b>
                    <span>Bayar langsung ke petugas saat pekerjaan selesai.</span>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq" id="faq">
            <h2>Pertanyaan yang sering diajukan</h2>
            <div class="faq-daftar">
                <details class="faq-item">
                    <summary>Apakah alat dan cairan pembersih disediakan oleh petugas?</summary>
                    <p>Ya, petugas Bersihin membawa peralatan standar pembersihan ruangan. Namun jika kamu memiliki cairan pembersih khusus untuk lantai atau furniture tertentu, kamu dapat menyediakannya.</p>
                </details>
                <details class="faq-item">
                    <summary>Kapan saya harus melakukan pembayaran?</summary>
                    <p>Pembayaran dapat dilakukan setelah pesanan berhasil dibuat melalui transfer bank, atau bayar tunai langsung kepada petugas begitu pekerjaan selesai dilakukan.</p>
                </details>
                <details class="faq-item">
                    <summary>Bisa batalkan pesanan jika jadwal saya berubah?</summary>
                    <p>Bisa! Kamu dapat membatalkan pesanan secara mandiri di halaman detail pesanan selama statusnya masih dalam tahap "Menunggu petugas".</p>
                </details>
                <details class="faq-item">
                    <summary>Bagaimana jika hasil pembersihan kurang memuaskan?</summary>
                    <p>Kamu dapat memberikan rating dan ulasan langsung di aplikasi. Tim admin memantau seluruh ulasan untuk menjaga standar kualitas kerja setiap petugas.</p>
                </details>
            </div>
        </section>

        <!-- Ajakan Petugas -->
        <section class="petugas">
            <div>
                <h2>Mau bergabung jadi mitra petugas?</h2>
                <p>Daftar sekarang, lewati verifikasi admin, dan mulai ambil pesanan dengan jam kerja fleksibel.</p>
            </div>
            <a href="{{ route('register') }}" class="btn btn-kedua">Daftar jadi petugas</a>
        </section>
    </main>

    <footer>
        <div class="isi">
            <span>Bersihin — Solusi Kebersihan Rumah Terpercaya</span>
            <span>
                <a href="{{ route('login') }}">Masuk</a> · 
                <a href="{{ route('register') }}">Daftar</a>
            </span>
        </div>
    </footer>
</body>
</html>
