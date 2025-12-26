@extends('layout')

@section('title', 'Setări Salon')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('salon.partials.sidebar')
        </div>
        <div class="col-md-9">
            <h2 class="mb-4"><i class="fas fa-cog"></i> Setări Salon</h2>

            {{-- Mesaje success/error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <strong>Erori:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Informații Salon --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Informații Salon</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('salon.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Logo actual --}}
                            <div class="col-md-12 mb-4 text-center">
                                @if($user->salon_logo)
                                    <img src="{{ Storage::url($user->salon_logo) }}" 
                                         alt="Logo {{ $user->name }}" 
                                         class="img-thumbnail mb-3"
                                         style="max-width: 200px; max-height: 200px;">
                                @else
                                    <div class="bg-light rounded mb-3 d-inline-flex align-items-center justify-content-center" 
                                         style="width: 200px; height: 200px;">
                                        <i class="fas fa-building fa-4x text-muted"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Nume Salon --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nume Salon <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>

                            {{-- Telefon --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="0700000000">
                            </div>

                            {{-- Adresă --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Adresă Salon</label>
                                <input type="text" name="salon_address" class="form-control" value="{{ old('salon_address', $user->salon_address) }}" placeholder="Str. Exemplu nr. 1, București">
                            </div>

                            {{-- Descriere --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Descriere Salon</label>
                                <textarea name="salon_description" class="form-control" rows="4" placeholder="Descrie salonul tău, serviciile oferite, atmosfera...">{{ old('salon_description', $user->salon_description) }}</textarea>
                                <small class="text-muted">Maxim 2000 caractere</small>
                            </div>

                            {{-- Logo Upload --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Logo Nou (opțional)</label>
                                <input type="file" name="salon_logo" class="form-control" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maxim 2MB</small>
                            </div>

                            {{-- Sub-brand --}}
                            <div class="col-md-12 mb-4">
                                <label class="form-label">Specializare Principală</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="sub_brand" id="nails" value="dariaNails" {{ old('sub_brand', $user->sub_brand) == 'dariaNails' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger w-100 py-3" for="nails">
                                            <i class="fas fa-hand-sparkles fa-2x mb-2"></i><br>
                                            <strong>dariaNails</strong><br>
                                            <small>Manichiură & Pedichiură</small>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="sub_brand" id="hair" value="dariaHair" {{ old('sub_brand', $user->sub_brand) == 'dariaHair' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary w-100 py-3" for="hair">
                                            <i class="fas fa-cut fa-2x mb-2"></i><br>
                                            <strong>dariaHair</strong><br>
                                            <small>Coafură & Styling</small>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="sub_brand" id="glow" value="dariaGlow" {{ old('sub_brand', $user->sub_brand) == 'dariaGlow' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning w-100 py-3" for="glow">
                                            <i class="fas fa-spa fa-2x mb-2"></i><br>
                                            <strong>dariaGlow</strong><br>
                                            <small>Îngrijire Facială & Corp</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Social Media --}}
                            <div class="col-md-12 mb-3">
                                <h6><i class="fas fa-share-alt"></i> Social Media</h6>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" name="salon_instagram" class="form-control" value="{{ old('salon_instagram', ltrim($user->salon_instagram ?? '', '@')) }}" placeholder="username">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" name="salon_facebook" class="form-control" value="{{ old('salon_facebook', ltrim($user->salon_facebook ?? '', '@')) }}" placeholder="pagename">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">TikTok</label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" name="salon_tiktok" class="form-control" value="{{ old('salon_tiktok', ltrim($user->salon_tiktok ?? '', '@')) }}" placeholder="username">
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Salvează Modificările
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Schimbare Parolă --}}
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Schimbă Parola</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('salon.settings.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Parola Curentă <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parolă Nouă <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" minlength="8" required>
                                <small class="text-muted">Minim 8 caractere</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmă Parola <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key"></i> Schimbă Parola
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Informații Cont --}}
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informații Cont</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong>Status Cont:</strong> 
                            @if($user->is_active)
                                <span class="badge bg-success">Activ</span>
                            @else
                                <span class="badge bg-warning">În așteptare aprobare</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Tip Cont:</strong> <span class="badge bg-primary">Salon Owner</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Specialiști:</strong> {{ $user->salon_specialists_count ?? 0 }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Membru din:</strong> {{ $user->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
