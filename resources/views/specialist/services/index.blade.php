@extends('layout')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('specialist.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Serviciile Mele</h1>
                <a href="{{ route('specialist.services.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Adaugă Serviciu Nou
                </a>
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

            <div class="row">
                @forelse($services as $service)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top" alt="{{ $service->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-white"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $service->name }}</h5>
                                <p class="card-text">{{ Str::limit($service->description, 120) }}</p>
                                
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <strong>Preț:</strong> {{ $service->price }} RON
                                    </div>
                                    <div class="col-6">
                                        <strong>Durată:</strong> {{ $service->duration }} min
                                    </div>
                                </div>

                                @if($service->category)
                                    <p class="mb-2">
                                        <span class="badge bg-secondary">{{ ucfirst($service->category) }}</span>
                                    </p>
                                @endif

                                <p class="mb-2">
                                    <strong>Status:</strong>
                                    @if($service->is_active)
                                        <span class="badge bg-success">Activ</span>
                                    @else
                                        <span class="badge bg-danger">Inactiv</span>
                                    @endif
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('specialist.services.edit', $service->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editează
                                    </a>
                                    <form method="POST" action="{{ route('specialist.services.destroy', $service->id) }}" class="d-inline" onsubmit="return confirm('Sigur doriți să ștergeți acest serviciu?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Șterge
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nu aveți servicii adăugate încă. 
                            <a href="{{ route('specialist.services.create') }}" class="alert-link">Adăugați primul serviciu</a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if(isset($services) && method_exists($services, 'links'))
                <div class="mt-3">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
