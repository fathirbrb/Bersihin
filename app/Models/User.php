<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'role',
        'status_verifikasi',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function pesananPelanggan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'pelanggan_id');
    }

    public function pesananPetugas(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'petugas_id');
    }


    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }
}
