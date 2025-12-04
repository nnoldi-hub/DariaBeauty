@extends('layout')

@section('title', 'Profilul Meu')

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
                        <a href="{{ route('client.profile') }}" class="btn btn-primary btn-sm active">
                            <i class="fas fa-user"></i> Profil
                        </a>
                        <a href="{{ route('client.appointments') }}" class="btn btn-outline-primary btn-sm">
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
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i> Informații Personale</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
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

                    <form method="POST" action="{{ route('client.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Nume -->
                            <div class="col-md-6">
                                <label class="form-label">Nume complet *</label>
                                <input type="text" name="name" class="form-control" 
                                       value="{{ old('name', auth()->user()->name) }}" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" 
                                       value="{{ old('email', auth()->user()->email) }}" required>
                            </div>

                            <!-- Telefon -->
                            <div class="col-md-6">
                                <label class="form-label">Telefon *</label>
                                <input type="tel" name="phone" class="form-control" 
                                       value="{{ old('phone', auth()->user()->phone) }}" 
                                       placeholder="+40 XXX XXX XXX" required>
                                <small class="text-muted">Format: +40 7XX XXX XXX</small>
                            </div>

                            <!-- Adresa -->
                            <div class="col-md-6">
                                <label class="form-label">Adresă</label>
                                <input type="text" name="address" class="form-control" 
                                       value="{{ old('address', auth()->user()->address) }}" 
                                       placeholder="Strada, număr, bloc, etc.">
                            </div>

                            <!-- Buton salvare -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvează Modificările
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Schimbare Parolă -->
            <div class="card mt-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Schimbă Parola</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Parola curentă -->
                            <div class="col-md-6">
                                <label class="form-label">Parola curentă *</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="col-12"></div>

                            <!-- Parola nouă -->
                            <div class="col-md-6">
                                <label class="form-label">Parola nouă *</label>
                                <input type="password" name="password" class="form-control" required>
                                <small class="text-muted">Minim 8 caractere</small>
                            </div>

                            <!-- Confirmare parolă -->
                            <div class="col-md-6">
                                <label class="form-label">Confirmă parola *</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <!-- Buton salvare -->
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key"></i> Actualizează Parola
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistici -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Statistici Cont</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3 border rounded">
                                <h3 class="text-primary mb-0">{{ $appointmentsCount }}</h3>
                                <small class="text-muted">Programări Totale</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded">
                                <h3 class="text-success mb-0">{{ $completedCount }}</h3>
                                <small class="text-muted">Servicii Completate</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded">
                                <h3 class="text-warning mb-0">{{ $reviewsCount }}</h3>
                                <small class="text-muted">Review-uri Date</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-0 text-center">
                        <small class="text-muted">Membru din {{ auth()->user()->created_at->format('d.m.Y') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
