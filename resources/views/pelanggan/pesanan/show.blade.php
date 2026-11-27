@extends('layouts.pelanggan')

@section('title', 'Detail Pesanan')

@section('content')
<a href="{{ route('pelanggan.pesanan.index') }}" class="kembali">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"/></svg>
    Pesananku
</a>

@php
    $mulaiCarbon = \Carbon\Carbon::parse($pesanan->tanggal->format('Y-m-d') . ' ' . $pesanan->jam_mulai);
    $selesaiCarbon = $mulaiCarbon->copy()->addHours($pesanan->durasi_jam);

    $step = match($pesanan->status) {
        'menunggu' => 1,
        'diterima' => 2,
        'dikerjakan' => 3,
        'selesai' => 4,
        default => 1,
    };
    $stepText = match($pesanan->status) {
        'menunggu' => 'Menunggu petugas',
        'diterima' => 'Diterima, belum mulai',
        'dikerjakan' => 'Sedang dikerjakan',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
        default => ucfirst($pesanan->status),
    };
    $percent = $step * 25;
@endphp

<div class="kepala">
    <h1>{{ $pesanan->paketLayanan->nama_paket ?? 'Pesanan' }}</h1>
    <p class="angka">{{ $pesanan->tanggal->translatedFormat('l, d F') }} · {{ substr($pesanan->jam_mulai, 0, 5) }} sampai {{ $selesaiCarbon->format('H.i') }}</p>
</div>

