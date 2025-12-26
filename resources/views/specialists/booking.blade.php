@extends('layout')

@section('title','Programează-te - '.$specialist->name)

@section('content')
<div class="container" style="padding-top:140px; padding-bottom:60px;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Programează-te la {{ $specialist->name }}</h1>
    @auth
      <div class="d-flex align-items-center gap-3">
        <div class="text-end">
          <small class="text-muted d-block">Conectat ca</small>
          <strong>{{ auth()->user()->name }}</strong>
        </div>
        <a href="{{ route('client.profile') }}" class="btn btn-outline-primary btn-sm">
          <i class="fas fa-user"></i> Profilul meu
        </a>
      </div>
    @endauth
  </div>
  
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('specialists.booking.store', $specialist->slug) }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-12">
        <label class="form-label">Alege serviciul</label>
        <select class="form-select" name="service_id" id="service_select" required>
          @foreach($services as $service)
            <option value="{{ $service->id }}" 
                    data-at-salon="{{ $service->available_at_salon ? 'true' : 'false' }}"
                    data-at-home="{{ $service->available_at_home ? 'true' : 'false' }}"
                    data-home-fee="{{ $service->home_service_fee ?? 0 }}"
                    data-price="{{ $service->price }}"
                    data-duration="{{ $service->duration }}"
                    {{ optional($selectedService)->id === $service->id ? 'selected' : '' }}>
              {{ $service->name }} - {{ $service->formatted_price }} ({{ $service->formatted_duration }})
            </option>
          @endforeach
        </select>
        <small class="text-muted mt-1 d-block" id="duration_info">
          <i class="fas fa-clock text-info"></i> 
          <span id="selected_duration_text">Durata serviciului va fi rezervată în calendarul specialistului.</span>
        </small>
      </div>

      <!-- Service Location Selection -->
      <div class="col-md-12">
        <label class="form-label fw-bold fs-5 mb-3">
          <i class="fas fa-map-marker-alt text-primary me-2"></i>Unde vrei să primești serviciul? *
        </label>
        <div class="alert alert-info mb-3">
          <i class="fas fa-info-circle me-2"></i>
          <strong>Alege unde vrei să beneficiezi de serviciu:</strong> la salon sau la domiciliu
        </div>
        <div class="row" id="location_options">
          @if($specialist->offers_at_salon)
            <div class="col-md-6 mb-3" id="salon_option">
              <div class="card border-primary h-100 location-card" data-location="salon">
                <div class="card-body">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="service_location" 
                           id="location_salon" value="salon" 
                           {{ old('service_location', 'salon') === 'salon' ? 'checked' : '' }} required>
                    <label class="form-check-label w-100" for="location_salon">
                      <div class="d-flex align-items-start">
                        <i class="fas fa-building fa-2x text-primary me-3"></i>
                        <div>
                          <h6 class="mb-1">La Salon</h6>
                          <p class="mb-1 text-muted small">
                            <i class="fas fa-map-marker-alt"></i> 
                            {{ $specialist->salon_address ?? 'Adresă salon disponibilă' }}
                          </p>
                          <p class="mb-0 text-success"><strong>Fără taxe suplimentare</strong></p>
                        </div>
                      </div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          @endif

          @if($specialist->offers_at_home)
            <div class="col-md-6 mb-3" id="home_option">
              <div class="card border-warning h-100 location-card" data-location="home">
                <div class="card-body">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="service_location" 
                           id="location_home" value="home" 
                           {{ old('service_location') === 'home' ? 'checked' : '' }} required>
                    <label class="form-check-label w-100" for="location_home">
                      <div class="d-flex align-items-start">
                        <i class="fas fa-home fa-2x text-warning me-3"></i>
                        <div class="w-100">
                          <h6 class="mb-2">La Domiciliu</h6>
                          <p class="mb-2 text-muted small">Specialistul vine la tine acasă</p>
                          <div class="alert alert-warning py-2 px-3 mb-0 small">
                            <div class="mb-1">
                              <i class="fas fa-car"></i> <strong>Taxa transport: {{ $specialist->transport_fee ?? 0 }} RON</strong>
                            </div>
                            @if($specialist->max_distance)
                              <div class="mb-1">
                                <i class="fas fa-map-marked-alt"></i> Distanță maximă: <strong>{{ $specialist->max_distance }} km</strong>
                              </div>
                            @endif
                            <div id="service_fee_info" style="display: none;">
                              <i class="fas fa-plus"></i> <span id="service_fee_text"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          @endif
        </div>
        
        @if(!$specialist->offers_at_salon && !$specialist->offers_at_home)
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Specialistul nu a configurat încă opțiunile de locație.</strong> Vă rugăm contactați direct specialistul.
          </div>
        @else
          <small class="text-muted">Selectează unde vrei să beneficiezi de serviciu</small>
        @endif
      </div>
      
      <div class="col-md-6">
        <label class="form-label">Nume complet *</label>
        <input type="text" name="client_name" class="form-control" 
               value="{{ old('client_name', auth()->check() ? auth()->user()->name : '') }}" 
               {{ auth()->check() ? 'readonly' : '' }} required>
        @auth
          <small class="text-muted">Datele tale din cont. <a href="{{ route('client.profile') }}">Editează profilul</a></small>
        @endauth
      </div>
      
      <div class="col-md-6">
        <label class="form-label">Email *</label>
        <input type="email" name="client_email" class="form-control" 
               value="{{ old('client_email', auth()->check() ? auth()->user()->email : '') }}" 
               {{ auth()->check() ? 'readonly' : '' }} required>
        @auth
          <small class="text-muted">Email-ul tău din cont</small>
        @endauth
      </div>
      
      <div class="col-md-6">
        <label class="form-label">Telefon *</label>
        <input type="tel" name="client_phone" class="form-control" 
               value="{{ old('client_phone', auth()->check() ? auth()->user()->phone : '') }}" required>
        @auth
          @if(!auth()->user()->phone)
            <small class="text-warning">⚠️ Adaugă telefonul în <a href="{{ route('client.profile') }}">profilul tău</a></small>
          @endif
        @endauth
      </div>
      
      <div class="col-md-6">
        <label class="form-label">Data *</label>
        <input type="date" name="date" class="form-control" value="{{ old('date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
      </div>
      
      <div class="col-md-6">
        <label class="form-label">Ora *</label>
        <input type="time" name="time" class="form-control" value="{{ old('time') }}" required>
      </div>
      
      <div class="col-md-6" id="address_field">
        <label class="form-label">Adresă *</label>
        <input type="text" name="address" class="form-control" id="client_address"
               value="{{ old('address', auth()->check() ? auth()->user()->address : '') }}" 
               placeholder="Strada, număr, bloc, etc.">
        @auth
          @if(!auth()->user()->address)
            <small class="text-warning">⚠️ Adaugă adresa în <a href="{{ route('client.profile') }}">profilul tău</a></small>
          @endif
        @endauth
        <small class="text-muted">Adresa ta unde va veni specialistul</small>
      </div>
      
      <div class="col-12">
        <label class="form-label">Observații speciale (opțional)</label>
        <textarea name="notes" class="form-control" rows="3" placeholder="Mențiuni speciale despre programare...">{{ old('notes') }}</textarea>
      </div>
      
      <!-- Total Cost Summary -->
      <div class="col-12">
        <div class="card bg-light">
          <div class="card-body">
            <h5 class="card-title">Rezumat cost</h5>
            <div class="d-flex justify-content-between mb-2">
              <span>Serviciu: <span id="selected_service_name">-</span></span>
              <strong><span id="service_price">0</span> RON</strong>
            </div>
            <div id="home_service_fee_row" style="display: none;" class="d-flex justify-content-between mb-2 text-warning">
              <span><i class="fas fa-plus"></i> Taxă serviciu la domiciliu</span>
              <strong><span id="home_service_fee_amount">0</span> RON</strong>
            </div>
            <div id="transport_fee_row" style="display: none;" class="d-flex justify-content-between mb-2 text-warning">
              <span><i class="fas fa-car"></i> Taxă transport</span>
              <strong>{{ $specialist->transport_fee ?? 0 }} RON</strong>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
              <h5 class="mb-0">TOTAL:</h5>
              <h5 class="mb-0 text-primary"><strong><span id="total_amount">0</span> RON</strong></h5>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-12">
        <button type="submit" class="btn btn-primary btn-lg w-100">Trimite solicitarea de programare</button>
      </div>
    </div>
  </form>
