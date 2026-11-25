<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePesananRequest;
use App\Models\PaketLayanan;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PelangganPesananController extends Controller
{
    private function cekPelanggan(): void
    {
        if (Auth::user()->role !== 'pelanggan') {
            abort(403, 'Hanya pelanggan yang dapat mengakses halaman ini.');
        }
    }

    public function beranda(): View
    {
        $this->cekPelanggan();

        $pesananAktif = Pesanan::with(['paketLayanan', 'petugas', 'pembayaran'])
            ->where('pelanggan_id', Auth::id())
            ->whereIn('status', ['menunggu', 'diterima', 'dikerjakan'])
            ->latest('tanggal')
            ->latest('jam_mulai')
            ->first();

        $paketLayanan = PaketLayanan::orderBy('harga')->get();

        return view('pelanggan.beranda', compact('pesananAktif', 'paketLayanan'));
    }

    public function index(): View
    {
        $this->cekPelanggan();

        $pesanan = Pesanan::with(['paketLayanan', 'petugas', 'ulasan'])
            ->where('pelanggan_id', Auth::id())
            ->latest('tanggal')
            ->latest('jam_mulai')
            ->get();

        $pesananJalan = $pesanan->whereIn('status', ['menunggu', 'diterima', 'dikerjakan']);
        $riwayat = $pesanan->whereIn('status', ['selesai', 'dibatalkan']);

        return view('pelanggan.pesanan.index', compact('pesanan', 'pesananJalan', 'riwayat'));
    }

    public function create(Request $request): View
    {
        $this->cekPelanggan();

        $paketLayanan = PaketLayanan::orderBy('harga')->get();
        $selectedPaketId = $request->query('paket');

        return view('pelanggan.pesanan.create', compact('paketLayanan', 'selectedPaketId'));
    }

    public function store(StorePesananRequest $request): RedirectResponse
    {
        $paket = PaketLayanan::findOrFail($request->paket_layanan_id);

        $pesanan = Pesanan::create([
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
            ->route('pelanggan.pesanan.show', $pesanan->id)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }

    public function show(int $id): View
    {
        $this->cekPelanggan();

        $pesanan = Pesanan::with(['paketLayanan', 'petugas', 'ulasan', 'pembayaran'])
            ->where('pelanggan_id', Auth::id())
            ->findOrFail($id);

        return view('pelanggan.pesanan.show', compact('pesanan'));
    }

    public function batalkan(int $id): RedirectResponse
    {
        $this->cekPelanggan();

        $pesanan = Pesanan::with('pembayaran')->where('pelanggan_id', Auth::id())->findOrFail($id);
        $pembayaran = $pesanan->pembayaran;

        if ($pembayaran && $pembayaran->status === 'lunas') {
            return redirect()
                ->back()
                ->with('error', 'Pesanan sudah dibayar. Hubungi admin untuk pembatalan.');
        }

        // Update bersyarat, anti balapan dengan petugas
        $dibatalkan = DB::transaction(function () use ($pesanan, $pembayaran) {
            $berubah = Pesanan::where('id', $pesanan->id)
                ->where('status', 'menunggu')
                ->update(['status' => 'dibatalkan']);

            if ($berubah && $pembayaran && $pembayaran->status === 'menunggu') {
                $pembayaran->update(['status' => 'ditolak']);
            }

            return $berubah;
        });

        if (! $dibatalkan) {
            return redirect()
                ->back()
                ->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses.');
        }

        return redirect()
            ->route('pelanggan.pesanan.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
