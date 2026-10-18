<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        if (! $user || ! $user->isRole($role)) {
            $dashboardRoute = $user?->dashboardRouteName();

            if ($dashboardRoute) {
                return redirect()
                    ->route($dashboardRoute)
                    ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
            }

            return abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
