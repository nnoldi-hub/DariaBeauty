@extends('layout')

@section('title', 'Detalii Review - Admin')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Detalii Review</h3>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Înapoi
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Review-ul clientului</h5>
                                @if($review->is_approved)
                                    <span class="badge bg-success">Aprobat</span>
                                @else
                                    <span class="badge bg-warning text-dark">În așteptare</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Rating:</strong>
                                <div style="color: #FFD700; font-size: 24px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                    <span class="text-muted" style="font-size: 16px;">({{ $review->rating }}/5)</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Comentariu:</strong>
                                <p class="mt-2 border rounded p-3 bg-light">{{ $review->comment }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>Postat la: {{ $review->created_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                            
                            @if($review->specialist_response)
                                <div class="alert alert-info">
                                    <strong><i class="fas fa-reply me-2"></i>Răspunsul specialistului:</strong>
                                    <p class="mb-0 mt-2">{{ $review->specialist_response }}</p>
                                </div>
                            @endif
                            
                            <form action="{{ route('admin.reviews.respond', $review) }}" method="POST" class="mt-4">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label class="form-label"><strong>{{ $review->specialist_response ? 'Actualizează' : 'Adaugă' }} răspuns specialist:</strong></label>
                                    <textarea name="specialist_response" class="form-control @error('specialist_response') is-invalid @enderror" 
                                              rows="4" placeholder="Scrie răspunsul aici...">{{ old('specialist_response', $review->specialist_response) }}</textarea>
                                    @error('specialist_response')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Salvează răspuns
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Client</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>{{ $review->client_name ?? 'Client necunoscut' }}</strong></p>
                        </div>
                    </div>
                    
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Specialist</h6>
                        </div>
                        <div class="card-body">
                            @if($review->specialist)
                                <p class="mb-2"><strong>{{ $review->specialist->name }}</strong></p>
                                <span class="badge mb-2" style="background-color: {{ $review->specialist->sub_brand === 'dariaNails' ? '#E91E63' : ($review->specialist->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800') }}">
                                    {{ ucfirst(str_replace('daria', '', $review->specialist->sub_brand)) }}
                                </span>
                                <p class="mb-1"><small class="text-muted">{{ $review->specialist->email }}</small></p>
                                @if($review->specialist->phone)
                                    <p class="mb-0"><small><i class="fas fa-phone me-1"></i>{{ $review->specialist->phone }}</small></p>
                                @endif
                            @else
                                <p class="text-muted">Specialist șters</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Acțiuni</h6>
                        </div>
                        <div class="card-body">
                            @if(!$review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check me-2"></i>Aprobă Review
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="fas fa-times me-2"></i>Respinge Review
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" 
                                  onsubmit="return confirm('Sigur ștergi acest review? Acțiunea este ireversibilă!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Șterge Review
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
