@extends('layout')

@section('title', 'Gestionare SMS - Twilio')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">
                    <i class="fas fa-sms me-2" style="color: #D4AF37;"></i>
                    Integrări API - Twilio SMS
                </h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Status Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-1">Status Integrare Twilio</h5>
                                    <p class="text-muted mb-0">Serviciul de notificări SMS automate</p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    @if($isConfigured)
                                        @if($isEnabled)
                                            <span class="badge bg-success fs-6 px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i> Activ
                                            </span>
                                        @else
                                            <span class="badge bg-warning fs-6 px-3 py-2">
                                                <i class="fas fa-pause-circle me-1"></i> Dezactivat
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary fs-6 px-3 py-2">
                                            <i class="fas fa-exclamation-circle me-1"></i> Neconfigurat
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-paper-plane fa-2x mb-2" style="color: #28a745;"></i>
                            <h3 class="mb-1">{{ number_format($stats['total_sent']) }}</h3>
                            <p class="text-muted mb-0 small">Total Trimise</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2" style="color: #dc3545;"></i>
                            <h3 class="mb-1">{{ number_format($stats['total_failed']) }}</h3>
                            <p class="text-muted mb-0 small">Eșuate</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-day fa-2x mb-2" style="color: #D4AF37;"></i>
                            <h3 class="mb-1">{{ number_format($stats['today_sent']) }}</h3>
                            <p class="text-muted mb-0 small">Astăzi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #17a2b8;"></i>
                            <h3 class="mb-1">{{ number_format($stats['this_month']) }}</h3>
                            <p class="text-muted mb-0 small">Luna Aceasta</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Configuration Form -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-cog me-2"></i>Configurare Twilio
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.sms.update-config') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label class="form-label">Twilio Account SID</label>
                                    <input type="text" name="twilio_sid" class="form-control" 
                                           value="{{ config('twilio.sid') }}" 
                                           placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                                    <small class="form-text text-muted">
                                        Găsești în <a href="https://console.twilio.com/" target="_blank">Twilio Console</a>
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Auth Token</label>
                                    <input type="password" name="twilio_auth_token" class="form-control" 
                                           value="{{ config('twilio.auth_token') }}" 
                                           placeholder="********************************">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Număr Telefon Twilio</label>
                                    <input type="text" name="twilio_phone_number" class="form-control" 
                                           value="{{ config('twilio.phone_number') }}" 
                                           placeholder="+40XXXXXXXXX">
                                    <small class="form-text text-muted">Format: +40XXXXXXXXX</small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="twilio_enabled" value="0">
                                        <input type="checkbox" name="twilio_enabled" value="1" 
                                               class="form-check-input" id="twilioEnabled"
                                               {{ $isEnabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="twilioEnabled">
                                            Activează Serviciul SMS
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Salvează Configurația
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Test & Actions -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-vial me-2"></i>Test SMS
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.sms.send-test') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label class="form-label">Număr Telefon</label>
                                    <input type="text" name="phone" class="form-control" 
                                           placeholder="+40XXXXXXXXX" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mesaj</label>
                                    <textarea name="message" class="form-control" rows="3" 
                                              placeholder="Test SMS de la DariaBeauty" 
                                              maxlength="160" required></textarea>
                                    <small class="form-text text-muted">Max 160 caractere</small>
                                </div>

                                <button type="submit" class="btn btn-success w-100" 
                                        {{ !$isEnabled ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane me-2"></i>Trimite SMS Test
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-tools me-2"></i>Acțiuni Rapide
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.sms.send-reminders') }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary w-100"
                                        {{ !$isEnabled ? 'disabled' : '' }}>
                                    <i class="fas fa-bell me-2"></i>Trimite Reminder-uri Acum
                                </button>
                            </form>
                            <small class="text-muted d-block mb-3">
                                Trimite reminder-uri pentru programările de mâine
                            </small>

                            <a href="https://console.twilio.com/" target="_blank" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-external-link-alt me-2"></i>Deschide Twilio Console
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent SMS Logs -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Istoric SMS Recent
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Destinatar</th>
                                    <th>Tip</th>
                                    <th>Status</th>
                                    <th>Mesaj</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLogs as $log)
                                    <tr>
                                        <td class="small">
                                            {{ $log->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $log->to }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ str_replace('_', ' ', ucfirst($log->type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($log->status === 'sent')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Trimis
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times"></i> Eșuat
                                                </span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            {{ Str::limit($log->message, 50) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Nu există SMS-uri trimise încă
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
