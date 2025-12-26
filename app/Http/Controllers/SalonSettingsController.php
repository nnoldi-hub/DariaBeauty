<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class SalonSettingsController extends Controller
{
    /**
     * Afișează pagina de setări salon
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verifică că e salon owner
        if (!($user->role === 'salon' || $user->is_salon_owner)) {
            return redirect()->route('specialist.dashboard');
        }

        return view('salon.settings.index', compact('user'));
    }

    /**
     * Actualizează informațiile salonului
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Verifică că e salon owner
        if (!($user->role === 'salon' || $user->is_salon_owner)) {
            return redirect()->route('specialist.dashboard')->with('error', 'Acces interzis');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'salon_address' => 'nullable|string|max:500',
            'salon_description' => 'nullable|string|max:2000',
            'sub_brand' => 'nullable|in:dariaNails,dariaHair,dariaGlow',
            'salon_instagram' => 'nullable|string|max:100',
            'salon_facebook' => 'nullable|string|max:100',
            'salon_tiktok' => 'nullable|string|max:100',
            'salon_logo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048'
        ]);

        // Handle logo upload
        if ($request->hasFile('salon_logo')) {
            // Delete old logo if exists
            if ($user->salon_logo && Storage::disk('public')->exists($user->salon_logo)) {
                Storage::disk('public')->delete($user->salon_logo);
            }
            
            $logoPath = $request->file('salon_logo')->store('salon-logos', 'public');
            $validated['salon_logo'] = $logoPath;
        }

        // Adaugă @ la social media dacă nu există
        if (!empty($validated['salon_instagram']) && !str_starts_with($validated['salon_instagram'], '@')) {
            $validated['salon_instagram'] = '@' . $validated['salon_instagram'];
        }
        if (!empty($validated['salon_facebook']) && !str_starts_with($validated['salon_facebook'], '@')) {
            $validated['salon_facebook'] = '@' . $validated['salon_facebook'];
        }
        if (!empty($validated['salon_tiktok']) && !str_starts_with($validated['salon_tiktok'], '@')) {
            $validated['salon_tiktok'] = '@' . $validated['salon_tiktok'];
        }

        $user->update($validated);

        return back()->with('success', 'Setările salonului au fost actualizate cu succes!');
    }

    /**
     * Actualizează parola
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // Verifică parola curentă
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Parola curentă este incorectă']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Parola a fost schimbată cu succes!');
    }
}
