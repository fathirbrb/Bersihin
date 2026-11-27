<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\KelolaProfil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelangganProfilController extends Controller
{
    use KelolaProfil;

    public function show(Request $request): View
    {
        return view('pelanggan.profil', ['user' => $request->user()]);
    }

    public function updateDataDiri(Request $request): RedirectResponse
    {
        return $this->simpanProfil($request, 'pelanggan.profil');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        return $this->simpanSandi($request, 'pelanggan.profil');
    }
}
