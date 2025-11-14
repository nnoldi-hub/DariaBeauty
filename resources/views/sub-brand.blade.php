@extends('layout')

@section('title', $subBrandInfo['title'] . ' - DariaBeauty')

@section('content')
<style>
    .sub-brand-hero {
        background: linear-gradient(135deg, {{ $subBrandInfo['color'] }}15 0%, {{ $subBrandInfo['color'] }}05 100%);
        padding: 120px 0 60px;
    }
    
    .sub-brand-badge {
        display: inline-block;
        background: {{ $subBrandInfo['color'] }};
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .service-category-title {
        color: {{ $subBrandInfo['color'] }};
        border-bottom: 2px solid {{ $subBrandInfo['color'] }};
        padding-bottom: 10px;
        margin-bottom: 25px;
    }
    
    .service-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .service-card-img {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    
    .service-card-body {
        padding: 20px;
    }
    
    .service-price {
        color: {{ $subBrandInfo['color'] }};
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .specialist-tag {
        display: inline-block;
        background: #f8f9fa;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .feature-list {
        list-style: none;
        padding: 0;
    }
    
    .feature-list li {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    
    .feature-list li:last-child {
        border-bottom: none;
    }
    
    .feature-list li i {
        color: {{ $subBrandInfo['color'] }};
        margin-right: 10px;
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 40px;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        aspect-ratio: 1;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover {
        transform: scale(1.05);
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .specialist-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .specialist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .specialist-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid {{ $subBrandInfo['color'] }};
    }
    
    .rating-stars {
        color: #ffc107;
    }
    
    .btn-book {
        background: {{ $subBrandInfo['color'] }};
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-book:hover {
        background: {{ $subBrandInfo['color'] }}dd;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px {{ $subBrandInfo['color'] }}50;
    }
</style>

<!-- Hero Section - Compact Modern -->
<section class="sub-brand-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="sub-brand-badge">
                    <i class="{{ $subBrandInfo['icon'] }} me-2"></i>{{ $subBrandInfo['name'] }}
                </span>
                <h1 class="display-5 fw-bold mt-3">{{ $subBrandInfo['title'] }}</h1>
                <p class="mt-3 text-muted">{{ $subBrandInfo['description'] }}</p>
                <div class="mt-4">
                    <a href="{{ route('booking.landing') }}" class="btn btn-book btn-lg px-5 rounded-pill">
                        <i class="fas fa-calendar-check me-2"></i>Programează-te
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    @foreach($subBrandInfo['services'] as $feature)
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle me-2" style="color: {{ $subBrandInfo['color'] }};"></i>
                        <span>{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section - Compact -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Serviciile Noastre</h2>
        
        @forelse($services as $category => $categoryServices)
        <div class="mb-4">
            <h4 class="fw-bold mb-3" style="color: {{ $subBrandInfo['color'] }};">{{ $category }}</h4>
            <div class="row g-3">
                @foreach($categoryServices as $service)
                <div class="col-md-4 col-lg-3">
                    <div class="service-card" style="height: 100%;">
                        @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" 
                             alt="{{ $service->name }}" 
                             style="height: 160px; object-fit: cover; width: 100%;">
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="height: 160px;">
                            <i class="{{ $subBrandInfo['icon'] }} fa-2x" style="color: {{ $subBrandInfo['color'] }}40;"></i>
                        </div>
                        @endif
                        <div class="p-3">
                            <h6 class="mb-2 fw-bold" style="font-size: 0.95rem;">{{ $service->name }}</h6>
                            @if($service->description)
                            <p class="text-muted small mb-2" style="font-size: 0.85rem;">
                                {{ strlen($service->description) > 60 ? substr($service->description, 0, 60) . '...' : $service->description }}
                            </p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold" style="color: {{ $subBrandInfo['color'] }}; font-size: 1.1rem;">
                                    {{ $service->formatted_price }}
                                </span>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>{{ $service->formatted_duration }}
                                </small>
                            </div>
                            @if($service->specialist)
                            <div class="mb-2">
                                <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                                    <i class="fas fa-user me-1"></i>{{ $service->specialist->name }}
                                </span>
                            </div>
                            @endif
                            @if($service->is_mobile)
                            <div class="mb-2">
                                <span class="badge bg-success" style="font-size: 0.75rem;">
                                    <i class="fas fa-home me-1"></i>La domiciliu
                                </span>
                            </div>
                            @endif
                            <a href="{{ route('booking.landing') }}" class="btn btn-sm w-100 rounded-pill" 
                               style="background: {{ $subBrandInfo['color'] }}; color: white; font-size: 0.85rem;">
                                <i class="fas fa-calendar-alt me-1"></i>Rezervă
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="{{ $subBrandInfo['icon'] }} fa-3x text-muted mb-3"></i>
            <p class="text-muted">Serviciile vor fi disponibile în curând.</p>
        </div>
        @endforelse
    </div>
</section>

<!-- Specialists Section -->
@if($specialists->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Specialiștii Noștri {{ $subBrandInfo['name'] }}</h2>
        <div class="row g-4">
            @foreach($specialists as $specialist)
            <div class="col-md-4">
                <div class="specialist-card text-center">
                    @if($specialist->profile_image)
                    <img src="{{ asset('storage/' . $specialist->profile_image) }}" 
                         alt="{{ $specialist->name }}" 
                         class="specialist-avatar mb-3">
                    @else
                    <div class="specialist-avatar mx-auto mb-3 bg-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-user fa-2x text-muted"></i>
                    </div>
                    @endif
                    <h5>{{ $specialist->name }}</h5>
                    @if($specialist->reviews_count > 0)
                    <div class="rating-stars mb-2">
                        @php
                            $rating = round($specialist->average_rating ?? 0);
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $rating ? '' : '-o' }}"></i>
                        @endfor
                        <span class="text-muted ms-2">({{ $specialist->reviews_count }})</span>
                    </div>
                    @endif
                    @if($specialist->bio)
                    <p class="text-muted small">{{ Str::limit($specialist->bio, 100) }}</p>
                    @endif
                    <a href="{{ route('booking.landing') }}" class="btn btn-book btn-sm mt-3">
                        <i class="fas fa-calendar-check me-1"></i>Rezervă
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @if($specialists->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $specialists->links() }}
        </div>
        @endif
    </div>
</section>
@endif

<!-- Gallery Section -->
@if($featuredGallery->count() > 0)
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Galeria {{ $subBrandInfo['name'] }}</h2>
        <p class="text-center text-muted mb-5">Descoperă transformările noastre spectaculoase</p>
        <div class="gallery-grid">
            @foreach($featuredGallery as $item)
            <div class="gallery-item">
                <img src="{{ asset('storage/' . $item->image) }}" 
                     alt="{{ $item->title ?? 'Gallery image' }}">
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('gallery') }}" class="btn btn-outline-secondary">
                <i class="fas fa-images me-2"></i>Vezi toată galeria
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, {{ $subBrandInfo['color'] }}15 0%, {{ $subBrandInfo['color'] }}05 100%);">
    <div class="container text-center">
        <h2 class="mb-4">Gata să îți transformi aspectul?</h2>
        <p class="lead text-muted mb-4">Alătură-te miilor de clienți mulțumiți care au descoperit {{ $subBrandInfo['name'] }}</p>
        <a href="{{ route('booking.landing') }}" class="btn btn-book btn-lg">
            <i class="fas fa-calendar-check me-2"></i>Programează-te acum
        </a>
    </div>
</section>

@endsection
