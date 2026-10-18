<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nama'               => fake()->name(),
            'email'              => fake()->unique()->safeEmail(),
            'password'           => static::$password ??= Hash::make('password'),
            'no_hp'              => fake()->numerify('08##########'),
            'role'               => 'pelanggan',
            'status_verifikasi'  => 'terverifikasi',
            'remember_token'     => Str::random(10),
        ];
    }

    /** State: role admin */
    public function admin(): static
    {
        return $this->state(fn () => [
            'role'             => 'admin',
            'status_verifikasi'=> 'terverifikasi',
        ]);
    }

    /** State: role petugas terverifikasi */
    public function petugas(): static
    {
        return $this->state(fn () => [
            'role'             => 'petugas',
            'status_verifikasi'=> 'terverifikasi',
        ]);
    }

    /** State: petugas menunggu verifikasi */
    public function petugasMenunggu(): static
    {
        return $this->state(fn () => [
            'role'             => 'petugas',
            'status_verifikasi'=> 'menunggu',
        ]);
    }
}
