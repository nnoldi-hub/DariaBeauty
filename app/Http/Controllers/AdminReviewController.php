<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::query()
            ->with(['specialist', 'appointment.service'])
            ->latest();

        if ($request->filled('approved')) {
            $query->where('is_approved', $request->approved === '1');
        }
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === '1');
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('client_name', 'like', "%$q%")
                    ->orWhere('comment', 'like', "%$q%");
            });
        }

    $reviews = $query->paginate(20);
    $reviews->appends($request->query());

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['specialist', 'appointment.service']);
        return view('admin.reviews.show', compact('review'));
    }
    
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Review-ul a fost aprobat cu succes!');
    }
    
    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);
        return redirect()->back()->with('success', 'Review-ul a fost respins.');
    }
    
    public function respond(Request $request, Review $review)
    {
        $request->validate([
            'specialist_response' => 'required|string|max:1000',
        ]);
        
        $review->update([
            'specialist_response' => $request->specialist_response,
        ]);
        
        return redirect()->back()->with('success', 'Răspunsul a fost salvat cu succes!');
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'is_approved' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'specialist_response' => 'nullable|string',
        ]);

        // Checkboxes may be missing; map explicitly if present
        if ($request->has('is_approved')) {
            $review->is_approved = (bool)$request->boolean('is_approved');
        }
        if ($request->has('is_featured')) {
            $review->is_featured = (bool)$request->boolean('is_featured');
        }
        if (array_key_exists('specialist_response', $data)) {
            $review->specialist_response = $data['specialist_response'];
        }
        $review->save();

        return back()->with('success', 'Review actualizat.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review sters.');
    }
}
