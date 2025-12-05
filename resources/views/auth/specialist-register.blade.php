@extends('layout')

@section('title', 'Inregistrare Specialist - DariaBeauty')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Inregistrare Specialist</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('specialist.register.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nume complet</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sub-brand</label>
                                <select name="sub_brand" class="form-select" required>
                                    <option value="">Alege...</option>
                                    @foreach($subBrands as $key => $label)
                                        <option value="{{ $key }}" @selected(old('sub_brand')===$key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Parola</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirma parola</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">📍 Unde oferi servicii?</label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="offers_at_salon" id="offers_at_salon" class="form-check-input" 
                                               value="1" {{ old('offers_at_salon') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="offers_at_salon">
                                            <i class="fas fa-store me-1"></i>La salon
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="offers_at_home" id="offers_at_home" class="form-check-input" 
                                               value="1" {{ old('offers_at_home', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="offers_at_home">
                                            <i class="fas fa-home me-1"></i>La domiciliu
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Salon Details (shown only if offers_at_salon is checked) -->
                            <div id="salonFields" class="col-12" style="display: none;">
                                <div class="card bg-light border-0 mt-2 mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-map-marker-alt me-2" style="color: #D4AF37;"></i>Detalii Salon
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Adresa salon</label>
                                                <input type="text" name="salon_address" id="salon_address" class="form-control" 
                                                       value="{{ old('salon_address') }}" 
                                                       placeholder="Ex: Str. Florilor nr. 10, Sector 1, București">
                                                <small class="text-muted">Adresa completă unde clienții te pot găsi</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Latitudine (opțional)</label>
                                                <input type="text" name="salon_lat" id="salon_lat" class="form-control" 
                                                       value="{{ old('salon_lat') }}" 
                                                       placeholder="Ex: 44.4268">
                                                <small class="text-muted">Pentru localizare exactă pe hartă</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Longitudine (opțional)</label>
                                                <input type="text" name="salon_lng" id="salon_lng" class="form-control" 
                                                       value="{{ old('salon_lng') }}" 
                                                       placeholder="Ex: 26.1025">
                                                <small class="text-muted">Pentru localizare exactă pe hartă</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Home Service Details (shown only if offers_at_home is checked) -->
                            <div id="homeFields" class="col-12">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-car me-2" style="color: #D4AF37;"></i>Detalii Deplasare
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Taxa transport (lei/km)</label>
                                                <input type="number" step="0.01" min="0" name="transport_fee" class="form-control" 
                                                       value="{{ old('transport_fee', 2) }}">
                                                <small class="text-muted">Cost per kilometru pentru deplasare</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Distanța maximă (km)</label>
                                                <input type="number" min="5" max="100" name="max_distance" class="form-control" 
                                                       value="{{ old('max_distance', 30) }}">
                                                <small class="text-muted">Cât de departe te deplasezi</small>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Zone acoperite</label>
                                                <select name="coverage_area[]" class="form-select" multiple>
                                                    @foreach($zones as $zone)
                                                        <option value="{{ $zone }}" @selected(collect(old('coverage_area',[]))->contains($zone))>{{ $zone }}</option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Ține apăsat Ctrl (sau Cmd pe Mac) pentru a selecta mai multe zone.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary" type="submit">
                                Trimite cererea
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-muted mt-3">
                <i class="fas fa-info-circle me-1"></i>
                După trimitere, un administrator va aproba contul tău în cel mai scurt timp.
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const offersAtSalon = document.getElementById('offers_at_salon');
    const offersAtHome = document.getElementById('offers_at_home');
    const salonFields = document.getElementById('salonFields');
    const homeFields = document.getElementById('homeFields');
    const salonAddress = document.getElementById('salon_address');

    function toggleFields() {
        // Show/hide salon fields
        if (offersAtSalon.checked) {
            salonFields.style.display = 'block';
            salonAddress.required = true;
        } else {
            salonFields.style.display = 'none';
            salonAddress.required = false;
        }

        // Show/hide home service fields
        if (offersAtHome.checked) {
            homeFields.style.display = 'block';
        } else {
            homeFields.style.display = 'none';
        }

        // At least one option must be selected
        if (!offersAtSalon.checked && !offersAtHome.checked) {
            offersAtHome.checked = true;
            homeFields.style.display = 'block';
        }
    }

    // Initial state
    toggleFields();

    // Listen for changes
    offersAtSalon.addEventListener('change', toggleFields);
    offersAtHome.addEventListener('change', function() {
        // Prevent both being unchecked
        if (!this.checked && !offersAtSalon.checked) {
            alert('Trebuie să oferi servicii cel puțin la salon SAU la domiciliu!');
            this.checked = true;
        }
        toggleFields();
    });

    // Prevent unchecking last option
    offersAtSalon.addEventListener('change', function() {
        if (!this.checked && !offersAtHome.checked) {
            alert('Trebuie să oferi servicii cel puțin la salon SAU la domiciliu!');
            this.checked = true;
            toggleFields();
        }
    });
});
</script>
@endsection
