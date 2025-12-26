@extends('layout')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('specialist.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Editează Serviciu</h1>
                <a href="{{ route('specialist.services.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Înapoi la Lista Serviciilor
                </a>
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

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('specialist.services.update', $service->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nume Serviciu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $service->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="price" class="form-label">Preț (RON) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $service->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="duration" class="form-label">Durată (minute) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" name="duration" value="{{ old('duration', $service->duration) }}" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descriere <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Categorie</label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                    <option value="">Selectează categoria</option>
                                    <option value="nails" {{ old('category', $service->category) == 'nails' ? 'selected' : '' }}>Nails</option>
                                    <option value="hair" {{ old('category', $service->category) == 'hair' ? 'selected' : '' }}>Hair</option>
                                    <option value="makeup" {{ old('category', $service->category) == 'makeup' ? 'selected' : '' }}>Makeup</option>
                                    <option value="skincare" {{ old('category', $service->category) == 'skincare' ? 'selected' : '' }}>Skincare</option>
                                    <option value="other" {{ old('category', $service->category) == 'other' ? 'selected' : '' }}>Altele</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Imagine Nouă</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format acceptat: JPG, PNG, WebP. Dimensiune maximă: 2MB</small>
                                
                                @if($service->image)
                                    <div class="mt-2">
                                        <p class="mb-1"><strong>Imagine Curentă:</strong></p>
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Disponibilitate Locație -->
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    Unde oferi acest serviciu?
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-2">
                                            <input type="checkbox" class="form-check-input" id="available_at_salon" 
                                                   name="available_at_salon" value="1" 
                                                   {{ old('available_at_salon', $service->available_at_salon) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="available_at_salon">
                                                <i class="fas fa-building text-primary me-1"></i>
                                                <strong>Disponibil la salon</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-2">
                                            <input type="checkbox" class="form-check-input" id="available_at_home" 
                                                   name="available_at_home" value="1" 
                                                   {{ old('available_at_home', $service->available_at_home) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="available_at_home">
                                                <i class="fas fa-home text-warning me-1"></i>
                                                <strong>Disponibil la domiciliu</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2" id="home_fee_section" style="display: {{ old('available_at_home', $service->available_at_home) ? 'block' : 'none' }};">
                                    <label for="home_service_fee" class="form-label">
                                        <i class="fas fa-plus-circle me-1"></i>
                                        Taxă suplimentară pentru domiciliu (RON)
                                    </label>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="home_service_fee" name="home_service_fee" 
                                           value="{{ old('home_service_fee', $service->home_service_fee ?? 0) }}" min="0">
                                    <small class="text-muted">Lasă 0 dacă nu există taxă suplimentară pentru acest serviciu</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Serviciu Activ (disponibil pentru programări)
                            </label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('specialist.services.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Anulează
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizează Serviciul
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Toggle home service fee visibility
document.getElementById('available_at_home').addEventListener('change', function() {
    document.getElementById('home_fee_section').style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection
