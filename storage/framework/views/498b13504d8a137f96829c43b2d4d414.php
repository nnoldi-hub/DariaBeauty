

<?php $__env->startSection('title', 'DariaBeauty - Frumusetea ta, in maini bune'); ?>
<?php $__env->startSection('description', 'DariaBeauty ofera servicii premium de frumusete la domiciliu: dariaNails (manichiura), dariaHair (coafura), dariaGlow (skincare). Programeaza acum!'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Bine ai venit la <span class="text-warning">DariaBeauty</span>
                </h1>
                <p class="slogan">
                    "Frumusetea ta, in maini bune."
                </p>
                <p class="lead mb-4">
                    Servicii premium de frumusete la domiciliu, adaptate stilului tau de viata. 
                    Descopera experienta <strong>dariaNails</strong>, <strong>dariaHair</strong> 
                    si <strong>dariaGlow</strong> in confortul casei tale.
                </p>
                
                <!-- Zone Coverage Search -->
                <div class="card shadow-sm p-4 mb-4" style="border-radius: 15px;">
                    <h5 class="mb-3"><i class="fas fa-map-marker-alt text-warning me-2"></i>Verifica disponibilitatea in zona ta</h5>
                    <form action="/search-specialists" method="GET" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="location" placeholder="Introdu adresa ta (ex: Sector 1, Bucuresti)" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Cauta
                            </button>
                        </div>
                    </form>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <a href="/booking" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-alt me-2"></i>Programeaza-te acum
                    </a>
                    <a href="/specialists" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-users me-2"></i>Vezi specialistii
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <img src="/images/hero-beauty.jpg" alt="DariaBeauty Services" class="img-fluid rounded-3 shadow-lg" style="max-height: 500px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 bg-warning text-dark px-3 py-2 rounded-bottom-start">
                        <strong><i class="fas fa-home me-1"></i>La domiciliu</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sub-brands Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center section-title">
            <h2 class="display-5 fw-bold">Descopera sub-brandurile noastre</h2>
            <p class="lead text-muted">Fiecare cu specialitatea sa, toate cu aceeasi excelenta</p>
        </div>

        <div class="row g-4">
            <!-- dariaNails -->
            <div class="col-lg-4">
                <div class="sub-brand-card nails">
                    <div class="sub-brand-icon nails">
                        <i class="fas fa-hand-sparkles"></i>
                    </div>
                    <h3 class="h4 mb-3" style="color: var(--accent-nails);">dariaNails</h3>
                    <p class="text-muted mb-4">
                        Manichiura si pedichiura profesionala la domiciliu. Unghii perfect ingrijite, 
                        design personalizat si produse premium.
                    </p>
                    <ul class="list-unstyled text-start mb-4">
                        <li><i class="fas fa-check text-success me-2"></i>Manichiura clasica & gel</li>
                        <li><i class="fas fa-check text-success me-2"></i>Pedichiura & spa</li>
                        <li><i class="fas fa-check text-success me-2"></i>Nail art personalizat</li>
                        <li><i class="fas fa-check text-success me-2"></i>Intretinere unghii</li>
                    </ul>
                    <a href="/services/darianails" class="btn btn-outline-primary">
                        Descopera serviciile
                    </a>
                </div>
            </div>

            <!-- dariaHair -->
            <div class="col-lg-4">
                <div class="sub-brand-card hair">
                    <div class="sub-brand-icon hair">
                        <i class="fas fa-cut"></i>
                    </div>
                    <h3 class="h4 mb-3" style="color: var(--accent-hair);">dariaHair</h3>
                    <p class="text-muted mb-4">
                        Servicii complete de coafura si styling la domiciliu. De la tunsori la 
                        styling-uri pentru evenimente speciale.
                    </p>
                    <ul class="list-unstyled text-start mb-4">
                        <li><i class="fas fa-check text-success me-2"></i>Tunsori & styling</li>
                        <li><i class="fas fa-check text-success me-2"></i>Colorare & suvite</li>
                        <li><i class="fas fa-check text-success me-2"></i>Tratamente par</li>
                        <li><i class="fas fa-check text-success me-2"></i>Coafuri evenimente</li>
                    </ul>
                    <a href="/services/dariahair" class="btn btn-outline-primary">
                        Descopera serviciile
                    </a>
                </div>
            </div>

            <!-- dariaGlow -->
            <div class="col-lg-4">
                <div class="sub-brand-card glow">
                    <div class="sub-brand-icon glow">
                        <i class="fas fa-spa"></i>
                    </div>
                    <h3 class="h4 mb-3" style="color: var(--accent-glow);">dariaGlow</h3>
                    <p class="text-muted mb-4">
                        Skincare si makeup profesional la domiciliu. Tratamente faciale 
                        si machiaj pentru orice ocazie.
                    </p>
                    <ul class="list-unstyled text-start mb-4">
                        <li><i class="fas fa-check text-success me-2"></i>Tratamente faciale</li>
                        <li><i class="fas fa-check text-success me-2"></i>Curatare faciala</li>
                        <li><i class="fas fa-check text-success me-2"></i>Machiaj profesional</li>
                        <li><i class="fas fa-check text-success me-2"></i>Consultanta beauty</li>
                    </ul>
                    <a href="/services/dariaglow" class="btn btn-outline-primary">
                        Descopera serviciile
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center section-title">
            <h2 class="display-5 fw-bold">De ce sa alegi DariaBeauty?</h2>
            <p class="lead text-muted">Experienta premium adaptata stilului tau de viata</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h5 class="mb-3">La domiciliu</h5>
                    <p class="text-muted">Servicii premium in confortul casei tale, fara stres si fara asteptare.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h5 class="mb-3">Specialisti verificati</h5>
                    <p class="text-muted">Echipa de profesionisti cu experienta si certificari in domeniu.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h5 class="mb-3">Program flexibil</h5>
                    <p class="text-muted">Programari adaptate programului tau, inclusiv weekend si seri.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="mb-3">Siguranta garantata</h5>
                    <p class="text-muted">Produse premium, sterilizare corecta si respect pentru normele de igiena.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-color), #E6C547);">
    <div class="container text-center text-white">
        <h2 class="display-5 fw-bold mb-4">Pregatita pentru o experienta de neuitat?</h2>
        <p class="lead mb-4">Alatura-te miilor de cliente multumite care au ales DariaBeauty</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="/booking" class="btn btn-light btn-lg">
                <i class="fas fa-calendar-alt me-2"></i>Programeaza-te acum
            </a>
            <a href="/specialists" class="btn btn-outline-light btn-lg">
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\salon\dariabeauty\resources\views/home.blade.php ENDPATH**/ ?>