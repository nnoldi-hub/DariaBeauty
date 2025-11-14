@extends('layout')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('specialist.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Reviews</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Rating Mediu</h5>
                            <p class="card-text display-4">
                                {{ number_format($averageRating ?? 0, 1) }}
                                <small class="text-muted">/5</small>
                            </p>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($averageRating ?? 0))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= ($averageRating ?? 0))
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h5 class="card-title text-success">Total Reviews</h5>
                            <p class="card-text display-6">{{ $totalReviews ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h5 class="card-title text-warning">În Așteptare</h5>
                            <p class="card-text display-6">{{ $pendingReviews ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h5 class="card-title text-info">Aprobate</h5>
                            <p class="card-text display-6">{{ $approvedReviews ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            @forelse($reviews as $review)
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $review->client_name }}</strong>
                                <span class="text-muted">• {{ $review->created_at->format('d.m.Y H:i') }}</span>
                                @if($review->service)
                                    <span class="text-muted">• {{ $review->service->name }}</span>
                                @endif
                            </div>
                            <div>
                                @if($review->is_approved)
                                    <span class="badge bg-success">Aprobat</span>
                                @else
                                    <span class="badge bg-warning">În Așteptare</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Rating -->
                        <div class="mb-2">
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span class="text-muted ms-2">{{ $review->rating }}/5</span>
                            </div>
                        </div>

                        <!-- Review Text -->
                        <p class="card-text">{{ $review->review }}</p>

                        <!-- Specialist Response -->
                        @if($review->specialist_response)
                            <div class="alert alert-info mt-3">
                                <strong><i class="fas fa-reply"></i> Răspunsul Tău:</strong>
                                <p class="mb-0 mt-2">{{ $review->specialist_response }}</p>
                                <small class="text-muted">
                                    Răspuns dat la: {{ $review->specialist_response_date ? \Carbon\Carbon::parse($review->specialist_response_date)->format('d.m.Y H:i') : 'N/A' }}
                                </small>
                            </div>
                        @else
                            <!-- Response Form -->
                            <div class="mt-3">
                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#responseForm{{ $review->id }}">
                                    <i class="fas fa-reply"></i> Răspunde la Review
                                </button>
                                
                                <div class="collapse mt-3" id="responseForm{{ $review->id }}">
                                    <form method="POST" action="{{ route('specialist.reviews.respond', $review->id) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="response{{ $review->id }}" class="form-label">Răspunsul Tău</label>
                                            <textarea class="form-control" id="response{{ $review->id }}" 
                                                      name="specialist_response" rows="3" required 
                                                      placeholder="Scrie un răspuns profesional și prietenos..."></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary btn-sm me-2" 
                                                    data-bs-toggle="collapse" data-bs-target="#responseForm{{ $review->id }}">
                                                Anulează
                                            </button>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-paper-plane"></i> Trimite Răspuns
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nu aveți reviews încă.
                </div>
            @endforelse

            @if(isset($reviews) && method_exists($reviews, 'links'))
                <div class="mt-3">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
