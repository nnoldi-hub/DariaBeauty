@extends('layout')

@section('title', 'Specialiști în Așteptare - Admin DariaBeauty')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Specialiști în Așteptare Aprobare</h3>
                <span class="badge bg-warning fs-6">{{ $pending->total() }} cereri</span>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @forelse($pending as $specialist)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                @if($specialist->profile_photo)
                                    <img src="{{ asset('storage/' . $specialist->profile_photo) }}" 
                                         class="rounded-circle mb-2" 
                                         style="width:80px; height:80px; object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-2" 
                                         style="width:80px; height:80px;">
                                        <i class="fas fa-user fa-2x text-white"></i>
                                    </div>
                                @endif
                                <small class="text-muted d-block">ID: #{{ $specialist->id }}</small>
                            </div>
                            
                            <div class="col-md-7">
                                <h5 class="mb-2">{{ $specialist->name }}</h5>
                                <p class="mb-1">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    <a href="mailto:{{ $specialist->email }}">{{ $specialist->email }}</a>
                                </p>
                                @if($specialist->phone)
                                    <p class="mb-1">
                                        <i class="fas fa-phone text-muted me-2"></i>{{ $specialist->phone }}
                                    </p>
                                @endif
                                @if($specialist->sub_brand)
                                    <p class="mb-1">
                                        <i class="fas fa-tag text-muted me-2"></i>
                                        <span class="badge" style="background:{{ $specialist->sub_brand === 'dariaNails' ? '#E91E63' : ($specialist->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800') }}">
                                            {{ $specialist->sub_brand }}
                                        </span>
                                    </p>
                                @endif
                                @if($specialist->coverage_area && is_array($specialist->coverage_area))
                                    <p class="mb-1">
                                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                        {{ implode(', ', $specialist->coverage_area) }}
                                    </p>
                                @endif
                                @if($specialist->bio)
                                    <p class="text-muted mt-2 mb-0">
                                        <small>{{ Str::limit($specialist->bio, 150) }}</small>
                                    </p>
                                @endif
                            </div>

                            <div class="col-md-3 text-end">
                                <p class="text-muted mb-2">
                                    <small><i class="fas fa-clock me-1"></i>{{ $specialist->created_at->diffForHumans() }}</small>
                                </p>
                                <p class="text-muted mb-3">
                                    <small>{{ $specialist->created_at->format('d M Y, H:i') }}</small>
                                </p>
                                
                                <form action="{{ route('admin.specialists.approve', $specialist->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100 mb-2">
                                        <i class="fas fa-check me-1"></i>Aprobă
                                    </button>
                                </form>
                                
                                <button class="btn btn-outline-danger btn-sm w-100" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal{{ $specialist->id }}">
                                    <i class="fas fa-times me-1"></i>Respinge
                                </button>

                                <!-- Modal Respingere -->
                                <div class="modal fade" id="rejectModal{{ $specialist->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Respinge Specialist</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Sigur vrei să respingi cererea lui <strong>{{ $specialist->name }}</strong>?</p>
                                                <p class="text-muted small">Această acțiune va șterge contul specialist din sistem.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                                                <form action="{{ route('admin.specialists.reject', $specialist->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Respinge</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Nu există cereri în așteptare</h5>
                        <p class="text-muted">Toate cererile de înregistrare au fost procesate.</p>
                    </div>
                </div>
            @endforelse

            @if($pending->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $pending->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
