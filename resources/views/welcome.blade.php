<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Bersihin · Pesan Petugas Kebersihan Rumah</title>
    <meta name="description" content="Pesan petugas kebersihan rumah. Harga pasti per paket, pantau status pesanan di halaman pesananmu, dan bayar lewat transfer atau tunai di lokasi.">
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

        .pembuka { display: grid; grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr); gap: 56px; align-items: center; padding: 72px 0 88px; }
        .pembuka h1 { font-size: 54px; line-height: 1.05; letter-spacing: -.035em; max-width: 13ch; }
        .pembuka h1 em { font-style: normal; color: var(--ember); }
        .pembuka .lead { font-size: 19px; color: var(--tinta-2); max-width: 46ch; margin: 20px 0 28px; }
        .sinyal-bukti { display: flex; flex-wrap: wrap; gap: 8px 20px; margin-top: 24px; font-size: 14px; color: var(--tinta-2); }
        .sinyal-bukti span { display: inline-flex; align-items: center; gap: 8px; }
        .sinyal-bukti span::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: var(--ember); flex-shrink: 0; }

        .contoh { display: flex; justify-content: flex-end; }
        .contoh img { width: 100%; max-width: 340px; height: auto; border-radius: 24px; border: 1px solid var(--garis); box-shadow: 0 16px 40px rgba(16, 24, 58, .10); display: block; }

        .keunggulan { padding: 48px 0 72px; border-top: 1px solid var(--garis); }
        .keunggulan-susunan { display: grid; grid-template-columns: minmax(0, .95fr) minmax(0, 1.05fr); gap: 24px; margin-top: 36px; align-items: start; }
        .keunggulan-utama { background: var(--lantai); border-radius: var(--r-kartu); padding: 28px; display: flex; flex-direction: column; gap: 10px; }
        .keunggulan-utama .ikon-lingkar { width: 44px; height: 44px; border-radius: 12px; background: var(--ember-muda); color: var(--ember-tua); display: grid; place-items: center; margin-bottom: 6px; }
        .keunggulan-utama h3 { font-size: 20px; font-weight: 600; }
        .keunggulan-utama p { color: var(--tinta-2); font-size: 15px; line-height: 1.55; }
        .keunggulan-daftar .baris { align-items: flex-start; padding: 20px 24px; }
        .keunggulan-daftar h3 { font-size: 17px; font-weight: 600; margin-bottom: 4px; }
        .keunggulan-daftar p { color: var(--tinta-2); font-size: 15px; line-height: 1.5; }

        .paket-bagian { display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr); gap: 56px; padding: 72px 0; border-top: 1px solid var(--garis); }
        .paket-bagian h2, .alur h2, .keunggulan h2, .faq h2 { font-size: 33px; line-height: 1.15; letter-spacing: -.02em; }
        .paket-bagian .lead { color: var(--tinta-2); margin-top: 12px; max-width: 36ch; }
        .paket-baris { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 4px 24px; padding: 20px 0; }
        .paket-baris + .paket-baris { border-top: 1px solid var(--garis); }
        .paket-baris h3 { font-size: 19px; letter-spacing: -.01em; }
        .paket-baris p { color: var(--tinta-2); font-size: 15px; }
        .paket-baris .harga { font-size: 19px; text-align: right; font-weight: 600; }
        .paket-baris .durasi { text-align: right; font-size: 14px; color: var(--tinta-2); }
        .paket-kosong { color: var(--tinta-2); padding: 20px 0; }

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

        .faq { padding: 48px 0 72px; border-top: 1px solid var(--garis); }
        .faq-daftar { margin-top: 32px; display: grid; gap: 12px; }
        .faq-item { background: var(--lantai); border-radius: var(--r-kontrol); overflow: hidden; transition: background 140ms var(--keluar); }
        .faq-item summary { padding: 18px 24px; font-weight: 600; font-size: 17px; cursor: pointer; list-style: none; display: flex; justify-content: space-between; align-items: center; }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item summary::after { content: '+'; font-size: 20px; font-weight: 400; color: var(--ember); transition: transform 140ms var(--keluar); }
        .faq-item[open] summary::after { content: '−'; }
        .faq-item p { padding: 0 24px 20px; color: var(--tinta-2); font-size: 15px; line-height: 1.6; }

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
            .contoh { justify-content: center; }
            .keunggulan-susunan { grid-template-columns: 1fr; gap: 16px; }
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
                <li><a href="{{ route('login') }}">Masuk</a></li>
                <li><a href="{{ route('register') }}" class="btn btn-utama btn-kecil">Daftar</a></li>
            </ul>
        </div>
    </header>

    <main class="isi" style="max-width:1120px;padding-top:0;padding-bottom:0;">
        <section class="pembuka">
            <div>
                <h1>Rumahmu dibersihin, kamu tinggal <em>santai</em>.</h1>
                <p class="lead">Pesan petugas kebersihan dalam hitungan menit. Harga pasti per paket tanpa tawar-menawar, dan bayar lewat transfer atau tunai di lokasi.</p>
                <div class="aksi">
                    <a href="{{ route('register') }}" class="btn btn-utama">Pesan petugas</a>
                    <a href="#paket" class="btn btn-kedua">Lihat paket</a>
                </div>
                <div class="sinyal-bukti">
                    <span>Pendaftaran petugas diperiksa admin</span>
                    <span>Transfer &amp; tunai</span>
                    <span>Ulasan dari pelanggan</span>
                </div>
            </div>

            <div class="contoh">
                <img src="{{ asset('img/pratinjau-beranda.png') }}" width="860" height="1520" alt="Tampilan beranda pelanggan di aplikasi Bersihin yang menampilkan kartu pesanan aktif dan progres pengerjaan">
            </div>
        </section>

        <section class="keunggulan" id="keunggulan">
            <h2>Kenapa pesan lewat Bersihin?</h2>
            <div class="keunggulan-susunan">
                <div class="keunggulan-utama">
                    <div class="ikon-lingkar">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <h3>Harga Pasti &amp; Tetap</h3>
                    <p>Semua biaya sudah final per paket layanan. Tidak ada biaya tersembunyi atau tawar-menawar di lokasi.</p>
                </div>

                <div class="daftar keunggulan-daftar">
                    <div class="baris">
                        <div>
                            <h3>Pendaftaran petugas diperiksa admin</h3>
                            <p>Petugas baru menunggu verifikasi admin sebelum bisa menerima pesanan.</p>
                        </div>
                    </div>
                    <div class="baris">
                        <div>
                            <h3>Pantau status pesanan di halaman pesananmu</h3>
                            <p>Lihat status pekerjaan mulai dari menunggu petugas, diterima, dikerjakan, hingga selesai.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="paket-bagian" id="paket">
            <div>
                <h2>Pilihan paket layanan</h2>
                <p class="lead">Pilih sesuai kebutuhan kebersihan rumahmu. Estimasi durasi disesuaikan dengan paket layanan.</p>
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
                    <p class="paket-kosong">Paket belum tersedia.</p>
                @endforelse
            </div>
        </section>

        <section class="alur" id="alur">
            <h2>Dari pesan sampai beres</h2>
            <p class="lead">Pantau status pesanan di halaman pesananmu dari browser HP.</p>
            <ol class="tahap">
                <li>
                    <div class="tabung"><span style="width:25%"></span></div>
                    <b>1. Menunggu petugas</b>
                    <p>Pesananmu masuk dan menunggu petugas yang tersedia.</p>
                </li>
                <li>
                    <div class="tabung"><span style="width:50%"></span></div>
                    <b>2. Diterima</b>
                    <p>Petugas mengambil jadwal sesuai waktu pesanan.</p>
                </li>
                <li>
                    <div class="tabung"><span style="width:75%"></span></div>
                    <b>3. Dikerjakan</b>
                    <p>Petugas membersihkan rumah sesuai rincian paket.</p>
                </li>
                <li>
                    <div class="tabung"><span style="width:100%"></span></div>
                    <b>4. Beres</b>
                    <p>Pekerjaan selesai, beri rating dan ulasan setelah pesanan selesai.</p>
                </li>
            </ol>
            <div class="bayar">
                <div>
                    <b>Transfer Bank</b>
                    <span>Unggah foto bukti transfer, admin mengonfirmasi bukti transfer.</span>
                </div>
                <div>
                    <b>Tunai di Lokasi</b>
                    <span>Bayar lewat transfer atau tunai di lokasi kepada petugas.</span>
                </div>
            </div>
        </section>

        <section class="faq" id="faq">
            <h2>Pertanyaan yang sering diajukan</h2>
            <div class="faq-daftar">
                <details class="faq-item">
                    <summary>Apakah alat dan cairan pembersih disediakan oleh petugas?</summary>
                    <p>Ya, petugas Bersihin membawa peralatan standar pembersihan ruangan. Namun jika kamu memiliki cairan pembersih khusus untuk lantai atau perabot tertentu, kamu dapat menyediakannya.</p>
                </details>
                <details class="faq-item">
                    <summary>Kapan saya harus melakukan pembayaran?</summary>
                    <p>Setelah memesan, kamu dapat langsung membayar lewat transfer bank dengan mengunggah bukti bayar, atau memilih bayar tunai di lokasi kepada petugas.</p>
                </details>
                <details class="faq-item">
                    <summary>Bisa batalkan pesanan jika jadwal saya berubah?</summary>
                    <p>Bisa. Kamu dapat membatalkan pesanan secara mandiri di halaman detail pesanan selama statusnya masih dalam tahap menunggu petugas.</p>
                </details>
                <details class="faq-item">
                    <summary>Bagaimana jika hasil pembersihan kurang memuaskan?</summary>
                    <p>Beri rating dan ulasan setelah pesanan selesai langsung di halaman ulasan atau detail pesananmu.</p>
                </details>
            </div>
        </section>

        <section class="petugas">
            <div>
                <h2>Mau bergabung jadi mitra petugas?</h2>
                <p>Daftar sekarang, tunggu verifikasi admin, lalu mulai ambil pesanan dengan jam kerja fleksibel.</p>
            </div>
            <a href="{{ route('register') }}" class="btn btn-kedua">Daftar jadi petugas</a>
        </section>
    </main>

    <footer>
        <div class="isi">
            <span>Bersihin, pesan petugas kebersihan rumah</span>
            <span>
                <a href="{{ route('login') }}">Masuk</a> ·
                <a href="{{ route('register') }}">Daftar</a>
            </span>
        </div>
    </footer>
</body>
</html>
