@extends('layouts.petugas')

@section('title', 'Ulasan')

@section('content')
    <div class="kepala">
        <h1>Ulasan</h1>
        @if($ulasan->isNotEmpty())
            <p>Rata-rata <b class="angka">{{ number_format($ulasan->avg('rating'), 1, ',', '.') }}</b> dari {{ $ulasan->count() }} ulasan</p>
        @endif
    </div>

    @if($ulasan->isEmpty())
        <div class="kosong">
            @include('petugas.partials.logo', ['ukuran' => 48])
            <b>Belum ada ulasan</b>
            <span>Ulasan muncul setelah pelanggan menilai pesanan yang sudah beres.</span>
        </div>
    @else
        <div class="daftar">
            @foreach($ulasan as $item)
                <div class="baris" style="display:block;">
                    <p><span class="bintang" aria-hidden="true">{{ str_repeat('★', $item->rating) }}{{ str_repeat('☆', 5 - $item->rating) }}</span> <b>{{ $item->rating }}/5</b></p>
                    <p style="margin-top:4px;">{{ $item->komentar ?: 'Tanpa komentar.' }}</p>
                    <p class="sub-baris" style="margin-top:4px;">{{ $item->pesanan->paketLayanan->nama_paket ?? '-' }} · {{ $item->pesanan->pelanggan->nama ?? '-' }} · {{ $item->pesanan->tanggal->translatedFormat('d M Y') }}</p>
                </div>
            @endforeach
        </div>
    @endif
@endsection
