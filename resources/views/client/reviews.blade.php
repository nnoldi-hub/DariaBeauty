@extends('layout')

@section('title', 'Review-urile Mele')

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
                        <a href="{{ route('client.profile') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user"></i> Profil
                        </a>
                        <a href="{{ route('client.appointments') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar"></i> Programări
                        </a>
                        <a href="{{ route('client.reviews') }}" class="btn btn-primary btn-sm active">
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
                    <h5 class="mb-0"><i class="fas fa-star"></i> Review-urile Mele</h5>
                </div>
                <div class="card-body">
                    @if($reviews->count() > 0)
                        @foreach($reviews as $review)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('specialists.show', $review->appointment->specialist->slug) }}">
                                                    {{ $review->appointment->specialist->name }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-cut"></i> {{ $review->appointment->service->name }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <div class="mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                <strong>{{ number_format($review->rating, 1) }}</strong>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ $review->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>

                                    @if($review->comment)
                                        <p class="mb-2">{{ $review->comment }}</p>
                                    @endif

                                    @if($review->specialist_response)
                                        <div class="alert alert-light mt-3 mb-0">
                                            <strong><i class="fas fa-reply"></i> Răspuns de la {{ $review->appointment->specialist->name }}:</strong>
                                            <p class="mb-0 mt-2">{{ $review->specialist_response }}</p>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($review->specialist_response_at)->diffForHumans() }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <!-- Paginare -->
                        <div class="mt-3">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-star-half-alt fa-4x text-muted mb-3"></i>
                            <h5>Nu ai lăsat încă review-uri</h5>
                            <p class="text-muted">După ce completezi o programare, vei putea lăsa un review pentru specialist.</p>
                            <a href="{{ route('client.appointments') }}" class="btn btn-primary">
                                <i class="fas fa-calendar"></i> Vezi Programările
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
