@extends('layout')

@section('title', 'DariaBeauty - Frumusetea ta, in maini bune')
@section('description', 'DariaBeauty ofera servicii premium de frumusete la domiciliu: dariaNails (manichiura), dariaHair (coafura), dariaGlow (skincare). Programeaza acum!')

@section('content')
<!-- Hero Section - Modern Compact -->
<section class="hero-section" style="background: linear-gradient(135deg, #D4AF37 0%, #8B6914 100%); padding: 100px 0 80px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3 text-white">
                    Bine ai venit la <span style="color: #FFD700;">DariaBeauty</span>
                </h1>
                <p class="h5 mb-4 text-white opacity-75" style="font-style: italic;">
                    "Frumusețea ta, în mâini bune."
                </p>
                <p class="lead mb-4 text-white">
                    Servicii premium de frumusețe la domiciliu, adaptate stilului tău de viață.
                </p>
                
                <!-- Compact Search Bar -->
                <div class="bg-white rounded-pill shadow-lg p-2 mb-4">
                    <form action="/search-specialists" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-8">
                            <input type="text" class="form-control border-0 rounded-pill" name="location" 
                                   placeholder="🔍 Caută specialiști în zona ta..." required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn w-100 rounded-pill text-white" 
                                    style="background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%); border: none; font-weight: 600;">
                                Caută
                            </button>
                        </div>
                    </form>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('booking.landing') }}" class="btn btn-light btn-lg px-5 rounded-pill" 
                       style="color: #D4AF37; font-weight: 600;">
                        <i class="fas fa-calendar-alt me-2"></i>Programează-te
                    </a>
                    <a href="{{ route('specialists.index') }}" class="btn btn-outline-light btn-lg px-5 rounded-pill">
                        <i class="fas fa-users me-2"></i>Specialiști
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block">
                <div class="position-relative">
                    <img src="/images/hero-beauty.jpg" alt="DariaBeauty Services" 
                         class="img-fluid rounded-4 shadow-lg" style="max-height: 450px; object-fit: cover; width: 100%;">
                    <div class="position-absolute top-0 end-0 bg-white px-4 py-2 rounded-bottom-start shadow-sm">
                        <strong style="color: #D4AF37;"><i class="fas fa-home me-2"></i>La domiciliu</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sub-brands Section - Compact Modern -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-2">Descoperă sub-brandurile noastre</h2>
            <p class="text-muted">Fiecare cu specialitatea sa, toate cu aceeași excelență</p>
        </div>

        <div class="row g-4">
            <!-- dariaNails -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow-sm p-4 h-100 border" 
                     style="transition: all 0.3s ease; border-color: #EC4899 !important;">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px; background: rgba(236, 72, 153, 0.1);">
                            <i class="fas fa-hand-sparkles" style="font-size: 1.8rem; color: #EC4899;"></i>
                        </div>
                        <h3 class="h5 mb-0 fw-bold" style="color: #EC4899;">dariaNails</h3>
                    </div>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">
                        Manichiură și pedichiură profesională la domiciliu cu produse premium.
                    </p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2" style="font-size: 0.9rem;"></i>
                            <small>Manichiură clasică & gel</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2" style="font-size: 0.9rem;"></i>
                            <small>Pedichiură & spa</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2" style="font-size: 0.9rem;"></i>
                            <small>Nail art personalizat</small>
                        </div>
                    </div>
                    <a href="{{ route('darianails') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                        Descoperă serviciile →
                    </a>
                </div>
            </div>

            <!-- dariaHair -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow-sm p-4 h-100 border" 
                     style="transition: all 0.3s ease; border-color: #9333EA !important;">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px; background: rgba(147, 51, 234, 0.1);">
                            <i class="fas fa-cut" style="font-size: 1.8rem; color: #9333EA;"></i>
                        </div>
                        <h3 class="h5 mb-0 fw-bold" style="color: #9333EA;">dariaHair</h3>
                    </div>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">
                        Servicii complete de coafură și styling pentru orice ocazie.
                    </p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2" style="font-size: 0.9rem;"></i>
                            <small>Tunsori & styling</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2" style="font-size: 0.9rem;"></i>
                            <small>Colorare & suvite</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2" style="font-size: 0.9rem;"></i>
                            <small>Coafuri evenimente</small>
                        </div>
                    </div>
                    <a href="{{ route('dariahair') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                        Descoperă serviciile →
                    </a>
                </div>
            </div>

            <!-- dariaGlow -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow-sm p-4 h-100 border" 
                     style="transition: all 0.3s ease; border-color: #F97316 !important;">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px; background: rgba(249, 115, 22, 0.1);">
                            <i class="fas fa-spa" style="font-size: 1.8rem; color: #F97316;"></i>
                        </div>
                        <h3 class="h5 mb-0 fw-bold" style="color: #F97316;">dariaGlow</h3>
                    </div>
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">
                        Skincare și machiaj profesional pentru un look impecabil.
                    </p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2" style="font-size: 0.9rem;"></i>
                            <small>Tratamente faciale</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2" style="font-size: 0.9rem;"></i>
                            <small>Curățare facială</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2" style="font-size: 0.9rem;"></i>
                            <small>Machiaj profesional</small>
                        </div>
                    </div>
                    <a href="{{ route('dariaglow') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                        Descoperă serviciile →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section - Compact -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-2">De ce să alegi DariaBeauty?</h2>
            <p class="text-muted">Experiență premium adaptată stilului tău de viață</p>
        </div>

        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="bg-white rounded-3 p-4 h-100 text-center shadow-sm" style="transition: all 0.3s ease;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);">
                        <i class="fas fa-home text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-2">La domiciliu</h6>
                    <p class="text-muted small mb-0">Servicii premium în confortul casei tale, fără stres.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="bg-white rounded-3 p-4 h-100 text-center shadow-sm" style="transition: all 0.3s ease;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);">
                        <i class="fas fa-user-check text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-2">Specialiști verificați</h6>
                    <p class="text-muted small mb-0">Profesioniști cu experiență și certificări.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="bg-white rounded-3 p-4 h-100 text-center shadow-sm" style="transition: all 0.3s ease;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);">
                        <i class="fas fa-clock text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-2">Program flexibil</h6>
                    <p class="text-muted small mb-0">Programări în weekend și seri, când îți convine.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="bg-white rounded-3 p-4 h-100 text-center shadow-sm" style="transition: all 0.3s ease;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                         style="width: 70px; height: 70px; background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);">
                        <i class="fas fa-shield-alt text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-2">Siguranță garantată</h6>
                    <p class="text-muted small mb-0">Produse premium și respect pentru igiena.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section - Compact -->
