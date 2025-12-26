<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Afiseaza toate review-urile pentru un specialist
     */
    public function index($specialistId)
    {
        $reviews = Review::where('specialist_id', $specialistId)
                        ->approved()
                        ->with(['appointment.service', 'user'])
                        ->latest()
                        ->paginate(10);

        $stats = [
            'total_reviews' => Review::where('specialist_id', $specialistId)->approved()->count(),
            'average_rating' => Review::where('specialist_id', $specialistId)->approved()->avg('rating') ?? 0,
            'rating_distribution' => $this->getRatingDistribution($specialistId)
        ];

        return view('reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Formular pentru adaugare review
     */
    public function create(Appointment $appointment)
    {
        // Verifica daca userul poate da review pentru aceasta programare
        if (auth()->user()->email !== $appointment->client_email) {
            abort(403, 'Nu poți da review pentru o programare care nu îți aparține.');
        }

        // Verifica daca programarea este completata
        if ($appointment->status !== 'completed') {
            return redirect()->back()->with('error', 'Poți da review doar pentru programările completate.');
        }

        // Verifica daca nu exista deja un review
        if ($appointment->review) {
            return redirect()->back()->with('error', 'Ai dat deja un review pentru această programare.');
        }

        return view('reviews.create', compact('appointment'));
    }

    /**
     * Salveaza review-ul
     */
    public function store(Request $request, Appointment $appointment)
    {
        // Verificari de securitate
        if (auth()->user()->email !== $appointment->client_email) {
            abort(403, 'Nu poți da review pentru o programare care nu îți aparține.');
        }

        if ($appointment->status !== 'completed') {
            return redirect()->back()->with('error', 'Poți da review doar pentru programările completate.');
        }

        if ($appointment->review) {
            return redirect()->back()->with('error', 'Ai dat deja un review pentru această programare.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'service_quality_rating' => 'nullable|integer|min:1|max:5',
            'punctuality_rating' => 'nullable|integer|min:1|max:5',
            'cleanliness_rating' => 'nullable|integer|min:1|max:5',
            'overall_experience' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = new Review([
            'appointment_id' => $appointment->id,
            'specialist_id' => $appointment->specialist_id,
            'client_name' => auth()->user()->name,
            'rating' => $request->rating,
            'service_quality_rating' => $request->service_quality_rating ?? $request->rating,
            'punctuality_rating' => $request->punctuality_rating ?? $request->rating,
            'cleanliness_rating' => $request->cleanliness_rating ?? $request->rating,
            'overall_experience' => $request->overall_experience ?? $request->rating,
            'comment' => $request->comment,
            'photos' => null,
            'is_approved' => true
        ]);

        $review->save();

        // Trimite notificare SMS către specialist despre review-ul primit
        try {
            $smsService = app(\App\Services\SmsService::class);
            $specialist = $appointment->specialist;
            
            if ($smsService->isEnabled() && $specialist) {
                $result = $smsService->notifySpecialistReview($review, $specialist);
                \Log::info("Specialist review notification SMS", [
                    'review_id' => $review->id,
                    'specialist_id' => $specialist->id,
                    'result' => $result ? 'SUCCESS' : 'FAILED'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send review notification SMS", [
                'review_id' => $review->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('specialists.show', $appointment->specialist->slug)
                        ->with('success', 'Mulțumim pentru review! Review-ul tău a fost salvat cu succes.');
    }

    /**
     * Afiseaza un review specific
     */
    public function show(Review $review)
    {
        if (!$review->is_approved && Auth::id() !== $review->user_id) {
            abort(404);
        }

        $review->load(['appointment.service', 'user', 'specialist']);

        return view('reviews.show', compact('review'));
    }

    /**
     * Aproba un review (doar pentru specialisti)
     */
    public function approve(Review $review)
    {
        // Verifica daca utilizatorul este specialistul pentru care e review-ul
        if (Auth::id() !== $review->specialist_id) {
            abort(403);
        }

        $review->update([
            'is_approved' => true,
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Review-ul a fost aprobat!');
    }

    /**
     * Respinge un review (doar pentru specialisti)
     */
    public function reject(Request $request, Review $review)
    {
        if (Auth::id() !== $review->specialist_id) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $review->update([
            'is_approved' => false,
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now()
        ]);

        return redirect()->back()->with('success', 'Review-ul a fost respins.');
    }

    /**
     * Raspunde la un review (doar pentru specialisti)
     */
    public function respond(Request $request, Review $review)
    {
        if (Auth::id() !== $review->specialist_id) {
            abort(403);
        }

        $request->validate([
            'response' => 'required|string|min:10|max:500'
        ]);

        $review->update([
            'specialist_response' => $request->response,
            'responded_at' => now()
        ]);

        return redirect()->back()->with('success', 'Raspunsul tau a fost salvat!');
    }

    /**
     * Review-uri pentru specialist (dashboard)
     */
    public function specialistReviews()
    {
        $specialist = Auth::user();

        $reviews = $specialist->reviews()
                             ->with(['appointment.service'])
                             ->latest()
                             ->paginate(15);

        $stats = [
            'total' => $specialist->reviews()->count(),
            'approved' => $specialist->reviews()->where('is_approved', true)->count(),
            'pending' => $specialist->reviews()->where('is_approved', false)->whereNull('specialist_response')->count(),
            'average_rating' => $specialist->reviews()->where('is_approved', true)->avg('rating') ?? 0
        ];

        return view('specialist.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Marcheaza review ca util
     */
    public function markHelpful(Request $request, Review $review)
    {
        $userId = Auth::id();
        
        // Verifica daca user-ul a mai marcat acest review
        $helpfulVotes = $review->helpful_votes ?? [];
        
        if (in_array($userId, $helpfulVotes)) {
            // Remove vote
            $helpfulVotes = array_filter($helpfulVotes, function($id) use ($userId) {
                return $id !== $userId;
            });
        } else {
            // Add vote
            $helpfulVotes[] = $userId;
        }

        $review->update([
            'helpful_votes' => array_values($helpfulVotes),
            'helpful_count' => count($helpfulVotes)
        ]);

        return response()->json([
            'helpful_count' => count($helpfulVotes),
            'user_voted' => in_array($userId, $helpfulVotes)
        ]);
    }

    /**
     * Raporteaza un review
     */
    public function report(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'required|string|in:inappropriate,fake,spam,offensive',
            'details' => 'nullable|string|max:500'
        ]);

        // Aici ar fi logica pentru salvarea raportului
        // Pentru demo, doar logam
        \Log::info("Review {$review->id} reported by user " . Auth::id() . " for: {$request->reason}");

        return redirect()->back()->with('success', 'Review-ul a fost raportat. Multumim!');
    }

    /**
     * Obtine distributia rating-urilor pentru un specialist
     */
    private function getRatingDistribution($specialistId)
    {
        $distribution = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $count = Review::where('specialist_id', $specialistId)
                          ->approved()
                          ->where('rating', $i)
                          ->count();
            
            $distribution[$i] = $count;
        }

        return $distribution;
    }

    /**
     * Export reviews ca CSV (pentru specialisti)
     */
    public function exportReviews()
    {
        $specialist = Auth::user();
        
        $reviews = $specialist->reviews()
                             ->approved()
                             ->with(['appointment.service', 'user'])
                             ->get();

        $csvData = [];
        $csvData[] = [
            'Data',
            'Client', 
            'Serviciu',
            'Rating General',
            'Calitate Serviciu',
            'Punctualitate', 
            'Curatenie',
            'Comunicare',
            'Raport Calitate/Pret',
            'Recomanda',
            'Comentariu'
        ];

        foreach ($reviews as $review) {
            $csvData[] = [
                $review->created_at->format('d/m/Y'),
                $review->user->name,
                $review->appointment->service->name,
                $review->rating,
                $review->service_quality,
                $review->punctuality,
                $review->cleanliness,
                $review->communication,
                $review->value_for_money,
                $review->would_recommend ? 'Da' : 'Nu',
                $review->comment
            ];
        }

        $filename = 'reviews_' . $specialist->name . '_' . now()->format('Y_m_d') . '.csv';
        
        return response()->streamDownload(function() use ($csvData) {
            $file = fopen('php://output', 'w');
            
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
    
    /**
     * Afișează review-urile clientului autentificat
     */
    public function clientReviews()
    {
        $user = auth()->user();
        
        $reviews = Review::whereHas('appointment', function($query) use ($user) {
                $query->where('client_email', $user->email);
            })
            ->with(['appointment.specialist', 'appointment.service'])
            ->latest()
            ->paginate(10);
        
        return view('client.reviews', compact('reviews'));
    }

    /**
     * Afișează formular review prin token (public, fără autentificare)
     */
    public function showByToken($token)
    {
        $appointment = Appointment::where('review_token', $token)
            ->where('status', 'completed')
            ->with(['specialist', 'service'])
            ->firstOrFail();

        // Verifică dacă nu există deja un review
        if ($appointment->review) {
            return view('reviews.already-submitted', ['appointment' => $appointment]);
        }

        return view('reviews.create-by-token', [
            'appointment' => $appointment,
            'token' => $token
        ]);
    }

    /**
     * Salvează review prin token (public, fără autentificare)
     */
    public function storeByToken(Request $request, $token)
    {
        $appointment = Appointment::where('review_token', $token)
            ->where('status', 'completed')
            ->firstOrFail();

        // Verifică dacă nu există deja un review
        if ($appointment->review) {
            return redirect()->route('review.token', $token)
                ->with('error', 'Ai dat deja un review pentru această programare.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'service_quality_rating' => 'nullable|integer|min:1|max:5',
            'punctuality_rating' => 'nullable|integer|min:1|max:5',
            'cleanliness_rating' => 'nullable|integer|min:1|max:5',
            'overall_experience' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = new Review([
            'appointment_id' => $appointment->id,
            'specialist_id' => $appointment->specialist_id,
            'client_name' => $appointment->client_name,
            'rating' => $request->rating,
            'service_quality_rating' => $request->service_quality_rating ?? $request->rating,
            'punctuality_rating' => $request->punctuality_rating ?? $request->rating,
            'cleanliness_rating' => $request->cleanliness_rating ?? $request->rating,
            'overall_experience' => $request->overall_experience ?? $request->rating,
            'comment' => $request->comment,
            'photos' => null,
            'is_approved' => true
        ]);

        $review->save();

        // Trimite notificare SMS către specialist despre review-ul primit
        try {
            $smsService = app(\App\Services\SmsService::class);
            $specialist = $appointment->specialist;
            
            if ($smsService->isEnabled() && $specialist) {
                $result = $smsService->notifySpecialistReview($review, $specialist);
                \Log::info("Specialist review notification SMS (via token)", [
                    'review_id' => $review->id,
                    'specialist_id' => $specialist->id,
                    'result' => $result ? 'SUCCESS' : 'FAILED'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send review notification SMS (via token)", [
                'review_id' => $review->id,
                'error' => $e->getMessage()
            ]);
        }

        return view('reviews.thank-you', compact('appointment', 'review'));
    }
}