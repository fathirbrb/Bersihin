@extends('layouts.pelanggan')

@section('title', 'Beranda')

@section('content')
<div class="kepala">
    <h1>Halo, {{ Auth::user()->nama }}</h1>
    <p>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="dua-kolom">
    <section>
        <h2 class="bagian">Lagi jalan</h2>
        @if($pesananAktif)
            @php
                $statusLabel = match($pesananAktif->status) {
                    'menunggu' => 'Menunggu',
                    'diterima' => 'Diterima',
                    'dikerjakan' => 'Dikerjakan',
                    default => ucfirst($pesananAktif->status),
                };

                $step = match($pesananAktif->status) {
                    'menunggu' => 1,
                    'diterima' => 2,
                    'dikerjakan' => 3,
                    default => 1,
                };

                $stepText = match($pesananAktif->status) {
                    'menunggu' => 'Menunggu petugas',
                    'diterima' => 'Diterima, belum mulai',
                    'dikerjakan' => 'Sedang dikerjakan',
                    default => $statusLabel,
                };

                $percent = $step * 25;
            @endphp
            <div class="kartu kartu-fokus">
                <div class="atas">
                    <p class="paket">{{ $pesananAktif->paketLayanan->nama_paket ?? 'Layanan' }} · {{ $pesananAktif->paketLayanan->estimasi_durasi_jam ?? 0 }} jam</p>
                    <span class="status s-jalan">
                        <span class="m" aria-hidden="true">
                            <i class="{{ $step >= 1 ? 'on' : '' }}"></i>
                            <i class="{{ $step >= 2 ? 'on' : '' }}"></i>
                            <i class="{{ $step >= 3 ? 'on' : '' }}"></i>
                            <i class="{{ $step >= 4 ? 'on' : '' }}"></i>
                        </span>
                        {{ $statusLabel }}
                    </span>
                </div>
                <p class="jam">{{ substr($pesananAktif->jam_mulai, 0, 5) }}</p>
                <p class="tgl">{{ $pesananAktif->tanggal->translatedFormat('l, d F') }}</p>
                <dl class="info">
                    <dt>Alamat</dt>
                    <dd>{{ $pesananAktif->alamat }}</dd>
                    <dt>Petugas</dt>
                    <dd>{{ $pesananAktif->petugas ? $pesananAktif->petugas->nama : 'Belum ada' }}</dd>
                    <dt>Bayar</dt>
                    <dd>
                        @if(!$pesananAktif->pembayaran)
                            <span class="status s-tunggu">Belum dibayar</span>
                        @elseif($pesananAktif->pembayaran->status === 'lunas')
                            <span class="status s-beres">Lunas</span>
                        @elseif($pesananAktif->pembayaran->status === 'ditolak')
                            <span class="status s-batal">Ditolak</span>
                        @else
                            <span class="status s-tunggu">Menunggu konfirmasi</span>
                        @endif
                    </dd>
                </dl>
                <div class="level">
                    <div class="tabung" role="progressbar" aria-valuemin="0" aria-valuemax="4" aria-valuenow="{{ $step }}" aria-label="Progres pesanan">
                        <span style="width:{{ $percent }}%"></span>
                    </div>
                    <div class="label">
                        <b>{{ $stepText }}</b>
                        <span>Langkah {{ $step }} dari 4</span>
                    </div>
                </div>
                <div class="aksi-dua">
                    <a href="{{ route('pelanggan.pesanan.show', $pesananAktif->id) }}" class="btn btn-kedua">Detail</a>
                    @if(!$pesananAktif->pembayaran)
                        <a href="{{ route('pelanggan.pesanan.show', $pesananAktif->id) }}#bayar" class="btn btn-utama">Bayar</a>
                    @endif
                </div>
            </div>
        @else
            <div class="kosong">
                <svg width="48" height="48" viewBox="0 0 26 26" aria-hidden="true"><path d="M6 6.5a7 5 0 0 1 14 0" fill="none" stroke="#2453D8" stroke-width="2"/><path d="M3.5 9h19l-2.2 13.2a2 2 0 0 1-2 1.8H7.7a2 2 0 0 1-2-1.8z" fill="#EAF0FF" stroke="#2453D8" stroke-width="2" stroke-linejoin="round"/></svg>
                <b>Embernya masih kosong</b>
                <span>Belum ada pesanan yang jalan.</span>
                <a href="{{ route('pelanggan.pesanan.create') }}" class="btn btn-utama">Pesan petugas</a>
            </div>
        @endif
    </section>

    <section>
        <h2 class="bagian">Pesan lagi</h2>
        <div class="daftar">
            @foreach($paketLayanan as $paket)
                <a href="{{ route('pelanggan.pesanan.create', ['paket' => $paket->id]) }}" class="baris">
                    <span>
                        <span class="judul-baris">{{ $paket->nama_paket }}</span>
                        <span class="sub-baris">± {{ $paket->estimasi_durasi_jam }} jam</span>
                    </span>
                    <span class="harga">Rp{{ number_format($paket->harga, 0, ',', '.') }}</span>
                </a>
            @endforeach
        </div>
    </section>
</div>
@endsection

