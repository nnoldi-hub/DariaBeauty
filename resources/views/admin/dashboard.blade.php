@extends('layout')

@section('title', 'Panou Admin - DariaBeauty')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Dashboard Admin</h3>
                <div>
                    <span class="text-muted">{{ now()->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <!-- Statistici Principale -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 opacity-75">Specialiști</h6>
                                    <h2 class="card-title mb-0">{{ \App\Models\User::where('role', 'specialist')->count() }}</h2>
                                    <small class="opacity-75">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \App\Models\User::where('role', 'specialist')->where('is_active', false)->count() }} în așteptare
                                    </small>
                                </div>
                                <i class="fas fa-users fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 opacity-75">Programări Azi</h6>
                                    <h2 class="card-title mb-0">{{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }}</h2>
                                    <small class="opacity-75">
                                        <i class="fas fa-check me-1"></i>
                                        {{ \App\Models\Appointment::whereDate('appointment_date', today())->where('status', 'confirmed')->count() }} confirmate
                                    </small>
                                </div>
                                <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 opacity-75">Reviews Noi</h6>
                                    <h2 class="card-title mb-0">{{ \App\Models\Review::where('is_approved', false)->count() }}</h2>
                                    <small class="opacity-75">
                                        <i class="fas fa-star me-1"></i>
                                        Necesită aprobare
                                    </small>
                                </div>
                                <i class="fas fa-comment-dots fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 opacity-75">Utilizatori</h6>
                                    <h2 class="card-title mb-0">{{ \App\Models\User::count() }}</h2>
                                    <small class="opacity-75">
                                        <i class="fas fa-user-plus me-1"></i>
                                        {{ \App\Models\User::whereDate('created_at', today())->count() }} noi azi
                                    </small>
                                </div>
                                <i class="fas fa-user-circle fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acțiuni Rapide -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-user-check text-primary me-2"></i>
                                Specialiști în așteptare
                            </h5>
                            <p class="card-text text-muted">Gestionează cererile de înregistrare.</p>
                            <a href="{{ route('admin.specialists.pending') }}" class="btn btn-primary btn-sm">
                                Vezi lista
                                @if(\App\Models\User::where('role', 'specialist')->where('is_active', false)->count() > 0)
                                    <span class="badge bg-light text-dark ms-2">
                                        {{ \App\Models\User::where('role', 'specialist')->where('is_active', false)->count() }}
                                    </span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-concierge-bell text-success me-2"></i>
                                Servicii
                            </h5>
                            <p class="card-text text-muted">Gestionează serviciile platformei.</p>
                            <a href="{{ route('admin.services') }}" class="btn btn-outline-success btn-sm">Administrează</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-cog text-secondary me-2"></i>
                                Setări
                            </h5>
                            <p class="card-text text-muted">Configurează aplicația.</p>
                            <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary btn-sm">Deschide</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activitate Recentă -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Activitate Recentă</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @php
                            $recentUsers = \App\Models\User::where('role', 'specialist')->latest()->take(5)->get();
                        @endphp
                        @forelse($recentUsers as $user)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-plus text-primary me-2"></i>
                                    <strong>{{ $user->name }}</strong> s-a înregistrat ca specialist
                                    @if(!$user->is_active)
                                        <span class="badge bg-warning text-dark ms-2">În așteptare</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0">Nu există activitate recentă</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
