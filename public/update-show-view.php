<?php
/**
 * Update specialists/show.blade.php with New Compact Design
 * Run via browser: https://dariabeauty.ro/update-show-view.php
 */

set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🎨 Update Specialist Profile View</h1>";

$basePath = dirname(__DIR__);
$targetFile = $basePath . '/resources/views/specialists/show.blade.php';

echo "<p><strong>Target:</strong> {$targetFile}</p><hr>";

// New compact design content
$newContent = <<<'BLADE'
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
                            <span class="fw-bold">{{ number_format($specialist->average_rating ?? 0, 1) }}</span>
                            <span class="text-muted ms-1">din 5 ({{ $specialist->reviews_count ?? 0 }} review-uri)</span>
                        </div>

                        <!-- Stats Compacte -->
                        <div class="d-flex gap-4 text-muted small">
                            <div>
                                <i class="fas fa-check-circle text-success"></i>
                                <strong>{{ $specialist->completed_appointments ?? 0 }}</strong> Servicii Completate
                            </div>
                            <div>
                                <i class="fas fa-calendar-check" style="color: #D4AF37;"></i>
                                <strong>{{ $specialist->years_experience ?? 0 }}</strong> Ani Experiență
                            </div>
                        </div>

                        <!-- Zone Badge -->
                        @if($specialist->zones && count($specialist->zones) > 0)
                        <div class="mt-2">
                            <i class="fas fa-map-marker-alt" style="color: #D4AF37;"></i>
                            <span class="text-muted small">Zone Acoperite:</span>
                            @foreach($specialist->zones as $zone)
                                <span class="badge bg-light text-dark border">{{ $zone }}</span>
                            @endforeach
                        </div>
                        @endif

                        <!-- Timp Raspuns -->
                        <div class="mt-2">
                            <i class="fas fa-clock text-info"></i>
                            <span class="text-muted small">Timp Răspuns: <strong>< 2 ore</strong></span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-4 text-md-end">
                        <a href="#rezervare" class="btn btn-lg text-white mb-2 w-100" 
                           style="background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%); border: none;">
                            <i class="fas fa-calendar-check"></i> Rezervă Acum
                        </a>
                        <button class="btn btn-outline-secondary btn-lg w-100" onclick="shareProfile()">
                            <i class="fas fa-share-alt"></i> Distribuie
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Tabs -->
        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button">
                    <i class="fas fa-concierge-bell"></i> Servicii
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button">
                    <i class="fas fa-images"></i> Galerie
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                    <i class="fas fa-star"></i> Review-uri
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="zones-tab" data-bs-toggle="tab" data-bs-target="#zones" type="button">
                    <i class="fas fa-map-marked-alt"></i> Zone Acoperite
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">
                    <i class="fas fa-envelope"></i> Contact
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabsContent">
            
            <!-- Tab Servicii -->
            <div class="tab-pane fade show active" id="services" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4"><i class="fas fa-concierge-bell" style="color: #D4AF37;"></i> Servicii Oferite</h4>
                        
                        @if($specialist->services && $specialist->services->count() > 0)
                            <div class="row g-3">
                                @foreach($specialist->services as $service)
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h5 class="card-title mb-0">{{ $service->name }}</h5>
                                                    <span class="badge" style="background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);">
                                                        {{ $service->price }} RON
                                                    </span>
                                                </div>
                                                <p class="text-muted small mb-2">{{ $service->description }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted small">
                                                        <i class="far fa-clock"></i> {{ $service->duration }} min
                                                    </span>
                                                    <a href="#rezervare" class="btn btn-sm btn-outline-warning">Rezervă</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-5">
                                <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                Nu sunt servicii disponibile momentan.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab Galerie -->
            <div class="tab-pane fade" id="gallery" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4"><i class="fas fa-images" style="color: #D4AF37;"></i> Galerie Lucrări</h4>
                        
                        @if($specialist->gallery && $specialist->gallery->count() > 0)
                            <div class="row g-3">
                                @foreach($specialist->gallery as $image)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                 class="card-img-top" 
                                                 alt="Lucrare" 
                                                 style="height: 250px; object-fit: cover; cursor: pointer;"
                                                 onclick="viewImage('{{ asset('storage/' . $image->image_path) }}')">
                                            @if($image->description)
                                                <div class="card-body py-2">
                                                    <p class="card-text small text-muted mb-0">{{ $image->description }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-5">
                                <i class="fas fa-camera fa-2x mb-2 d-block"></i>
                                Nu sunt imagini în galerie momentan.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab Review-uri -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4"><i class="fas fa-star" style="color: #D4AF37;"></i> Review-uri Clienți</h4>
                        
                        @if($specialist->reviews && $specialist->reviews->count() > 0)
                            @foreach($specialist->reviews as $review)
                                <div class="card mb-3 border-0 bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">{{ $review->client_name ?? 'Client Anonim' }}</h6>
                                                <div class="text-warning small">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <span class="text-muted small">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-5">
                                <i class="fas fa-comment-dots fa-2x mb-2 d-block"></i>
                                Nu sunt review-uri disponibile încă.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab Zone Acoperite -->
            <div class="tab-pane fade" id="zones" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4"><i class="fas fa-map-marked-alt" style="color: #D4AF37;"></i> Zone Acoperite</h4>
                        
                        @if($specialist->zones && count($specialist->zones) > 0)
                            <div class="row">
                                @foreach($specialist->zones as $zone)
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="card border-0 bg-light h-100">
                                            <div class="card-body text-center">
                                                <i class="fas fa-map-marker-alt fa-2x mb-2" style="color: #D4AF37;"></i>
                                                <h6 class="mb-0">{{ $zone }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-5">
                                <i class="fas fa-map fa-2x mb-2 d-block"></i>
                                Zone de acoperire vor fi adăugate în curând.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab Contact -->
            <div class="tab-pane fade" id="contact" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4"><i class="fas fa-envelope" style="color: #D4AF37;"></i> Contact Direct</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6><i class="fas fa-phone" style="color: #D4AF37;"></i> Telefon</h6>
                                        <p class="mb-0">
                                            @if($specialist->phone)
                                                <a href="tel:{{ $specialist->phone }}" class="text-decoration-none">
                                                    {{ $specialist->phone }}
                                                </a>
                                            @else
                                                <span class="text-muted">Nu este disponibil</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6><i class="fas fa-envelope" style="color: #D4AF37;"></i> Email</h6>
                                        <p class="mb-0">
                                            @if($specialist->email)
                                                <a href="mailto:{{ $specialist->email }}" class="text-decoration-none">
                                                    {{ $specialist->email }}
                                                </a>
                                            @else
                                                <span class="text-muted">Nu este disponibil</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Notă:</strong> Pentru rezervări, te rugăm să folosești butonul "Rezervă Acum" pentru o experiență optimă.
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Modal pentru vizualizare imagine -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 bg-white" data-bs-dismiss="modal"></button>
                <img id="modalImage" src="" class="img-fluid w-100" alt="Preview">
            </div>
        </div>
    </div>
</div>

<script>
function viewImage(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function shareProfile() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $specialist->name }} - DariaBeauty',
            text: 'Descoperă serviciile oferite de {{ $specialist->name }}',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert('Link copiat în clipboard!');
    }
}
</script>

@endsection
BLADE;

// Create backup
if (file_exists($targetFile)) {
    $backupPath = $targetFile . '.backup-' . date('Y-m-d-His');
    copy($targetFile, $backupPath);
    echo "<p>✅ Backup creat: " . basename($backupPath) . "</p>";
}

// Write new content
file_put_contents($targetFile, $newContent);
echo "<p>✅ Fișier actualizat cu design compact!</p>";

// Clear view cache
$viewsPath = $basePath . '/storage/framework/views';
$viewFiles = glob($viewsPath . '/*.php');
if ($viewFiles) {
    foreach ($viewFiles as $file) {
        unlink($file);
    }
    echo "<p>✅ Cache view-uri curățat (" . count($viewFiles) . " fișiere)</p>";
}

echo "<hr>";
echo "<h2 style='color: green;'>✅ Design actualizat cu succes!</h2>";
echo "<p>Reîmprospătează pagina profilului pentru a vedea noul design compact:</p>";
echo "<p><a href='/specialisti/daria-nyikora' target='_blank'>https://dariabeauty.ro/specialisti/daria-nyikora</a></p>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Șterge acest script după utilizare!</p>";
echo "<p><code>rm /home/ooxlvzey/public_html/public/update-show-view.php</code></p>";
