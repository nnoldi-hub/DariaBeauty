@extends('layout')

@section('title', 'Editează Serviciu - Admin')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Editează Serviciu</h3>
                <a href="{{ route('admin.services-crud.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Înapoi
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.services-crud.update', $service) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Specialist *</label>
                                <select name="specialist_id" class="form-select @error('specialist_id') is-invalid @enderror" required>
                                    <option value="">Selectează specialist...</option>
                                    @foreach($specialists as $specialist)
                                        <option value="{{ $specialist->id }}" {{ old('specialist_id', $service->specialist_id) == $specialist->id ? 'selected' : '' }}>
                                            {{ $specialist->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialist_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Brand *</label>
                                <select name="sub_brand" class="form-select @error('sub_brand') is-invalid @enderror" required>
                                    <option value="">Selectează brand...</option>
                                    <option value="dariaNails" {{ old('sub_brand', $service->sub_brand) == 'dariaNails' ? 'selected' : '' }}>dariaNails</option>
                                    <option value="dariaHair" {{ old('sub_brand', $service->sub_brand) == 'dariaHair' ? 'selected' : '' }}>dariaHair</option>
                                    <option value="dariaGlow" {{ old('sub_brand', $service->sub_brand) == 'dariaGlow' ? 'selected' : '' }}>dariaGlow</option>
                                </select>
                                @error('sub_brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nume Serviciu *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $service->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Categorie *</label>
                                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" 
                                       value="{{ old('category', $service->category) }}" required>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descriere</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3">{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preț (RON) *</label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                       value="{{ old('price', $service->price) }}" min="0" step="0.01" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Durată (minute) *</label>
                                <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                                       value="{{ old('duration', $service->duration) }}" min="15" step="15" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                   {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Serviciu activ
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizează Serviciu
                            </button>
                            <a href="{{ route('admin.services-crud.index') }}" class="btn btn-outline-secondary">
                                Anulează
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
