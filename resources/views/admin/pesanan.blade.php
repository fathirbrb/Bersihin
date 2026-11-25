@extends('layouts.admin')

@section('title', 'Pesanan')

@section('content')
    <div class="kepala">
        <h1>Pesanan<span class="sr"> - Semua Pesanan</span></h1>
        <p>{{ $pesanan->count() }} pesanan</p>
    </div>

    @if($pesanan->isEmpty())
        <div class="kosong">
            <b>Belum ada pesanan</b>
            <span>Pesanan pelanggan muncul di sini.</span>
        </div>
    @else
        <div class="saring" role="group" aria-label="Saring status">
            <button type="button" data-status="semua" aria-pressed="true">Semua<b>{{ $countStatus['semua'] ?? $pesanan->count() }}</b></button>
            <button type="button" data-status="menunggu" aria-pressed="false">Menunggu<b>{{ $countStatus['menunggu'] ?? 0 }}</b></button>
            <button type="button" data-status="diterima" aria-pressed="false">Diterima<b>{{ $countStatus['diterima'] ?? 0 }}</b></button>
            <button type="button" data-status="dikerjakan" aria-pressed="false">Dikerjakan<b>{{ $countStatus['dikerjakan'] ?? 0 }}</b></button>
            <button type="button" data-status="selesai" aria-pressed="false">Selesai<b>{{ $countStatus['selesai'] ?? 0 }}</b></button>
            <button type="button" data-status="dibatalkan" aria-pressed="false">Dibatalkan<b>{{ $countStatus['dibatalkan'] ?? 0 }}</b></button>
        </div>

        <div class="tabel-wadah" id="tabel-isi">
            <table class="tabel">
                <thead>
                    <tr><th>#</th><th>Paket</th><th>Pelanggan</th><th>Petugas</th><th>Jadwal</th><th>Alamat</th><th class="kanan">Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($pesanan as $item)
                        <tr data-status="{{ $item->status }}">
                            <td data-label="#" class="angka">{{ $item->id }}</td>
                            <td data-label="Paket">{{ $item->paketLayanan->nama_paket ?? '-' }}</td>
                            <td data-label="Pelanggan">{{ $item->pelanggan->nama ?? '-' }}</td>
                            <td data-label="Petugas" @if(!$item->petugas) style="color:var(--tinta-2);" @endif>{{ $item->petugas->nama ?? '-' }}</td>
                            <td data-label="Jadwal" class="angka">
                                {{ $item->tanggal ? $item->tanggal->translatedFormat('d M') : '-' }} · {{ str_replace(':', '.', substr($item->jam_mulai, 0, 5)) }}
                            </td>
                            <td data-label="Alamat">{{ $item->alamat }}</td>
                            <td data-label="Total" class="kanan harga">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td data-label="Status">
                                @if($item->status === 'menunggu')
                                    <span class="status s-tunggu"><span class="m" aria-hidden="true"><i class="on"></i><i></i><i></i><i></i></span>Menunggu</span>
                                @elseif($item->status === 'diterima')
                                    <span class="status s-jalan"><span class="m" aria-hidden="true"><i class="on"></i><i class="on"></i><i></i><i></i></span>Diterima</span>
                                @elseif($item->status === 'dikerjakan')
                                    <span class="status s-jalan"><span class="m" aria-hidden="true"><i class="on"></i><i class="on"></i><i class="on"></i><i></i></span>Dikerjakan</span>
                                @elseif($item->status === 'selesai')
                                    <span class="status s-beres"><span class="m" aria-hidden="true"><i class="on"></i><i class="on"></i><i class="on"></i><i class="on"></i></span>Selesai</span>
                                @elseif($item->status === 'dibatalkan')
                                    <span class="status s-batal">Dibatalkan</span>
                                @else
                                    <span class="status s-netral">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="kosong" id="tabel-kosong" hidden style="padding:28px 20px;">
            <b>Nggak ada pesanan di status ini</b>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    const saring = document.querySelector('.saring');
    if (saring) {
        saring.addEventListener('click', (e) => {
            const btn = e.target.closest('button');
            if (!btn) return;
            saring.querySelectorAll('button').forEach((b) => b.setAttribute('aria-pressed', b === btn));
            let ada = 0;
            document.querySelectorAll('tr[data-status]').forEach((tr) => {
                tr.hidden = btn.dataset.status !== 'semua' && tr.dataset.status !== btn.dataset.status;
                if (!tr.hidden) ada++;
            });
            const tabelIsi = document.getElementById('tabel-isi');
            const tabelKosong = document.getElementById('tabel-kosong');
            if (tabelIsi) tabelIsi.hidden = ada === 0;
            if (tabelKosong) tabelKosong.hidden = ada > 0;
        });
    }
</script>
@endpush
