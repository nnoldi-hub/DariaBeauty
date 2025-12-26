@extends('layout')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('specialist.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="fas fa-calendar-alt text-primary me-2"></i>Programul Meu de Lucru</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
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

            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Setează-ți programul de lucru!</strong> 
                Clienții vor putea rezerva doar în orele pe care le setezi aici. 
                Poți avea ore diferite pentru salon și deplasări la domiciliu.
            </div>

            <form method="POST" action="{{ route('specialist.schedule.store') }}">
                @csrf

                <!-- Setări Generale -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Setări Generale Programări</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="slot_interval" class="form-label">
                                    <i class="fas fa-clock text-info me-1"></i>
                                    Interval între programări
                                </label>
                                <select name="slot_interval" id="slot_interval" class="form-select">
                                    <option value="15" {{ ($specialist->slot_interval ?? 30) == 15 ? 'selected' : '' }}>15 minute</option>
                                    <option value="30" {{ ($specialist->slot_interval ?? 30) == 30 ? 'selected' : '' }}>30 minute</option>
                                    <option value="45" {{ ($specialist->slot_interval ?? 30) == 45 ? 'selected' : '' }}>45 minute</option>
                                    <option value="60" {{ ($specialist->slot_interval ?? 30) == 60 ? 'selected' : '' }}>1 oră</option>
                                </select>
                                <small class="text-muted">La câte minute pot începe programările</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="min_booking_notice" class="form-label">
                                    <i class="fas fa-hourglass-start text-warning me-1"></i>
                                    Timp minim rezervare
                                </label>
                                <select name="min_booking_notice" id="min_booking_notice" class="form-select">
                                    <option value="1" {{ ($specialist->min_booking_notice ?? 2) == 1 ? 'selected' : '' }}>1 oră înainte</option>
                                    <option value="2" {{ ($specialist->min_booking_notice ?? 2) == 2 ? 'selected' : '' }}>2 ore înainte</option>
                                    <option value="4" {{ ($specialist->min_booking_notice ?? 2) == 4 ? 'selected' : '' }}>4 ore înainte</option>
                                    <option value="12" {{ ($specialist->min_booking_notice ?? 2) == 12 ? 'selected' : '' }}>12 ore înainte</option>
                                    <option value="24" {{ ($specialist->min_booking_notice ?? 2) == 24 ? 'selected' : '' }}>24 ore înainte</option>
                                    <option value="48" {{ ($specialist->min_booking_notice ?? 2) == 48 ? 'selected' : '' }}>48 ore înainte</option>
                                </select>
                                <small class="text-muted">Cu cât timp înainte trebuie să rezerve</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="max_booking_days" class="form-label">
                                    <i class="fas fa-calendar-plus text-success me-1"></i>
                                    Zile în avans
                                </label>
                                <select name="max_booking_days" id="max_booking_days" class="form-select">
                                    <option value="7" {{ ($specialist->max_booking_days ?? 30) == 7 ? 'selected' : '' }}>1 săptămână</option>
                                    <option value="14" {{ ($specialist->max_booking_days ?? 30) == 14 ? 'selected' : '' }}>2 săptămâni</option>
                                    <option value="30" {{ ($specialist->max_booking_days ?? 30) == 30 ? 'selected' : '' }}>1 lună</option>
                                    <option value="60" {{ ($specialist->max_booking_days ?? 30) == 60 ? 'selected' : '' }}>2 luni</option>
                                    <option value="90" {{ ($specialist->max_booking_days ?? 30) == 90 ? 'selected' : '' }}>3 luni</option>
                                </select>
                                <small class="text-muted">Cu cât timp pot rezerva în avans</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Program Săptămânal -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-week me-2"></i>Program Săptămânal</h5>
                    </div>
                    <div class="card-body">
                        @foreach($daysOfWeek as $dayNum => $dayName)
                            @php $schedule = $schedules[$dayNum]; @endphp
                            <div class="card mb-3 {{ $schedule->isWorkingDay() ? 'border-success' : 'border-secondary' }}">
                                <div class="card-header {{ $schedule->isWorkingDay() ? 'bg-success bg-opacity-10' : 'bg-light' }} d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-calendar-day me-2"></i>
                                        <strong>{{ $dayName }}</strong>
                                    </h6>
                                    <span class="badge {{ $schedule->isWorkingDay() ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $schedule->isWorkingDay() ? 'Activ' : 'Liber' }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="schedules[{{ $dayNum }}][day_of_week]" value="{{ $dayNum }}">
                                    
                                    <div class="row">
                                        <!-- Disponibilitate Salon -->
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input salon-toggle" type="checkbox" 
                                                       id="salon_{{ $dayNum }}" 
                                                       name="schedules[{{ $dayNum }}][available_at_salon]"
                                                       value="1"
                                                       data-day="{{ $dayNum }}"
                                                       {{ $schedule->available_at_salon ? 'checked' : '' }}>
                                                <label class="form-check-label" for="salon_{{ $dayNum }}">
                                                    <i class="fas fa-building text-primary me-1"></i>
                                                    <strong>La Salon</strong>
                                                </label>
                                            </div>
                                            <div class="salon-hours-{{ $dayNum }} {{ !$schedule->available_at_salon ? 'd-none' : '' }}">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label class="form-label small">De la</label>
                                                        <input type="time" class="form-control form-control-sm" 
                                                               name="schedules[{{ $dayNum }}][salon_start_time]"
                                                               value="{{ $schedule->salon_start_time ?? '09:00' }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">Până la</label>
                                                        <input type="time" class="form-control form-control-sm" 
                                                               name="schedules[{{ $dayNum }}][salon_end_time]"
                                                               value="{{ $schedule->salon_end_time ?? '18:00' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Disponibilitate Domiciliu -->
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input home-toggle" type="checkbox" 
                                                       id="home_{{ $dayNum }}" 
                                                       name="schedules[{{ $dayNum }}][available_at_home]"
                                                       value="1"
                                                       data-day="{{ $dayNum }}"
                                                       {{ $schedule->available_at_home ? 'checked' : '' }}>
                                                <label class="form-check-label" for="home_{{ $dayNum }}">
                                                    <i class="fas fa-home text-warning me-1"></i>
                                                    <strong>La Domiciliu</strong>
                                                </label>
                                            </div>
                                            <div class="home-hours-{{ $dayNum }} {{ !$schedule->available_at_home ? 'd-none' : '' }}">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label class="form-label small">De la</label>
                                                        <input type="time" class="form-control form-control-sm" 
                                                               name="schedules[{{ $dayNum }}][home_start_time]"
                                                               value="{{ $schedule->home_start_time ?? '10:00' }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">Până la</label>
                                                        <input type="time" class="form-control form-control-sm" 
                                                               name="schedules[{{ $dayNum }}][home_end_time]"
                                                               value="{{ $schedule->home_end_time ?? '20:00' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pauză și Note -->
                                    <div class="row mt-2" id="break-section-{{ $dayNum }}" 
                                         style="{{ !$schedule->isWorkingDay() ? 'display:none' : '' }}">
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted">
                                                <i class="fas fa-coffee me-1"></i>Pauză de masă (opțional)
                                            </label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <input type="time" class="form-control form-control-sm" 
                                                           name="schedules[{{ $dayNum }}][break_start_time]"
                                                           value="{{ $schedule->break_start_time }}"
                                                           placeholder="13:00">
                                                </div>
                                                <div class="col-6">
                                                    <input type="time" class="form-control form-control-sm" 
                                                           name="schedules[{{ $dayNum }}][break_end_time]"
                                                           value="{{ $schedule->break_end_time }}"
                                                           placeholder="14:00">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted">
                                                <i class="fas fa-sticky-note me-1"></i>Note (opțional)
                                            </label>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="schedules[{{ $dayNum }}][notes]"
                                                   value="{{ $schedule->notes }}"
                                                   placeholder="Ex: Program redus, doar dimineața">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Salvează Programul
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle pentru ore salon
    document.querySelectorAll('.salon-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const day = this.dataset.day;
            const hoursDiv = document.querySelector('.salon-hours-' + day);
            const breakSection = document.getElementById('break-section-' + day);
            
            if (this.checked) {
                hoursDiv.classList.remove('d-none');
            } else {
                hoursDiv.classList.add('d-none');
            }
            
            updateBreakSection(day);
        });
    });

    // Toggle pentru ore domiciliu
    document.querySelectorAll('.home-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const day = this.dataset.day;
            const hoursDiv = document.querySelector('.home-hours-' + day);
            
            if (this.checked) {
                hoursDiv.classList.remove('d-none');
            } else {
                hoursDiv.classList.add('d-none');
            }
            
            updateBreakSection(day);
        });
    });

    function updateBreakSection(day) {
        const salonChecked = document.getElementById('salon_' + day).checked;
        const homeChecked = document.getElementById('home_' + day).checked;
        const breakSection = document.getElementById('break-section-' + day);
        
        if (salonChecked || homeChecked) {
            breakSection.style.display = '';
        } else {
            breakSection.style.display = 'none';
        }
    }
});
</script>
@endsection
