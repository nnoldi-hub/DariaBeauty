@extends('layout')

@section('title', 'Cauta Specialisti - DariaBeauty')

@section('content')
<div class="search-page">
    <!-- Hero Section cu Search -->
    <section class="search-hero" style="background: linear-gradient(135deg, #D4AF37 0%, #8B6914 100%); padding: 80px 0;">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-white mb-3">Găsește Specialistul Perfect</h1>
                <p class="lead text-white opacity-75 mb-5">Servicii profesionale de frumusețe la domiciliul tău</p>
            </div>

            <!-- Compact Search Bar -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="{{ route('search') }}" method="GET" class="search-form-compact bg-white rounded-pill shadow-lg p-2">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <select name="sub_brand" class="form-select border-0 rounded-pill">
                                    <option value="">Toate serviciile</option>
                                    <option value="dariaNails" {{ request('sub_brand') == 'dariaNails' ? 'selected' : '' }}>dariaNails</option>
                                    <option value="dariaHair" {{ request('sub_brand') == 'dariaHair' ? 'selected' : '' }}>dariaHair</option>
                                    <option value="dariaGlow" {{ request('sub_brand') == 'dariaGlow' ? 'selected' : '' }}>dariaGlow</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="zone" class="form-select border-0 rounded-pill">
                                    <option value="">Toate zonele</option>
                                    <option value="Sector 1" {{ request('zone') == 'Sector 1' ? 'selected' : '' }}>Sector 1</option>
                                    <option value="Sector 2" {{ request('zone') == 'Sector 2' ? 'selected' : '' }}>Sector 2</option>
                                    <option value="Sector 3" {{ request('zone') == 'Sector 3' ? 'selected' : '' }}>Sector 3</option>
                                    <option value="Sector 4" {{ request('zone') == 'Sector 4' ? 'selected' : '' }}>Sector 4</option>
                                    <option value="Sector 5" {{ request('zone') == 'Sector 5' ? 'selected' : '' }}>Sector 5</option>
                                    <option value="Sector 6" {{ request('zone') == 'Sector 6' ? 'selected' : '' }}>Sector 6</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="min_rating" class="form-select border-0 rounded-pill">
                                    <option value="">Orice rating</option>
                                    <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ stele</option>
                                    <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ stele</option>
                                    <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>4.5+ stele</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill" style="background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%); border: none; font-weight: 600;">
                                    <i class="fas fa-search me-2"></i>Caută
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Filtere pe o linie cu View Toggle -->
    <section class="filters-bar py-3 bg-white shadow-sm sticky-top" style="top: 70px; z-index: 999;">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <!-- Left: Results Count & Active Filters -->
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="fw-semibold text-dark">
                        <i class="fas fa-users text-primary me-2"></i>{{ $specialists->total() }} specialiști
                    </span>
                    
                    @if(request()->hasAny(['sub_brand', 'zone', 'min_rating']))
                    <div class="d-flex gap-2 flex-wrap">
                        @if(request('sub_brand'))
                        <span class="badge rounded-pill" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; padding: 6px 12px;">
                            {{ $subBrands[request('sub_brand')] }}
                            <a href="{{ request()->fullUrlWithQuery(['sub_brand' => null]) }}" class="text-decoration-none ms-1" style="color: inherit;">×</a>
                        </span>
                        @endif
                        
                        @if(request('zone'))
                        <span class="badge rounded-pill bg-info bg-opacity-10 text-info" style="padding: 6px 12px;">
                            {{ request('zone') }}
                            <a href="{{ request()->fullUrlWithQuery(['zone' => null]) }}" class="text-decoration-none ms-1" style="color: inherit;">×</a>
                        </span>
                        @endif
                        
                        @if(request('min_rating'))
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success" style="padding: 6px 12px;">
                            {{ request('min_rating') }}+ ⭐
                            <a href="{{ request()->fullUrlWithQuery(['min_rating' => null]) }}" class="text-decoration-none ms-1" style="color: inherit;">×</a>
                        </span>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Right: View Toggle & Sort -->
                <div class="d-flex align-items-center gap-3">
                    <!-- View Toggle -->
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary view-toggle active" data-view="grid" onclick="toggleView('grid')">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary view-toggle" data-view="list" onclick="toggleView('list')">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>

                    <!-- Sort -->
                    <select name="sort" onchange="updateSort(this.value)" class="form-select form-select-sm" style="width: auto; min-width: 180px;">
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Cel mai bine cotat</option>
                        <option value="reviews" {{ request('sort') == 'reviews' ? 'selected' : '' }}>Cele mai multe review-uri</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nume (A-Z)</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Cei mai noi</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Specialists Display (Grid/List) -->
    <section class="specialists-section py-5">
        <div class="container">
            @if($specialists->count() > 0)
            
            <!-- Grid View -->
            <div id="gridView" class="row g-4">
                @foreach($specialists as $specialist)
                <div class="col-md-6 col-lg-4">
                    <div class="specialist-card-compact bg-white rounded-4 shadow-sm overflow-hidden h-100" 
                         style="transition: all 0.3s ease; border: 1px solid #f0f0f0;">
                        
                        <!-- Image Section - Compact -->
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            @if($specialist->profile_picture)
                            <img src="{{ asset('storage/' . $specialist->profile_picture) }}" 
                                 alt="{{ $specialist->name }}" 
                                 class="w-100 h-100 object-fit-cover" style="transition: transform 0.3s ease;">
                            @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                                 style="background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(139,105,20,0.1) 100%);">
                                <i class="fas fa-user-circle" style="font-size: 4rem; color: #D4AF37;"></i>
                            </div>
                            @endif
                            
                            <!-- Badges Overlay -->
                            <div class="position-absolute top-0 start-0 end-0 p-2 d-flex justify-content-between align-items-start">
                                @if($specialist->sub_brand)
                                <span class="badge rounded-pill px-3 py-2 text-white fw-semibold" 
                                      style="background: {{ $subBrandColors[$specialist->sub_brand] ?? '#D4AF37' }}; font-size: 0.7rem;">
                                    {{ $subBrands[$specialist->sub_brand] ?? 'DariaBeauty' }}
                                </span>
                                @endif
                                
                                <span class="badge bg-white text-warning fw-bold px-3 py-2 shadow-sm">
                                    ⭐ {{ number_format($specialist->average_rating, 1) }}
                                </span>
                            </div>
                        </div>

                        <!-- Content Section - Compact -->
                        <div class="p-3">
                            <h5 class="fw-bold mb-2" style="font-size: 1.1rem;">{{ $specialist->name }}</h5>
                            
                            <!-- Rating -->
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="text-warning" style="font-size: 0.85rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($specialist->average_rating))
                                            ★
                                        @elseif($i - 0.5 <= $specialist->average_rating)
                                            ⯨
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">({{ $specialist->reviews->count() }})</small>
                            </div>

                            <!-- Location -->
                            @if($specialist->coverage_area)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                    @php
                                        $coverage = is_array($specialist->coverage_area) 
                                            ? implode(', ', array_slice($specialist->coverage_area, 0, 2)) 
                                            : $specialist->coverage_area;
                                    @endphp
                                    {{ Str::limit($coverage, 30) }}
                                </small>
                            </div>
                            @endif

                            <!-- Services Tags - Compact -->
                            @if($specialist->services && $specialist->services->count() > 0)
                            <div class="mb-3">
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($specialist->services->take(2) as $service)
                                    <span class="badge bg-light text-dark border" style="font-size: 0.7rem; font-weight: 500;">
                                        {{ strlen($service->name) > 15 ? substr($service->name, 0, 15) . '...' : $service->name }}
                                    </span>
                                    @endforeach
                                    @if($specialist->services->count() > 2)
                                    <span class="badge" style="background: rgba(212,175,55,0.2); color: #D4AF37; font-size: 0.7rem;">
                                        +{{ $specialist->services->count() - 2 }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Actions - Compact -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('specialist.profile', $specialist->slug ?? $specialist->id) }}" 
                                   class="btn btn-sm btn-outline-primary flex-fill" style="font-size: 0.85rem;">
                                    Profil
                                </a>
                                <a href="{{ route('booking.landing') }}?specialist={{ $specialist->id }}" 
                                   class="btn btn-sm flex-fill text-white" 
                                   style="background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%); border: none; font-size: 0.85rem;">
                                    Rezervă
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- List View (Hidden by default) -->
            <div id="listView" class="d-none">
                @foreach($specialists as $specialist)
                <div class="specialist-card-list bg-white rounded-3 shadow-sm mb-3 p-3" 
                     style="border: 1px solid #f0f0f0; transition: all 0.3s ease;">
                    <div class="row align-items-center g-3">
                        <!-- Image -->
                        <div class="col-auto">
                            <div class="position-relative" style="width: 80px; height: 80px; border-radius: 12px; overflow: hidden;">
                                @if($specialist->profile_picture)
                                <img src="{{ asset('storage/' . $specialist->profile_picture) }}" 
                                     alt="{{ $specialist->name }}" 
                                     class="w-100 h-100 object-fit-cover">
                                @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                                     style="background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(139,105,20,0.1) 100%);">
                                    <i class="fas fa-user-circle" style="font-size: 2rem; color: #D4AF37;"></i>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="col">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $specialist->name }}</h5>
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="text-warning" style="font-size: 0.9rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($specialist->average_rating))
                                                    ★
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                            <span class="text-dark ms-1">{{ number_format($specialist->average_rating, 1) }}</span>
                                        </div>
                                        <small class="text-muted">({{ $specialist->reviews->count() }} review-uri)</small>
                                        @if($specialist->sub_brand)
                                        <span class="badge rounded-pill px-2 text-white" 
                                              style="background: {{ $subBrandColors[$specialist->sub_brand] ?? '#D4AF37' }}; font-size: 0.7rem;">
                                            {{ $subBrands[$specialist->sub_brand] }}
                                        </span>
                                        @endif
                                    </div>
                                    
                                    @if($specialist->coverage_area)
                                    <div class="text-muted mb-2" style="font-size: 0.9rem;">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                        @php
                                            $coverage = is_array($specialist->coverage_area) 
                                                ? implode(', ', $specialist->coverage_area) 
                                                : $specialist->coverage_area;
                                        @endphp
                                        {{ $coverage }}
                                    </div>
                                    @endif

                                    @if($specialist->services && $specialist->services->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($specialist->services->take(4) as $service)
                                        <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                                            {{ $service->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="d-flex gap-2">
                                    <a href="{{ route('specialist.profile', $specialist->slug ?? $specialist->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        Vezi Profil
                                    </a>
                                    <a href="{{ route('booking.landing') }}?specialist={{ $specialist->id }}" 
                                       class="btn btn-sm text-white" 
                                       style="background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%); border: none;">
                                        Programează-te
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $specialists->links() }}
            </div>

            @else
            <!-- No Results -->
            <div class="text-center py-5">
                <i class="fas fa-search text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                <h4 class="mt-3 text-muted">Nu am găsit specialiști</h4>
                <p class="text-muted mb-4">Încearcă să modifici filtrele de căutare</p>
                <a href="{{ route('search') }}" class="btn btn-primary">Resetează căutarea</a>
            </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section text-white py-5" style="background: linear-gradient(135deg, #D4AF37 0%, #8B6914 100%);">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-3">Ești specialist în frumusețe?</h2>
            <p class="lead mb-4">Alătură-te echipei DariaBeauty și ajunge la mai mulți clienți</p>
            <a href="/inregistrare-specialist" class="btn btn-light btn-lg px-5" 
               style="color: #D4AF37; font-weight: 600;">
                Devino Specialist DariaBeauty
            </a>
        </div>
    </section>
</div>

<script>
function updateSort(value) {
    const url = new URL(window.location);
    url.searchParams.set('sort', value);
    window.location.href = url.toString();
}

function toggleView(view) {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const buttons = document.querySelectorAll('.view-toggle');
    
    if (view === 'grid') {
        gridView.classList.remove('d-none');
        listView.classList.add('d-none');
    } else {
        gridView.classList.add('d-none');
        listView.classList.remove('d-none');
    }
    
    // Update active button
    buttons.forEach(btn => {
        if (btn.dataset.view === view) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    
    // Save preference
    localStorage.setItem('specialistViewMode', view);
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('specialistViewMode');
    if (savedView) {
        toggleView(savedView);
    }
});
</script>

<style>
.search-form-compact {
    transition: all 0.3s ease;
}

.search-form-compact:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.specialist-card-compact {
    transition: all 0.3s ease;
}

.specialist-card-compact:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.specialist-card-compact:hover img {
    transform: scale(1.05);
}

.specialist-card-list {
    transition: all 0.3s ease;
}

.specialist-card-list:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.08) !important;
    border-color: #D4AF37 !important;
}

.view-toggle {
    transition: all 0.2s ease;
}

.view-toggle.active {
    background-color: #D4AF37;
    color: white !important;
    border-color: #D4AF37 !important;
}

.filters-bar {
    backdrop-filter: blur(10px);
}

@media (max-width: 768px) {
    .specialist-card-list .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .specialist-card-list .col {
        width: 100%;
    }
}

.sub-brand-badge {
    backdrop-filter: blur(10px);
}

.service-tag {
    font-size: 0.75rem;
    white-space: nowrap;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
}

@media (max-width: 768px) {
    .search-form {
        padding: 1rem;
    }
    
    .search-form .grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filters-left,
    .filters-right {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection