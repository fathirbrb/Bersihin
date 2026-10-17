<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'pelanggan_id',
        'petugas_id',
        'paket_layanan_id',
        'alamat',
        'tanggal',
        'jam_mulai',
        'catatan',
        'total_harga',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'     => 'date',
            'total_harga' => 'decimal:2',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelanggan_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function paketLayanan(): BelongsTo
    {
        return $this->belongsTo(PaketLayanan::class, 'paket_layanan_id');
    }

    public function ulasan(): HasOne
    {
        return $this->hasOne(Ulasan::class, 'pesanan_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id');
    }
}
