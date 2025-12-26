<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SalonMiddleware
{
    /**
     * Handle an incoming request.
     * Permite accesul doar utilizatorilor cu rol 'salon' sau 'specialist' cu is_salon_owner = true
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('=== SalonMiddleware ENTRY ===', [
            'timestamp' => now()->toDateTimeString(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'route_name' => $request->route() ? $request->route()->getName() : 'NO_ROUTE',
        ]);

        $user = Auth::user();

        Log::info('SalonMiddleware Check', [
            'url' => $request->url(),
            'user_id' => $user ? $user->id : 'NULL',
            'user_role' => $user ? $user->role : 'NULL',
            'is_salon_owner' => $user ? ($user->is_salon_owner ?? false) : 'NULL',
            'user_active' => $user ? $user->is_active : 'NULL'
        ]);

        if (!$user) {
            Log::error('SalonMiddleware: No user authenticated');
            return redirect('/')
                ->with('error', 'Autentificare necesară pentru a accesa zona de salon.');
        }

        // Permite accesul dacă:
        // - Rol = 'salon' SAU
        // - Rol = 'specialist' și is_salon_owner = true
        if ($user->role !== 'salon' && !($user->role === 'specialist' && $user->is_salon_owner)) {
            Log::error('SalonMiddleware: Wrong role or not salon owner', [
                'role' => $user->role,
                'is_salon_owner' => $user->is_salon_owner ?? false
            ]);
            abort(403, 'Acces interzis: doar saloanele au acces la această secțiune.');
        }

        if (!$user->is_active) {
            Log::error('SalonMiddleware: User inactive');
            return redirect('/')->with('error', 'Contul de salon este în curs de aprobare.');
        }

        Log::info('SalonMiddleware: Access granted');
        return $next($request);
    }
}
