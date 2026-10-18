@extends('layouts.admin')

@section('title', 'Semua Pesanan')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
        <h1 class="page-title" style="margin-bottom:0;">Semua Pesanan</h1>
    </div>

    <div class="card table-wrap">
        @if($pesanan->isEmpty())
            <div class="empty-state">
                <p>Belum ada pesanan masuk.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Petugas</th>
                        <th>Paket</th>
                        <th>Tanggal & Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan as $i => $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $item->petugas->nama ?? '—' }}</td>
                            <td>{{ $item->paketLayanan->nama_paket ?? '-' }}</td>
                            <td>
                                {{ $item->tanggal->format('d M Y') }}<br>
                                <span class="muted">{{ $item->jam_mulai }}</span>
                            </td>
                            <td>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $item->status }}">{{ $item->status }}</span>
                            </td>
                            <td>
                                @if($item->pembayaran)
                                    <span class="badge badge-{{ $item->pembayaran->status === 'lunas' ? 'lunas' : ($item->pembayaran->status === 'ditolak' ? 'ditolak' : 'menunggu') }}">
                                        {{ $item->pembayaran->status }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection