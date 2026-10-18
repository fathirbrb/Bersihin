<?php

namespace App\Http\Controllers;

use App\Models\PaketLayanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function cekAdmin(): ?RedirectResponse
    {
        $user = Auth::user();

        if (! $user || ! $user->isRole('admin')) {
            $dashboardRoute = $user?->dashboardRouteName();

            if ($dashboardRoute) {
                return redirect()
                    ->route($dashboardRoute)
                    ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
            }

            return abort(403, 'Akses ditolak.');
        }

        return null;
    }

    //verif petugas F-02
    public function indexPetugas()
    {
        $petugas = User::where('role', 'petugas')->get();
        return view('admin.petugas.index', compact('petugas'));
    }
    
    public function updateVerifikasiPetugas(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:terverifikasi,ditolak,pending',
        ]);
    
        $petugas = User::where('role', 'petugas')->findOrFail($id);
        $petugas->update([
            'status_verifikasi' => $request->status_verifikasi
        ]);
    
        return redirect()->back()->with('success', 'Status verifikasi petugas berhasil diperbarui.');
    }
    
    //kelola harga dan deskripsi F-03
    public function indexPaket()
    {
        $redirect = $this->cekAdmin();

        if ($redirect) {
            return $redirect;
        }

        $paketLayanan = PaketLayanan::all();

        return view('admin.paket_layanan', compact('paketLayanan'));
    }

    public function updatePaket(Request $request, PaketLayanan $paketLayanan)
    {
        $redirect = $this->cekAdmin();

        if ($redirect) {
            return $redirect;
        }

        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'estimasi_durasi_jam' => 'required|integer|min:1',
        ]);

        $paketLayanan->update($request->all());

        return redirect()->route('admin.paket.index')->with('success', 'Paket layanan berhasil diperbarui.');
    }

    //lihat daftar seluruh pesanan F-09
    public function indexPesanan()
    {
        $redirect = $this->cekAdmin();

        if ($redirect) {
            return $redirect;
        }

        $pesanan = Pesanan::with(['pelanggan', 'petugas', 'paketLayanan', 'pembayaran', 'ulasan'])
            ->latest()
            ->get();

        return view('admin.pesanan', compact('pesanan'));
    }

    //konfirmasi pembyaaran F-07
    public function indexPembayaran()
    {
        $redirect = $this->cekAdmin();

        if ($redirect) {
            return $redirect;
        }

        $pembayaran = Pembayaran::with('pesanan.pelanggan')->latest()->get();

        return view('admin.pembayaran', compact('pembayaran'));
    }

    public function updateStatusPembayaran(Request $request, $id)
    {
        $redirect = $this->cekAdmin();

        if ($redirect) {
            return $redirect;
        }

        $request->validate([
            'status' => 'required|in:menunggu,lunas,ditolak',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil dikonfirmasi.');
    }
}
