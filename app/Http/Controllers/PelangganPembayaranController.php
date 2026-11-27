<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePembayaranRequest;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PelangganPembayaranController extends Controller
{
    public function store(StorePembayaranRequest $request, int $pesananId): RedirectResponse
    {
        $pesanan = Pesanan::with('pembayaran')->where('pelanggan_id', Auth::id())->findOrFail($pesananId);
        $pembayaran = $pesanan->pembayaran;

        // Hanya pembayaran ditolak yang boleh dikirim ulang
        if ($pembayaran && $pembayaran->status !== 'ditolak') {
            return redirect()
                ->back()
                ->with('error', 'Pesanan ini sudah memiliki data pembayaran.');
        }

        if ($pesanan->status === 'dibatalkan') {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat melakukan pembayaran untuk pesanan yang dibatalkan.');
        }

        $buktiBayarPath = null;
        if ($request->metode === 'transfer' && $request->hasFile('bukti_bayar')) {
            $buktiBayarPath = $request->file('bukti_bayar')->store('bukti_pembayaran', 'local');
        }

        if ($pembayaran) {
            // Kirim ulang: ganti bukti lama
            if ($pembayaran->bukti_bayar) {
                Storage::disk('local')->delete($pembayaran->bukti_bayar);
            }
            $pembayaran->update([
                'metode'      => $request->metode,
                'bukti_bayar' => $buktiBayarPath,
                'status'      => 'menunggu',
            ]);
        } else {
            Pembayaran::create([
                'pesanan_id'  => $pesanan->id,
                'metode'      => $request->metode,
                'bukti_bayar' => $buktiBayarPath,
                'status'      => 'menunggu',
            ]);
        }

        return redirect()
            ->route('pelanggan.pesanan.show', $pesanan->id)
            ->with('success', 'Pembayaran berhasil dikirim dan menunggu konfirmasi.');
    }

    // Bukti hanya untuk admin dan pemilik pesanan
    public function bukti(Pembayaran $pembayaran): StreamedResponse
    {
        $user = Auth::user();
        $pemilik = $pembayaran->pesanan->pelanggan_id === $user->id;

        abort_unless($user->role === 'admin' || $pemilik, 403);
        abort_unless(
            $pembayaran->bukti_bayar && Storage::disk('local')->exists($pembayaran->bukti_bayar),
            404
        );

        return Storage::disk('local')->response($pembayaran->bukti_bayar);
    }
}
