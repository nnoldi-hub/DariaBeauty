@extends('layout')

@section('title','Galerie - DariaBeauty')

@section('content')
<style>
  .gallery-hero {
    background: linear-gradient(135deg, #FFFBF7 0%, #F7F3EF 100%);
    padding: 80px 0 40px;
  }
  
  .brand-section {
    padding: 40px 0;
  }
  
  .brand-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    margin-bottom: 30px;
    transition: all 0.3s ease;
  }
  
  .brand-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
  }
  
  .brand-header-compact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .brand-icon-modern {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
  
  .brand-icon-modern.nails {
    background: linear-gradient(135deg, #E91E63, #F06292);
  }
  
  .brand-icon-modern.hair {
    background: linear-gradient(135deg, #9C27B0, #BA68C8);
  }
  
  .brand-icon-modern.glow {
    background: linear-gradient(135deg, #FF9800, #FFB74D);
  }
  
  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 12px;
  }
  
  .gallery-item-modern {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    aspect-ratio: 1;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
  
  .gallery-item-modern:hover {
    transform: scale(1.03);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    z-index: 10;
  }
  
  .gallery-item-modern img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  
  .gallery-item-modern:hover img {
    transform: scale(1.1);
  }
  
  .gallery-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-end;
    padding: 12px;
  }
  
  .gallery-item-modern:hover .gallery-overlay {
    opacity: 1;
  }
  
  .btn-view-services {
    padding: 8px 24px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    color: white;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  }
  
  .btn-view-services.nails {
    background: linear-gradient(135deg, #E91E63, #F06292);
  }
  
  .btn-view-services.hair {
    background: linear-gradient(135deg, #9C27B0, #BA68C8);
  }
  
  .btn-view-services.glow {
    background: linear-gradient(135deg, #FF9800, #FFB74D);
  }
  
  .btn-view-services:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    color: white;
  }
  
  .gallery-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    background: #f8f9fa;
    border-radius: 20px;
    font-size: 0.85rem;
    color: #6c757d;
  }
  
  @media (max-width: 768px) {
    .gallery-grid {
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 8px;
    }
    
    .brand-card {
      padding: 20px;
    }
  }
</style>

<div class="gallery-hero">
  <div class="container text-center">
    <h1 class="display-5 fw-bold mb-2">Galeria DariaBeauty</h1>
    <p class="text-muted mb-0">Descoperă transformările noastre spectaculoase</p>
  </div>
</div>

<div class="container py-4">
  @php
    $brandData = [
      'nails' => [
        'label' => 'dariaNails',
        'title' => 'Manichiură & Pedichiură',
        'icon' => 'fas fa-hand-sparkles',
        'route' => 'darianails',
        'class' => 'nails'
      ],
      'hair' => [
        'label' => 'dariaHair',
        'title' => 'Coafură & Styling',
        'icon' => 'fas fa-cut',
        'route' => 'dariahair',
        'class' => 'hair'
      ],
      'glow' => [
        'label' => 'dariaGlow',
        'title' => 'Skincare & Makeup',
        'icon' => 'fas fa-spa',
        'route' => 'dariaglow',
        'class' => 'glow'
      ]
    ];
  @endphp

  @foreach($brandData as $key => $brand)
    <div class="brand-card">
      <div class="brand-header-compact">
        <div class="d-flex align-items-center gap-3">
          <div class="brand-icon-modern {{ $brand['class'] }}">
            <i class="{{ $brand['icon'] }}"></i>
          </div>
          <div>
            <h5 class="mb-0 fw-bold">{{ $brand['label'] }}</h5>
            <small class="text-muted">{{ $brand['title'] }}</small>
          </div>
          @if(isset($gallery[$key]) && count($gallery[$key]) > 0)
            <span class="gallery-count">
              <i class="fas fa-images"></i>
              {{ count($gallery[$key]) }}
            </span>
          @endif
        </div>
        <a href="{{ route($brand['route']) }}" class="btn btn-view-services {{ $brand['class'] }} btn-sm">
          Vezi serviciile <i class="fas fa-arrow-right ms-1"></i>
        </a>
      </div>
      
      @if(isset($gallery[$key]) && count($gallery[$key]) > 0)
        <div class="gallery-grid">
          @foreach($gallery[$key] as $item)
            <div class="gallery-item-modern" onclick="viewImage('{{ asset('storage/'.$item->image_path) }}', '{{ $item->caption ?? '' }}')">
              <img src="{{ asset('storage/'.$item->image_path) }}" 
                   alt="{{ $item->caption ?? 'Gallery image' }}"
                   loading="lazy">
              @if($item->caption)
                <div class="gallery-overlay">
                  <small class="text-white fw-500">{{ $item->caption }}</small>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-5">
          <i class="{{ $brand['icon'] }} fa-3x text-muted mb-3" style="opacity: 0.2;"></i>
          <p class="text-muted mb-3">Imaginile vor fi disponibile în curând.</p>
          <a href="{{ route($brand['route']) }}" class="btn btn-view-services {{ $brand['class'] }} btn-sm">
            Vezi serviciile {{ $brand['label'] }}
          </a>
        </div>
      @endif
    </div>
  @endforeach
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 bg-transparent">
      <div class="modal-body p-0 position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3 bg-white rounded-circle p-2 shadow" data-bs-dismiss="modal" style="z-index: 10;"></button>
        <img id="modalImage" src="" class="img-fluid w-100 rounded-4 shadow-lg" alt="Preview">
        <div id="modalCaption" class="text-center text-white mt-3 fw-500"></div>
      </div>
    </div>
  </div>
</div>

<script>
function viewImage(src, caption) {
  document.getElementById('modalImage').src = src;
  document.getElementById('modalCaption').textContent = caption || '';
  new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>

<section class="py-5 bg-light">
  <div class="container text-center">
    <h3 class="fw-bold mb-3">Inspirată de ceea ce ai văzut?</h3>
    <p class="text-muted mb-4">Alege serviciul perfect și rezervă acum</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="{{ route('booking.landing') }}" class="btn btn-primary btn-lg px-5 rounded-pill">
        <i class="fas fa-calendar-check me-2"></i>Programează-te
      </a>
      <a href="{{ route('services') }}" class="btn btn-outline-secondary btn-lg px-5 rounded-pill">
        <i class="fas fa-list me-2"></i>Toate serviciile
      </a>
    </div>
  </div>
</section>

@endsection
