<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function share(Request $request)
    {
        // Logica pentru partajarea pe rețelele sociale
        return response()->json(['status' => 'shared']);
    }

    public function login($provider)
    {
        // Logica pentru login prin rețelele sociale
        return redirect()->route('dashboard');
    }

    public function callback($provider)
    {
        // Logica pentru callback de la rețelele sociale
        return redirect()->route('dashboard');
    }

    // Feed Instagram simplificat (placeholder)
    public function instagramFeed()
    {
        return response()->json(['items' => []]);
    }

    // Partajare specialist (compatibil cu ruta)
    public function shareSpecialist($specialist)
    {
        return response()->json(['status' => 'shared', 'specialist' => $specialist]);
    }
}