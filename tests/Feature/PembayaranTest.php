<?php

namespace Tests\Feature;

use App\Models\PaketLayanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    private User $pelanggan;
    private PaketLayanan $paket;
    private Pesanan $pesanan;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->pelanggan = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $this->paket = PaketLayanan::create([
            'nama_paket' => 'Paket Rumah Bersih',
            'deskripsi' => 'Membersihkan seluruh rumah',
            'harga' => 150000,
            'estimasi_durasi_jam' => 3,
        ]);

        $this->pesanan = Pesanan::create([
            'pelanggan_id' => $this->pelanggan->id,
            'paket_layanan_id' => $this->paket->id,
            'alamat' => 'Jl. Merdeka No. 45',
            'tanggal' => now()->addDay()->format('Y-m-d'),
            'jam_mulai' => '09:00',
            'catatan' => 'Harap tepat waktu',
            'total_harga' => 150000,
            'status' => 'menunggu',
        ]);
    }

    public function test_halaman_detail_pesanan_menampilkan_form_pembayaran(): void
    {
        $response = $this->actingAs($this->pelanggan)->get(route('pelanggan.pesanan.show', $this->pesanan->id));

        $response->assertStatus(200);
        $response->assertSee('💳 Pembayaran');
        $response->assertSee('Total yang harus dibayar');
        $response->assertSee('Rp150.000');
        $response->assertSee('Transfer');
        $response->assertSee('Tunai');
        $response->assertSee('Upload Bukti Pembayaran');
    }

    public function test_pembayaran_metode_transfer_dengan_bukti_berhasil(): void
    {
        $file = UploadedFile::fake()->image('bukti_transfer.jpg', 600, 600)->size(500);

        $response = $this->actingAs($this->pelanggan)->post(
            route('pelanggan.pembayaran.store', $this->pesanan->id),
            [
                'metode' => 'transfer',
                'bukti_bayar' => $file,
            ]
        );

        $response->assertRedirect(route('pelanggan.pesanan.show', $this->pesanan->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pembayaran', [
            'pesanan_id' => $this->pesanan->id,
            'metode' => 'transfer',
            'status' => 'menunggu',
        ]);

        $pembayaran = Pembayaran::where('pesanan_id', $this->pesanan->id)->first();
        $this->assertNotNull($pembayaran->bukti_bayar);
        Storage::disk('public')->assertExists($pembayaran->bukti_bayar);
    }

    public function test_pembayaran_metode_tunai_tanpa_bukti_berhasil(): void
    {
        $response = $this->actingAs($this->pelanggan)->post(
            route('pelanggan.pembayaran.store', $this->pesanan->id),
            [
                'metode' => 'tunai',
            ]
        );

        $response->assertRedirect(route('pelanggan.pesanan.show', $this->pesanan->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pembayaran', [
            'pesanan_id' => $this->pesanan->id,
            'metode' => 'tunai',
            'bukti_bayar' => null,
            'status' => 'menunggu',
        ]);
    }

    public function test_validasi_transfer_wajib_unggah_bukti_bayar(): void
    {
        $response = $this->actingAs($this->pelanggan)->post(
            route('pelanggan.pembayaran.store', $this->pesanan->id),
            [
                'metode' => 'transfer',
            ]
        );

        $response->assertSessionHasErrors(['bukti_bayar']);
        $this->assertDatabaseMissing('pembayaran', [
            'pesanan_id' => $this->pesanan->id,
        ]);
    }

    public function test_validasi_file_bukti_bayar_harus_gambar(): void
    {
        $file = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->pelanggan)->post(
            route('pelanggan.pembayaran.store', $this->pesanan->id),
            [
                'metode' => 'transfer',
                'bukti_bayar' => $file,
            ]
        );

        $response->assertSessionHasErrors(['bukti_bayar']);
    }

    public function test_validasi_file_bukti_bayar_tidak_boleh_lebih_dari_2mb(): void
    {
        $file = UploadedFile::fake()->image('bukti_besar.png')->size(2500); // 2.5 MB

        $response = $this->actingAs($this->pelanggan)->post(
            route('pelanggan.pembayaran.store', $this->pesanan->id),
            [
                'metode' => 'transfer',
                'bukti_bayar' => $file,
            ]
        );

        $response->assertSessionHasErrors(['bukti_bayar']);
    }

    public function test_validasi_metode_pembayaran_hanya_transfer_atau_tunai(): void
    {
        $response = $this->actingAs($this->pelanggan)->post(
            route('pelanggan.pembayaran.store', $this->pesanan->id),
            [
                'metode' => 'qris_crypto',
            ]
        );

        $response->assertSessionHasErrors(['metode']);
    }

    public function test_pelanggan_lain_tidak_bisa_membayar_pesanan_orang_lain(): void
    {
        $pelangganLain = User::factory()->create(['role' => 'pelanggan']);

        $response = $this->actingAs($pelangganLain)->post(
            route('pelanggan.pembayaran.store', $this->pesanan->id),
            [
                'metode' => 'tunai',
            ]
        );

        $response->assertStatus(404);
        $this->assertDatabaseMissing('pembayaran', [
            'pesanan_id' => $this->pesanan->id,
        ]);
    }

    public function test_tidak_bisa_membayar_dua_kali_pada_pesanan_yang_sama(): void
    {
        Pembayaran::create([
            'pesanan_id' => $this->pesanan->id,
            'metode' => 'tunai',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->pelanggan)->post(
            route('pelanggan.pembayaran.store', $this->pesanan->id),
            [
                'metode' => 'tunai',
            ]
        );

        $response->assertSessionHas('error', 'Pesanan ini sudah memiliki data pembayaran.');
        $this->assertEquals(1, Pembayaran::where('pesanan_id', $this->pesanan->id)->count());
    }

    public function test_tidak_bisa_membayar_pesanan_yang_dibatalkan(): void
    {
        $this->pesanan->update(['status' => 'dibatalkan']);

        $response = $this->actingAs($this->pelanggan)->post(
            route('pelanggan.pembayaran.store', $this->pesanan->id),
            [
                'metode' => 'tunai',
            ]
        );

        $response->assertSessionHas('error', 'Tidak dapat melakukan pembayaran untuk pesanan yang dibatalkan.');
        $this->assertDatabaseMissing('pembayaran', [
            'pesanan_id' => $this->pesanan->id,
        ]);
    }

    public function test_setelah_bayar_detail_menampilkan_info_dan_bukti_bayar(): void
    {
        Pembayaran::create([
            'pesanan_id' => $this->pesanan->id,
            'metode' => 'transfer',
            'bukti_bayar' => 'bukti_pembayaran/dummy.jpg',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->pelanggan)->get(route('pelanggan.pesanan.show', $this->pesanan->id));

        $response->assertStatus(200);
        $response->assertSee('transfer');
        $response->assertSee('menunggu');
        $response->assertSee('Bukti Pembayaran');
        $response->assertDontSee('Kirim Pembayaran');
    }
}
