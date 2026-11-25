<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Ulasan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

        // Update atomik, hanya satu petugas yang menang
        $diambil = Pesanan::where('id', $pesanan->id)
            ->where('status', 'menunggu')
            ->whereNull('petugas_id')
            ->update(['petugas_id' => Auth::id(), 'status' => 'diterima']);

        if ($diambil === 0) {
            return redirect()->back()->with('error', 'Pesanan ini sudah diambil petugas lain.');
        }

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

    // Halaman profil petugas
    public function profil()
    {
        $this->cekPetugas();

        $user = Auth::user();

        return view('petugas.profil', compact('user'));
    }

    // Simpan perubahan data diri dan foto
    public function updateProfil(Request $request)
    {
        $this->cekPetugas();

        $user = Auth::user();

        $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_hp'      => ['required', 'string', 'max:20'],
            'foto'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'hapus_foto' => ['nullable', 'boolean'],
        ], [
            'nama.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'foto.image'     => 'Berkas harus berupa gambar.',
            'foto.mimes'     => 'Foto harus berformat JPG atau PNG.',
            'foto.max'       => 'Ukuran foto maksimal 2 MB.',
        ]);

        $data = [
            'nama'  => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ];

        if ($request->boolean('hapus_foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = null;
        }

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto_profil', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('petugas.profil')
            ->with('success', 'Profil disimpan.');
    }

    // Ganti kata sandi petugas
    public function updatePassword(Request $request)
    {
        $this->cekPetugas();

        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required'         => 'Kata sandi sekarang wajib diisi.',
            'current_password.current_password' => 'Kata sandi sekarang tidak cocok.',
            'password.required'                 => 'Kata sandi baru wajib diisi.',
            'password.min'                      => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed'                => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('petugas.profil')
            ->with('success', 'Kata sandi sudah diganti.');
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
