@extends('layout')

@section('title', 'Inregistrare Specialist - DariaBeauty')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Inregistrare Specialist</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('specialist.register.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nume complet</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sub-brand</label>
                                <select name="sub_brand" class="form-select" required>
                                    <option value="">Alege...</option>
                                    @foreach($subBrands as $key => $label)
                                        <option value="{{ $key }}" @selected(old('sub_brand')===$key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Parola</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirma parola</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Taxa transport (lei/km)</label>
                                <input type="number" step="0.01" min="0" name="transport_fee" class="form-control" value="{{ old('transport_fee', 2) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Distanța maximă (km)</label>
                                <input type="number" min="5" max="100" name="max_distance" class="form-control" value="{{ old('max_distance', 30) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Zone acoperite</label>
                                <select name="coverage_area[]" class="form-select" multiple required>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone }}" @selected(collect(old('coverage_area',[]))->contains($zone))>{{ $zone }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Tine apasat Ctrl (sau Cmd pe Mac) pentru a selecta mai multe zone.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary" type="submit">
                                Trimite cererea
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-muted mt-3">Dupa trimitere, un administrator va aproba contul tau in cel mai scurt timp.</p>
        </div>
    </div>
</div>
@endsection
