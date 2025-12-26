@extends('layout')

@section('title', 'Înregistrare Salon')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:80px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <i class="fas fa-building fa-3x mb-3"></i>
                    <h3 class="mb-0">Înregistrare Salon de Frumusețe</h3>
                    <p class="mb-0">Completează datele salonului tău</p>
                </div>

                <div class="card-body p-5">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register.salon.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Informații Salon --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Informații Salon</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Numele Salonului <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg" 
                                       value="{{ old('name') }}" required 
                                       placeholder="Ex: Salon Elegance Beauty">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Salon <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       value="{{ old('email') }}" required
                                       placeholder="contact@salon.ro">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Parolă <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control form-control-lg" 
                                           required minlength="8"
                                           placeholder="Minim 8 caractere">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirmă Parola <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg" 
                                           required
                                           placeholder="Reintroduceți parola">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Telefon Salon <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control form-control-lg" 
                                       value="{{ old('phone') }}" required
                                       placeholder="0721234567">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Adresa Completă Salon <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control form-control-lg" 
                                       value="{{ old('address') }}" required
                                       placeholder="Strada, Nr, Bloc, Oraș, Județ">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descriere Salon</label>
                                <textarea name="salon_description" class="form-control" rows="4" 
                                          placeholder="Scrie câteva cuvinte despre salonul tău, atmosferă, echipă, experiență...">{{ old('salon_description') }}</textarea>
                                <small class="text-muted">Această descriere va fi vizibilă pe profilul public al salonului</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Logo Salon (opțional)</label>
                                <input type="file" name="salon_logo" class="form-control" accept="image/*">
                                <small class="text-muted">Format acceptat: JPG, PNG. Max 2MB</small>
                            </div>
                        </div>

                        {{-- Sub-Brands (Multiple Selection) --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-tag text-primary me-2"></i>Specializări Salon</h5>
                            
                            <label class="form-label">Selectează toate categoriile pe care le oferiți <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-3">Puteți selecta mai multe opțiuni dacă oferiți servicii din mai multe domenii</p>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="checkbox" name="sub_brands[]" value="dariaNails" id="nails"
                                               {{ (is_array(old('sub_brands')) && in_array('dariaNails', old('sub_brands'))) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="nails">
                                            <div class="card h-100 text-center p-3">
                                                <i class="fas fa-hand-sparkles fa-2x text-danger mb-2"></i>
                                                <strong>DariaNails</strong>
                                                <small class="text-muted">Manichiură & Pedichiură</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="checkbox" name="sub_brands[]" value="dariaHair" id="hair"
                                               {{ (is_array(old('sub_brands')) && in_array('dariaHair', old('sub_brands'))) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="hair">
                                            <div class="card h-100 text-center p-3">
                                                <i class="fas fa-cut fa-2x text-primary mb-2"></i>
                                                <strong>DariaHair</strong>
                                                <small class="text-muted">Coafură & Stilism</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="checkbox" name="sub_brands[]" value="dariaGlow" id="glow"
                                               {{ (is_array(old('sub_brands')) && in_array('dariaGlow', old('sub_brands'))) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="glow">
                                            <div class="card h-100 text-center p-3">
                                                <i class="fas fa-spa fa-2x text-success mb-2"></i>
                                                <strong>DariaGlow</strong>
                                                <small class="text-muted">Makeup & Skin Care</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Social Media --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-share-alt text-primary me-2"></i>Social Media (opțional)</h5>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><i class="fab fa-instagram text-danger me-2"></i>Instagram</label>
                                    <input type="text" name="instagram" class="form-control" 
                                           value="{{ old('instagram') }}"
                                           placeholder="@username">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><i class="fab fa-facebook text-primary me-2"></i>Facebook</label>
                                    <input type="text" name="facebook" class="form-control" 
                                           value="{{ old('facebook') }}"
                                           placeholder="@username">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><i class="fab fa-tiktok me-2"></i>TikTok</label>
                                    <input type="text" name="tiktok" class="form-control" 
                                           value="{{ old('tiktok') }}"
                                           placeholder="@username">
                                </div>
                            </div>
                        </div>

                        {{-- Termeni și Condiții --}}
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Sunt de acord cu <a href="{{ route('terms') }}" target="_blank">Termenii și Condițiile</a> 
                                    și <a href="{{ route('privacy') }}" target="_blank">Politica de Confidențialitate</a> <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        {{-- Beneficii Recap --}}
                        <div class="alert alert-success mb-4">
                            <h6 class="mb-2"><i class="fas fa-gift me-2"></i>Beneficii înregistrare salon:</h6>
                            <ul class="mb-0 small">
                                <li>✅ <strong>3 luni GRATUIT</strong> - fără costuri ascunse</li>
                                <li>✅ Profil salon vizibil pe platformă</li>
                                <li>✅ Gestionare nelimitată specialiști</li>
                                <li>✅ Dashboard cu rapoarte și statistici</li>
                                <li>✅ Featured pe primele pagini 6 luni</li>
                                <li>✅ Kit marketing digital GRATUIT</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>Înregistrează Salonul
                        </button>
                    </form>
                </div>

                <div class="card-footer text-center py-3 bg-light">
                    <p class="mb-0 text-muted">
                        Ai deja cont? <a href="{{ route('login') }}">Autentifică-te</a> | 
                        <a href="{{ route('register.choice') }}">Înapoi la selecție</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-check-card .form-check-input {
    position: absolute;
    opacity: 0;
}

.form-check-card .form-check-input:checked + .form-check-label .card {
    border: 2px solid #0d6efd;
    background-color: #e7f1ff;
}

.form-check-card .card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-check-card .card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
</style>
@endsection
