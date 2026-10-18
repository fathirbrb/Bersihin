@extends('layouts.admin')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
        <h1 class="page-title" style="margin-bottom:0;">Konfirmasi Pembayaran</h1>
    </div>

    <div class="card table-wrap">
        @if($pembayaran->isEmpty())
            <div class="empty-state">
                <p>Belum ada pembayaran masuk.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayaran as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                #{{ $item->pesanan->id }}<br>
                                <span class="muted">{{ $item->pesanan->paketLayanan->nama_paket ?? '-' }}</span>
                            </td>
                            <td>{{ $item->pesanan->pelanggan->nama ?? '-' }}</td>
                            <td>{{ ucfirst($item->metode) }}</td>
                            <td>
                                @if($item->bukti_bayar)
                                    <a href="{{ $item->bukti_bayar }}" target="_blank">Lihat</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $item->status === 'lunas' ? 'lunas' : ($item->status === 'ditolak' ? 'ditolak' : 'menunggu') }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.pembayaran.status', $item->id) }}" method="POST"
                                      style="display:flex; gap:.5rem; align-items:center;">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control" style="width:auto; min-width:130px;">
                                        <option value="menunggu" {{ $item->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="lunas" {{ $item->status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="ditolak" {{ $item->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="padding:.4rem .9rem; font-size:.8rem;">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection