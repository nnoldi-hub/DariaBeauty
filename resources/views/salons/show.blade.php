@extends('layout')

@section('title', $salon->name . ' - DariaBeauty')

@section('content')
<div class="container my-5">
    <!-- Header Salon -->
    <div class="card mb-4 shadow-lg border-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="card-body p-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-building fa-3x text-warning me-3"></i>
                        <div>
                            <h1 class="mb-1">{{ $salon->name }}</h1>
                            @if($salon->sub_brands && count($salon->sub_brands) > 0)
                                @foreach($salon->sub_brands as $brand)
                                    <span class="badge {{ $brand === 'dariaNails' ? 'bg-danger' : ($brand === 'dariaHair' ? 'bg-primary' : 'bg-success') }} me-1">
                                        {{ $brand === 'dariaNails' ? 'Manichiură' : ($brand === 'dariaHair' ? 'Coafură' : 'Makeup & Skin') }}
                                    </span>
                                @endforeach
                            @elseif($salon->sub_brand)
                                <span class="badge bg-secondary">{{ $salon->sub_brand }}</span>
                            @endif
                        </div>
                    </div>

                    @if($salon->description)
                        <p class="lead mb-3">{{ $salon->description }}</p>
                    @endif

                    <div class="d-flex gap-3 flex-wrap">
                        @if($salon->salon_address)
                            <div>
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                <strong>{{ $salon->salon_address }}</strong>
                            </div>
                        @endif
                        @if($salon->phone)
                            <div>
                                <i class="fas fa-phone text-success me-2"></i>
                                <a href="tel:{{ $salon->phone }}">{{ $salon->phone }}</a>
                            </div>
                        @endif
                    </div>

                    <!-- Social Media -->
                    @if($salon->instagram || $salon->facebook || $salon->tiktok)
                        <div class="mt-3">
                            @if($salon->instagram)
                                <a href="https://instagram.com/{{ $salon->instagram }}" target="_blank" class="btn btn-sm btn-outline-danger me-2">
                                    <i class="fab fa-instagram"></i> Instagram
                                </a>
                            @endif
                            @if($salon->facebook)
                                <a href="https://facebook.com/{{ $salon->facebook }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fab fa-facebook"></i> Facebook
                                </a>
                            @endif
                            @if($salon->tiktok)
                                <a href="https://tiktok.com/@{{ $salon->tiktok }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    <i class="fab fa-tiktok"></i> TikTok
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <!-- Statistici -->
                    <div class="card border-warning" style="border-width: 3px;">
                        <div class="card-body text-center">
                            @if($stats['average_rating'] > 0)
                                <div class="mb-3">
                                    <h2 class="mb-0 text-warning">
                                        <i class="fas fa-star"></i> {{ number_format($stats['average_rating'], 1) }}
                                    </h2>
                                    <small class="text-muted">din {{ $stats['total_reviews'] }} recenzii</small>
                                </div>
                            @endif

                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <h4 class="mb-0 text-primary">{{ $stats['total_specialists'] }}</h4>
                                    <small class="text-muted">Specialiști</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="mb-0 text-success">{{ $stats['member_since'] }}</h4>
                                    <small class="text-muted">Membru din</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Specialiștii din salon -->
    @if($specialists->count() > 0)
        <div class="mb-5">
            <h3 class="mb-4">
                <i class="fas fa-users text-primary"></i> Specialiștii noștri ({{ $specialists->count() }})
            </h3>
            <div class="row g-4">
                @foreach($specialists as $specialist)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm hover-shadow">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    @if($specialist->profile_image)
                                        <img src="{{ asset('storage/' . $specialist->profile_image) }}" 
                                             alt="{{ $specialist->name }}"
                                             class="rounded-circle me-3"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-user fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ $specialist->name }}</h5>
                                        @if($specialist->sub_brand)
                                            <span class="badge bg-secondary small">{{ $specialist->sub_brand }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if($specialist->reviews_count > 0)
                                    <div class="mb-3">
                                        <span class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($specialist->reviews_avg_rating))
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $specialist->reviews_avg_rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="text-muted small">
                                            ({{ $specialist->reviews_count }} recenzii)
                                        </span>
                                    </div>
                                @endif

                                <div class="d-flex gap-2">
                                    <a href="{{ route('specialists.show', $specialist->slug) }}" 
                                       class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="fas fa-eye"></i> Vezi profil
                                    </a>
                                    <a href="{{ route('appointments.create', ['specialist' => $specialist->id]) }}" 
                                       class="btn btn-sm flex-fill text-white"
                                       style="background: linear-gradient(135deg, #D4AF37, #C5A028);">
                                        <i class="fas fa-calendar-check"></i> Programează-te
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Acest salon nu are momentan specialiști înregistrați pe platformă.
        </div>
    @endif

    <!-- Recenzii -->
    @if($reviews->count() > 0)
        <div class="mb-5">
            <h3 class="mb-4">
                <i class="fas fa-comments text-success"></i> Recenzii ({{ $stats['total_reviews'] }})
            </h3>
            <div class="row">
                @foreach($reviews as $review)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>{{ $review->client_name ?? 'Client' }}</strong>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                @if($review->comment)
                                    <p class="mb-0">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    @endif

    <!-- CTA -->
    <div class="text-center py-5 bg-light rounded">
        <h4 class="mb-3">Programează-te acum la {{ $salon->name }}</h4>
        <p class="text-muted mb-4">Alege unul dintre specialiștii noștri și bucură-te de servicii premium</p>
        @if($salon->phone)
            <a href="tel:{{ $salon->phone }}" class="btn btn-success btn-lg me-3">
                <i class="fas fa-phone"></i> Sună acum
            </a>
        @endif
        <a href="{{ route('salons.index') }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-arrow-left"></i> Înapoi la saloane
        </a>
    </div>
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    transition: all 0.3s ease;
}
</style>
@endsection
