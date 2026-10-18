<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Ulasan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    // Pesanan berikutnya dan ringkasan petugas
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

        $pesananBerikutnya = Pesanan::with(['pelanggan', 'paketLayanan'])
            ->where('petugas_id', $petugas->id)
            ->whereIn('status', ['diterima', 'dikerjakan'])
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->first();

        return view('petugas.dashboard', compact(
            'petugas',
            'pesananBerikutnya',
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
                ->with('error', 'Akunmu lagi dicek admin. Begitu beres, kamu bisa ambil pesanan.');
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
            return redirect()->back()->with('error', 'Akunmu lagi dicek admin. Begitu beres, kamu bisa ambil pesanan.');
        }

        $pesanan = Pesanan::with('paketLayanan')->findOrFail($id);

        if ($pesanan->status != 'menunggu' || $pesanan->petugas_id != null) {
            return redirect()->back()->with('error', 'Pesanan ini sudah diambil petugas lain.');
        }

        if ($this->jadwalBentrok($pesanan)) {
            return redirect()->back()->with('error', 'Jamnya bentrok dengan pesanan kamu yang lain di hari itu.');
        }

        $pesanan->update([
            'petugas_id' => Auth::id(),
            'status' => 'diterima',
        ]);

        $jadwal = $pesanan->tanggal->translatedFormat('l, d F') . ' jam ' . str_replace(':', '.', substr($pesanan->jam_mulai, 0, 5));

        return redirect()->route('petugas.pesanan.show', $pesanan->id)
            ->with('success', 'Pesanan kamu ambil. Datang ' . $jadwal . '.');
    }

    // Status diterima menjadi dikerjakan
    public function kerjakan($id)
    {
        $this->cekPetugas();

        $pesanan = Pesanan::where('petugas_id', Auth::id())->findOrFail($id);

        if ($pesanan->status != 'diterima') {
            return redirect()->back()->with('error', 'Pesanan ini belum bisa dimulai.');
        }

        $pesanan->update(['status' => 'dikerjakan']);

        return redirect()->back()->with('success', 'Oke, pesanan sedang dikerjakan. Tekan Tandai beres kalau sudah selesai.');
    }

    // Status dikerjakan menjadi selesai
    public function selesai($id)
    {
        $this->cekPetugas();

        $pesanan = Pesanan::where('petugas_id', Auth::id())->findOrFail($id);

        if ($pesanan->status != 'dikerjakan') {
            return redirect()->back()->with('error', 'Pesanan ini belum bisa ditandai beres.');
        }

        $pesanan->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Mantap, pesanan beres.');
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
