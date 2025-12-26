<div class="list-group">
    @if(Auth::user()->role === 'salon' || Auth::user()->is_salon_owner)
    <a href="{{ route('salon.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('salon.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard Salon
    </a>
    @else
    <a href="{{ route('specialist.dashboard') }}" class="list-group-item list-group-item-action">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </a>
    @endif
    
    @if(Auth::user()->role === 'salon' || Auth::user()->is_salon_owner)
    <a href="{{ route('salon.specialists.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('salon.specialists.*') ? 'active' : '' }}">
        <i class="fas fa-users me-2"></i>Specialiștii Mei
        @if(Auth::user()->salon_specialists_count > 0)
            <span class="badge bg-info float-end">{{ Auth::user()->salon_specialists_count }}</span>
        @endif
    </a>
    @endif
    
    <a href="{{ route('salon.reports.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('salon.reports.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line me-2"></i>Rapoarte & Statistici
    </a>
    
    @if(Auth::user()->role === 'salon' || Auth::user()->is_salon_owner)
    <a href="{{ route('salon.settings') }}" class="list-group-item list-group-item-action {{ request()->routeIs('salon.settings*') ? 'active' : '' }}">
        <i class="fas fa-cog me-2"></i>Setări Salon
    </a>
    @endif
    
    @if(Auth::user()->role === 'salon' || Auth::user()->is_salon_owner)
        {{-- Salon owner nu are propriile programări/servicii, doar vede ale specialiștilor săi --}}
        <div class="list-group-item disabled bg-light">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Gestionezi specialiștii și vezi rapoartele lor combinate
            </small>
        </div>
    @else
        {{-- Specialist normal are acces la propriile pagini --}}
        <a href="{{ route('specialist.appointments.index') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-calendar-check me-2"></i>Programări
        </a>
        <a href="{{ route('specialist.services.index') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-concierge-bell me-2"></i>Serviciile Mele
        </a>
        <a href="{{ route('specialist.reviews.index') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-star me-2"></i>Reviews
        </a>
        <a href="{{ route('specialist.gallery.index') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-images me-2"></i>Galerie
        </a>
        <a href="{{ route('specialist.profile') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-user me-2"></i>Profilul Meu
        </a>
        <a href="{{ route('specialist.social') }}" class="list-group-item list-group-item-action">
            <i class="fas fa-share-alt me-2"></i>Social Media
        </a>
    @endif
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="list-group-item list-group-item-action text-danger border-0 w-100 text-start">
            <i class="fas fa-sign-out-alt me-2"></i>Deconectare
        </button>
    </form>
</div>
