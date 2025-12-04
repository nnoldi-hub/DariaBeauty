@extends('layout')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('specialist.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Profilul Meu</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
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

            <form method="POST" action="{{ route('specialist.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Informații de Bază</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nume Complet <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $specialist->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $specialist->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Telefon</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $specialist->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sub_brand" class="form-label">Specializare <span class="text-danger">*</span></label>
                                        <select class="form-select @error('sub_brand') is-invalid @enderror" id="sub_brand" name="sub_brand" required>
                                            <option value="">Selectează specializarea</option>
                                            <option value="dariaNails" {{ old('sub_brand', $specialist->sub_brand) == 'dariaNails' ? 'selected' : '' }}>
                                                dariaNails - Manichiură & Pedichiură
                                            </option>
                                            <option value="dariaHair" {{ old('sub_brand', $specialist->sub_brand) == 'dariaHair' ? 'selected' : '' }}>
                                                dariaHair - Coafură & Styling
                                            </option>
                                            <option value="dariaGlow" {{ old('sub_brand', $specialist->sub_brand) == 'dariaGlow' ? 'selected' : '' }}>
                                                dariaGlow - Skincare & Makeup
                                            </option>
                                        </select>
                                        @error('sub_brand')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Descriere Profesională</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Descrie experiența ta, stilul de lucru, certificările...">{{ old('description', $specialist->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Service Location Options -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Unde Oferi Servicii?</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Alege unde vrei să oferi serviciile tale: la salon, la domiciliu sau ambele.
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="offers_at_salon" 
                                                   name="offers_at_salon" value="1"
                                                   {{ old('offers_at_salon', $specialist->offers_at_salon) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="offers_at_salon">
                                                <strong><i class="fas fa-building"></i> Ofer servicii la salon/cabinet</strong>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="offers_at_home" 
                                                   name="offers_at_home" value="1"
                                                   {{ old('offers_at_home', $specialist->offers_at_home) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="offers_at_home">
                                                <strong><i class="fas fa-home"></i> Ofer servicii la domiciliu</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Salon Address (shown only if offers_at_salon is checked) -->
                                <div id="salon_address_section" style="display: {{ old('offers_at_salon', $specialist->offers_at_salon) ? 'block' : 'none' }};">
                                    <div class="border-top pt-3">
                                        <h6 class="mb-3"><i class="fas fa-map-marked-alt"></i> Adresa Salonului/Cabinetului</h6>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="salon_address" class="form-label">Adresă Completă</label>
                                                <input type="text" class="form-control @error('salon_address') is-invalid @enderror" 
                                                       id="salon_address" name="salon_address" 
                                                       value="{{ old('salon_address', $specialist->salon_address) }}"
                                                       placeholder="Str. Exemplu nr. 10, Sector 1, București">
                                                @error('salon_address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Adresa unde clienții pot veni pentru servicii</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Settings (for home services) -->
                        <div class="card mb-4" id="home_service_settings" style="display: {{ old('offers_at_home', $specialist->offers_at_home) ? 'block' : 'none' }};">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-car"></i> Setări Servicii la Domiciliu</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="transport_fee" class="form-label">Tarif Transport (RON)</label>
                                        <input type="number" step="0.01" class="form-control @error('transport_fee') is-invalid @enderror" 
                                               id="transport_fee" name="transport_fee" 
                                               value="{{ old('transport_fee', $specialist->transport_fee ?? 30) }}" required>
                                        @error('transport_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Tariful pentru deplasarea la domiciliu</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="max_distance" class="form-label">Distanță Maximă (km) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('max_distance') is-invalid @enderror" 
                                               id="max_distance" name="max_distance" 
                                               value="{{ old('max_distance', $specialist->max_distance ?? 25) }}" required>
                                        @error('max_distance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Distanța maximă pentru deplasări</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Zone de Acoperire <span class="text-danger">*</span></label>
                                    <div class="row">
                                        @php
                                            $zones = [
                                                'Sector 1', 'Sector 2', 'Sector 3', 'Sector 4', 'Sector 5', 'Sector 6',
                                                'Baneasa', 'Pipera', 'Floreasca', 'Herastrau', 'Dorobanti', 'Amzei',
                                                'Calea Victoriei', 'Centrul Vechi', 'Universitate', 'Romana'
                                            ];
                                            $selectedZones = old('coverage_area', $specialist->coverage_area ?? []);
                                        @endphp
                                        @foreach($zones as $zone)
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="zone_{{ $loop->index }}" name="coverage_area[]" value="{{ $zone }}"
                                                           {{ in_array($zone, $selectedZones) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="zone_{{ $loop->index }}">
                                                        {{ $zone }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('coverage_area')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Equipment -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Echipament Mobil</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $equipment = [
                                        'Kit profesional mobil',
                                        'Sterilizator UV',
                                        'Lampa LED/UV',
                                        'Produse premium',
                                        'Instrumentar sterilizat',
                                        'Materiale consumabile',
                                        'Aspirator unghii',
                                        'Scaun mobil ergonomic',
                                        'Sistem de ventilatie'
                                    ];
                                    $selectedEquipment = old('mobile_equipment', $specialist->mobile_equipment ?? []);
                                @endphp
                                <div class="row">
                                    @foreach($equipment as $item)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="equipment_{{ $loop->index }}" name="mobile_equipment[]" value="{{ $item }}"
                                                       {{ in_array($item, $selectedEquipment) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="equipment_{{ $loop->index }}">
                                                    {{ $item }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Profile Image -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Imagine Profil</h5>
                            </div>
                            <div class="card-body text-center">
                                @if($specialist->profile_image)
                                    <img src="{{ asset('storage/' . $specialist->profile_image) }}" 
                                         alt="{{ $specialist->name }}" class="img-thumbnail mb-3" style="max-width: 200px;">
                                @else
                                    <div class="bg-secondary rounded mb-3 d-flex align-items-center justify-content-center" 
                                         style="width: 200px; height: 200px; margin: 0 auto;">
                                        <i class="fas fa-user fa-3x text-white"></i>
                                    </div>
                                @endif
                                
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Schimbă Imaginea</label>
                                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                           id="profile_image" name="profile_image" accept="image/*">
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format acceptat: JPG, PNG. Max 2MB</small>
                                </div>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Status Cont</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    <strong>Status:</strong> 
                                    @if($specialist->is_active)
                                        <span class="badge bg-success">Activ</span>
                                    @else
                                        <span class="badge bg-danger">Inactiv</span>
                                    @endif
                                </p>
                                <p class="mb-2">
                                    <strong>Membru din:</strong> 
                                    {{ $specialist->created_at->format('d.m.Y') }}
                                </p>
                                <p class="mb-0">
                                    <strong>Ultima actualizare:</strong> 
                                    {{ $specialist->updated_at->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Salvează Modificările
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const offersAtSalon = document.getElementById('offers_at_salon');
    const offersAtHome = document.getElementById('offers_at_home');
    const salonAddressSection = document.getElementById('salon_address_section');
    const homeServiceSettings = document.getElementById('home_service_settings');

    // Toggle salon address section
    if (offersAtSalon) {
        offersAtSalon.addEventListener('change', function() {
            salonAddressSection.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Toggle home service settings
    if (offersAtHome) {
        offersAtHome.addEventListener('change', function() {
            homeServiceSettings.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Validation: At least one option must be selected
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!offersAtSalon.checked && !offersAtHome.checked) {
                e.preventDefault();
                alert('Trebuie să selectezi cel puțin o opțiune: servicii la salon sau la domiciliu!');
            }
        });
    }
});
</script>
@endpush
@endsection