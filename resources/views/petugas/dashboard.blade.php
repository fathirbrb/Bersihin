@extends('layouts.petugas')

@section('title', 'Beranda')

@section('content')
    <div class="kepala">
        <h1>Halo, {{ Str::before($petugas->nama, ' ') ?: $petugas->nama }}</h1>
        <p>{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Info status verifikasi akun --}}
    @if($petugas->status_verifikasi == 'menunggu')
        <div class="pesan pesan-tunggu" role="status">
            <b>Akunmu lagi dicek admin.</b>
            <span>Begitu beres, kamu bisa ambil pesanan.</span>
        </div>
    @elseif($petugas->status_verifikasi == 'ditolak')
        <div class="pesan pesan-salah" role="alert">
            <b>Pendaftaranmu ditolak admin.</b>
            <span>Hubungi admin untuk tahu alasannya.</span>
        </div>
    @endif

    <h2 class="bagian">Kerjaan berikutnya</h2>

    @if($pesananBerikutnya)
        @php $p = $pesananBerikutnya; @endphp
        <div class="kartu kartu-fokus">
            <div class="atas">
                <p class="paket">{{ $p->paketLayanan->nama_paket ?? '-' }} · {{ $p->paketLayanan->estimasi_durasi_jam ?? '-' }} jam</p>
                @include('petugas.partials.status', ['status' => $p->status])
            </div>
            <p class="jam">{{ str_replace(':', '.', substr($p->jam_mulai, 0, 5)) }}</p>
            <p class="tgl">{{ $p->tanggal->translatedFormat('l, d F') }}</p>
            <dl class="info">
                <dt>Alamat</dt><dd>{{ $p->alamat }}</dd>
                <dt>Pelanggan</dt><dd>{{ $p->pelanggan->nama ?? '-' }}</dd>
            </dl>
            @include('petugas.partials.level', ['status' => $p->status])
            <a href="{{ route('petugas.pesanan.show', $p->id) }}" class="btn btn-utama btn-penuh" style="margin-top:16px;">Buka pesanan</a>
        </div>
    @else
        <div class="kosong">
            @include('petugas.partials.logo', ['ukuran' => 48])
            <b>Embernya masih kosong</b>
            @if($petugas->status_verifikasi == 'terverifikasi')
                <span>Kamu belum punya kerjaan. Ambil pesanan yang lagi nunggu petugas.</span>
                <a href="{{ route('petugas.pesanan-masuk') }}" class="btn btn-utama">Lihat pesanan masuk</a>
            @else
                <span>Pesanan bisa diambil setelah akunmu diverifikasi admin.</span>
            @endif
        </div>
    @endif

    {{-- Ringkasan singkat dalam satu baris --}}
    <div class="ringkas">
        <span><b>{{ $jumlahPesananMasuk }}</b> pesanan nunggu diambil</span>
        <span><b>{{ $jumlahDikerjakan }}</b> sedang jalan</span>
        <span><b>{{ $jumlahSelesai }}</b> sudah beres</span>
        <span>Rating <b>{{ $rataRating ? number_format($rataRating, 1, ',', '.') : '-' }}</b></span>
    </div>
@endsection
