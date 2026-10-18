<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureRole — middleware terpusat pengecekan role.
 * Menggantikan cekPetugas(), cekPelanggan() di controller lama.
 *
 * Pemakaian: ->middleware('role:admin') / 'role:petugas' / 'role:pelanggan'
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, $roles, true)) {
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
