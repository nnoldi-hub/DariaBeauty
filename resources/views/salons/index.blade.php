@extends('layout')

@section('title', 'Saloane de Frumusețe - DariaBeauty')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">
            <i class="fas fa-store text-warning"></i> Saloane de Frumusețe
        </h1>
        <p class="lead text-muted">
            Descoperă cele mai apreciate saloane de frumusețe din rețeaua DariaBeauty
        </p>
    </div>

    <!-- Featured Salons -->
    @if($featuredSalons->count() > 0)
        <div class="mb-5">
            <h3 class="mb-4">
                <i class="fas fa-star text-warning"></i> Saloane de top
            </h3>
            <div class="row g-4">
                @foreach($featuredSalons as $salon)
                    <div class="col-md-4">
                        <div class="card h-100 border-warning shadow-lg" style="border-width: 3px;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-crown text-warning"></i> {{ $salon->name }}
                                    </h5>
                                    @if($salon->reviews_count > 0)
                                        <span class="badge bg-warning text-dark fs-6">
                                            <i class="fas fa-star"></i> {{ number_format($salon->reviews_avg_rating, 1) }}
                                        </span>
                                    @endif
                                </div>

                                @if($salon->salon_address)
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-map-marker-alt text-danger"></i> 
                                        {{ $salon->salon_address }}
                                    </p>
                                @endif

                                <div class="d-flex gap-2 mb-3">
                                    @if($salon->salon_specialists_count > 0)
                                        <span class="badge bg-info">
                                            <i class="fas fa-users"></i> {{ $salon->salon_specialists_count }} specialiști
                                        </span>
                                    @endif
                                    @if($salon->reviews_count > 0)
                                        <span class="badge bg-success">
                                            <i class="fas fa-comments"></i> {{ $salon->reviews_count }} recenzii
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('salons.show', $salon->id) }}" 
                                       class="btn btn-warning flex-fill btn-sm text-white">
                                        <i class="fas fa-eye"></i> Vezi detalii
                                    </a>
                                    @if($salon->phone)
                                        <a href="tel:{{ $salon->phone }}" 
                                           class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('salons.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Locație</label>
                        <select name="location" class="form-select">
                            <option value="">Toate locațiile</option>
                            <option value="București" {{ request('location') == 'București' ? 'selected' : '' }}>București</option>
                            <option value="Sector 1" {{ request('location') == 'Sector 1' ? 'selected' : '' }}>Sector 1</option>
                            <option value="Sector 2" {{ request('location') == 'Sector 2' ? 'selected' : '' }}>Sector 2</option>
                            <option value="Sector 3" {{ request('location') == 'Sector 3' ? 'selected' : '' }}>Sector 3</option>
                            <option value="Sector 4" {{ request('location') == 'Sector 4' ? 'selected' : '' }}>Sector 4</option>
                            <option value="Sector 5" {{ request('location') == 'Sector 5' ? 'selected' : '' }}>Sector 5</option>
                            <option value="Sector 6" {{ request('location') == 'Sector 6' ? 'selected' : '' }}>Sector 6</option>
                            <option value="Cluj" {{ request('location') == 'Cluj' ? 'selected' : '' }}>Cluj-Napoca</option>
                            <option value="Timișoara" {{ request('location') == 'Timișoara' ? 'selected' : '' }}>Timișoara</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Specializare</label>
                        <select name="sub_brand" class="form-select">
                            <option value="">Toate</option>
                            <option value="dariaNails" {{ request('sub_brand') == 'dariaNails' ? 'selected' : '' }}>DariaNails (Manichiură)</option>
                            <option value="dariaHair" {{ request('sub_brand') == 'dariaHair' ? 'selected' : '' }}>DariaHair (Coafură)</option>
                            <option value="dariaGlow" {{ request('sub_brand') == 'dariaGlow' ? 'selected' : '' }}>DariaGlow (Skincare)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sortează după</label>
                        <select name="sort" class="form-select">
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Cel mai bine cotat</option>
                            <option value="specialists" {{ request('sort') == 'specialists' ? 'selected' : '' }}>Cei mai mulți specialiști</option>
                            <option value="reviews" {{ request('sort') == 'reviews' ? 'selected' : '' }}>Cele mai multe recenzii</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Cel mai recent adăugate</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Aplică filtre
                        </button>
                        <a href="{{ route('salons.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Resetează
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Salons Grid -->
    @if($salons->count() > 0)
        <div class="row g-4 mb-5">
            @foreach($salons as $salon)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm hover-shadow" style="transition: all 0.3s ease;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-building text-warning"></i> {{ $salon->name }}
                                </h5>
                                @if($salon->reviews_count > 0)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star"></i> {{ number_format($salon->reviews_avg_rating, 1) }}
                                    </span>
                                @endif
                            </div>

                            @if($salon->salon_address)
                                <p class="text-muted mb-2 small">
                                    <i class="fas fa-map-marker-alt text-danger"></i> 
                                    {{ Str::limit($salon->salon_address, 50) }}
                                </p>
                            @endif

                            @if($salon->phone)
                                <p class="text-muted mb-2 small">
                                    <i class="fas fa-phone text-success"></i> 
                                    {{ $salon->phone }}
                                </p>
                            @endif

                            @if($salon->description)
                                <p class="text-muted small mb-3">
                                    {{ Str::limit($salon->description, 80) }}
                                </p>
                            @endif

                            <div class="d-flex gap-2 mb-3 flex-wrap">
                                @if($salon->sub_brands && count($salon->sub_brands) > 0)
                                    @foreach($salon->sub_brands as $brand)
                                        <span class="badge {{ $brand === 'dariaNails' ? 'bg-danger' : ($brand === 'dariaHair' ? 'bg-primary' : 'bg-success') }}">
                                            {{ $brand === 'dariaNails' ? 'Manichiură' : ($brand === 'dariaHair' ? 'Coafură' : 'Makeup & Skin') }}
                                        </span>
                                    @endforeach
                                @elseif($salon->sub_brand)
                                    <span class="badge bg-secondary">{{ $salon->sub_brand }}</span>
                                @endif
                                @if($salon->salon_specialists_count > 0)
                                    <span class="badge bg-info">
                                        <i class="fas fa-users"></i> {{ $salon->salon_specialists_count }} specialiști
                                    </span>
                                @endif
                                @if($salon->reviews_count > 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-comments"></i> {{ $salon->reviews_count }} recenzii
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('salons.show', $salon->id) }}" 
                                   class="btn btn-primary flex-fill btn-sm">
                                    <i class="fas fa-eye"></i> Vezi detalii
                                </a>
                                @if($salon->phone)
                                    <a href="tel:{{ $salon->phone }}" 
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $salons->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-store fa-4x text-muted mb-3"></i>
            <h4>Nu am găsit saloane</h4>
            <p class="text-muted">Încearcă să modifici filtrele de căutare</p>
        </div>
    @endif
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>
@endsection
