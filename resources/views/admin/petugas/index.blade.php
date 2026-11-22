@extends('layouts.admin')

@section('title', 'Petugas · Admin Bersihin')

@section('content')
    <div class="kepala">
        <h1>Petugas<span class="sr"> - Verifikasi Petugas</span></h1>
        <p>{{ $petugas->count() }} terdaftar, {{ $petugasMenungguCount }} nunggu dicek</p>
    </div>

    @if($petugas->isEmpty())
        <div class="kosong">
            <b>Belum ada petugas</b>
            <span>Petugas yang daftar muncul di sini.</span>
        </div>
    @else
        <div class="tabel-wadah">
            <table class="tabel">
                <thead>
                    <tr><th>Nama</th><th>Email</th><th>No. HP</th><th>Daftar</th><th>Status</th><th class="kanan">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($petugas as $item)
                        <tr @if($item->status_verifikasi === 'menunggu') class="sorot" @endif>
                            <td data-label="Nama"><b style="font-weight:500;">{{ $item->nama }}</b></td>
                            <td data-label="Email">{{ $item->email }}</td>
                            <td data-label="No. HP" class="angka">{{ $item->no_hp }}</td>
                            <td data-label="Daftar" class="angka">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                            <td data-label="Status">
                                @if($item->status_verifikasi === 'menunggu')
                                    <span class="status s-tunggu">Menunggu</span>
                                @elseif($item->status_verifikasi === 'terverifikasi')
                                    <span class="status s-beres">Terverifikasi</span>
                                @elseif($item->status_verifikasi === 'ditolak')
                                    <span class="status s-batal">Ditolak</span>
                                @else
                                    <span class="status s-netral">{{ ucfirst($item->status_verifikasi) }}</span>
                                @endif
                            </td>
                            <td data-label="">
                                <form action="{{ route('admin.petugas.verifikasi', $item->id) }}" method="POST" class="aksi">
                                    @csrf
                                    @method('PUT')
                                    @if($item->status_verifikasi === 'menunggu')
                                        <button type="submit" name="status_verifikasi" value="ditolak" class="btn btn-teks bahaya">Tolak</button>
                                        <button type="submit" name="status_verifikasi" value="terverifikasi" class="btn btn-utama btn-kecil">Verifikasi</button>
                                    @elseif($item->status_verifikasi === 'terverifikasi')
                                        <button type="submit" name="status_verifikasi" value="ditolak" class="btn btn-teks bahaya">Cabut verifikasi</button>
                                    @elseif($item->status_verifikasi === 'ditolak')
                                        <button type="submit" name="status_verifikasi" value="terverifikasi" class="btn btn-utama btn-kecil">Verifikasi</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
