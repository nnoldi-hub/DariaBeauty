@extends('layout')

@section('title', 'Lasă un Review')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-star"></i> Lasă un Review</h4>
                </div>
                <div class="card-body">
                    <!-- Info Programare -->
                    <div class="alert alert-info mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fas fa-user"></i> Specialist:</strong><br>
                                {{ $appointment->specialist->name }}
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-cut"></i> Serviciu:</strong><br>
                                {{ $appointment->service->name }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong><i class="fas fa-calendar"></i> Data:</strong><br>
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y') }}
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-clock"></i> Ora:</strong><br>
                                {{ $appointment->appointment_time }}
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reviews.store', $appointment->id) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Rating General -->
                        <div class="mb-4">
                            <label class="form-label"><strong>Rating General *</strong></label>
                            <div class="rating-stars" id="rating-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                    <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                                @endfor
                            </div>
                            <small class="text-muted">Click pe stele pentru a alege rating-ul</small>
                        </div>

                        <!-- Comentariu -->
                        <div class="mb-4">
                            <label for="comment" class="form-label"><strong>Comentariul tău *</strong></label>
                            <textarea name="comment" id="comment" class="form-control" rows="5" 
                                      placeholder="Descrie experiența ta cu acest specialist..." required>{{ old('comment') }}</textarea>
                            <small class="text-muted">Minim 10 caractere</small>
                        </div>

                        <!-- Evaluări Detaliate (Opțional) -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Evaluări Detaliate (Opțional)</h6>
                            </div>
                            <div class="card-body">
                                <!-- Calitate Serviciu -->
                                <div class="mb-3">
                                    <label class="form-label">Calitatea Serviciului</label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @for($i = 1; $i <= 5; $i++)
                                            <div class="form-check">
                                                <input type="radio" name="service_quality_rating" value="{{ $i }}" 
                                                       class="form-check-input" id="service_quality_{{ $i }}"
                                                       {{ old('service_quality_rating') == $i ? 'checked' : '' }}>
                                                <label class="form-check-label" for="service_quality_{{ $i }}">
                                                    {{ $i }} <i class="fas fa-star text-warning"></i>
                                                </label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Punctualitate -->
                                <div class="mb-3">
                                    <label class="form-label">Punctualitate</label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @for($i = 1; $i <= 5; $i++)
                                            <div class="form-check">
                                                <input type="radio" name="punctuality_rating" value="{{ $i }}" 
                                                       class="form-check-input" id="punctuality_{{ $i }}"
                                                       {{ old('punctuality_rating') == $i ? 'checked' : '' }}>
                                                <label class="form-check-label" for="punctuality_{{ $i }}">
                                                    {{ $i }} <i class="fas fa-star text-warning"></i>
                                                </label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Curățenie -->
                                <div class="mb-3">
                                    <label class="form-label">Curățenie & Igienă</label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @for($i = 1; $i <= 5; $i++)
                                            <div class="form-check">
                                                <input type="radio" name="cleanliness_rating" value="{{ $i }}" 
                                                       class="form-check-input" id="cleanliness_{{ $i }}"
                                                       {{ old('cleanliness_rating') == $i ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cleanliness_{{ $i }}">
                                                    {{ $i }} <i class="fas fa-star text-warning"></i>
                                                </label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Experiență Generală -->
                                <div class="mb-0">
                                    <label class="form-label">Experiență Generală</label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @for($i = 1; $i <= 5; $i++)
                                            <div class="form-check">
                                                <input type="radio" name="overall_experience" value="{{ $i }}" 
                                                       class="form-check-input" id="overall_{{ $i }}"
                                                       {{ old('overall_experience') == $i ? 'checked' : '' }}>
                                                <label class="form-check-label" for="overall_{{ $i }}">
                                                    {{ $i }} <i class="fas fa-star text-warning"></i>
                                                </label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Butoane -->
                        <div class="d-flex gap-2 justify-content-between">
                            <a href="{{ route('client.appointments') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Înapoi
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Trimite Review-ul
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 10px;
    font-size: 2.5rem;
}

.rating-stars input[type="radio"] {
    display: none;
}

.rating-stars label {
    cursor: pointer;
    color: #ddd;
    transition: color 0.2s, transform 0.2s;
}

.rating-stars label:hover {
    transform: scale(1.2);
}

.rating-stars input[type="radio"]:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffc107;
}

.form-check-input:checked {
    background-color: #ffc107;
    border-color: #ffc107;
}
</style>
@endsection
