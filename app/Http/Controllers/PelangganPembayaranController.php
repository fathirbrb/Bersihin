<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePembayaranRequest;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PelangganPembayaranController extends Controller
{
    public function store(StorePembayaranRequest $request, int $pesananId): RedirectResponse
    {
        $pesanan = Pesanan::where('pelanggan_id', Auth::id())->findOrFail($pesananId);

        // Cegah pembayaran ganda
        if ($pesanan->pembayaran) {
            return redirect()
                ->back()
                ->with('error', 'Pesanan ini sudah memiliki data pembayaran.');
        }

        // Cegah pembayaran untuk pesanan yang dibatalkan
        if ($pesanan->status === 'dibatalkan') {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat melakukan pembayaran untuk pesanan yang dibatalkan.');
        }

        // Upload bukti bayar jika metode transfer
        $buktiBayarPath = null;
        if ($request->metode === 'transfer' && $request->hasFile('bukti_bayar')) {
            $buktiBayarPath = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');
        }

        Pembayaran::create([
            'pesanan_id'  => $pesanan->id,
            'metode'      => $request->metode,
            'bukti_bayar' => $buktiBayarPath,
            'status'      => 'menunggu',
        ]);

        return redirect()
            ->route('pelanggan.pesanan.show', $pesanan->id)
            ->with('success', 'Pembayaran berhasil dikirim dan menunggu konfirmasi.');
    }
}
