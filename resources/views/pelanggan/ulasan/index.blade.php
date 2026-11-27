@extends('layouts.pelanggan')

@section('title', 'Ulasan')

@section('content')
<div class="kepala">
    <h1>Ulasan</h1>
    @if($belumDiulas->isNotEmpty() || $sudahDiulas->isNotEmpty())
        <p>{{ $belumDiulas->count() }} belum diulas</p>
    @endif
</div>

@if($belumDiulas->isEmpty() && $sudahDiulas->isEmpty())
    <div class="kosong">
        <svg width="48" height="48" viewBox="0 0 26 26" aria-hidden="true"><path d="M6 6.5a7 5 0 0 1 14 0" fill="none" stroke="#2453D8" stroke-width="2"/><path d="M3.5 9h19l-2.2 13.2a2 2 0 0 1-2 1.8H7.7a2 2 0 0 1-2-1.8z" fill="#EAF0FF" stroke="#2453D8" stroke-width="2" stroke-linejoin="round"/></svg>
        <b>Belum ada ulasan</b>
        <span>Selesaikan pesanan untuk memberikan ulasan kepada petugas.</span>
    </div>
@else
    @if($belumDiulas->isNotEmpty())
        <h2 class="bagian" style="margin-top:8px;">Belum diulas</h2>
        <div class="daftar">
            @foreach($belumDiulas as $item)
                <div class="baris">
                    <span>
                        <span class="judul-baris">{{ $item->paketLayanan->nama_paket ?? '-' }}</span>
                        <span class="sub-baris">{{ $item->tanggal->translatedFormat('l, d F') }} · {{ $item->petugas->nama ?? 'Petugas' }}</span>
                    </span>
                    <a href="{{ route('pelanggan.ulasan.create', $item->id) }}" class="btn btn-kedua btn-kecil">Kasih ulasan</a>
                </div>
            @endforeach
        </div>
    @endif

    @if($sudahDiulas->isNotEmpty())
        <h2 class="bagian" style="{{ $belumDiulas->isEmpty() ? 'margin-top:8px;' : '' }}">Sudah diulas</h2>
        @foreach($sudahDiulas as $item)
            <div class="kartu">
                <div class="atas">
                    <p class="paket">{{ $item->pesanan->paketLayanan->nama_paket ?? 'Layanan' }} · {{ $item->pesanan->tanggal->translatedFormat('l, d F') }}</p>
                    <button type="button" class="btn btn-teks bahaya" onclick="document.getElementById('dialog-hapus-{{ $item->id }}').showModal()" style="min-height:32px;padding:0 6px;">Hapus</button>
                </div>
                <div style="margin-top:4px;">
                    <span class="status s-beres">Rating {{ $item->rating }}/5</span>
                </div>
                @if($item->komentar)
                    <p style="margin-top:10px;">{{ $item->komentar }}</p>
                @endif
                <p class="sub-baris" style="margin-top:10px;">Dikerjakan oleh {{ $item->pesanan->petugas->nama ?? 'Petugas' }}</p>
            </div>

            <dialog id="dialog-hapus-{{ $item->id }}" aria-labelledby="judul-hapus-{{ $item->id }}">
                <h2 id="judul-hapus-{{ $item->id }}">Hapus ulasan ini?</h2>
                <p>Ulasan yang dihapus tidak bisa dikembalikan.</p>
                <form action="{{ route('pelanggan.ulasan.destroy', $item->id) }}" method="POST" class="aksi">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-teks" onclick="document.getElementById('dialog-hapus-{{ $item->id }}').close()">Tidak jadi</button>
                    <button type="submit" class="btn btn-bahaya btn-kecil">Ya, hapus</button>
                </form>
            </dialog>
        @endforeach
    @endif
@endif
@endsection
