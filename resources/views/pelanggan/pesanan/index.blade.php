@extends('layouts.pelanggan')

@section('title', 'Pesananku')

@section('content')
<div class="kepala kepala-baris">
    <div>
        <h1>Pesananku</h1>
        @if($pesanan->isNotEmpty())
            <p>{{ $pesanan->count() }} pesanan</p>
        @endif
    </div>
    @if($pesanan->isNotEmpty())
        <a href="{{ route('pelanggan.pesanan.create') }}" class="btn btn-kedua btn-kecil">Pesan baru</a>
    @endif
</div>

@if($pesanan->isEmpty())
    <div class="kosong">
        <svg width="48" height="48" viewBox="0 0 26 26" aria-hidden="true"><path d="M6 6.5a7 5 0 0 1 14 0" fill="none" stroke="#2453D8" stroke-width="2"/><path d="M3.5 9h19l-2.2 13.2a2 2 0 0 1-2 1.8H7.7a2 2 0 0 1-2-1.8z" fill="#EAF0FF" stroke="#2453D8" stroke-width="2" stroke-linejoin="round"/></svg>
        <b>Belum pernah pesan</b>
        <span>Pesananmu muncul di sini.</span>
        <a href="{{ route('pelanggan.pesanan.create') }}" class="btn btn-utama">Pesan petugas</a>
    </div>
@else
    @if($pesananJalan->isNotEmpty())
        <h2 class="bagian" style="margin-top:8px;">Lagi jalan</h2>
        <div class="daftar">
            @foreach($pesananJalan as $item)
                @php
                    $step = match($item->status) {
                        'menunggu' => 1,
                        'diterima' => 2,
                        'dikerjakan' => 3,
                        default => 1,
                    };
                    $statusClass = match($item->status) {
                        'menunggu' => 's-tunggu',
                        'diterima', 'dikerjakan' => 's-jalan',
                        default => 's-jalan',
                    };
                    $label = match($item->status) {
                        'menunggu' => 'Menunggu',
                        'diterima' => 'Diterima',
                        'dikerjakan' => 'Dikerjakan',
                        default => ucfirst($item->status),
                    };
                @endphp
                <a href="{{ route('pelanggan.pesanan.show', $item->id) }}" class="baris">
                    <span>
                        <span class="judul-baris">{{ $item->paketLayanan->nama_paket ?? '-' }}</span>
                        <span class="sub-baris">{{ $item->tanggal->translatedFormat('l, d F') }} · {{ substr($item->jam_mulai, 0, 5) }}</span>
                    </span>
                    <span class="status {{ $statusClass }}">
                        <span class="m" aria-hidden="true">
                            <i class="{{ $step >= 1 ? 'on' : '' }}"></i>
                            <i class="{{ $step >= 2 ? 'on' : '' }}"></i>
                            <i class="{{ $step >= 3 ? 'on' : '' }}"></i>
                            <i class="{{ $step >= 4 ? 'on' : '' }}"></i>
                        </span>
                        {{ $label }}
                    </span>
                </a>
            @endforeach
        </div>
    @endif

    @if($riwayat->isNotEmpty())
        <h2 class="bagian" style="{{ $pesananJalan->isEmpty() ? 'margin-top:8px;' : '' }}">Riwayat</h2>
        <div class="daftar">
            @foreach($riwayat as $item)
                <a href="{{ route('pelanggan.pesanan.show', $item->id) }}" class="baris">
                    <span>
                        <span class="judul-baris">{{ $item->paketLayanan->nama_paket ?? '-' }}</span>
                        <span class="sub-baris">{{ $item->tanggal->translatedFormat('l, d F') }} · {{ substr($item->jam_mulai, 0, 5) }}</span>
                    </span>
                    @if($item->status === 'selesai')
                        <span class="status s-beres">
                            <span class="m" aria-hidden="true"><i class="on"></i><i class="on"></i><i class="on"></i><i class="on"></i></span>
                            Selesai
                        </span>
                    @else
                        <span class="status s-batal">Dibatalkan</span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
@endif
@endsection
