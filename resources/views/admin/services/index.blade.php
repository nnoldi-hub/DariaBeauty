@extends('layout')

@section('title', 'Servicii - Admin')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Gestionare Servicii</h3>
                <a href="{{ route('admin.services-crud.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Adaugă Serviciu
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.services-crud.index') }}" class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Caută serviciu..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="sub_brand" class="form-select">
                                <option value="">Toate brandurile</option>
                                <option value="dariaNails" {{ request('sub_brand') === 'dariaNails' ? 'selected' : '' }}>dariaNails</option>
                                <option value="dariaHair" {{ request('sub_brand') === 'dariaHair' ? 'selected' : '' }}>dariaHair</option>
                                <option value="dariaGlow" {{ request('sub_brand') === 'dariaGlow' ? 'selected' : '' }}>dariaGlow</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">Toate categoriile</option>
                                @foreach(\App\Models\Service::select('category')->distinct()->pluck('category') as $cat)
                                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-search"></i> Filtrează
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Serviciu</th>
                                    <th>Brand</th>
                                    <th>Categorie</th>
                                    <th>Specialist</th>
                                    <th>Preț</th>
                                    <th>Durată</th>
                                    <th>Status</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                    <tr>
                                        <td>{{ $service->id }}</td>
                                        <td><strong>{{ $service->name }}</strong></td>
                                        <td>
                                            <span class="badge" style="background:{{ $service->sub_brand === 'dariaNails' ? '#E91E63' : ($service->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800') }}">
                                                {{ $service->sub_brand }}
                                            </span>
                                        </td>
                                        <td>{{ $service->category }}</td>
                                        <td>{{ $service->specialist->name ?? 'N/A' }}</td>
                                        <td>{{ $service->price }} RON</td>
                                        <td>{{ $service->duration }} min</td>
                                        <td>
                                            <span class="badge bg-{{ $service->is_active ? 'success' : 'secondary' }}">
                                                {{ $service->is_active ? 'Activ' : 'Inactiv' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.services-crud.edit', $service) }}" class="btn btn-sm btn-outline-primary" title="Editează">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.services-crud.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Sigur ștergi acest serviciu?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Șterge">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">Nu există servicii în baza de date.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($services->hasPages())
                        <div class="mt-3">
                            {{ $services->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
