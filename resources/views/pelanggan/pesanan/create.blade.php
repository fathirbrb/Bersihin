@extends('layouts.pelanggan')

@section('title', 'Pesan petugas')
@section('main-class', 'sempit')

@section('content')
<div class="kepala">
    <h1>Pesan petugas</h1>
    <p>Bayar setelah pesanan dibuat.</p>
</div>

<form action="{{ route('pelanggan.pesanan.store') }}" method="POST">
    @csrf

    <fieldset class="pilihan isian">
        <legend>Paket</legend>
        @foreach($paketLayanan as $paket)
            <label class="opsi">
                <input type="radio" name="paket_layanan_id" value="{{ $paket->id }}" data-harga="Rp{{ number_format($paket->harga, 0, ',', '.') }}" {{ (old('paket_layanan_id', $selectedPaketId ?? '') == $paket->id) || ($loop->first && !old('paket_layanan_id') && empty($selectedPaketId)) ? 'checked' : '' }} required>
                <span>
                    <span class="judul-baris">{{ $paket->nama_paket }}</span>
                    <span class="sub-baris">{{ $paket->deskripsi }}</span>
                </span>
                <span style="text-align:right;flex:none;">
                    <span class="harga" style="display:block;">Rp{{ number_format($paket->harga, 0, ',', '.') }}</span>
                    <span class="sub-baris">± {{ $paket->estimasi_durasi_jam }} jam</span>
                </span>
            </label>
        @endforeach
        @error('paket_layanan_id')
            <p class="galat">{{ $message }}</p>
        @enderror
    </fieldset>

    <div class="isian {{ $errors->has('alamat') ? 'isian-salah' : '' }}">
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3" autocomplete="street-address" placeholder="Jalan, nomor rumah, kelurahan" required>{{ old('alamat') }}</textarea>
        @error('alamat')
            <p class="galat" id="galat-alamat">{{ $message }}</p>
        @enderror
    </div>

    <div class="isian-dua">
        <div class="isian {{ $errors->has('tanggal') ? 'isian-salah' : '' }}">
            <label for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::tomorrow()->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
            @error('tanggal')
                <p class="galat">{{ $message }}</p>
            @enderror
        </div>
        <div class="isian {{ $errors->has('jam_mulai') ? 'isian-salah' : '' }}">
            <label for="jam_mulai">Jam mulai</label>
            <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', '09:00') }}" required>
            @error('jam_mulai')
                <p class="galat">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="isian {{ $errors->has('catatan') ? 'isian-salah' : '' }}">
        <label for="catatan">Catatan <span class="sub-baris" style="display:inline;">(opsional)</span></label>
        <textarea id="catatan" name="catatan" rows="3" placeholder="Misal: pagar hijau, ada kucing di rumah">{{ old('catatan') }}</textarea>
        @error('catatan')
            <p class="galat">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn btn-utama btn-penuh" id="tombol-pesan">Pesan</button>
</form>
@endsection
