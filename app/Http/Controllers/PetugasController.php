<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Ulasan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    // Ringkasan statistik untuk dashboard petugas
    public function dashboard()
    {
        $this->cekPetugas();

        $petugas = Auth::user();

        $jumlahPesananMasuk = Pesanan::where('status', 'menunggu')
            ->whereNull('petugas_id')
            ->count();

        $jumlahDikerjakan = Pesanan::where('petugas_id', $petugas->id)
            ->whereIn('status', ['diterima', 'dikerjakan'])
            ->count();

        $jumlahSelesai = Pesanan::where('petugas_id', $petugas->id)
            ->where('status', 'selesai')
            ->count();

        $rataRating = Ulasan::whereHas('pesanan', function ($query) use ($petugas) {
            $query->where('petugas_id', $petugas->id);
        })->avg('rating');

        return view('petugas.dashboard', compact(
            'petugas',
            'jumlahPesananMasuk',
            'jumlahDikerjakan',
            'jumlahSelesai',
            'rataRating'
        ));
    }

    // Pesanan menunggu yang belum diambil petugas
    public function pesananMasuk()
    {
        $this->cekPetugas();

        if (Auth::user()->status_verifikasi != 'terverifikasi') {
            return redirect()->route('petugas.dashboard')
                ->with('error', 'Akun kamu belum diverifikasi admin.');
        }

        $pesanan = Pesanan::with(['pelanggan', 'paketLayanan'])
            ->where('status', 'menunggu')
            ->whereNull('petugas_id')
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return view('petugas.pesanan-masuk', compact('pesanan'));
    }

    // Pesanan yang sudah diambil petugas ini
    public function pesananSaya()
    {
        $this->cekPetugas();

        $pesanan = Pesanan::with(['pelanggan', 'paketLayanan'])
            ->where('petugas_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get();

        return view('petugas.pesanan-saya', compact('pesanan'));
    }

    // Detail pesanan milik sendiri atau tersedia
    public function show($id)
    {
        $this->cekPetugas();

        $pesanan = Pesanan::with(['pelanggan', 'paketLayanan', 'pembayaran', 'ulasan'])
            ->findOrFail($id);

        $bolehLihat = $pesanan->petugas_id == Auth::id()
            || ($pesanan->petugas_id == null && $pesanan->status == 'menunggu');

        if (!$bolehLihat) {
            abort(403);
        }

        return view('petugas.detail', compact('pesanan'));
    }

    // Ambil pesanan, cek verifikasi dan bentrok
    public function terima($id)
    {
        $this->cekPetugas();

        if (Auth::user()->status_verifikasi != 'terverifikasi') {
            return redirect()->back()->with('error', 'Akun kamu belum diverifikasi admin.');
        }

        $pesanan = Pesanan::with('paketLayanan')->findOrFail($id);

        if ($pesanan->status != 'menunggu' || $pesanan->petugas_id != null) {
            return redirect()->back()->with('error', 'Pesanan ini sudah tidak tersedia.');
        }

        if ($this->jadwalBentrok($pesanan)) {
            return redirect()->back()->with('error', 'Jadwal bentrok dengan pesanan kamu yang lain.');
        }

        $pesanan->update([
            'petugas_id' => Auth::id(),
            'status' => 'diterima',
        ]);

        return redirect()->route('petugas.pesanan-saya')->with('success', 'Pesanan berhasil diterima.');
    }

    // Status diterima menjadi dikerjakan
    public function kerjakan($id)
    {
        $this->cekPetugas();

        $pesanan = Pesanan::where('petugas_id', Auth::id())->findOrFail($id);

        if ($pesanan->status != 'diterima') {
            return redirect()->back()->with('error', 'Status pesanan tidak bisa diubah ke dikerjakan.');
        }

        $pesanan->update(['status' => 'dikerjakan']);

        return redirect()->back()->with('success', 'Status pesanan diubah menjadi dikerjakan.');
    }

    // Status dikerjakan menjadi selesai
    public function selesai($id)
    {
        $this->cekPetugas();

        $pesanan = Pesanan::where('petugas_id', Auth::id())->findOrFail($id);

        if ($pesanan->status != 'dikerjakan') {
            return redirect()->back()->with('error', 'Status pesanan tidak bisa diubah ke selesai.');
        }

        $pesanan->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Pesanan selesai dikerjakan.');
    }

    // Ulasan dari pesanan yang dikerjakan petugas
    public function ulasan()
    {
        $this->cekPetugas();

        $ulasan = Ulasan::with(['pesanan.pelanggan', 'pesanan.paketLayanan'])
            ->whereHas('pesanan', function ($query) {
                $query->where('petugas_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('petugas.ulasan', compact('ulasan'));
    }

    // Tolak akses selain role petugas
    private function cekPetugas()
    {
        if (Auth::user()->role != 'petugas') {
            abort(403);
        }
    }

    // Cek jam tumpang tindih di tanggal sama
    private function jadwalBentrok($pesanan)
    {
        $tanggal = $pesanan->tanggal->format('Y-m-d');
        $mulai = Carbon::parse($tanggal . ' ' . $pesanan->jam_mulai);
        $selesai = $mulai->copy()->addHours($pesanan->paketLayanan->estimasi_durasi_jam);

        $jadwalLain = Pesanan::with('paketLayanan')
            ->where('petugas_id', Auth::id())
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['diterima', 'dikerjakan'])
            ->get();

        foreach ($jadwalLain as $item) {
            $mulaiLain = Carbon::parse($tanggal . ' ' . $item->jam_mulai);
            $selesaiLain = $mulaiLain->copy()->addHours($item->paketLayanan->estimasi_durasi_jam);

            if ($mulai < $selesaiLain && $selesai > $mulaiLain) {
                return true;
            }
        }

        return false;
    }
}
