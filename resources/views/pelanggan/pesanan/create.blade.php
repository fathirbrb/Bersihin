@extends('layouts.pelanggan')

@section('title', 'Buat Pesanan Baru')

@section('content')
    <h1 class="page-title">Buat Pesanan Baru</h1>

    <div class="card">
        <form action="{{ route('pelanggan.pesanan.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="paket_layanan_id">Paket Layanan</label>
                <select name="paket_layanan_id" id="paket_layanan_id" class="form-control" required>
                    <option value="">— Pilih paket —</option>
                    @foreach($paketLayanan as $paket)
                        <option value="{{ $paket->id }}" {{ old('paket_layanan_id') == $paket->id ? 'selected' : '' }}>
                            {{ $paket->nama_paket }} — Rp{{ number_format($paket->harga, 0, ',', '.') }}
                            (± {{ $paket->estimasi_durasi_jam }} jam)
                        </option>
                    @endforeach
                </select>
                @error('paket_layanan_id') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" class="form-control" required placeholder="Masukkan alamat lengkap...">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                    @error('tanggal') <p class="text-danger">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label for="jam_mulai">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
                    @error('jam_mulai') <p class="text-danger">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan (opsional)</label>
                <textarea name="catatan" id="catatan" class="form-control" placeholder="Contoh: kunci di bawah pot bunga...">{{ old('catatan') }}</textarea>
                @error('catatan') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex; gap:.75rem; margin-top:1rem;">
                <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
                <a href="{{ route('pelanggan.pesanan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
