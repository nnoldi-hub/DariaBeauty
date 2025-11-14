@extends('layout')

@section('title', 'Programări - Admin DariaBeauty')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Monitorizare Programări</h3>
                <a href="{{ route('admin.appointments.export', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Export Excel
                </a>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Astăzi</h6>
                            <h3 class="mb-0">{{ $stats['today'] }}</h3>
                            <small>programări</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Confirmate</h6>
                            <h3 class="mb-0">{{ $stats['confirmed'] }}</h3>
                            <small>finalizate</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">În așteptare</h6>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                            <small>neprocesate</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6 class="card-title">Anulate</h6>
                            <h3 class="mb-0">{{ $stats['cancelled'] }}</h3>
                            <small>azi</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.appointments') }}" class="row mb-3">
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">Toate statusurile</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>În așteptare</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmată</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Finalizată</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Anulată</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="brand">
                                <option value="">Toate brandurile</option>
                                <option value="dariaNails" {{ request('brand') == 'dariaNails' ? 'selected' : '' }}>dariaNails</option>
                                <option value="dariaHair" {{ request('brand') == 'dariaHair' ? 'selected' : '' }}>dariaHair</option>
                                <option value="dariaGlow" {{ request('brand') == 'dariaGlow' ? 'selected' : '' }}>dariaGlow</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="search" class="form-control" placeholder="Caută specialist..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Dată & Oră</th>
                                    <th>Client</th>
                                    <th>Specialist</th>
                                    <th>Serviciu</th>
                                    <th>Brand</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    <tr>
                                        <td>#{{ $appointment->id }}</td>
                                        <td>
                                            {{ $appointment->appointment_date ? $appointment->appointment_date->format('d M Y') : 'N/A' }}, 
                                            {{ $appointment->appointment_time ?? 'N/A' }}
                                        </td>
                                        <td>{{ $appointment->client_name ?? 'N/A' }}</td>
                                        <td>{{ $appointment->specialist ? $appointment->specialist->name : 'N/A' }}</td>
                                        <td>{{ $appointment->service ? $appointment->service->name : 'N/A' }}</td>
                                        <td>
                                            @if($appointment->specialist)
                                                <span class="badge" style="background-color: {{ $appointment->specialist->sub_brand === 'dariaNails' ? '#E91E63' : ($appointment->specialist->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800') }}">
                                                    {{ ucfirst(str_replace('daria', '', $appointment->specialist->sub_brand)) }}
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($appointment->status === 'pending')
                                                <span class="badge bg-warning text-dark">În așteptare</span>
                                            @elseif($appointment->status === 'confirmed')
                                                <span class="badge bg-success">Confirmată</span>
                                            @elseif($appointment->status === 'completed')
                                                <span class="badge bg-info">Finalizată</span>
                                            @else
                                                <span class="badge bg-danger">Anulată</span>
                                            @endif
                                        </td>
                                        <td>{{ $appointment->service ? $appointment->service->price : '0' }} RON</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Nu există programări care să corespundă criteriilor de căutare.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($appointments->hasPages())
                        <div class="mt-3">
                            {{ $appointments->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
