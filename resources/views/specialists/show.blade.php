@extends('layout')

@section('title', $specialist->name . ' - Specialist DariaBeauty')

@section('content')
<div class="bg-gray-50 min-h-screen py-5">
    <div class="container">
        
        <!-- Header Compact Card -->
        <div class="card shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <!-- Poza de Profil -->
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        @if($specialist->profile_image)
                            <img src="{{ asset('storage/' . $specialist->profile_image) }}" 
                                 alt="{{ $specialist->name }}" 
                                 class="rounded-circle shadow" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center shadow mx-auto" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="mt-2">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> Verificat
                            </span>
                        </div>
                    </div>

                    <!-- Info Principal -->
                    <div class="col-md-6">
                        <span class="badge" style="background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);">
                            {{ ucfirst($specialist->sub_brand) ?? 'dariaGlow' }}
                        </span>
                        <h1 class="h3 fw-bold mt-2 mb-2">{{ $specialist->name }}</h1>
                        
                        <!-- Rating -->
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($specialist->average_rating ?? 0))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-muted">
                                {{ number_format($specialist->average_rating ?? 0, 1) }} din 5 
                                ({{ $specialist->reviews_count ?? 0 }} review-uri)
                            </span>
                        </div>

                        <!-- Service Locations -->
                        <div class="mb-3">
                            @if($specialist->offers_at_salon)
                                <span class="badge bg-primary me-2">
                                    <i class="fas fa-building"></i> Servicii la salon
                                </span>
                            @endif
                            @if($specialist->offers_at_home)
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-home"></i> Servicii la domiciliu
                                </span>
                            @endif
                        </div>

                        @if($specialist->offers_at_salon && $specialist->salon_address)
                        <div class="mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <span class="text-muted small">Salon: {{ $specialist->salon_address }}</span>
                        </div>
                        @endif

                        <!-- Descriere Scurtă -->
                        @if($specialist->description)
                        <p class="text-muted mb-2">
                            <i class="fas fa-quote-left text-warning me-2"></i>
                            {{ Str::limit($specialist->description, 150) }}
                        </p>
                        @endif

                        <!-- Zone -->
                        @if($specialist->coverage_area && count($specialist->coverage_area) > 0)
                        <div class="mb-2">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            <span class="text-muted">
                                {{ is_array($specialist->coverage_area) ? implode(', ', array_slice($specialist->coverage_area, 0, 3)) : $specialist->coverage_area }}
                                @if(is_array($specialist->coverage_area) && count($specialist->coverage_area) > 3)
                                    <span class="badge bg-secondary">+{{ count($specialist->coverage_area) - 3 }} zone</span>
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Statistici & Acțiuni -->
                    <div class="col-md-4">
                        <!-- Stats -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="card border-0 bg-light text-center">
                                    <div class="card-body p-2">
                                        <h4 class="mb-0 text-warning">{{ $services->count() }}</h4>
                                        <small class="text-muted">Servicii</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 bg-light text-center">
                                    <div class="card-body p-2">
                                        <h4 class="mb-0 text-warning">{{ $specialist->created_at ? $specialist->created_at->diffInYears(now()) : 0 }}</h4>
                                        <small class="text-muted">Ani Exp.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Acțiuni -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('specialists.booking', $specialist->slug) }}" 
                               class="btn text-white btn-lg" 
                               style="background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);">
                                <i class="fas fa-calendar-check me-2"></i>Rezervă Acum
                            </a>
                            @if($specialist->phone)
                            <a href="tel:{{ $specialist->phone }}" class="btn btn-outline-success">
                                <i class="fas fa-phone me-2"></i>{{ $specialist->phone }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Servicii -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><i class="fas fa-scissors text-warning me-2"></i>Servicii Oferite</h4>
                    </div>
                    <div class="card-body">
                        @if($services->count() > 0)
                        <div class="row g-3">
                            @foreach($services as $service)
                            <div class="col-md-6">
                                <div class="card h-100 border">
                                    @if($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $service->name }}"
                                         style="height: 180px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $service->name }}</h5>
                                        <p class="card-text text-muted small">{{ Str::limit($service->description, 80) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>{{ $service->formatted_duration ?? $service->duration . ' min' }}
                                            </span>
                                            <span class="h5 mb-0 text-success fw-bold">
                                                {{ $service->formatted_price ?? $service->price . ' RON' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted text-center py-4">
                            <i class="fas fa-info-circle me-2"></i>Specialistul nu a adăugat încă servicii.
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Reviews -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Recenzii Clienți ({{ $reviews->total() ?? $reviews->count() }})</h4>
                        
                        @auth
                            @php
                                // Verifică dacă user-ul autentificat are programări finalizate cu acest specialist
                                $completedAppointments = \App\Models\Appointment::where('client_email', auth()->user()->email)
                                    ->where('specialist_id', $specialist->id)
                                    ->where('status', 'completed')
                                    ->whereDoesntHave('review')
                                    ->get();
                            @endphp
                            
                            @if($completedAppointments->count() > 0)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-pen me-1"></i> Lasă Review
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($completedAppointments as $appointment)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('reviews.create', $appointment->id) }}">
                                                    <i class="fas fa-calendar-check me-2 text-success"></i>
                                                    {{ $appointment->service->name ?? 'Serviciu' }}
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->appointment_date->format('d M Y') }}</small>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endauth
                    </div>
                    <div class="card-body">
                        @if($reviews->count() > 0)
                            @foreach($reviews->take(5) as $review)
                            <div class="border-bottom pb-3 mb-3 @if($loop->last) border-0 @endif">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            {{ $review->client_name ?? ($review->user->name ?? 'Client Verificat') }}
                                            @if($review->user_id)
                                                <span class="badge bg-success badge-sm">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            @endif
                                        </h6>
                                        <div class="text-warning mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o text-muted' }}"></i>
                                            @endfor
                                            <span class="text-dark ms-2 fw-bold">{{ $review->rating }}.0</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                
                                @if($review->comment)
                                    <p class="mb-2 text-dark">{{ $review->comment }}</p>
                                @endif
                                
                                @if($review->appointment && $review->appointment->service)
                                    <small class="text-muted">
                                        <i class="fas fa-tag me-1"></i>{{ $review->appointment->service->name }}
                                    </small>
                                @endif
                                
                                <!-- Răspuns specialist -->
                                @if($review->specialist_response)
                                    <div class="mt-2 ms-4 p-2 bg-light rounded">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-reply text-primary me-2"></i>
                                            <strong class="small">Răspuns de la {{ $specialist->name }}</strong>
                                        </div>
                                        <p class="small mb-0 text-muted">{{ $review->specialist_response }}</p>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                            
                            @if($reviews->total() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('reviews.specialist', $specialist->id) }}" class="btn btn-outline-primary btn-sm">
                                        Vezi toate cele {{ $reviews->total() }} recenzii
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-star text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3 mb-0">Acest specialist nu are încă recenzii.</p>
                                <p class="text-muted small">Fii primul care lasă un review!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Contact -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-address-card text-warning me-2"></i>Contact</h5>
                    </div>
                    <div class="card-body">
                        @if($specialist->phone)
                        <div class="mb-3">
                            <i class="fas fa-phone text-success me-2"></i>
                            <a href="tel:{{ $specialist->phone }}" class="text-decoration-none">
                                {{ $specialist->phone }}
                            </a>
                        </div>
                        @endif
                        @if($specialist->email)
                        <div class="mb-3">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <a href="mailto:{{ $specialist->email }}" class="text-decoration-none">
                                {{ $specialist->email }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Zone Acoperire -->
                @if($specialist->coverage_area && count($specialist->coverage_area) > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-map-marked-alt text-danger me-2"></i>Zone Acoperite</h5>
                    </div>
                    <div class="card-body">
                        @foreach(is_array($specialist->coverage_area) ? $specialist->coverage_area : [$specialist->coverage_area] as $zone)
                        <span class="badge bg-light text-dark border me-1 mb-1">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $zone }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Social Media -->
                @php
                    $socialLinks = $specialist->socialLinks ?? collect();
                @endphp
                @if($socialLinks->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-share-alt text-info me-2"></i>Rețele Sociale</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 justify-content-center">
                            @foreach($socialLinks as $link)
                                @if($link->is_active)
                                    @php
                                        $icons = [
                                            'instagram' => ['icon' => 'fab fa-instagram', 'color' => '#E4405F'],
                                            'facebook' => ['icon' => 'fab fa-facebook', 'color' => '#1877F2'],
                                            'tiktok' => ['icon' => 'fab fa-tiktok', 'color' => '#000000'],
                                            'youtube' => ['icon' => 'fab fa-youtube', 'color' => '#FF0000'],
                                            'twitter' => ['icon' => 'fab fa-twitter', 'color' => '#1DA1F2'],
                                            'linkedin' => ['icon' => 'fab fa-linkedin', 'color' => '#0A66C2']
                                        ];
                                        $iconData = $icons[$link->platform] ?? ['icon' => 'fas fa-link', 'color' => '#666'];
                                    @endphp
                                    <a href="{{ $link->url }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="btn btn-outline-secondary rounded-circle"
                                       style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"
                                       title="{{ ucfirst($link->platform) }}">
                                        <i class="{{ $iconData['icon'] }}" style="color: {{ $iconData['color'] }};"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
