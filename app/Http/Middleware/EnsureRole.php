<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureRole — middleware terpusat pengecekan role.
 *
 * Pemakaian di routes:
 *   ->middleware('role:admin')
 *   ->middleware('role:petugas')
 *   ->middleware('role:pelanggan')
 *
 * Menggantikan fungsi private cekPetugas(), cekPelanggan(), dan cek_admin()
 * yang sebelumnya ada di masing-masing controller.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Seharusnya sudah difilter middleware auth, tapi aman untuk dicek ulang
        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah role user ada di daftar role yang diizinkan
        if (!in_array($user->role, $roles, true)) {
            // Arahkan ke halaman yang sesuai role user sendiri, bukan 403 mentah
            return match ($user->role) {
                'admin'     => redirect()->route('admin.paket.index')
                                ->with('error', 'Kamu tidak punya akses ke halaman itu.'),
                'petugas'   => redirect()->route('petugas.dashboard')
                                ->with('error', 'Kamu tidak punya akses ke halaman itu.'),
                'pelanggan' => redirect()->route('pelanggan.pesanan.index')
                                ->with('error', 'Kamu tidak punya akses ke halaman itu.'),
                default     => abort(403, 'Akses ditolak.'),
            };
        }

        return $next($request);
    }
}
