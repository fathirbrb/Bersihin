@extends('layouts.petugas')

@section('title', 'Detail pesanan')

@section('content')
    @php
        $durasi = $pesanan->paketLayanan->estimasi_durasi_jam ?? 0;
        $mulai = str_replace(':', '.', substr($pesanan->jam_mulai, 0, 5));
        $selesai = \Carbon\Carbon::parse($pesanan->jam_mulai)->addHours($durasi)->format('H.i');
        $milikSaya = $pesanan->petugas_id == Auth::id();
    @endphp

    <div class="kepala kepala-baris">
        <div>
            <h1>{{ $pesanan->paketLayanan->nama_paket ?? 'Pesanan' }}</h1>
            <p class="angka">{{ $pesanan->tanggal->translatedFormat('l, d F') }} · {{ $mulai }} sampai {{ $selesai }}</p>
        </div>
        <a href="{{ $milikSaya ? route('petugas.pesanan-saya') : route('petugas.pesanan-masuk') }}" class="btn btn-teks">Kembali</a>
    </div>

    <div class="kartu kartu-fokus">
        @if($pesanan->status == 'dibatalkan')
            @include('petugas.partials.status', ['status' => $pesanan->status])
        @else
            @include('petugas.partials.level', ['status' => $pesanan->status])
        @endif

        <dl class="info">
            <dt>Alamat</dt><dd>{{ $pesanan->alamat }}</dd>
            <dt>Catatan</dt><dd>{{ $pesanan->catatan ?: '-' }}</dd>
            <dt>Pelanggan</dt>
            <dd>
                {{ $pesanan->pelanggan->nama ?? '-' }}
                {{-- Nomor HP setelah pesanan diambil --}}
                @if($milikSaya)
                    · <a href="tel:{{ $pesanan->pelanggan->no_hp ?? '' }}">{{ $pesanan->pelanggan->no_hp ?? '-' }}</a>
                @endif
            </dd>
            <dt>Bayaran</dt><dd class="angka">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</dd>
        </dl>

        {{-- Satu aksi utama sesuai status --}}
        @if($pesanan->status == 'menunggu' && $pesanan->petugas_id == null)
            <form action="{{ route('petugas.pesanan.terima', $pesanan->id) }}" method="POST" onsubmit="return confirm('Ambil pesanan ini?')">
                @csrf
                <button type="submit" class="btn btn-utama btn-penuh">Ambil pesanan ini</button>
            </form>
        @elseif($pesanan->status == 'diterima' && $milikSaya)
            <form action="{{ route('petugas.pesanan.kerjakan', $pesanan->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-utama btn-penuh">Aku sudah sampai</button>
            </form>
            <p class="sub-baris" style="margin-top:8px;text-align:center;">Tekan begitu kamu tiba di lokasi.</p>
        @elseif($pesanan->status == 'dikerjakan' && $milikSaya)
            <form action="{{ route('petugas.pesanan.selesai', $pesanan->id) }}" method="POST" onsubmit="return confirm('Pesanan ini sudah beres?')">
                @csrf
                <button type="submit" class="btn btn-utama btn-penuh">Tandai beres</button>
            </form>
        @endif
    </div>

    <h2 class="bagian">Pembayaran</h2>
    <div class="daftar">
        <div class="baris">
            @if($pesanan->pembayaran)
                @php
                    $bayar = [
                        'menunggu' => ['s-tunggu', 'Menunggu konfirmasi'],
                        'lunas' => ['s-beres', 'Lunas'],
                        'ditolak' => ['s-batal', 'Ditolak'],
                    ][$pesanan->pembayaran->status] ?? ['s-jalan', $pesanan->pembayaran->status];
                @endphp
                <span>
                    <span class="judul-baris">{{ ucfirst($pesanan->pembayaran->metode) }}</span>
                    <span class="sub-baris">{{ $pesanan->pembayaran->metode == 'tunai' ? 'Dibayar langsung ke petugas' : 'Bukti transfer dicek admin' }}</span>
                </span>
                <span class="status {{ $bayar[0] }}">{{ $bayar[1] }}</span>
            @else
                <span>
                    <span class="judul-baris">Belum dibayar</span>
                    <span class="sub-baris">Pelanggan belum memilih cara bayar.</span>
                </span>
            @endif
        </div>
    </div>

    {{-- Ulasan setelah pesanan beres --}}
    @if($pesanan->status == 'selesai')
        <h2 class="bagian">Ulasan pelanggan</h2>
        <div class="daftar">
            <div class="baris" style="display:block;">
                @if($pesanan->ulasan)
                    <p><span class="bintang" aria-hidden="true">{{ str_repeat('★', $pesanan->ulasan->rating) }}{{ str_repeat('☆', 5 - $pesanan->ulasan->rating) }}</span> <b>{{ $pesanan->ulasan->rating }}/5</b></p>
                    <p style="margin-top:4px;">{{ $pesanan->ulasan->komentar ?: 'Tanpa komentar.' }}</p>
                @else
                    <p class="sub-baris">Pelanggan belum memberi ulasan.</p>
                @endif
            </div>
        </div>
    @endif
@endsection
