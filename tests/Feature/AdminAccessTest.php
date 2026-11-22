<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureUserHasRole;
use App\Models\PaketLayanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    // UserFactory bawaan tidak cocok dengan skema (kolom `nama`, bukan `name`),
    // jadi user dibuat langsung di sini.
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

    private function buatPembayaran(User $pelanggan): Pembayaran
    {
        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'paket_layanan_id' => $this->buatPaket()->id,
            'alamat' => 'Jl. Mawar No. 1',
            'tanggal' => '2026-10-20',
            'jam_mulai' => '09:00',
            'total_harga' => 100000,
            'status' => 'menunggu',
        ]);

        return Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode' => 'transfer',
            'status' => 'menunggu',
        ]);
    }

    public function test_pelanggan_dilarang_mengakses_halaman_paket(): void
    {
        $pelanggan = $this->buatUser('pelanggan', 'pelanggan@example.com');

        $response = $this->actingAs($pelanggan)->get('/admin/paket');

        $response->assertRedirect(route('pelanggan.pesanan.index'));
        $response->assertSessionHas('error');
    }

    public function test_petugas_dilarang_mengakses_halaman_pembayaran(): void
    {
        $petugas = $this->buatUser('petugas', 'petugas@example.com');

        $response = $this->actingAs($petugas)->get('/admin/pembayaran');

        $response->assertRedirect(route('petugas.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_petugas_tidak_bisa_mengubah_harga_paket(): void
    {
        $petugas = $this->buatUser('petugas', 'petugas@example.com');
        $paket = $this->buatPaket();

        $response = $this->actingAs($petugas)->put("/admin/paket/{$paket->id}", [
            'nama_paket' => 'Paket Regular',
            'deskripsi' => 'Deskripsi diubah',
            'harga' => 1,
            'estimasi_durasi_jam' => 2,
        ]);

        $response->assertRedirect(route('petugas.dashboard'));
        $this->assertEquals('100000.00', $paket->fresh()->harga);
    }

    public function test_pelanggan_tidak_bisa_mengubah_harga_paket(): void
    {
        $pelanggan = $this->buatUser('pelanggan', 'pelanggan@example.com');
        $paket = $this->buatPaket();

        $response = $this->actingAs($pelanggan)->put("/admin/paket/{$paket->id}", [
            'nama_paket' => 'Paket Regular',
            'deskripsi' => 'Deskripsi diubah',
            'harga' => 1,
            'estimasi_durasi_jam' => 2,
        ]);

        $response->assertRedirect(route('pelanggan.pesanan.index'));
        $this->assertEquals('100000.00', $paket->fresh()->harga);
    }

    public function test_pelanggan_tidak_bisa_mengkonfirmasi_pembayaran(): void
    {
        $pelanggan = $this->buatUser('pelanggan', 'pelanggan@example.com');
        $pembayaran = $this->buatPembayaran($pelanggan);

        $response = $this->actingAs($pelanggan)
            ->put("/admin/pembayaran/{$pembayaran->id}/status", ['status' => 'lunas']);

        $response->assertRedirect(route('pelanggan.pesanan.index'));
        $this->assertEquals('menunggu', $pembayaran->fresh()->status);
    }

    public function test_admin_bisa_mengubah_harga_paket(): void
    {
        $admin = $this->buatUser('admin', 'admin@example.com');
        $paket = $this->buatPaket();

        $response = $this->actingAs($admin)->put("/admin/paket/{$paket->id}", [
            'nama_paket' => 'Paket Premium',
            'deskripsi' => 'Deskripsi diperbarui',
            'harga' => 250000,
            'estimasi_durasi_jam' => 3,
        ]);

        $response->assertRedirect(route('admin.paket.index'));
        $this->assertEquals('250000.00', $paket->fresh()->harga);
    }

    public function test_admin_bisa_mengkonfirmasi_pembayaran(): void
    {
        $pelanggan = $this->buatUser('pelanggan', 'pelanggan@example.com');
        $admin = $this->buatUser('admin', 'admin@example.com');
        $pembayaran = $this->buatPembayaran($pelanggan);

        $response = $this->actingAs($admin)
            ->put("/admin/pembayaran/{$pembayaran->id}/status", ['status' => 'lunas']);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertEquals('lunas', $pembayaran->fresh()->status);
    }

    // Defense in depth: guard di controller tetap menutup akses
    // bila middleware role dilewatkan (mis. lupa dipasang di route baru).
    public function test_guard_controller_memblokir_non_admin_walaupun_middleware_dilewatkan(): void
    {
        $pelanggan = $this->buatUser('pelanggan', 'pelanggan@example.com');
        $paket = $this->buatPaket();

        $response = $this->actingAs($pelanggan)
            ->withoutMiddleware(EnsureUserHasRole::class)
            ->put("/admin/paket/{$paket->id}", [
                'nama_paket' => 'Paket Regular',
                'deskripsi' => 'Deskripsi diubah',
                'harga' => 1,
                'estimasi_durasi_jam' => 2,
            ]);

        $response->assertRedirect(route('pelanggan.pesanan.index'));
        $this->assertEquals('100000.00', $paket->fresh()->harga);
    }
}
