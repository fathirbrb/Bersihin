@extends('layouts.pelanggan')

@section('title', 'Tulis Ulasan')
@section('main-class', 'sempit')

@section('content')
<a href="{{ route('pelanggan.ulasan.index') }}" class="kembali">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"/></svg>
    Ulasan
</a>

<div class="kepala">
    <h1>Gimana hasilnya?</h1>
    <p>{{ $pesanan->paketLayanan->nama_paket ?? '-' }} · {{ $pesanan->tanggal->translatedFormat('l, d F') }} · {{ $pesanan->petugas->nama ?? 'Petugas' }}</p>
</div>

<form action="{{ route('pelanggan.ulasan.store', $pesanan->id) }}" method="POST">
    @csrf

    <fieldset class="isian" style="border:none;">
        <legend class="judul-isian">Bintang</legend>
        <div class="rating">
            <input type="radio" name="rating" id="r1" value="1" data-label="Kecewa" {{ old('rating') == '1' ? 'checked' : '' }} required>
            <label for="r1"><span class="sr">1 bintang</span><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9z"/></svg></label>

            <input type="radio" name="rating" id="r2" value="2" data-label="Kurang" {{ old('rating') == '2' ? 'checked' : '' }}>
            <label for="r2"><span class="sr">2 bintang</span><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9z"/></svg></label>

            <input type="radio" name="rating" id="r3" value="3" data-label="Lumayan" {{ old('rating') == '3' ? 'checked' : '' }}>
            <label for="r3"><span class="sr">3 bintang</span><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9z"/></svg></label>

            <input type="radio" name="rating" id="r4" value="4" data-label="Bagus" {{ old('rating') == '4' ? 'checked' : '' }}>
            <label for="r4"><span class="sr">4 bintang</span><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9z"/></svg></label>

            <input type="radio" name="rating" id="r5" value="5" data-label="Mantap" {{ old('rating') == '5' ? 'checked' : '' }}>
            <label for="r5"><span class="sr">5 bintang</span><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9z"/></svg></label>
        </div>
        <p class="rating-label" id="rating-label" aria-live="polite"></p>
        @error('rating')
            <p class="galat">{{ $message }}</p>
        @enderror
    </fieldset>

    <div class="isian">
        <label for="komentar">Komentar <span class="sub-baris" style="display:inline;">(opsional)</span></label>
        <textarea id="komentar" name="komentar" rows="4" placeholder="Ceritain sedikit">{{ old('komentar') }}</textarea>
        @error('komentar')
            <p class="galat">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn btn-utama btn-penuh">Kirim ulasan</button>
</form>

@push('scripts')
<script>
    document.querySelectorAll('.rating input').forEach(radio => {
        if (radio.checked) {
            document.getElementById('rating-label').textContent = radio.dataset.label;
        }
        radio.addEventListener('change', () => {
            document.getElementById('rating-label').textContent = radio.dataset.label;
        });
    });
</script>
@endpush
@endsection