</div>

<style>
.location-card {
  cursor: pointer;
  transition: all 0.3s ease;
}
.location-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.location-card input[type="radio"]:checked ~ label {
  font-weight: bold;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_select');
    const locationSalon = document.getElementById('location_salon');
    const locationHome = document.getElementById('location_home');
    const addressField = document.getElementById('address_field');
    const clientAddress = document.getElementById('client_address');
    const salonOption = document.getElementById('salon_option');
    const homeOption = document.getElementById('home_option');
    const serviceFeeInfo = document.getElementById('service_fee_info');
    const serviceFeeText = document.getElementById('service_fee_text');
    
    // Total calculation elements
    const selectedServiceName = document.getElementById('selected_service_name');
    const servicePriceDisplay = document.getElementById('service_price');
    const homeServiceFeeRow = document.getElementById('home_service_fee_row');
    const homeServiceFeeAmount = document.getElementById('home_service_fee_amount');
    const transportFeeRow = document.getElementById('transport_fee_row');
    const totalAmountDisplay = document.getElementById('total_amount');
    
    const transportFee = {{ $specialist->transport_fee ?? 0 }};
    
    let currentServicePrice = 0;
    let currentHomeFee = 0;
    
    // Calculate and display total
    function updateTotal() {
        const isHome = locationHome && locationHome.checked;
        let total = currentServicePrice;
        
        // Show/hide fee rows
        if (isHome) {
            if (currentHomeFee > 0) {
                homeServiceFeeRow.style.display = 'flex';
                homeServiceFeeAmount.textContent = currentHomeFee.toFixed(0);
                total += currentHomeFee;
            } else {
                homeServiceFeeRow.style.display = 'none';
            }
            
            if (transportFee > 0) {
                transportFeeRow.style.display = 'flex';
                total += transportFee;
            }
        } else {
            homeServiceFeeRow.style.display = 'none';
            transportFeeRow.style.display = 'none';
        }
        
        totalAmountDisplay.textContent = total.toFixed(0);
    }
    
    // Handle location selection
    function updateAddressField() {
        if (locationSalon && locationSalon.checked) {
            addressField.style.display = 'none';
            clientAddress.required = false;
            clientAddress.value = '';
        } else if (locationHome && locationHome.checked) {
            addressField.style.display = 'block';
            clientAddress.required = true;
        }
    }
    
    // Handle service selection - show/hide location options based on service availability
    if (serviceSelect) {
        serviceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const atSalon = selectedOption.dataset.atSalon === 'true';
            const atHome = selectedOption.dataset.atHome === 'true';
            const homeFee = parseFloat(selectedOption.dataset.homeFee) || 0;
            const duration = parseInt(selectedOption.dataset.duration) || 60;
            const price = parseFloat(selectedOption.dataset.price) || 0;
            
            // Update duration info text
            const durationText = document.getElementById('selected_duration_text');
            if (durationText) {
                const hours = Math.floor(duration / 60);
                const mins = duration % 60;
                let durationFormatted = '';
                if (hours > 0) {
                    durationFormatted = hours + 'h' + (mins > 0 ? ' ' + mins + ' min' : '');
                } else {
                    durationFormatted = mins + ' minute';
                }
                durationText.textContent = 'Acest serviciu durează ' + durationFormatted + '. Ora selectată va fi blocată în calendarul specialistului.';
            }
            
            // Extract service name and price from option text
            const optionText = selectedOption.textContent;
            const serviceName = optionText.split(' - ')[0];
            
            // Update current values
            currentServicePrice = servicePrice;
            currentHomeFee = homeFee;
            
            // Update display
            selectedServiceName.textContent = serviceName;
            servicePriceDisplay.textContent = servicePrice.toFixed(0);
            
            // Show/hide location options
            if (salonOption) {
                salonOption.style.display = atSalon ? 'block' : 'none';
            }
            if (homeOption) {
                homeOption.style.display = atHome ? 'block' : 'none';
                if (serviceFeeInfo && atHome && homeFee > 0) {
                    serviceFeeInfo.style.display = 'block';
                    serviceFeeText.textContent = `Taxă serviciu domiciliu: ${homeFee} RON`;
                } else if (serviceFeeInfo) {
                    serviceFeeInfo.style.display = 'none';
                }
            }
            
            // Auto-select if only one option available
            if (atSalon && !atHome && locationSalon) {
                locationSalon.checked = true;
            } else if (!atSalon && atHome && locationHome) {
                locationHome.checked = true;
            }
            
            updateAddressField();
            updateTotal();
        });
        
        // Trigger on page load
        serviceSelect.dispatchEvent(new Event('change'));
    }
    
    // Handle location radio button changes
    if (locationSalon) {
        locationSalon.addEventListener('change', function() {
            updateAddressField();
            updateTotal();
        });
    }
    if (locationHome) {
        locationHome.addEventListener('change', function() {
            updateAddressField();
            updateTotal();
        });
    }
    
    // Handle card click to select radio
    document.querySelectorAll('.location-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT') {
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    updateAddressField();
                }
            }
        });
    });
    
    // Initial state
    updateAddressField();
    
    // Trigger initial calculation if service is pre-selected
    if (serviceSelect && serviceSelect.selectedIndex >= 0) {
        serviceSelect.dispatchEvent(new Event('change'));
    }
});
</script>

@endsection