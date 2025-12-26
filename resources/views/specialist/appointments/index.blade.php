@extends('layout')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('specialist.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Programările Mele</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Astăzi</h5>
                            <p class="card-text display-6">{{ $todayCount ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h5 class="card-title text-warning">În Așteptare</h5>
                            <p class="card-text display-6">{{ $pendingCount ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h5 class="card-title text-success">Confirmate</h5>
                            <p class="card-text display-6">{{ $confirmedCount ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h5 class="card-title text-info">Finalizate</h5>
                            <p class="card-text display-6">{{ $completedCount ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('specialist.appointments.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Toate</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>În Așteptare</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmat</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Finalizat</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Anulat</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Data Început</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">Data Sfârșit</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter"></i> Filtrează
                                </button>
                                <a href="{{ route('specialist.appointments.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Resetează
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lista Programări</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data și Ora</th>
                                    <th>Serviciu</th>
                                    <th>Client</th>
                                    <th>Telefon</th>
                                    <th>Locație</th>
                                    <th>Status</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->id }}</td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y') }}</strong><br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                                @php
                                                    $duration = $appointment->duration ?? ($appointment->service->duration ?? 60);
                                                    $endTime = \Carbon\Carbon::parse($appointment->appointment_time)->addMinutes($duration);
                                                @endphp
                                                - {{ $endTime->format('H:i') }}
                                            </small><br>
                                            <span class="badge bg-secondary" style="font-size: 0.7em;">
                                                <i class="fas fa-clock"></i> {{ $duration }} min
                                            </span>
                                        </td>
                                        <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                        <td>{{ $appointment->client_name }}</td>
                                        <td>{{ $appointment->client_phone }}</td>
                                        <td>
                                            @if($appointment->location === 'salon')
                                                <span class="badge bg-info">Salon</span>
                                            @else
                                                <span class="badge bg-warning">Deplasare</span><br>
                                                <small class="text-muted">{{ $appointment->client_address }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($appointment->status === 'pending')
                                                <span class="badge bg-warning">În Așteptare</span>
                                            @elseif($appointment->status === 'confirmed')
                                                <span class="badge bg-success">Confirmat</span>
                                            @elseif($appointment->status === 'completed')
                                                <span class="badge bg-info">Finalizat</span>
                                            @elseif($appointment->status === 'cancelled')
                                                <span class="badge bg-danger">Anulat</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1" role="group">
                                                @if($appointment->status === 'pending')
                                                    <form method="POST" action="{{ route('specialist.appointments.confirm', $appointment->id) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" title="Confirmă">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($appointment->status === 'confirmed')
                                                    <form method="POST" action="{{ route('specialist.appointments.complete', $appointment->id) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-sm" title="Marchează ca Finalizat">
                                                            <i class="fas fa-check-double"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($appointment->status === 'completed')
                                                    <form method="POST" action="{{ route('specialist.appointments.send-review-whatsapp', $appointment->id) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" title="Trimite Review WhatsApp" onclick="return confirm('Sigur vrei să trimiți link-ul de review prin WhatsApp?')">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-info btn-sm copy-review-link" 
                                                            data-appointment-id="{{ $appointment->id }}"
                                                            data-client-name="{{ $appointment->client_name }}"
                                                            title="Copiază Link Review">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $appointment->id }}" title="Detalii">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Details Modal -->
                                    <div class="modal fade" id="detailsModal{{ $appointment->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detalii Programare #{{ $appointment->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl class="row">
                                                        <dt class="col-sm-4">Data și Ora:</dt>
                                                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y') }} la {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</dd>

                                                        <dt class="col-sm-4">Serviciu:</dt>
                                                        <dd class="col-sm-8">{{ $appointment->service->name ?? 'N/A' }}</dd>

                                                        <dt class="col-sm-4">Client:</dt>
                                                        <dd class="col-sm-8">{{ $appointment->client_name }}</dd>

                                                        <dt class="col-sm-4">Email:</dt>
                                                        <dd class="col-sm-8">{{ $appointment->client_email }}</dd>

                                                        <dt class="col-sm-4">Telefon:</dt>
                                                        <dd class="col-sm-8">{{ $appointment->client_phone }}</dd>

                                                        <dt class="col-sm-4">Locație:</dt>
                                                        <dd class="col-sm-8">
                                                            @if($appointment->location === 'salon')
                                                                Salon
                                                            @else
                                                                Deplasare la: {{ $appointment->client_address }}
                                                            @endif
                                                        </dd>

                                                        <dt class="col-sm-4">Status:</dt>
                                                        <dd class="col-sm-8">
                                                            @if($appointment->status === 'pending')
                                                                <span class="badge bg-warning">În Așteptare</span>
                                                            @elseif($appointment->status === 'confirmed')
                                                                <span class="badge bg-success">Confirmat</span>
                                                            @elseif($appointment->status === 'completed')
                                                                <span class="badge bg-info">Finalizat</span>
                                                            @elseif($appointment->status === 'cancelled')
                                                                <span class="badge bg-danger">Anulat</span>
                                                            @endif
                                                        </dd>

                                                        @if($appointment->notes)
                                                            <dt class="col-sm-4">Observații:</dt>
                                                            <dd class="col-sm-8">{{ $appointment->notes }}</dd>
                                                        @endif

                                                        <dt class="col-sm-4">Creat la:</dt>
                                                        <dd class="col-sm-8">{{ $appointment->created_at->format('d.m.Y H:i') }}</dd>
                                                    </dl>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Nu există programări</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($appointments) && method_exists($appointments, 'links'))
                        <div class="mt-3">
                            {{ $appointments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="copyToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Link-ul a fost copiat în clipboard!
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle copy review link buttons
    document.querySelectorAll('.copy-review-link').forEach(button => {
        button.addEventListener('click', async function() {
            const appointmentId = this.dataset.appointmentId;
            const clientName = this.dataset.clientName;
            const btn = this;
            
            // Disable button temporarily
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            try {
                // Generate review token via AJAX
                const response = await fetch(`/specialist/programari/genereaza-link-review/${appointmentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Copy to clipboard
                    await navigator.clipboard.writeText(data.link);
                    
                    // Show success toast
                    const toastEl = document.getElementById('copyToast');
                    const toastMessage = document.getElementById('toastMessage');
                    toastMessage.innerHTML = `
                        <strong>Link copiat!</strong><br>
                        <small class="text-muted">Pentru: ${clientName}</small><br>
                        <code class="text-break small">${data.link}</code>
                    `;
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                } else {
                    alert('Eroare: ' + (data.message || 'Nu s-a putut genera link-ul'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Eroare la generarea link-ului. Verifică consola pentru detalii.');
            } finally {
                // Re-enable button
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        });
    });
});
</script>
@endpush