<section class="py-5" style="background: linear-gradient(135deg, #D4AF37 0%, #8B6914 100%);">
    <div class="container text-center text-white">
        <h2 class="display-6 fw-bold mb-3">Pregătită pentru o experiență de neuitat?</h2>
        <p class="mb-4">Alătură-te miilor de cliente mulțumite care au ales DariaBeauty</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('booking.landing') }}" class="btn btn-light btn-lg px-5 rounded-pill" 
               style="color: #D4AF37; font-weight: 600;">
                <i class="fas fa-calendar-alt me-2"></i>Programează-te
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5 rounded-pill">
                <i class="fas fa-phone me-2"></i>Contact direct
            </a>
        </div>
    </div>
</section>

<!-- Recent Reviews -->
<section class="py-5">
    <div class="container">
        <div class="text-center section-title">
            <h2 class="display-5 fw-bold">Ce spun clientele noastre</h2>
            <p class="lead text-muted">Feedback real de la femei care au ales DariaBeauty</p>
        </div>

        <div class="row g-4">
            <!-- Review 1 -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text mb-3">
                            "Serviciu exceptional! Maria de la dariaNails a venit acasa si a facut o manichiura perfecta. 
                            Economie de timp si rezultat superb!"
                        </p>
                        <div class="d-flex align-items-center">
                            <img src="/images/client-1.jpg" alt="Ana M." class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-0">Ana M.</h6>
                                <small class="text-muted">Bucuresti, Sector 1</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text mb-3">
                            "dariaHair - experienta minunata! Coafura pentru nunta mea a fost exact ce imi doream. 
                            Recomand cu incredere!"
                        </p>
                        <div class="d-flex align-items-center">
                            <img src="/images/client-2.jpg" alt="Elena P." class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-0">Elena P.</h6>
                                <small class="text-muted">Bucuresti, Sector 3</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text mb-3">
                            "dariaGlow m-a ajutat sa imi gasesc rutina perfecta de skincare. 
                            Tratamentul facial a fost relaxant si eficient!"
                        </p>
                        <div class="d-flex align-items-center">
                            <img src="/images/client-3.jpg" alt="Ioana R." class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h6 class="mb-0">Ioana R.</h6>
                                <small class="text-muted">Bucuresti, Sector 2</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Smooth scrolling pentru linkuri
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Form validation pentru cautarea zonei
    document.querySelector('form[action="/search-specialists"]').addEventListener('submit', function(e) {
        const location = this.querySelector('input[name="location"]').value.trim();
        if (location.length < 3) {
            e.preventDefault();
            alert('Te rugam sa introduci cel putin 3 caractere pentru locatie.');
        }
    });
</script>
@endpush