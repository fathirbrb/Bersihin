@extends('layouts.pelanggan')

@section('title', 'Tulis Ulasan')

@section('content')
    <h1 class="page-title">Tulis Ulasan</h1>

    <div class="card" style="margin-bottom:1rem;">
        <p><strong>Pesanan #{{ $pesanan->id }}</strong> — {{ $pesanan->paketLayanan->nama_paket ?? '-' }}</p>
        <p style="font-size:.85rem; color:#6b7280;">{{ $pesanan->tanggal->format('d M Y') }} — {{ $pesanan->jam_mulai }}</p>
    </div>

    <div class="card">
        <form action="{{ route('pelanggan.ulasan.store', $pesanan->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="rating">Rating (1–5)</label>
                <select name="rating" id="rating" class="form-control" required>
                    <option value="">— Pilih rating —</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                            {{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }} ({{ $i }})
                        </option>
                    @endfor
                </select>
                @error('rating') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="komentar">Komentar (opsional)</label>
                <textarea name="komentar" id="komentar" class="form-control" placeholder="Ceritakan pengalamanmu...">{{ old('komentar') }}</textarea>
                @error('komentar') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex; gap:.75rem; margin-top:1rem;">
                <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                <a href="{{ route('pelanggan.pesanan.show', $pesanan->id) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
