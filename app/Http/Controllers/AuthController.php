<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    // ─── Login ────────────────────────────────────────────────────────────

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $kredensial = $request->only('email', 'password');

        if (!Auth::attempt($kredensial, $request->boolean('ingat_saya'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi salah.']);
        }

        $request->session()->regenerate();

        return $this->redirectBerdasarkanRole(Auth::user());
    }

    // ─── Registrasi ───────────────────────────────────────────────────────

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp'    => ['required', 'string', 'max:20'],
            'role'     => ['required', 'in:pelanggan,petugas'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.required'              => 'Nama lengkap wajib diisi.',
            'nama.max'                   => 'Nama maksimal 255 karakter.',
            'email.required'             => 'Email wajib diisi.',
            'email.email'                => 'Format email tidak valid.',
            'email.unique'               => 'Email ini sudah terdaftar. Coba masuk atau pakai email lain.',
            'no_hp.required'             => 'Nomor HP wajib diisi.',
            'no_hp.max'                  => 'Nomor HP maksimal 20 karakter.',
            'role.required'              => 'Pilih peran kamu.',
            'role.in'                    => 'Peran tidak valid.',
            'password.required'          => 'Kata sandi wajib diisi.',
            'password.min'               => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'         => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Petugas baru selalu menunggu verifikasi admin
        $statusVerifikasi = $request->role === 'petugas' ? 'menunggu' : 'terverifikasi';

        $user = User::create([
            'nama'              => $request->nama,
            'email'             => $request->email,
            'no_hp'             => $request->no_hp,
            'role'              => $request->role,
            'status_verifikasi' => $statusVerifikasi,
            'password'          => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Petugas baru diarahkan ke dashboard dengan info menunggu verifikasi
        return $this->redirectBerdasarkanRole($user);
    }

    // ─── Logout ───────────────────────────────────────────────────────────

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Kamu berhasil keluar.');
    }

    // ─── Helper: redirect ke dashboard sesuai role ────────────────────────

    private function redirectBerdasarkanRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin'     => redirect()->intended(route('admin.paket.index')),
            'petugas'   => redirect()->intended(route('petugas.dashboard')),
            'pelanggan' => redirect()->intended(route('pelanggan.pesanan.index')),
            default     => redirect()->route('login'),
        };
    }
}