<div class="dua-kolom">
    <section>
        <h2 class="bagian">Pesanan</h2>
        <div class="kartu {{ $pesanan->status !== 'dibatalkan' ? 'kartu-fokus' : '' }}">
            @if($pesanan->status === 'dibatalkan')
                <span class="status s-batal">Dibatalkan</span>
            @else
                <div class="level">
                    <div class="tabung" role="progressbar" aria-valuemin="0" aria-valuemax="4" aria-valuenow="{{ $step }}" aria-label="Progres pesanan">
                        <span style="width:{{ $percent }}%"></span>
                    </div>
                    <div class="label">
                        <b>{{ $stepText }}</b>
                        <span>Langkah {{ $step }} dari 4</span>
                    </div>
                </div>
            @endif

            <dl class="info">
                <dt>Alamat</dt>
                <dd>{{ $pesanan->alamat }}</dd>
                <dt>Catatan</dt>
                <dd>{{ $pesanan->catatan ?: '-' }}</dd>
                <dt>Petugas</dt>
                @if($pesanan->petugas)
                    <dd>{{ $pesanan->petugas->nama }} @if($pesanan->petugas->no_hp) · <a href="tel:{{ $pesanan->petugas->no_hp }}">{{ $pesanan->petugas->no_hp }}</a>@endif</dd>
                @else
                    <dd>Belum ada</dd>
                @endif
                <dt>Harga</dt>
                <dd class="angka">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</dd>
            </dl>
        </div>

        @if($pesanan->status === 'menunggu')
            <button type="button" class="btn btn-teks bahaya" onclick="document.getElementById('dialog-batal').showModal()" style="margin:8px 0 0 -10px;">Batalkan pesanan</button>
        @elseif($pesanan->status === 'dibatalkan')
            <a href="{{ route('pelanggan.pesanan.create', ['paket' => $pesanan->paket_layanan_id]) }}" class="btn btn-kedua" style="margin-top:16px;">Pesan lagi</a>
        @endif
    </section>

    @if($pesanan->status !== 'dibatalkan')
        <section>
            <h2 class="bagian" id="bayar">💳 Pembayaran</h2>

            @if($pesanan->pembayaran)
                <div class="daftar">
                    <div class="baris">
                        <span>
                            <span class="judul-baris" style="text-transform: capitalize;">{{ $pesanan->pembayaran->metode }}</span>
                            <span class="sub-baris">
                                Status: {{ $pesanan->pembayaran->status }}
                                @if($pesanan->pembayaran->bukti_bayar)
                                    · <a href="{{ route('bukti-bayar', $pesanan->pembayaran->id) }}" target="_blank" style="text-decoration:underline;">Bukti Pembayaran</a>
                                @endif
                            </span>
                        </span>
                        @if($pesanan->pembayaran->status === 'lunas')
                            <span class="status s-beres">Lunas</span>
                        @elseif($pesanan->pembayaran->status === 'ditolak')
                            <span class="status s-batal">Ditolak</span>
                        @else
                            <span class="status s-tunggu">Menunggu</span>
                        @endif
                    </div>
                </div>
            @endif

            @if(! $pesanan->pembayaran || $pesanan->pembayaran->status === 'ditolak')
                <div class="kartu" @if($pesanan->pembayaran) style="margin-top:12px;" @endif>
                    <form action="{{ route('pelanggan.pembayaran.store', $pesanan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($pesanan->pembayaran)
                            <p class="sub-baris" style="margin-bottom:12px;">Pembayaran sebelumnya ditolak. Kirim ulang dengan bukti yang benar.</p>
                        @endif
                        <p class="sub-baris" style="margin-bottom:12px;">Total yang harus dibayar: <b style="color:var(--tinta);">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</b></p>
                        <fieldset class="pilihan isian">
                            <legend class="sr">Cara bayar</legend>
                            <div class="segmen">
                                <label class="opsi">
                                    <input type="radio" name="metode" value="transfer" checked onchange="toggleBukti(true)"> Transfer
                                </label>
                                <label class="opsi">
                                    <input type="radio" name="metode" value="tunai" onchange="toggleBukti(false)"> Tunai
                                </label>
                            </div>
                        </fieldset>

                        <div class="isian" id="isian-bukti">
                            <p class="sub-baris">Rekening: <b style="color:var(--tinta);">BCA 1234567890 a.n. Bersihin</b></p>
                            <p class="judul-isian">Upload Bukti Pembayaran</p>
                            <div class="berkas">
                                <label class="btn btn-kedua btn-kecil pilih-berkas">
                                    Pilih foto
                                    <input type="file" id="bukti_bayar" name="bukti_bayar" accept="image/jpeg,image/png" onchange="previewBukti(this)" aria-describedby="nama-bukti">
                                </label>
                                <span class="sub-baris" id="nama-bukti">JPG atau PNG, maks. 2 MB</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-utama btn-penuh">Kirim Pembayaran</button>
                    </form>
                </div>
            @endif

            @if($pesanan->status === 'selesai')
                <h2 class="bagian">Ulasan</h2>
                @if($pesanan->ulasan)
                    <div class="kartu">
                        <div class="atas">
                            <span class="status s-beres">⭐ {{ $pesanan->ulasan->rating }}/5</span>
                        </div>
                        @if($pesanan->ulasan->komentar)
                            <p style="margin-top:12px;">{{ $pesanan->ulasan->komentar }}</p>
                        @endif
                    </div>
                @else
                    <div class="daftar">
                        <div class="baris">
                            <span class="judul-baris">Belum diulas</span>
                            <a href="{{ route('pelanggan.ulasan.create', $pesanan->id) }}" class="btn btn-kedua btn-kecil">Kasih ulasan</a>
                        </div>
                    </div>
                @endif
            @endif
        </section>
    @endif
</div>

@if($pesanan->status === 'menunggu')
    <dialog id="dialog-batal" aria-labelledby="judul-batal">
        <h2 id="judul-batal">Batalkan pesanan ini?</h2>
        <p>Pesanan yang dibatalkan nggak bisa dibalikin.</p>
        <form action="{{ route('pelanggan.pesanan.batalkan', $pesanan->id) }}" method="POST" class="aksi">
            @csrf
            <button type="button" class="btn btn-teks" onclick="document.getElementById('dialog-batal').close()">Nggak jadi</button>
            <button type="submit" class="btn btn-bahaya btn-kecil">Ya, batalkan</button>
        </form>
    </dialog>
@endif

@push('scripts')
<script>
    function toggleBukti(isTransfer) {
        const isian = document.getElementById('isian-bukti');
        if (isian) {
            isian.style.display = isTransfer ? 'grid' : 'none';
        }
    }

    function previewBukti(input) {
        const nama = document.getElementById('nama-bukti');
        if (input.files && input.files[0]) {
            nama.textContent = input.files[0].name;
        }
    }
</script>
@endpush
@endsection
