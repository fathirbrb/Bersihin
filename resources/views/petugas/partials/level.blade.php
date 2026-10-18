{{-- Progres pesanan seperti air di ember --}}
@php
    $daftar = [
        'menunggu' => [1, 'Menunggu petugas'],
        'diterima' => [2, 'Diterima, belum mulai'],
        'dikerjakan' => [3, 'Sedang dikerjakan'],
        'selesai' => [4, 'Beres'],
    ];
    [$level, $label] = $daftar[$status] ?? [0, 'Dibatalkan'];
@endphp
<div class="level">
    <div class="tabung" role="progressbar" aria-valuemin="0" aria-valuemax="4" aria-valuenow="{{ $level }}" aria-label="Progres pesanan"><span style="width: {{ $level * 25 }}%"></span></div>
    <div class="label"><b>{{ $label }}</b><span>{{ $level > 0 ? 'Langkah ' . $level . ' dari 4' : '' }}</span></div>
</div>
