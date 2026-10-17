@extends('layouts.pelanggan')

@section('title', 'Ulasan Saya')

@section('content')
    <h1 class="page-title">Ulasan Saya</h1>

    @if($ulasan->isEmpty())
        <div class="card empty-state">
            <p>Belum ada ulasan yang ditulis. 📝</p>
        </div>
    @else
        @foreach($ulasan as $item)
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <p style="font-weight:700;">{{ $item->pesanan->paketLayanan->nama_paket ?? '-' }}</p>
                        <p style="font-size:.85rem; color:#6b7280;">
                            Petugas: {{ $item->pesanan->petugas->nama ?? '-' }}
                            &middot; {{ $item->pesanan->tanggal->format('d M Y') }}
                        </p>
                        <p class="star" style="margin-top:.3rem;">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $item->rating ? '★' : '☆' }}
                            @endfor
                        </p>
                        <p style="margin-top:.4rem;">{{ $item->komentar ?: '—' }}</p>
                    </div>
                    <form action="{{ route('pelanggan.ulasan.destroy', $item->id) }}" method="POST"
                          onsubmit="return confirm('Hapus ulasan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:.3rem .7rem; font-size:.75rem;">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
@endsection
