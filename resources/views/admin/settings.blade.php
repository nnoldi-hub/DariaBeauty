@extends('layout')

@section('title', 'Setări - Admin DariaBeauty')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Setări Platformă</h3>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Setări Generale</h5>
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nume Platformă</label>
                                <input type="text" class="form-control" name="platform_name" value="{{ $settings['platform_name'] ?? 'DariaBeauty' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Contact</label>
                                <input type="email" class="form-control" name="contact_email" value="{{ $settings['contact_email'] ?? 'contact@dariabeauty.ro' }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefon Contact</label>
                                <input type="text" class="form-control" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+40 XXX XXX XXX' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Comision Platformă (%)</label>
                                <input type="number" class="form-control" name="platform_commission" value="{{ $settings['platform_commission'] ?? 15 }}" min="0" max="100" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6>Program Lucru Default</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Oră Început</label>
                                <input type="time" class="form-control" name="default_start_time" value="{{ $settings['default_start_time'] ?? '09:00' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Oră Sfârșit</label>
                                <input type="time" class="form-control" name="default_end_time" value="{{ $settings['default_end_time'] ?? '18:00' }}" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6>Notificări Email</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="notify_new_specialist" id="notify1" {{ ($settings['notify_new_specialist'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify1">
                                Specialist nou înregistrat
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="notify_new_booking" id="notify2" {{ ($settings['notify_new_booking'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify2">
                                Programare nouă
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="notify_negative_review" id="notify3" {{ ($settings['notify_negative_review'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify3">
                                Review negativ (sub 3 stele)
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvează Setări
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Integrări API</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Twilio API Key (SMS)</label>
                            <input type="text" class="form-control" placeholder="SK..." disabled>
                            <small class="text-muted">În dezvoltare</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stripe API Key (Plăți)</label>
                            <input type="text" class="form-control" placeholder="pk_..." disabled>
                            <small class="text-muted">În dezvoltare</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
