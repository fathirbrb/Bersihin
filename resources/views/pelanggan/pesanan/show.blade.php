@extends('layouts.pelanggan')

@section('title', 'Detail Pesanan')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
        <h1 class="page-title" style="margin-bottom:0;">Detail Pesanan #{{ $pesanan->id }}</h1>
        <a href="{{ route('pelanggan.pesanan.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <table style="border-collapse:collapse; width:100%;">
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600; width:200px;">Paket Layanan</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->paketLayanan->nama_paket ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Alamat</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->alamat }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Tanggal & Jam</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->tanggal->format('d M Y') }} — {{ $pesanan->jam_mulai }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Catatan</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->catatan ?: '—' }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Total Harga</td>
                <td style="padding:.5rem .75rem; font-weight:700;">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Status</td>
                <td style="padding:.5rem .75rem;"><span class="badge badge-{{ $pesanan->status }}">{{ $pesanan->status }}</span></td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Petugas</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->petugas->nama ?? 'Belum ada petugas' }}</td>
            </tr>
        </table>
    </div>

    {{-- Tombol Batalkan --}}
    @if($pesanan->status === 'menunggu')
        <div class="card">
            <form action="{{ route('pelanggan.pesanan.batalkan', $pesanan->id) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                @csrf
                <button type="submit" class="btn btn-danger">Batalkan Pesanan</button>
            </form>
        </div>
    @endif

    {{-- Ulasan --}}
    @if($pesanan->status === 'selesai')
        <div class="card">
            <h2 style="font-size:1.15rem; font-weight:700; margin-bottom:.75rem;">Ulasan</h2>

            @if($pesanan->ulasan)
                <p>
                    <span class="star">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $pesanan->ulasan->rating ? '★' : '☆' }}
                        @endfor
                    </span>
                    ({{ $pesanan->ulasan->rating }}/5)
                </p>
                <p style="margin-top:.4rem;">{{ $pesanan->ulasan->komentar ?: '—' }}</p>
            @else
                <p style="margin-bottom:.75rem; color:#6b7280;">Belum ada ulasan untuk pesanan ini.</p>
                <a href="{{ route('pelanggan.ulasan.create', $pesanan->id) }}" class="btn btn-warning">Tulis Ulasan</a>
            @endif
        </div>
    @endif

    {{-- Pembayaran --}}
    @if($pesanan->pembayaran)
        <div class="card">
            <h2 style="font-size:1.15rem; font-weight:700; margin-bottom:.75rem;">Pembayaran</h2>
            <table style="border-collapse:collapse; width:100%;">
                <tr>
                    <td style="padding:.5rem .75rem; font-weight:600; width:200px;">Metode</td>
                    <td style="padding:.5rem .75rem; text-transform:capitalize;">{{ $pesanan->pembayaran->metode }}</td>
                </tr>
                <tr>
                    <td style="padding:.5rem .75rem; font-weight:600;">Status</td>
                    <td style="padding:.5rem .75rem;"><span class="badge badge-{{ $pesanan->pembayaran->status === 'lunas' ? 'selesai' : ($pesanan->pembayaran->status === 'ditolak' ? 'dibatalkan' : 'menunggu') }}">{{ $pesanan->pembayaran->status }}</span></td>
                </tr>
            </table>
        </div>
    @endif
@endsection
