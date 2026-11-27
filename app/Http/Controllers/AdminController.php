<?php

namespace App\Http\Controllers;

use App\Models\PaketLayanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
                    ->with('error', 'Kamu tidak memiliki akses ke halaman admin.');
            }

            return abort(403, 'Akses ditolak.');
        }

        return null;
    }

    // Beranda Admin
    public function beranda()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) {
            return $redirect;
        }

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
        $redirect = $this->cekAdmin();
        if ($redirect) {
            return $redirect;
        }

        $user = Auth::user();

        return view('admin.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) {
            return $redirect;
        }

        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'no_hp' => ['required', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'hapus_foto' => ['nullable', 'in:0,1'],
        ]);

        if ($request->input('hapus_foto') === '1') {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->foto = null;
        }

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->foto = $request->file('foto')->store('foto-profil', 'public');
        }

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->no_hp = $validated['no_hp'];
        $user->save();

        return redirect()->route('admin.profil')->with('success', 'Profil disimpan.');
    }

    public function updatePassword(Request $request)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) {
            return $redirect;
        }

        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profil')->with('success', 'Kata sandi sudah diganti.');
    }

    // Verifikasi petugas
    public function indexPetugas()
    {
        $redirect = $this->cekAdmin();

        if ($redirect) {
            return $redirect;
        }

        $petugas = User::where('role', 'petugas')
            ->orderByRaw("CASE WHEN status_verifikasi = 'menunggu' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        $petugasMenungguCount = $petugas->where('status_verifikasi', 'menunggu')->count();

        return view('admin.petugas.index', compact('petugas', 'petugasMenungguCount'));
    }

    public function updateVerifikasiPetugas(Request $request, $id)
    {
        $redirect = $this->cekAdmin();

        if ($redirect) {
            return $redirect;
        }

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

        return redirect()->route('admin.paket.index')->with('success', 'Paket disimpan.');
    }

    // Daftar seluruh pesanan
    public function indexPesanan()
    {
        $redirect = $this->cekAdmin();

        if ($redirect) {
            return $redirect;
        }

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
        $redirect = $this->cekAdmin();

        if ($redirect) {
            return $redirect;
        }

        $pembayaran = Pembayaran::with('pesanan.pelanggan')
            ->orderByRaw("CASE WHEN status = 'menunggu' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        $pembayaranMenungguCount = $pembayaran->where('status', 'menunggu')->count();

        return view('admin.pembayaran', compact('pembayaran', 'pembayaranMenungguCount'));
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

        $pesan = $request->status === 'lunas'
            ? "Pembayaran pesanan #{$pembayaran->pesanan_id} ditandai lunas."
            : 'Status pembayaran berhasil diperbarui.';

        return redirect()->back()->with('success', $pesan);
    }
}
