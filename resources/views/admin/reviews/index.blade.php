@extends('layout')

@section('title', 'Reviews - Admin DariaBeauty')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Gestionare Reviews</h3>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Caută în reviews..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="is_approved">
                                <option value="">Toate statusurile</option>
                                <option value="1" {{ request('is_approved') === '1' ? 'selected' : '' }}>Aprobat</option>
                                <option value="0" {{ request('is_approved') === '0' ? 'selected' : '' }}>În așteptare</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="rating">
                                <option value="">Toate rating-urile</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5)</option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4)</option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3)</option>
                                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ (2)</option>
                                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ (1)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-search"></i> Filtrează
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Specialist</th>
                                    <th>Rating</th>
                                    <th>Comentariu</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>
                                            @if($review->user)
                                                <strong>{{ $review->user->name }}</strong><br>
                                                <small class="text-muted">{{ $review->user->email }}</small>
                                            @else
                                                <span class="text-muted">Utilizator șters</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->specialist)
                                                <strong>{{ $review->specialist->name }}</strong><br>
                                                <span class="badge" style="background-color: {{ $review->specialist->sub_brand === 'dariaNails' ? '#E91E63' : ($review->specialist->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800') }}">
                                                    {{ ucfirst(str_replace('daria', '', $review->specialist->sub_brand)) }}
                                                </span>
                                            @else
                                                <span class="text-muted">Specialist șters</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span style="color: #FFD700;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        ⭐
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </span>
                                            <br>
                                            <small class="text-muted">({{ $review->rating }}/5)</small>
                                        </td>
                                        <td style="max-width: 250px;">
                                            <p class="mb-0 text-truncate" title="{{ $review->comment }}">
                                                {{ Str::limit($review->comment, 60) }}
                                            </p>
                                            @if($review->specialist_response)
                                                <small class="text-primary">
                                                    <i class="fas fa-reply me-1"></i>Răspuns: {{ Str::limit($review->specialist_response, 40) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->is_approved)
                                                <span class="badge bg-success">Aprobat</span>
                                            @else
                                                <span class="badge bg-warning text-dark">În așteptare</span>
                                            @endif
                                        </td>
                                        <td>{{ $review->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.reviews.show', $review) }}" 
                                                   class="btn btn-sm btn-outline-info" title="Detalii">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(!$review->is_approved)
                                                    <form action="{{ route('admin.reviews.approve', $review) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Aprobă">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.reviews.reject', $review) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Respinge">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.reviews.destroy', $review) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Sigur ștergi acest review?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Șterge">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Nu există reviews care să corespundă criteriilor de căutare.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($reviews->hasPages())
                        <div class="mt-3">
                            {{ $reviews->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
