@extends('layout')

@section('title', 'Programările Mele')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                    <small class="text-muted">Client</small>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="{{ route('client.profile') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user"></i> Profil
                        </a>
                        <a href="{{ route('client.appointments') }}" class="btn btn-primary btn-sm active">
                            <i class="fas fa-calendar"></i> Programări
                        </a>
                        <a href="{{ route('client.reviews') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-star"></i> Review-uri
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-sign-out-alt"></i> Deconectare
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Statistici -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-primary">{{ $stats['total'] }}</h3>
                            <small>Total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-warning">{{ $stats['pending'] }}</h3>
                            <small>În așteptare</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-info">{{ $stats['confirmed'] }}</h3>
                            <small>Confirmate</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-success">{{ $stats['completed'] }}</h3>
                            <small>Completate</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista Programări -->
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Programările Mele</h5>
                    <a href="{{ route('specialists.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Programare Nouă
                    </a>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data & Ora</th>
                                        <th>Specialist</th>
                                        <th>Serviciu</th>
                                        <th>Status</th>
                                        <th>Preț</th>
                                        <th>Acțiuni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y') }}</strong><br>
                                                <small class="text-muted">{{ $appointment->appointment_time }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('specialists.show', $appointment->specialist->slug) }}">
                                                    {{ $appointment->specialist->name }}
                                                </a>
                                            </td>
                                            <td>{{ $appointment->service->name }}</td>
                                            <td>
                                                @if($appointment->status === 'pending')
                                                    <span class="badge bg-warning">În așteptare</span>
                                                @elseif($appointment->status === 'confirmed')
                                                    <span class="badge bg-info">Confirmată</span>
                                                @elseif($appointment->status === 'completed')
                                                    <span class="badge bg-success">Completată</span>
                                                @elseif($appointment->status === 'cancelled')
                                                    <span class="badge bg-danger">Anulată</span>
                                                @endif
                                            </td>
                                            <td>{{ $appointment->service->formatted_price }}</td>
                                            <td>
                                                @if($appointment->status === 'completed' && !$appointment->review)
                                                    <a href="{{ route('reviews.create', $appointment->id) }}" 
                                                       class="btn btn-sm btn-warning" title="Lasă Review">
                                                        <i class="fas fa-star"></i>
                                                    </a>
                                                @elseif($appointment->review)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Review dat
                                                    </span>
                                                @endif
                                                
                                                @if(in_array($appointment->status, ['pending', 'confirmed']))
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="if(confirm('Sigur vrei să anulezi programarea?')) document.getElementById('cancel-{{ $appointment->id }}').submit()">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <form id="cancel-{{ $appointment->id }}" 
                                                          action="{{ route('appointments.cancel', $appointment->id) }}" 
                                                          method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginare -->
                        <div class="mt-3">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5>Nu ai încă programări</h5>
                            <p class="text-muted">Caută un specialist și programează-te pentru primul tău serviciu!</p>
                            <a href="{{ route('specialists.index') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i> Caută Specialiști
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
