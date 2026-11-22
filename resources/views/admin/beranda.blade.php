@extends('layouts.admin')

@section('title', 'Beranda admin')

@section('content')
    <div class="kepala">
        <h1>Halo, {{ Auth::user()->nama }}</h1>
        <p>{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <h2 class="bagian" style="margin-top:8px;">Perlu dicek</h2>

    @if($pembayaranMenungguCount > 0 || $petugasMenungguCount > 0)
        <div class="daftar">
            @if($pembayaranMenungguCount > 0)
                <a href="{{ route('admin.pembayaran.index') }}" class="baris">
                    <span>
                        <span class="judul-baris"><span class="angka">{{ $pembayaranMenungguCount }}</span> pembayaran nunggu konfirmasi</span>
                        @if($pembayaranMenungguTerbaru)
                            <span class="sub-baris">Pesanan #{{ $pembayaranMenungguTerbaru->pesanan_id }}, {{ $pembayaranMenungguTerbaru->metode }}, {{ $pembayaranMenungguTerbaru->pesanan->pelanggan->nama ?? '-' }}</span>
                        @endif
                    </span>
                    <span class="status s-tunggu">Cek</span>
                </a>
            @endif

            @if($petugasMenungguCount > 0)
                <a href="{{ route('admin.petugas.index') }}" class="baris">
                    <span>
                        <span class="judul-baris"><span class="angka">{{ $petugasMenungguCount }}</span> petugas nunggu verifikasi</span>
                        @if($petugasMenungguTerbaru)
                            <span class="sub-baris">{{ $petugasMenungguTerbaru->nama }}, daftar {{ $petugasMenungguTerbaru->created_at->translatedFormat('d F') }}</span>
                        @endif
                    </span>
                    <span class="status s-tunggu">Cek</span>
                </a>
            @endif
        </div>
    @else
        <div class="kosong" style="padding:28px 20px;">
            <b>Semua sudah dicek</b>
            <span style="margin-bottom:0;">Nggak ada pembayaran atau petugas yang nunggu.</span>
        </div>
    @endif

    <div class="kepala-baris" style="margin:32px 0 12px;">
        <h2 class="bagian" style="margin:0;">Pesanan terbaru</h2>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-teks">Semua pesanan</a>
    </div>

    @if($pesananTerbaru->isEmpty())
        <div class="kosong" style="padding:28px 20px;">
            <b>Belum ada pesanan</b>
            <span style="margin-bottom:0;">Pesanan terbaru akan muncul di sini.</span>
        </div>
    @else
        <div class="tabel-wadah">
            <table class="tabel">
                <thead>
                    <tr><th>#</th><th>Paket</th><th>Pelanggan</th><th>Petugas</th><th>Jadwal</th><th class="kanan">Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($pesananTerbaru as $p)
                        <tr>
                            <td data-label="#" class="angka">{{ $p->id }}</td>
                            <td data-label="Paket">{{ $p->paketLayanan->nama_paket ?? '-' }}</td>
                            <td data-label="Pelanggan">{{ $p->pelanggan->nama ?? '-' }}</td>
                            <td data-label="Petugas" @if(!$p->petugas) style="color:var(--tinta-2);" @endif>{{ $p->petugas->nama ?? '-' }}</td>
                            <td data-label="Jadwal" class="angka">
                                {{ $p->tanggal ? $p->tanggal->translatedFormat('d M') : '-' }} · {{ str_replace(':', '.', substr($p->jam_mulai, 0, 5)) }}
                            </td>
                            <td data-label="Total" class="kanan harga">Rp{{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td data-label="Status">
                                @if($p->status === 'menunggu')
                                    <span class="status s-tunggu"><span class="m" aria-hidden="true"><i class="on"></i><i></i><i></i><i></i></span>Menunggu</span>
                                @elseif($p->status === 'diterima')
                                    <span class="status s-jalan"><span class="m" aria-hidden="true"><i class="on"></i><i class="on"></i><i></i><i></i></span>Diterima</span>
                                @elseif($p->status === 'dikerjakan')
                                    <span class="status s-jalan"><span class="m" aria-hidden="true"><i class="on"></i><i class="on"></i><i class="on"></i><i></i></span>Dikerjakan</span>
                                @elseif($p->status === 'selesai')
                                    <span class="status s-beres"><span class="m" aria-hidden="true"><i class="on"></i><i class="on"></i><i class="on"></i><i class="on"></i></span>Selesai</span>
                                @elseif($p->status === 'dibatalkan')
                                    <span class="status s-batal">Dibatalkan</span>
                                @else
                                    <span class="status s-netral">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="ringkas">
        <span><b>{{ $totalPesanan }}</b> pesanan</span>
        <span><b>{{ $totalPaket }}</b> paket</span>
        <span><b>{{ $totalPetugasAktif }}</b> petugas aktif</span>
        <span><b>{{ $totalPelanggan }}</b> pelanggan</span>
    </div>
@endsection
