<?php

namespace App\Http\Controllers;

use App\Models\PaketLayanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    // User login diarahkan ke berandanya
    public function __invoke(Request $request): View|RedirectResponse
    {
        if ($user = $request->user()) {
            return redirect()->route($user->dashboardRouteName() ?? 'login');
        }

        return view('welcome', [
            'paketLayanan' => PaketLayanan::orderBy('harga')->get(),
        ]);
    }
}
