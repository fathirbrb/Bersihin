{{-- Kelas, jumlah batang, dan label status --}}
@php
    $daftar = [
        'menunggu' => ['s-tunggu', 1, 'Menunggu'],
        'diterima' => ['s-jalan', 2, 'Diterima'],
        'dikerjakan' => ['s-jalan', 3, 'Dikerjakan'],
        'selesai' => ['s-beres', 4, 'Selesai'],
        'dibatalkan' => ['s-batal', 0, 'Dibatalkan'],
    ];
    [$kelas, $level, $label] = $daftar[$status] ?? ['s-jalan', 0, ucfirst($status)];
@endphp
<span class="status {{ $kelas }}">
    @if($level > 0)
        <span class="m" aria-hidden="true">@for($i = 1; $i <= 4; $i++)<i class="{{ $i <= $level ? 'on' : '' }}"></i>@endfor</span>
    @endif
    {{ $label }}
</span>
