<?php

namespace Tests\Feature;

use App\Models\PaketLayanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminViewTest extends TestCase
{
    use RefreshDatabase;

    // @vite butuh manifest build; tidak relevan untuk verifikasi render view.
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function buatUser(string $role, string $email): User
    {
        return User::create([
            'nama' => 'User '.ucfirst($role),
            'email' => $email,
            'password' => 'password',
            'no_hp' => '081234567890',
            'role' => $role,
        ]);
    }

    private function buatPaket(): PaketLayanan
    {
        return PaketLayanan::create([
            'nama_paket' => 'Paket Regular',
            'deskripsi' => 'Deskripsi paket',
            'harga' => 100000,
            'estimasi_durasi_jam' => 2,
        ]);
    }

    public function test_admin_bisa_membuka_halaman_beranda(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');

        $response = $this->actingAs($admin)->get('/admin/beranda');

        $response->assertStatus(200);
        $response->assertSee('Halo, User Admin');
        $response->assertSee('Perlu dicek');
        $response->assertSee('Pesanan terbaru');
    }

    public function test_admin_bisa_membuka_halaman_profil(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');

        $response = $this->actingAs($admin)->get('/admin/profil');

        $response->assertStatus(200);
        $response->assertSee('Data diri');
        $response->assertSee('Kata sandi');
        $response->assertSee('admin@example.com');
    }

    public function test_admin_bisa_update_profil(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');

        $response = $this->actingAs($admin)->put('/admin/profil', [
            'nama' => 'Admin Super',
            'email' => 'adminsuper@example.com',
            'no_hp' => '089988776655',
            'hapus_foto' => '0',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Profil disimpan.');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'nama' => 'Admin Super',
            'email' => 'adminsuper@example.com',
            'no_hp' => '089988776655',
        ]);
    }

    public function test_admin_bisa_update_password(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');

        $response = $this->actingAs($admin)->put('/admin/profil/password', [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Kata sandi sudah diganti.');

        $this->assertTrue(Hash::check('newpassword123', $admin->fresh()->password));
    }

    public function test_admin_bisa_membuka_halaman_paket(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');
        $this->buatPaket();

        $response = $this->actingAs($admin)->get('/admin/paket');

        $response->assertStatus(200);
        $response->assertSee('Kelola Paket Layanan');
        $response->assertSee('Paket Regular');
    }

    public function test_admin_bisa_membuka_halaman_petugas(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');
        $this->buatUser('petugas', 'petugas@example.com');

        $response = $this->actingAs($admin)->get('/admin/petugas');

        $response->assertStatus(200);
        $response->assertSee('Verifikasi Petugas');
        $response->assertSee('petugas@example.com');
    }

    public function test_admin_bisa_membuka_halaman_pesanan(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');
        $pelanggan = $this->buatUser('pelanggan', 'pelanggan@example.com');

        Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'paket_layanan_id' => $this->buatPaket()->id,
            'alamat' => 'Jl. Mawar No. 1',
            'tanggal' => '2026-10-20',
            'jam_mulai' => '09:00',
            'total_harga' => 100000,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($admin)->get('/admin/pesanan');

        $response->assertStatus(200);
        $response->assertSee('Semua Pesanan');
        $response->assertSee('User Pelanggan');
    }

    public function test_admin_bisa_membuka_halaman_pembayaran(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');
        $pelanggan = $this->buatUser('pelanggan', 'pelanggan@example.com');

        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'paket_layanan_id' => $this->buatPaket()->id,
            'alamat' => 'Jl. Mawar No. 1',
            'tanggal' => '2026-10-20',
            'jam_mulai' => '09:00',
            'total_harga' => 100000,
            'status' => 'menunggu',
        ]);

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode' => 'transfer',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($admin)->get('/admin/pembayaran');

        $response->assertStatus(200);
        $response->assertSee('Konfirmasi Pembayaran');
        $response->assertSee('User Pelanggan');
    }

    public function test_admin_bisa_mengubah_status_verifikasi_petugas(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');
        $petugas = $this->buatUser('petugas', 'petugas@example.com');

        $response = $this->actingAs($admin)
            ->put("/admin/petugas/{$petugas->id}/verifikasi", ['status_verifikasi' => 'terverifikasi']);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertEquals('terverifikasi', $petugas->fresh()->status_verifikasi);
    }

    public function test_validasi_verifikasi_hanya_terima_nilai_enum(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');
        $petugas = $this->buatUser('petugas', 'petugas@example.com');

        $response = $this->actingAs($admin)
            ->put("/admin/petugas/{$petugas->id}/verifikasi", ['status_verifikasi' => 'pending']);

        $response->assertSessionHasErrors('status_verifikasi');
        $this->assertEquals('menunggu', $petugas->fresh()->status_verifikasi);
    }
}
