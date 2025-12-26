<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SalonSpecialistsController extends Controller
{
    /**
     * Lista specialiști din salon
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verifică că e salon owner
        if (!($user->role === 'salon' || $user->is_salon_owner)) {
            abort(403, 'Acces interzis. Doar saloanele pot gestiona specialiști.');
        }

        // Specialiștii din salon
        $specialists = User::where('salon_id', $user->id)
            ->where('role', 'specialist')
            ->with(['appointments' => function($query) {
                $query->where('appointment_date', '>=', Carbon::now()->subMonth());
            }])
            ->get();

        return view('salon.specialists.index', compact('specialists'));
    }

    /**
     * Caută specialiști disponibili pentru asociere
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        
        // Verifică că e salon owner
        if (!($user->role === 'salon' || $user->is_salon_owner)) {
            abort(403);
        }

        $query = $request->input('query');
        
        if (empty($query)) {
            return response()->json([]);
        }

        // Caută specialiști fără salon sau cu is_active=true
        $specialists = User::where('role', 'specialist')
            ->where('is_active', true)
            ->whereNull('salon_id') // Doar specialiști fără salon
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('email', 'LIKE', '%' . $query . '%')
                  ->orWhere('phone', 'LIKE', '%' . $query . '%');
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone', 'sub_brand']);

        return response()->json($specialists);
    }

    /**
     * Asociază un specialist cu salonul
     */
    public function associate(Request $request)
    {
        $user = Auth::user();
        
        // Verifică că e salon owner
        if (!($user->role === 'salon' || $user->is_salon_owner)) {
            abort(403);
        }

        $request->validate([
            'specialist_id' => 'required|exists:users,id'
        ]);

        $specialist = User::findOrFail($request->specialist_id);

        // Verificări
        if ($specialist->role !== 'specialist') {
            return back()->with('error', 'Utilizatorul selectat nu este specialist.');
        }

        if ($specialist->salon_id === $user->id) {
            return back()->with('error', 'Acest specialist face deja parte din salonul tău.');
        }
        
        if ($specialist->salon_id) {
            return back()->with('error', 'Acest specialist face deja parte dintr-un alt salon.');
        }

        // Asociază specialistul cu salonul
        $specialist->update([
            'salon_id' => $user->id
        ]);

        // Actualizează numărul de specialiști
        $specialistsCount = User::where('salon_id', $user->id)->where('role', 'specialist')->count();
        $user->update(['salon_specialists_count' => $specialistsCount]);

        return back()->with('success', 'Specialistul ' . $specialist->name . ' a fost adăugat în salon cu succes!');
    }

    /**
     * Elimină specialist din salon (doar deconectare, nu ștergere cont)
     */
    public function remove($specialist_id)
    {
        $user = Auth::user();
        
        // Verifică că e salon owner
        if (!($user->role === 'salon' || $user->is_salon_owner)) {
            abort(403);
        }

        $specialist = User::where('id', $specialist_id)
            ->where('salon_id', $user->id)
            ->firstOrFail();

        $specialist->salon_id = null;
        $specialist->save();

        // Update counter
        $user->decrement('salon_specialists_count');

        return back()->with('success', $specialist->name . ' a fost eliminat din salon.');
    }
}
