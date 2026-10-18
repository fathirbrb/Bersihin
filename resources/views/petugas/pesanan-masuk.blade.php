@extends('layouts.petugas')

@section('title', 'Pesanan masuk')

@section('content')
    <div class="kepala">
        <h1>Pesanan masuk</h1>
        <p>{{ $pesanan->count() }} pesanan nunggu diambil</p>
    </div>

    @if($pesanan->isEmpty())
        <div class="kosong">
            @include('petugas.partials.logo', ['ukuran' => 48])
            <b>Belum ada yang pesan</b>
            <span>Pesanan baru muncul di sini begitu pelanggan memesan.</span>
        </div>
    @else
        @foreach($pesanan as $item)
            {{-- Pesanan terdekat jadi kartu fokus --}}
            <div class="kartu {{ $loop->first ? 'kartu-fokus' : '' }}">
                <div class="atas">
                    <p class="paket">{{ $item->paketLayanan->nama_paket ?? '-' }} · {{ $item->paketLayanan->estimasi_durasi_jam ?? '-' }} jam</p>
                    <span class="angka" style="font-weight:600;">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</span>
                </div>
                <p class="{{ $loop->first ? 'jam' : 'jam-kecil' }}">{{ str_replace(':', '.', substr($item->jam_mulai, 0, 5)) }}</p>
                <p class="tgl">{{ $item->tanggal->translatedFormat('l, d F') }}</p>
                <dl class="info">
                    <dt>Alamat</dt><dd>{{ $item->alamat }}</dd>
                    @if($item->catatan)
                        <dt>Catatan</dt><dd>{{ $item->catatan }}</dd>
                    @endif
                </dl>
                <div class="{{ $loop->first ? 'aksi-tumpuk' : 'aksi' }}">
                    <form action="{{ route('petugas.pesanan.terima', $item->id) }}" method="POST" onsubmit="return confirm('Ambil pesanan ini?')">
                        @csrf
                        <button type="submit" class="btn {{ $loop->first ? 'btn-utama btn-penuh' : 'btn-kedua btn-kecil' }}">{{ $loop->first ? 'Ambil pesanan ini' : 'Ambil' }}</button>
                    </form>
                    <a href="{{ route('petugas.pesanan.show', $item->id) }}" class="btn btn-teks">Lihat detail</a>
                </div>
            </div>
        @endforeach
    @endif
@endsection
