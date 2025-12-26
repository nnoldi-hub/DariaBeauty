<div class="list-group">
    <a href="{{ route('specialist.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('specialist.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </a>
    <a href="{{ route('specialist.appointments.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('specialist.appointments.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-check me-2"></i>Programări
        @php
            $pendingApps = Auth::user()->appointments()->where('status', 'pending')->count();
        @endphp
        @if($pendingApps > 0)
            <span class="badge bg-warning text-dark float-end">{{ $pendingApps }}</span>
        @endif
    </a>
    <a href="{{ route('specialist.services.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('specialist.services.*') ? 'active' : '' }}">
        <i class="fas fa-concierge-bell me-2"></i>Serviciile Mele
    </a>
    <a href="{{ route('specialist.schedule.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('specialist.schedule.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt me-2"></i>Program de Lucru
    </a>
    <a href="{{ route('specialist.reviews.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('specialist.reviews.*') ? 'active' : '' }}">
        <i class="fas fa-star me-2"></i>Reviews
    </a>
    <a href="{{ route('salon.reports.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('salon.reports.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line me-2"></i>Rapoarte & Statistici
    </a>
    <a href="{{ route('specialist.gallery.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('specialist.gallery.*') ? 'active' : '' }}">
        <i class="fas fa-images me-2"></i>Galerie
    </a>
    <a href="{{ route('specialist.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('specialist.profile') ? 'active' : '' }}">
        <i class="fas fa-user me-2"></i>Profilul Meu
    </a>
    <a href="{{ route('specialist.social') }}" class="list-group-item list-group-item-action {{ request()->routeIs('specialist.social') ? 'active' : '' }}">
        <i class="fas fa-share-alt me-2"></i>Social Media
    </a>
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="list-group-item list-group-item-action text-danger border-0 w-100 text-start">
            <i class="fas fa-sign-out-alt me-2"></i>Deconectare
        </button>
    </form>
</div>
