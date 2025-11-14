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
        if (Auth::id() !== $appointment->user_id) {
            abort(403);
        }

        // Verifica daca programarea este completata
        if ($appointment->status !== 'completed') {
            return redirect()->back()->with('error', 'Poti da review doar pentru programarile completate.');
        }

        // Verifica daca nu exista deja un review
        if ($appointment->review) {
            return redirect()->back()->with('error', 'Ai dat deja un review pentru aceasta programare.');
        }

        return view('reviews.create', compact('appointment'));
    }

    /**
     * Salveaza review-ul
     */
    public function store(Request $request, Appointment $appointment)
    {
        // Verificari de securitate
        if (Auth::id() !== $appointment->user_id) {
            abort(403);
        }

        if ($appointment->status !== 'completed') {
            return redirect()->back()->with('error', 'Poti da review doar pentru programarile completate.');
        }

        if ($appointment->review) {
            return redirect()->back()->with('error', 'Ai dat deja un review pentru aceasta programare.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'service_quality' => 'required|integer|min:1|max:5',
            'punctuality' => 'required|integer|min:1|max:5',
            'cleanliness' => 'required|integer|min:1|max:5',
            'communication' => 'required|integer|min:1|max:5',
            'value_for_money' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'would_recommend' => 'required|boolean',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('reviews', 'public');
                $photos[] = $path;
            }
        }

        $review = new Review([
            'user_id' => Auth::id(),
            'specialist_id' => $appointment->specialist_id,
            'appointment_id' => $appointment->id,
            'rating' => $request->rating,
            'service_quality' => $request->service_quality,
            'punctuality' => $request->punctuality,
            'cleanliness' => $request->cleanliness,
            'communication' => $request->communication,
            'value_for_money' => $request->value_for_money,
            'comment' => $request->comment,
            'would_recommend' => $request->would_recommend,
            'photos' => $photos,
            'is_approved' => false // Require manual approval
        ]);

        $review->save();

        return redirect()->route('appointments.show', $appointment)
                        ->with('success', 'Multumim pentru review! Va fi afisat dupa aprobare.');
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
}