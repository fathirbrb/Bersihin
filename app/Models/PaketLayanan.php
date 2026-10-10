<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'paket_layanan_id');
    }
}
