@extends('layouts.pelanggan')

@section('title', 'Pesanan Saya')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
        <h1 class="page-title" style="margin-bottom:0;">Pesanan Saya</h1>
        <a href="{{ route('pelanggan.pesanan.create') }}" class="btn btn-primary">+ Buat Pesanan</a>
    </div>

    @if($pesanan->isEmpty())
        <div class="card empty-state">
            <p>Belum ada pesanan. Yuk buat pesanan pertamamu! 🧹</p>
        </div>
    @else
        <div class="card table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Paket</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->paketLayanan->nama_paket ?? '-' }}</td>
                            <td>{{ $item->tanggal->format('d M Y') }}</td>
                            <td>{{ $item->jam_mulai }}</td>
                            <td>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td><span class="badge badge-{{ $item->status }}">{{ $item->status }}</span></td>
                            <td>
                                <a href="{{ route('pelanggan.pesanan.show', $item->id) }}" class="btn btn-secondary" style="padding:.35rem .8rem; font-size:.8rem;">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
