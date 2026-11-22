<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PelangganProfilController extends Controller
{
    private function cekPelanggan(): void
    {
        if (Auth::user()->role !== 'pelanggan') {
            abort(403, 'Hanya pelanggan yang dapat mengakses halaman ini.');
        }
    }

    public function show(): View
    {
        $this->cekPelanggan();

        $user = Auth::user();

        return view('pelanggan.profil', compact('user'));
    }

    public function updateDataDiri(Request $request): RedirectResponse
    {
        $this->cekPelanggan();

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

        // Hapus foto jika diminta
        if ($request->boolean('hapus_foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = null;
        }

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto_profil', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('pelanggan.profil')
            ->with('success', 'Profil disimpan.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $this->cekPelanggan();

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
            ->route('pelanggan.profil')
            ->with('success', 'Kata sandi sudah diganti.');
    }
}

