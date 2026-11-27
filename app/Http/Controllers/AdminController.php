<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\KelolaProfil;
use App\Models\PaketLayanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    use KelolaProfil;

    // Beranda Admin
    public function beranda()
    {
        $pembayaranMenungguCount = Pembayaran::where('status', 'menunggu')->count();
        $pembayaranMenungguTerbaru = Pembayaran::with('pesanan.pelanggan')
            ->where('status', 'menunggu')
            ->latest()
            ->first();

        $petugasMenungguCount = User::where('role', 'petugas')
            ->where('status_verifikasi', 'menunggu')
            ->count();
        $petugasMenungguTerbaru = User::where('role', 'petugas')
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->first();

        $pesananTerbaru = Pesanan::with(['pelanggan', 'petugas', 'paketLayanan'])
            ->latest()
            ->take(5)
            ->get();

        $totalPesanan = Pesanan::count();
        $totalPaket = PaketLayanan::count();
        $totalPetugasAktif = User::where('role', 'petugas')
            ->where('status_verifikasi', 'terverifikasi')
            ->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();

        return view('admin.beranda', compact(
            'pembayaranMenungguCount',
            'pembayaranMenungguTerbaru',
            'petugasMenungguCount',
            'petugasMenungguTerbaru',
            'pesananTerbaru',
            'totalPesanan',
            'totalPaket',
            'totalPetugasAktif',
            'totalPelanggan'
        ));
    }

    // Profil Admin
    public function profil()
    {
        $user = Auth::user();

        return view('admin.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        return $this->simpanProfil($request, 'admin.profil');
    }

    public function updatePassword(Request $request)
    {
        return $this->simpanSandi($request, 'admin.profil');
    }

    // Verifikasi petugas
    public function indexPetugas()
    {
        $petugas = User::where('role', 'petugas')
            ->orderByRaw("CASE WHEN status_verifikasi = 'menunggu' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        $petugasMenungguCount = $petugas->where('status_verifikasi', 'menunggu')->count();

        return view('admin.petugas.index', compact('petugas', 'petugasMenungguCount'));
    }

    public function updateVerifikasiPetugas(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak',
        ]);

        $petugas = User::where('role', 'petugas')->findOrFail($id);

        $petugas->update([
            'status_verifikasi' => $request->status_verifikasi,
        ]);

        $pesan = $request->status_verifikasi === 'terverifikasi'
            ? "{$petugas->nama} sudah diverifikasi."
            : 'Status verifikasi petugas berhasil diperbarui.';

        return redirect()->back()->with('success', $pesan);
    }

    // Kelola harga dan deskripsi paket
    public function indexPaket()
    {
        $paketLayanan = PaketLayanan::all();

        return view('admin.paket_layanan', compact('paketLayanan'));
    }

    public function updatePaket(Request $request, PaketLayanan $paketLayanan)
    {
        // Batas mengikuti tipe kolom database
        $data = $request->validate([
            'nama_paket' => ['required', 'string', 'max:255', Rule::unique('paket_layanan')->ignore($paketLayanan->id)],
            'deskripsi' => ['required', 'string'],
            'harga' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'estimasi_durasi_jam' => ['required', 'integer', 'min:1', 'max:24'],
        ]);

        $paketLayanan->update($data);

        return redirect()->route('admin.paket.index')->with('success', 'Paket disimpan.');
    }

    // Daftar seluruh pesanan
    public function indexPesanan()
    {
        $pesanan = Pesanan::with(['pelanggan', 'petugas', 'paketLayanan', 'pembayaran', 'ulasan'])
            ->latest()
            ->get();

        $countStatus = [
            'semua' => $pesanan->count(),
            'menunggu' => $pesanan->where('status', 'menunggu')->count(),
            'diterima' => $pesanan->where('status', 'diterima')->count(),
            'dikerjakan' => $pesanan->where('status', 'dikerjakan')->count(),
            'selesai' => $pesanan->where('status', 'selesai')->count(),
            'dibatalkan' => $pesanan->where('status', 'dibatalkan')->count(),
        ];

        return view('admin.pesanan', compact('pesanan', 'countStatus'));
    }

    // Konfirmasi pembayaran
    public function indexPembayaran()
    {
        $pembayaran = Pembayaran::with(['pesanan.pelanggan', 'pesanan.paketLayanan'])
            ->orderByRaw("CASE WHEN status = 'menunggu' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        $pembayaranMenungguCount = $pembayaran->where('status', 'menunggu')->count();

        return view('admin.pembayaran', compact('pembayaran', 'pembayaranMenungguCount'));
    }

    public function updateStatusPembayaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,lunas,ditolak',
        ]);

        $pembayaran = Pembayaran::with('pesanan')->findOrFail($id);

        if ($pembayaran->pesanan->status === 'dibatalkan') {
            return redirect()->back()->with('error', 'Pesanan ini sudah dibatalkan.');
        }

        $pembayaran->update([
            'status' => $request->status,
        ]);

        $pesan = $request->status === 'lunas'
            ? "Pembayaran pesanan #{$pembayaran->pesanan_id} ditandai lunas."
            : 'Status pembayaran berhasil diperbarui.';

        return redirect()->back()->with('success', $pesan);
    }
}
