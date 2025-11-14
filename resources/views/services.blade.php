@extends('layout')

@section('title','Servicii - DariaBeauty')

@section('content')
<style>
  .services-hero {
    background: linear-gradient(135deg, #FFFBF7 0%, #F7F3EF 100%);
    padding: 120px 0 60px;
  }
  
  .brand-section {
    padding: 60px 0;
  }
  
  .brand-header {
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 3px solid;
  }
  
  .brand-header.nails {
    border-color: #E91E63;
  }
  
  .brand-header.hair {
    border-color: #9C27B0;
  }
  
  .brand-header.glow {
    border-color: #FF9800;
  }
  
  .brand-title {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .brand-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
  }
  
  .brand-icon.nails {
    background: linear-gradient(135deg, #E91E63, #F06292);
  }
  
  .brand-icon.hair {
    background: linear-gradient(135deg, #9C27B0, #BA68C8);
  }
  
  .brand-icon.glow {
    background: linear-gradient(135deg, #FF9800, #FFB74D);
  }
  
  .category-title {
    color: #6B7280;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  
  .service-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    border-top: 4px solid transparent;
  }
  
  .service-card.nails {
    border-top-color: #E91E63;
  }
  
  .service-card.hair {
    border-top-color: #9C27B0;
  }
  
  .service-card.glow {
    border-top-color: #FF9800;
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
  
  .service-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #2C1810;
  }
  
  .service-specialist {
    color: #6B7280;
    font-size: 0.9rem;
    margin-bottom: 15px;
  }
  
  .service-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2C1810;
  }
  
  .btn-explore {
    padding: 10px 25px;
    border-radius: 25px;
    font-weight: 600;
    border: none;
    color: white;
    transition: all 0.3s ease;
  }
  
  .btn-explore.nails {
    background: #E91E63;
  }
  
  .btn-explore.hair {
    background: #9C27B0;
  }
  
  .btn-explore.glow {
    background: #FF9800;
  }
  
  .btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  }
</style>

<div class="services-hero">
  <div class="container text-center">
    <h1 class="display-5 fw-bold mb-3">Toate Serviciile Noastre</h1>
    <p class="text-muted">Descoperă gama completă de servicii premium de frumusețe</p>
  </div>
</div>

<div class="container py-5">
  @foreach($servicesByBrand as $brand => $groups)
    @php
      $brandClass = strtolower(str_replace('daria', '', $brand));
      $brandIcons = [
        'nails' => 'fas fa-hand-sparkles',
        'hair' => 'fas fa-cut',
        'glow' => 'fas fa-spa'
      ];
      $brandTitles = [
        'nails' => 'dariaNails - Manichiură & Pedichiură',
        'hair' => 'dariaHair - Coafură & Styling',
        'glow' => 'dariaGlow - Skincare & Makeup'
      ];
      $brandRoutes = [
        'nails' => 'darianails',
        'hair' => 'dariahair',
        'glow' => 'dariaglow'
      ];
      $brandColors = [
        'nails' => '#E91E63',
        'hair' => '#9C27B0',
        'glow' => '#FF9800'
      ];
    @endphp
    
    <section class="brand-section">
      <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: {{ $brandColors[$brandClass] ?? '#ccc' }};">
        <div class="d-flex align-items-center gap-3">
          <div class="brand-icon {{ $brandClass }}">
            <i class="{{ $brandIcons[$brandClass] ?? 'fas fa-star' }}"></i>
          </div>
          <div>
            <h3 class="mb-0 fw-bold">{{ $brandTitles[$brandClass] ?? $brand }}</h3>
          </div>
        </div>
        <a href="{{ route($brandRoutes[$brandClass] ?? 'services') }}" class="btn btn-explore {{ $brandClass }} btn-sm rounded-pill px-4">
          Explorează →
        </a>
      </div>
      
      @forelse($groups as $category => $services)
        <div class="mb-4">
          <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">{{ $category }}</h6>
          <div class="row g-3">
            @foreach($services as $service)
            <div class="col-md-4 col-lg-3">
              <div class="service-card {{ $brandClass }}">
                @if($service->image)
                  <img src="{{ asset('storage/'.$service->image) }}" style="height: 160px; object-fit: cover; width: 100%;" alt="{{ $service->name }}">
                @else
                  <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                    <i class="{{ $brandIcons[$brandClass] ?? 'fas fa-star' }} fa-2x text-muted" style="opacity: 0.3;"></i>
                  </div>
                @endif
                <div class="p-3">
                  <h6 class="fw-bold mb-2" style="font-size: 0.95rem;">{{ $service->name }}</h6>
                  <div class="mb-2">
                    <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">
                      <i class="fas fa-user me-1"></i>{{ $service->specialist->name ?? 'Specialist' }}
                    </span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="font-size: 1.1rem; color: {{ $brandColors[$brandClass] ?? '#000' }};">
                      {{ $service->formatted_price }}
                    </span>
                    @if($service->duration)
                    <small class="text-muted">
                      <i class="far fa-clock me-1"></i>{{ $service->formatted_duration }}
                    </small>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      @empty
        <div class="text-center py-5">
          <i class="{{ $brandIcons[$brandClass] ?? 'fas fa-star' }} fa-3x text-muted mb-3"></i>
          <p class="text-muted">Nu sunt servicii disponibile momentan pentru {{ $brand }}.</p>
        </div>
      @endforelse
    </section>
  @endforeach
</div>

<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-4">Gata să te programezi?</h2>
    <p class="lead text-muted mb-4">Alege serviciul perfect pentru tine și rezervă acum</p>
    <a href="{{ route('booking.landing') }}" class="btn btn-primary btn-lg">
      <i class="fas fa-calendar-check me-2"></i>Programează-te acum
    </a>
  </div>
</section>

@endsection
