<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaketLayanan extends Model
{
    use HasFactory;

    protected $table = 'paket_layanan';

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga',
        'estimasi_durasi_jam',
    ];

    protected function casts(): array
    {
        return [
            'harga'              => 'decimal:2',
            'estimasi_durasi_jam' => 'integer',
        ];
    }

    // ──────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'paket_layanan_id');
    }
}
