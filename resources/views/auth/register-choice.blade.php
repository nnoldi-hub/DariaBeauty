@extends('layout')

@section('title', 'Alege Tipul de Cont')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:80px;">
    <div class="text-center mb-5">
        <h1 class="mb-3">Alătură-te DariaBeauty</h1>
        <p class="lead text-muted">Selectează tipul de cont potrivit pentru tine</p>
    </div>

    <div class="row justify-content-center">
        {{-- Salon Owner --}}
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <div class="icon-circle bg-primary mb-3">
                            <i class="fas fa-building fa-3x text-white"></i>
                        </div>
                        <h3 class="mb-3">Salon de Frumusețe</h3>
                        <p class="text-muted">Deții un salon și vrei să gestionezi echipa ta de specialiști</p>
                    </div>

                    <div class="features-list text-start mb-4">
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Dashboard cu statistici complete
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Gestionează specialiști multipli
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Rapoarte combinate performanță
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Invită și administrează echipa
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Profil salon vizibil public
                        </div>
                    </div>

                    <a href="{{ route('register.salon') }}" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-building me-2"></i>Înregistrare Salon
                    </a>

                    <div class="mt-3">
                        <span class="badge bg-success">GRATUIT 3 LUNI</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Specialist Independent --}}
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <div class="icon-circle bg-success mb-3">
                            <i class="fas fa-user-tie fa-3x text-white"></i>
                        </div>
                        <h3 class="mb-3">Specialist Independent</h3>
                        <p class="text-muted">Lucrezi pe cont propriu și vrei să îți crești clientela</p>
                    </div>

                    <div class="features-list text-start mb-4">
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Profil personal cu portofoliu
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Calendar rezervări online
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Gestionează serviciile tale
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Reviews și rating clienți
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Poți lucra la salon sau la domiciliu
                        </div>
                    </div>

                    <a href="{{ route('specialist.register') }}" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-user-tie me-2"></i>Înregistrare Specialist
                    </a>

                    <div class="mt-3">
                        <span class="badge bg-success">GRATUIT 4 LUNI</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Client --}}
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        <div class="icon-circle bg-info mb-3">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                        <h3 class="mb-3">Client</h3>
                        <p class="text-muted">Caută și rezervă servicii de beauty în zona ta</p>
                    </div>

                    <div class="features-list text-start mb-4">
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-info me-2"></i>
                            Caută specialiști verificați
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-info me-2"></i>
                            Rezervări online instant
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-info me-2"></i>
                            Vezi reviews reale
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-info me-2"></i>
                            Istoric programări
                        </div>
                        <div class="feature-item mb-2">
                            <i class="fas fa-check-circle text-info me-2"></i>
                            Notificări și reminder-e
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="btn btn-info btn-lg w-100">
                        <i class="fas fa-user me-2"></i>Înregistrare Client
                    </a>

                    <div class="mt-3">
                        <span class="badge bg-info">100% GRATUIT</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="text-muted">
            Ai deja cont? 
            <a href="{{ route('login') }}" class="text-primary fw-bold">Autentifică-te aici</a>
        </p>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.icon-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.features-list {
    font-size: 14px;
    min-height: 150px;
}

.feature-item {
    line-height: 1.8;
}
</style>
@endsection
