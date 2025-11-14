@extends('layout')

@section('title', 'Setari Cookie-uri - DariaBeauty')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4" style="color: var(--primary-color);">
                        <i class="fas fa-cookie-bite me-2"></i>Setări Cookie-uri
                    </h1>
                    
                    <p class="text-muted text-center mb-5">
                        Gestionează preferințele tale pentru cookie-uri și urmărire
                    </p>

                    <!-- Info Section -->
                    <div class="alert alert-info mb-5">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Ce sunt cookie-urile?</strong><br>
                        Cookie-urile sunt fișiere mici de text stocate pe dispozitivul tău când vizitezi site-ul nostru. Ele ne ajută să îmbunătățim experiența ta și să personalizăm conținutul.
                    </div>

                    <!-- Cookie Categories -->
                    <div class="mb-5">
                        <h3 class="mb-4">Categorii de Cookie-uri</h3>

                        <!-- Essential Cookies -->
                        <div class="card mb-3 border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Cookie-uri Esențiale
                                            <span class="badge bg-success ms-2">Obligatorii</span>
                                        </h5>
                                        <p class="mb-2">Aceste cookie-uri sunt necesare pentru funcționarea de bază a site-ului. Nu pot fi dezactivate.</p>
                                        <div class="small text-muted">
                                            <strong>Exemple:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li><code>XSRF-TOKEN</code> - Protecție împotriva atacurilor CSRF</li>
                                                <li><code>laravel_session</code> - Gestionarea sesiunii utilizatorului</li>
                                                <li><code>remember_token</code> - Funcția "Ține-mă minte"</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input" type="checkbox" checked disabled style="cursor: not-allowed;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Functional Cookies -->
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2">
                                            <i class="fas fa-cog text-primary me-2"></i>
                                            Cookie-uri Funcționale
                                        </h5>
                                        <p class="mb-2">Stochează preferințele tale (limba, vizualizare grid/list, preferințe UI).</p>
                                        <div class="small text-muted">
                                            <strong>Exemple:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li><code>view_mode</code> - Preferință vizualizare (grid/list)</li>
                                                <li><code>locale</code> - Limba preferată</li>
                                                <li><code>theme</code> - Tema aleasă (light/dark)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input cookie-toggle" type="checkbox" id="functionalCookies" data-category="functional" checked>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Analytics Cookies -->
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2">
                                            <i class="fas fa-chart-line text-info me-2"></i>
                                            Cookie-uri Analitice
                                        </h5>
                                        <p class="mb-2">Ne ajută să înțelegem cum interactionezi cu site-ul (Google Analytics, heatmaps).</p>
                                        <div class="small text-muted">
                                            <strong>Exemple:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li><code>_ga</code> - Google Analytics ID utilizator (2 ani)</li>
                                                <li><code>_gid</code> - Google Analytics ID sesiune (24 ore)</li>
                                                <li><code>_gat</code> - Limitare rate cereri (1 minut)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input cookie-toggle" type="checkbox" id="analyticsCookies" data-category="analytics" checked>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Marketing Cookies -->
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-2">
                                            <i class="fas fa-bullhorn text-warning me-2"></i>
                                            Cookie-uri Marketing
                                        </h5>
                                        <p class="mb-2">Utilizate pentru a afișa reclame relevante și măsurarea eficienței campaniilor.</p>
                                        <div class="small text-muted">
                                            <strong>Exemple:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li><code>_fbp</code> - Facebook Pixel (90 zile)</li>
                                                <li><code>fr</code> - Facebook remarketing (90 zile)</li>
                                                <li><code>IDE</code> - Google DoubleClick (1 an)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input cookie-toggle" type="checkbox" id="marketingCookies" data-category="marketing">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mb-5">
                        <h3 class="mb-4">Acțiuni Rapide</h3>
                        <div class="d-flex gap-3 flex-wrap">
                            <button type="button" class="btn btn-success" id="acceptAll">
                                <i class="fas fa-check-double me-2"></i>Acceptă Toate
                            </button>
                            <button type="button" class="btn btn-danger" id="rejectAll">
                                <i class="fas fa-times-circle me-2"></i>Respinge Opționale
                            </button>
                            <button type="button" class="btn btn-primary" id="savePreferences">
                                <i class="fas fa-save me-2"></i>Salvează Preferințele
                            </button>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="card h-100 bg-light border-0">
                                <div class="card-body">
                                    <h5 class="mb-3">
                                        <i class="fas fa-shield-alt text-primary me-2"></i>
                                        Securitatea Ta
                                    </h5>
                                    <p class="mb-0 small">Cookie-urile nu conțin informații personale direct identificabile și sunt securizate conform standardelor GDPR. Poți șterge cookie-urile oricând din browser.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 bg-light border-0">
                                <div class="card-body">
                                    <h5 class="mb-3">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        Durată Stocare
                                    </h5>
                                    <p class="mb-0 small">Cookie-urile sunt stocate pentru perioade diferite: esențiale (sesiune), funcționale (30 zile), analitice (2 ani), marketing (90 zile). Poți șterge oricând.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Browser Instructions -->
                    <div class="mb-5">
                        <h3 class="mb-4">Cum Să Ștergi Cookie-urile</h3>
                        <div class="accordion" id="browserAccordion">
                            <!-- Chrome -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#chrome">
                                        <i class="fab fa-chrome text-warning me-2"></i>Google Chrome
                                    </button>
                                </h2>
                                <div id="chrome" class="accordion-collapse collapse" data-bs-parent="#browserAccordion">
                                    <div class="accordion-body">
                                        <ol class="small mb-0">
                                            <li>Click pe cele 3 puncte (⋮) din dreapta sus</li>
                                            <li>Setări → Confidențialitate și securitate</li>
                                            <li>Cookie-uri și alte date ale site-urilor</li>
                                            <li>Afișați toate cookie-urile și datele site-urilor → Șterge toate</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Firefox -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#firefox">
                                        <i class="fab fa-firefox text-danger me-2"></i>Mozilla Firefox
                                    </button>
                                </h2>
                                <div id="firefox" class="accordion-collapse collapse" data-bs-parent="#browserAccordion">
                                    <div class="accordion-body">
                                        <ol class="small mb-0">
                                            <li>Click pe cele 3 linii (≡) din dreapta sus</li>
                                            <li>Setări → Confidențialitate și securitate</li>
                                            <li>Cookie-uri și date de site → Șterge datele</li>
                                            <li>Bifează "Cookie-uri și date de site" → Șterge</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Safari -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#safari">
                                        <i class="fab fa-safari text-info me-2"></i>Safari
                                    </button>
                                </h2>
                                <div id="safari" class="accordion-collapse collapse" data-bs-parent="#browserAccordion">
                                    <div class="accordion-body">
                                        <ol class="small mb-0">
                                            <li>Safari → Preferințe</li>
                                            <li>Tab "Confidențialitate"</li>
                                            <li>Gestionați datele site-urilor web</li>
                                            <li>Șterge toate sau selectează site-uri individuale</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Edge -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#edge">
                                        <i class="fab fa-edge text-primary me-2"></i>Microsoft Edge
                                    </button>
                                </h2>
                                <div id="edge" class="accordion-collapse collapse" data-bs-parent="#browserAccordion">
                                    <div class="accordion-body">
                                        <ol class="small mb-0">
                                            <li>Click pe cele 3 puncte (•••) din dreapta sus</li>
                                            <li>Setări → Cookie-uri și permisiuni site</li>
                                            <li>Gestionați și ștergeți cookie-urile și datele site-ului</li>
                                            <li>Afișați toate cookie-urile → Șterge toate</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Legal Links -->
                    <div class="alert alert-secondary">
                        <i class="fas fa-file-alt me-2"></i>
                        Pentru mai multe detalii despre cum folosim cookie-urile și procesăm datele tale, consultă 
                        <a href="{{ route('privacy') }}" class="alert-link">Politica de Confidențialitate</a> și 
                        <a href="{{ route('terms') }}" class="alert-link">Termenii și Condițiile</a> noastre.
                    </div>

                    <!-- Success Message -->
                    <div id="successMessage" class="alert alert-success d-none" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="successText">Preferințele tale au fost salvate cu succes!</span>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="text-center mt-5">
                        <a href="{{ url('/') }}" class="btn btn-outline-primary rounded-pill px-5">
                            <i class="fas fa-home me-2"></i>Înapoi la Homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load saved preferences
    loadCookiePreferences();

    // Accept All
    document.getElementById('acceptAll').addEventListener('click', function() {
        document.querySelectorAll('.cookie-toggle').forEach(function(toggle) {
            toggle.checked = true;
        });
        saveCookiePreferences('Toate cookie-urile au fost acceptate!');
    });

    // Reject All (except essential)
    document.getElementById('rejectAll').addEventListener('click', function() {
        document.querySelectorAll('.cookie-toggle').forEach(function(toggle) {
            toggle.checked = false;
        });
        saveCookiePreferences('Cookie-urile opționale au fost respinse!');
    });

    // Save Preferences
    document.getElementById('savePreferences').addEventListener('click', function() {
        saveCookiePreferences('Preferințele tale au fost salvate!');
    });

    function loadCookiePreferences() {
        const preferences = JSON.parse(localStorage.getItem('cookiePreferences') || '{}');
        
        if (preferences.functional !== undefined) {
            document.getElementById('functionalCookies').checked = preferences.functional;
        }
        if (preferences.analytics !== undefined) {
            document.getElementById('analyticsCookies').checked = preferences.analytics;
        }
        if (preferences.marketing !== undefined) {
            document.getElementById('marketingCookies').checked = preferences.marketing;
        }
    }

    function saveCookiePreferences(message) {
        const preferences = {
            functional: document.getElementById('functionalCookies').checked,
            analytics: document.getElementById('analyticsCookies').checked,
            marketing: document.getElementById('marketingCookies').checked,
            timestamp: new Date().toISOString()
        };

        localStorage.setItem('cookiePreferences', JSON.stringify(preferences));

        // Show success message
        const successMessage = document.getElementById('successMessage');
        const successText = document.getElementById('successText');
        successText.textContent = message;
        successMessage.classList.remove('d-none');

        // Hide after 3 seconds
        setTimeout(function() {
            successMessage.classList.add('d-none');
        }, 3000);

        // Apply preferences (in a real app, this would affect tracking scripts)
        applyCookiePreferences(preferences);
    }

    function applyCookiePreferences(preferences) {
        // In production, you would disable/enable tracking scripts here
        console.log('Cookie preferences applied:', preferences);

        // Example: Disable Google Analytics if analytics cookies are rejected
        if (!preferences.analytics && typeof ga !== 'undefined') {
            window['ga-disable-UA-XXXXX-Y'] = true;
        }

        // Example: Remove marketing pixels if marketing cookies are rejected
        if (!preferences.marketing) {
            // Remove Facebook Pixel, Google Ads, etc.
            console.log('Marketing cookies disabled');
        }
    }
});
</script>
@endpush
@endsection
