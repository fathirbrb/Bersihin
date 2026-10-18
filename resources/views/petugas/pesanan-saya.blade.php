@extends('layouts.petugas')

@section('title', 'Pesanan saya')

@section('content')
    @php
        $berjalan = $pesanan->whereIn('status', ['diterima', 'dikerjakan'])->sortBy(fn ($p) => $p->tanggal->format('Y-m-d') . $p->jam_mulai);
        $riwayat = $pesanan->whereNotIn('status', ['diterima', 'dikerjakan']);
    @endphp

    <div class="kepala">
        <h1>Pesanan saya</h1>
        <p>{{ $berjalan->count() }} sedang jalan · {{ $riwayat->count() }} di riwayat</p>
    </div>

    @if($pesanan->isEmpty())
        <div class="kosong">
            @include('petugas.partials.logo', ['ukuran' => 48])
            <b>Kamu belum ambil pesanan</b>
            <span>Pesanan yang kamu ambil akan muncul di sini.</span>
            <a href="{{ route('petugas.pesanan-masuk') }}" class="btn btn-utama">Lihat pesanan masuk</a>
        </div>
    @else
        {{-- Pesanan yang masih harus dikerjakan --}}
        <h2 class="bagian">Sedang jalan</h2>
        @if($berjalan->isEmpty())
            <p style="color:var(--tinta-2);">Nggak ada kerjaan yang lagi jalan.</p>
        @else
            <div class="daftar">
                @foreach($berjalan as $item)
                    <a href="{{ route('petugas.pesanan.show', $item->id) }}" class="baris">
                        <span>
                            <span class="judul-baris">{{ $item->paketLayanan->nama_paket ?? '-' }}</span>
                            <span class="sub-baris angka">{{ $item->tanggal->translatedFormat('D, d M') }} · {{ str_replace(':', '.', substr($item->jam_mulai, 0, 5)) }} · {{ Str::limit($item->alamat, 32) }}</span>
                        </span>
                        @include('petugas.partials.status', ['status' => $item->status])
                    </a>
                @endforeach
            </div>
        @endif

        @if($riwayat->isNotEmpty())
            <h2 class="bagian">Riwayat</h2>
            <div class="daftar">
                @foreach($riwayat as $item)
                    <a href="{{ route('petugas.pesanan.show', $item->id) }}" class="baris">
                        <span>
                            <span class="judul-baris">{{ $item->paketLayanan->nama_paket ?? '-' }}</span>
                            <span class="sub-baris angka">{{ $item->tanggal->translatedFormat('D, d M Y') }} · {{ $item->pelanggan->nama ?? '-' }}</span>
                        </span>
                        @include('petugas.partials.status', ['status' => $item->status])
                    </a>
                @endforeach
            </div>
        @endif
    @endif
@endsection
