<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

trait KelolaProfil
{
    // Simpan data diri dan foto
    protected function simpanProfil(Request $request, string $rute): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $valid = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'no_hp'      => ['required', 'string', 'max:20'],
            'foto'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'hapus_foto' => ['nullable', 'boolean'],
        ]);

        $data = Arr::only($valid, ['nama', 'email', 'no_hp']);

        if ($request->boolean('hapus_foto') || $request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')?->store('foto-profil', 'public');
        }

        $user->update($data);

        return redirect()->route($rute)->with('success', 'Profil disimpan.');
    }

    // Ganti kata sandi
    protected function simpanSandi(Request $request, string $rute): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update(['password' => $data['password']]);

        return redirect()->route($rute)->with('success', 'Kata sandi sudah diganti.');
    }
}
