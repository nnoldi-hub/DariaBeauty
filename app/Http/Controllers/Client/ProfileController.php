<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Appointment;
use App\Models\Review;

class ProfileController extends Controller
{
    /**
     * Afișează profilul clientului
     */
    public function show()
    {
        $user = auth()->user();
        
        // Statistici
        $appointmentsCount = Appointment::where('client_email', $user->email)->count();
        $completedCount = Appointment::where('client_email', $user->email)
            ->where('status', 'completed')
            ->count();
        $reviewsCount = Review::whereHas('appointment', function($query) use ($user) {
            $query->where('client_email', $user->email);
        })->count();
        
        return view('client.profile', compact('appointmentsCount', 'completedCount', 'reviewsCount'));
    }
    
    /**
     * Actualizează informațiile profilului
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        
        $user->update($validated);
        
        return redirect()->route('client.profile')->with('success', 'Profilul a fost actualizat cu succes!');
    }
    
    /**
     * Actualizează parola
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        $user = auth()->user();
        
        // Verifică parola curentă
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Parola curentă este incorectă.']);
        }
        
        // Actualizează parola
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('client.profile')->with('success', 'Parola a fost schimbată cu succes!');
    }
}
