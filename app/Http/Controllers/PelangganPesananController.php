<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePesananRequest;
use App\Models\PaketLayanan;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PelangganPesananController extends Controller
{
    // ──────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────

    /**
     * Pastikan user login sebagai pelanggan.
     */
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
     * Tampilkan daftar pesanan milik pelanggan.
     */
    public function index(): View
    {
        $this->cekPelanggan();

        $pesanan = Pesanan::with(['paketLayanan', 'petugas', 'ulasan'])
            ->where('pelanggan_id', Auth::id())
            ->latest('tanggal')
            ->latest('jam_mulai')
            ->get();

        return view('pelanggan.pesanan.index', compact('pesanan'));
    }

    /**
     * Form pemesanan baru.
     */
    public function create(): View
    {
        $this->cekPelanggan();

        $paketLayanan = PaketLayanan::orderBy('harga')->get();

        return view('pelanggan.pesanan.create', compact('paketLayanan'));
    }

    /**
     * Simpan pesanan baru.
     */
    public function store(StorePesananRequest $request): RedirectResponse
    {
        $paket = PaketLayanan::findOrFail($request->paket_layanan_id);

        Pesanan::create([
            'pelanggan_id'     => Auth::id(),
            'paket_layanan_id' => $paket->id,
            'alamat'           => $request->alamat,
            'tanggal'          => $request->tanggal,
            'jam_mulai'        => $request->jam_mulai,
            'catatan'          => $request->catatan,
            'total_harga'      => $paket->harga,
            'status'           => 'menunggu',
        ]);

        return redirect()
            ->route('pelanggan.pesanan.index')
            ->with('success', 'Pesanan berhasil dibuat dan menunggu petugas.');
    }

    /**
     * Detail pesanan.
     */
    public function show(int $id): View
    {
        $this->cekPelanggan();

        $pesanan = Pesanan::with(['paketLayanan', 'petugas', 'ulasan', 'pembayaran'])
            ->where('pelanggan_id', Auth::id())
            ->findOrFail($id);

        return view('pelanggan.pesanan.show', compact('pesanan'));
    }

    /**
     * Batalkan pesanan (hanya status 'menunggu').
     */
    public function batalkan(int $id): RedirectResponse
    {
        $this->cekPelanggan();

        $pesanan = Pesanan::where('pelanggan_id', Auth::id())->findOrFail($id);

        if ($pesanan->status !== 'menunggu') {
            return redirect()
                ->back()
                ->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses.');
        }

        $pesanan->update(['status' => 'dibatalkan']);

        return redirect()
            ->route('pelanggan.pesanan.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
