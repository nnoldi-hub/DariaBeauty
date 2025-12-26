<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SalonController extends Controller
{
    /**
     * Afișează lista publică cu saloanele
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'salon')
                    ->where('is_salon_owner', true)
                    ->where('is_active', true)
                    ->with(['reviews'])
                    ->withCount('reviews')
                    ->withAvg('reviews', 'rating');

        // Filtrare după locație
        if ($request->has('location') && !empty($request->location)) {
            $location = $request->location;
            $query->where(function($q) use ($location) {
                $q->where('salon_address', 'LIKE', "%{$location}%")
                  ->orWhere('address', 'LIKE', "%{$location}%");
            });
        }

        // Filtrare după sub-brand (suportă și JSON array)
        if ($request->has('sub_brand') && !empty($request->sub_brand)) {
            $subBrand = $request->sub_brand;
            $query->where(function($q) use ($subBrand) {
                // Verifică dacă sub_brand conține valoarea (fie direct, fie în JSON)
                $q->where('sub_brand', $subBrand)
                  ->orWhere('sub_brand', 'LIKE', "%\"{$subBrand}\"%");
            });
        }

        // Sortare
        $sortBy = $request->input('sort', 'rating');
        switch ($sortBy) {
            case 'rating':
                $query->orderByDesc('reviews_avg_rating');
                break;
            case 'specialists':
                $query->orderByDesc('salon_specialists_count');
                break;
            case 'reviews':
                $query->orderByDesc('reviews_count');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->orderByDesc('reviews_avg_rating');
        }

        $salons = $query->paginate(12);

        // Statistici pentru featured saloane
        $featuredSalons = User::where('role', 'salon')
                            ->where('is_salon_owner', true)
                            ->where('is_active', true)
                            ->with(['reviews'])
                            ->withCount('reviews')
                            ->withAvg('reviews', 'rating')
                            ->orderByDesc('reviews_avg_rating')
                            ->limit(3)
                            ->get();

        return view('salons.index', compact('salons', 'featuredSalons'));
    }

    /**
     * Afișează profilul public al unui salon
     */
    public function show($id)
    {
        $salon = User::where('role', 'salon')
                    ->where('is_salon_owner', true)
                    ->where('is_active', true)
                    ->with(['reviews'])
                    ->withCount('reviews')
                    ->withAvg('reviews', 'rating')
                    ->findOrFail($id);

        // Specialiștii din salon
        $specialists = User::where('salon_id', $salon->id)
                          ->where('role', 'specialist')
                          ->where('is_active', true)
                          ->with(['services', 'reviews'])
                          ->withCount('reviews')
                          ->withAvg('reviews', 'rating')
                          ->get();

        // Reviews ale salonului
        $reviews = $salon->reviews()
                        ->approved()
                        ->latest()
                        ->paginate(10);

        // Statistici salon
        $stats = [
            'total_specialists' => $salon->salon_specialists_count,
            'total_reviews' => $salon->reviews_count,
            'average_rating' => $salon->reviews_avg_rating ?? 0,
            'member_since' => $salon->created_at->format('Y')
        ];

        return view('salons.show', compact('salon', 'specialists', 'reviews', 'stats'));
    }
}
