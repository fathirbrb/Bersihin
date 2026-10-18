@extends('layouts.admin')

@section('title', 'Kelola Paket Layanan')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
        <h1 class="page-title" style="margin-bottom:0;">Kelola Paket Layanan</h1>
    </div>

    @if($paketLayanan->isEmpty())
        <div class="card empty-state">
            <p>Belum ada paket layanan.</p>
        </div>
    @else
        @foreach($paketLayanan as $paket)
            <div class="card">
                <form action="{{ route('admin.paket.update', $paket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h2 class="section-title">Paket #{{ $paket->id }}</h2>

                    <div class="form-group">
                        <label for="nama_paket_{{ $paket->id }}">Nama Paket</label>
                        <input type="text" name="nama_paket" id="nama_paket_{{ $paket->id }}"
                               class="form-control" value="{{ old('nama_paket', $paket->nama_paket) }}" required>
                        @error('nama_paket') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_{{ $paket->id }}">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi_{{ $paket->id }}" class="form-control" required>{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div class="form-group">
                            <label for="harga_{{ $paket->id }}">Harga (Rp)</label>
                            <input type="number" name="harga" id="harga_{{ $paket->id }}"
                                   class="form-control" min="0" value="{{ old('harga', $paket->harga) }}" required>
                            @error('harga') <p class="text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="estimasi_durasi_jam_{{ $paket->id }}">Estimasi Durasi (jam)</label>
                            <input type="number" name="estimasi_durasi_jam" id="estimasi_durasi_jam_{{ $paket->id }}"
                                   class="form-control" min="1" value="{{ old('estimasi_durasi_jam', $paket->estimasi_durasi_jam) }}" required>
                            @error('estimasi_durasi_jam') <p class="text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div style="margin-top:1rem;">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        @endforeach
    @endif
@endsection