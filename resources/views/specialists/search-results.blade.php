@extends('layout')

@section('title', 'Rezultate Căutare Specialiști')

@section('content')
<div class="container my-5">
    <!-- Search Summary -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h2 class="mb-4">
                <i class="fas fa-search text-primary"></i> Rezultate Căutare
            </h2>
            
            <!-- Selected Filters Display -->
            <div class="mb-3">
                <h6 class="text-muted mb-2">Criterii selectate:</h6>
                
                @if(!empty($requestedServices))
                    <div class="mb-2">
                        <strong>Servicii:</strong>
                        @foreach($requestedServices as $service)
                            <span class="badge bg-warning text-dark me-1">{{ ucfirst($service) }}</span>
                        @endforeach
                    </div>
                @endif
                
                @if($location)
                    <div class="mb-2">
                        <strong>Locație:</strong>
                        <span class="badge bg-info text-white">{{ $location }}</span>
                    </div>
                @endif
                
                @if($serviceLocation)
                    <div class="mb-2">
                        <strong>Tip serviciu:</strong>
                        <span class="badge bg-success">
                            @if($serviceLocation === 'salon')
                                La salon
                            @elseif($serviceLocation === 'home')
                                La domiciliu
                            @else
                                Oriunde
                            @endif
                        </span>
                    </div>
                @endif
            </div>
            
            <!-- Results Count -->
            <p class="text-muted mb-0">
                <i class="fas fa-user-check"></i> 
                Am găsit <strong>{{ $specialists->total() }}</strong> specialist(i)
                @if($salons->count() > 0)
                    și <strong>{{ $salons->count() }}</strong> salon(e)
                @endif
                care corespund criteriilor tale
            </p>
        </div>
    </div>

    <!-- Salons Section -->
    @if($salons->count() > 0)
        <div class="mb-5">
            <h3 class="mb-4">
                <i class="fas fa-store text-primary"></i> Saloane disponibile
            </h3>
            <div class="row g-4">
                @foreach($salons as $salon)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm hover-shadow" style="transition: all 0.3s ease; border-left: 4px solid #D4AF37;">
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
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-map-marker-alt text-danger"></i> 
                                        {{ $salon->salon_address }}
                                    </p>
                                @endif

                                @if($salon->phone)
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-phone text-success"></i> 
                                        {{ $salon->phone }}
                                    </p>
                                @endif

                                @if($salon->description)
                                    <p class="text-muted small mb-3">
                                        {{ Str::limit($salon->description, 100) }}
                                    </p>
                                @endif

                                <!-- Specialists Count -->
                                @if($salon->salon_specialists_count > 0)
                                    <div class="alert alert-info py-2 mb-3">
                                        <i class="fas fa-users"></i> 
                                        <strong>{{ $salon->salon_specialists_count }}</strong> specialiști în salon
                                    </div>
                                @endif

                                <!-- Reviews -->
                                @if($salon->reviews_count > 0)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($salon->reviews_avg_rating))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif($i - 0.5 <= $salon->reviews_avg_rating)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-muted small">
                                                ({{ $salon->reviews_count }} recenzii)
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex gap-2">
                                    @if($salon->salon_specialists_count > 0)
                                        <a href="{{ route('specialists.index') }}?salon_id={{ $salon->id }}" 
                                           class="btn flex-fill btn-sm text-white"
                                           style="background: linear-gradient(135deg, #D4AF37, #C5A028);">
                                            <i class="fas fa-users"></i> Vezi specialiștii
                                        </a>
                                    @endif
                                    
                                    @if($salon->phone)
                                        <a href="tel:{{ $salon->phone }}" 
                                           class="btn btn-success flex-fill btn-sm">
                                            <i class="fas fa-phone"></i> Sună acum
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

    <!-- Specialists Section -->
    @if($specialists->count() > 0)
        <div class="mb-4">
            <h3 class="mb-4">
                <i class="fas fa-user-tie text-primary"></i> Specialiști disponibili
            </h3>
        </div>
        <!-- Specialists Grid -->
        <div class="row g-4">
            @foreach($specialists as $specialist)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm hover-shadow position-relative" style="transition: all 0.3s ease;">
                        <!-- Match Score Badge (Top Right) -->
                        @if(!empty($requestedServices))
                            <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <span class="badge" style="background: linear-gradient(135deg, #D4AF37, #C5A028); font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                    <i class="fas fa-check-circle"></i> 
                                    {{ $specialist->match_score }}/{{ count($requestedServices) }} servicii
                                </span>
                            </div>
                        @endif
                        
                        <!-- Profile Image -->
                        <div class="position-relative">
                            @if($specialist->profile_photo)
                                <img src="{{ asset('storage/' . $specialist->profile_photo) }}" 
                                     class="card-img-top" 
                                     alt="{{ $specialist->name }}"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 250px;">
                                    <i class="fas fa-user fa-4x text-muted"></i>
                                </div>
                            @endif
                            
                            <!-- Service Location Badges (Bottom Left) -->
                            <div class="position-absolute bottom-0 start-0 m-2">
                                @if($specialist->offers_at_salon)
                                    <span class="badge bg-white text-dark me-1">
                                        <i class="fas fa-store"></i> La salon
                                    </span>
                                @endif
                                @if($specialist->offers_at_home)
                                    <span class="badge bg-white text-dark">
                                        <i class="fas fa-home"></i> La domiciliu
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <!-- Name -->
                            <h5 class="card-title mb-2">
                                <a href="{{ route('specialists.show', $specialist->id) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $specialist->name }}
                                </a>
                            </h5>
                            
                            <!-- Rating -->
                            <div class="mb-3">
                                @if($specialist->reviews_count > 0)
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($specialist->reviews_avg_rating))
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i - 0.5 <= $specialist->reviews_avg_rating)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-muted">
                                            {{ number_format($specialist->reviews_avg_rating, 1) }} 
                                            ({{ $specialist->reviews_count }} recenzii)
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted">
                                        <i class="far fa-star"></i> Fără recenzii încă
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Matched Services -->
                            @if(!empty($specialist->matched_services))
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-check-circle text-success"></i> Servicii potrivite:
                                    </h6>
                                    @foreach($specialist->matched_services as $matchedService)
                                        <span class="badge bg-success me-1 mb-1">
                                            {{ ucfirst($matchedService) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Location Info -->
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt"></i> Locație:
                                </h6>
                                @if($specialist->offers_at_salon && $specialist->salon_address)
                                    <p class="mb-0 small">
                                        <i class="fas fa-store text-primary"></i> 
                                        {{ Str::limit($specialist->salon_address, 50) }}
                                    </p>
                                @endif
                                @if($specialist->offers_at_home && $specialist->coverage_area)
                                    <p class="mb-0 small">
                                        <i class="fas fa-home text-success"></i> 
                                        Zone acoperite: 
                                        @if(is_array($specialist->coverage_area))
                                            {{ implode(', ', $specialist->coverage_area) }}
                                        @else
                                            {{ $specialist->coverage_area }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-auto d-flex gap-2">
                                <a href="{{ route('specialists.show', $specialist->id) }}" 
                                   class="btn btn-outline-primary flex-fill">
                                    <i class="fas fa-eye"></i> Vezi profilul
                                </a>
                                <a href="{{ route('appointments.create', ['specialist' => $specialist->id]) }}" 
                                   class="btn flex-fill"
                                   style="background: linear-gradient(135deg, #D4AF37, #C5A028); color: white;">
                                    <i class="fas fa-calendar-check"></i> Programează-te
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-5 d-flex justify-content-center">
            {{ $specialists->links() }}
        </div>
        
    @elseif($salons->count() === 0)
        <!-- No Results Message -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-search fa-4x text-muted"></i>
            </div>
            <h3 class="mb-3">Nu am găsit specialiști sau saloane</h3>
            <p class="text-muted mb-4">
                Ne pare rău, dar nu există specialiști sau saloane care să corespundă criteriilor tale de căutare.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Înapoi la pagina principală
                </a>
                <a href="{{ route('specialists.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-users"></i> Vezi toți specialiștii
                </a>
            </div>
            <p class="text-muted mt-4 small">
                <i class="fas fa-lightbulb"></i> 
                <strong>Sugestie:</strong> Încearcă să modifici criteriile de căutare sau să extinzi zona de căutare.
            </p>
        </div>
    @endif
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    transition: all 0.3s ease;
}
</style>
@endsection
