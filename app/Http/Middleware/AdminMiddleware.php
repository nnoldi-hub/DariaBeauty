<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/')
                ->with('error', 'Autentificare necesara pentru a accesa zona de admin.');
        }

        if (!in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Acces interzis: doar administratorii au acces.');
        }

        return $next($request);
    }
}
