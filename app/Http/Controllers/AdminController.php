<?php

namespace App\Http\Controllers;

use App\Models\PaketLayanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   //kelola harga dan deskripsi F-03
   public function indexPaket()
   {
       $paketLayanan = PaketLayanan::all();
       return view('admin.paket_layanan', compact('paketLayanan'));
   }

   public function updatePaket(Request $request, PaketLayanan $paketLayanan)
    {
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
        $pesanan = Pesanan::with(['pelanggan', 'petugas', 'paketLayanan', 'pembayaran', 'ulasan'])
                        ->latest()
                        ->get();

        return view('admin.pesanan', compact('pesanan'));
    }

    //konfirmasi pembyaaran F-07
    public function indexPembayaran()
    {
        $pembayaran = Pembayaran::with('pesanan.pelanggan')->latest()->get();

        return view('admin.pembayaran', compact('pembayaran'));
    }

    public function updateStatusPembayaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,lunas,ditolak',
        ]);
    
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update([
            'status' => $request->status,
        ]);
    
        if ($request->status == 'lunas') {
            $pembayaran->pesanan()->update(['status' => 'menunggu']);
        }
    
        return redirect()->back()->with('success', 'Status pembayaran berhasil dikonfirmasi.');
    }
}
