@extends('layouts.admin')

@section('title', 'Verifikasi Petugas')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
        <h1 class="page-title" style="margin-bottom:0;">Verifikasi Petugas</h1>
    </div>

    <div class="card">
        <div class="table-wrap">
            @if($petugas->isEmpty())
                <div class="empty-state">
                    <p>Belum ada petugas terdaftar.</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($petugas as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->no_hp }}</td>
                                <td>
                                    <span class="badge badge-{{ $item->status_verifikasi }}">
                                        {{ str_replace('_', ' ', $item->status_verifikasi) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.petugas.verifikasi', $item->id) }}" method="POST"
                                          style="display:flex; gap:.5rem; align-items:center;">
                                        @csrf
                                        @method('PUT')
                                        <select name="status_verifikasi" class="form-control" style="width:auto; min-width:140px;">
                                            <option value="menunggu" {{ $item->status_verifikasi === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="terverifikasi" {{ $item->status_verifikasi === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                                            <option value="ditolak" {{ $item->status_verifikasi === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
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
    </div>
@endsection