<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialistMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/')
                ->with('error', 'Autentificare necesara pentru a accesa zona de specialist.');
        }

        if ($user->role !== 'specialist') {
            abort(403, 'Acces interzis: doar specialistii au acces.');
        }

        if (!$user->is_active) {
            return redirect('/')->with('error', 'Contul de specialist este in curs de aprobare.');
        }

        return $next($request);
    }
}
