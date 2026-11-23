<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    private function buatUser(string $role, string $email, string $password = 'secret123'): User
    {
        return User::create([
            'nama'              => 'Pengguna '.ucfirst($role),
            'email'             => $email,
            'password'          => Hash::make($password),
            'no_hp'             => '081234567890',
            'role'              => $role,
            'status_verifikasi' => 'terverifikasi',
        ]);
    }

    public function test_login_admin_diarahkan_ke_beranda_admin(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');

        $response = $this->post('/login', [
            'email'    => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.beranda'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_pelanggan_diarahkan_ke_beranda_pelanggan(): void
    {
        $pelanggan = $this->buatUser('pelanggan', 'pelanggan@example.com');

        $response = $this->post('/login', [
            'email'    => 'pelanggan@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('pelanggan.beranda'));
        $this->assertAuthenticatedAs($pelanggan);
    }

    public function test_login_petugas_diarahkan_ke_dashboard_petugas(): void
    {
        $petugas = $this->buatUser('petugas', 'petugas@example.com');

        $response = $this->post('/login', [
            'email'    => 'petugas@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('petugas.dashboard'));
        $this->assertAuthenticatedAs($petugas);
    }

    public function test_route_beranda_admin_dan_alias_beranda_bisa_diakses(): void
    {
        $this->withoutVite();
        $admin = $this->buatUser('admin', 'admin@example.com');

        $responseUtama = $this->actingAs($admin)->get('/admin');
        $responseUtama->assertStatus(200);

        $responseAlias = $this->actingAs($admin)->get('/admin/beranda');
        $responseAlias->assertStatus(200);
    }

    public function test_route_beranda_pelanggan_dan_alias_beranda_bisa_diakses(): void
    {
        $this->withoutVite();
        $pelanggan = $this->buatUser('pelanggan', 'pelanggan@example.com');

        $responseUtama = $this->actingAs($pelanggan)->get('/pelanggan');
        $responseUtama->assertStatus(200);

        $responseAlias = $this->actingAs($pelanggan)->get('/pelanggan/beranda');
        $responseAlias->assertStatus(200);
    }
}
