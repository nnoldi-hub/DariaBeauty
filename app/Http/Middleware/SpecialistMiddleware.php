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

        \Log::info('SpecialistMiddleware Check', [
            'url' => $request->url(),
            'method' => $request->method(),
            'user_id' => $user ? $user->id : 'NULL',
            'user_role' => $user ? $user->role : 'NULL',
            'user_active' => $user ? $user->is_active : 'NULL'
        ]);

        if (!$user) {
            \Log::error('SpecialistMiddleware: No user authenticated');
            return redirect('/')
                ->with('error', 'Autentificare necesara pentru a accesa zona de specialist.');
        }

        if ($user->role !== 'specialist') {
            \Log::error('SpecialistMiddleware: Wrong role', ['role' => $user->role]);
            abort(403, 'Acces interzis: doar specialistii au acces.');
        }

        if (!$user->is_active) {
            \Log::error('SpecialistMiddleware: User inactive');
            return redirect('/')->with('error', 'Contul de specialist este in curs de aprobare.');
        }

        \Log::info('SpecialistMiddleware: PASSED');
        return $next($request);
    }
}
