<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUlasanRequest;
use App\Models\Pesanan;
use App\Models\Ulasan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PelangganUlasanController extends Controller
{
    // ──────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────

    private function cekPelanggan(): void
    {
        if (Auth::user()->role !== 'pelanggan') {
            abort(403, 'Hanya pelanggan yang dapat mengakses halaman ini.');
        }
    }

    // ──────────────────────────────────────
    // CRUD
    // ──────────────────────────────────────

    /**
     * Daftar ulasan yang pernah ditulis pelanggan.
     */
    public function index(): View
    {
        $this->cekPelanggan();

        $ulasan = Ulasan::with(['pesanan.paketLayanan', 'pesanan.petugas'])
            ->whereHas('pesanan', function ($query) {
                $query->where('pelanggan_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('pelanggan.ulasan.index', compact('ulasan'));
    }

    /**
     * Form tulis ulasan untuk pesanan yang sudah selesai.
     */
    public function create(int $pesananId): View
    {
        $this->cekPelanggan();

        $pesanan = Pesanan::where('pelanggan_id', Auth::id())
            ->where('status', 'selesai')
            ->findOrFail($pesananId);

        // Cek apakah sudah pernah memberikan ulasan
        if ($pesanan->ulasan) {
            abort(409, 'Pesanan ini sudah memiliki ulasan.');
        }

        return view('pelanggan.ulasan.create', compact('pesanan'));
    }

    /**
     * Simpan ulasan baru.
     */
    public function store(StoreUlasanRequest $request, int $pesananId): RedirectResponse
    {
        $pesanan = Pesanan::where('pelanggan_id', Auth::id())
            ->where('status', 'selesai')
            ->findOrFail($pesananId);

        // Guard: 1 pesanan = 1 ulasan
        if ($pesanan->ulasan) {
            return redirect()
                ->back()
                ->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        Ulasan::create([
            'pesanan_id' => $pesanan->id,
            'rating'     => $request->rating,
            'komentar'   => $request->komentar,
        ]);

        return redirect()
            ->route('pelanggan.pesanan.show', $pesanan->id)
            ->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }

    /**
     * Hapus ulasan milik sendiri.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->cekPelanggan();

        $ulasan = Ulasan::whereHas('pesanan', function ($query) {
            $query->where('pelanggan_id', Auth::id());
        })->findOrFail($id);

        $ulasan->delete();

        return redirect()
            ->route('pelanggan.ulasan.index')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
