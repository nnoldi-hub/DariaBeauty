@extends('layout')

@section('title', 'Dashboard Specialist - DariaBeauty')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('specialist.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3>Bun venit, {{ $specialist->name }}!</h3>
                    <p class="text-muted mb-0">
                        <span class="badge" style="background-color: {{ $specialist->sub_brand === 'dariaNails' ? '#E91E63' : ($specialist->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800') }}">
                            {{ ucfirst(str_replace('daria', '', $specialist->sub_brand)) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Statistici -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: #06D6A0;">
                        <div class="card-body">
                            <h6 class="card-title">Programări Astăzi</h6>
                            <h2 class="mb-0">{{ $stats['today_appointments'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: #118AB2;">
                        <div class="card-body">
                            <h6 class="card-title">Programări Viitoare</h6>
                            <h2 class="mb-0">{{ $stats['upcoming_appointments'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: #FFD60A;">
                        <div class="card-body">
                            <h6 class="card-title">Rating Mediu</h6>
                            <h2 class="mb-0">{{ number_format($stats['average_rating'], 1) }} ⭐</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: #9C27B0;">
                        <div class="card-body">
                            <h6 class="card-title">Total Reviews</h6>
                            <h2 class="mb-0">{{ $stats['total_reviews'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Programări Recente -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Programări Recente</h5>
                        <a href="{{ route('specialist.appointments.index') }}" class="btn btn-sm btn-outline-primary">
                            Vezi toate
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data & Ora</th>
                                        <th>Client</th>
                                        <th>Serviciu</th>
                                        <th>Status</th>
                                        <th>Preț</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAppointments as $appointment)
                                        <tr>
                                            <td>
                                                {{ $appointment->appointment_date->format('d M Y') }}<br>
                                                <small class="text-muted">{{ $appointment->appointment_time }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $appointment->client_name }}</strong><br>
                                                <small class="text-muted">{{ $appointment->client_phone }}</small>
                                            </td>
                                            <td>{{ $appointment->service ? $appointment->service->name : 'N/A' }}</td>
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Nu ai programări recente.</p>
                    @endif
                </div>
            </div>

            <!-- Reviews Recente -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Reviews Recente</h5>
                        <a href="{{ route('specialist.reviews.index') }}" class="btn btn-sm btn-outline-primary">
                            Vezi toate
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentReviews->count() > 0)
                        @foreach($recentReviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong>{{ $review->client_name }}</strong>
                                        <div style="color: #FFD700;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    ⭐
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0">{{ Str::limit($review->comment, 150) }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">Nu ai reviews încă.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
