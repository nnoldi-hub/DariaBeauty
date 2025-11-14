@extends('layout')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('specialist.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Social Media</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
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

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Gestionează Linkurile Tale Social Media</h5>
                    <p class="text-muted mb-0">Adaugă linkurile către profilurile tale pentru a fi vizibile în profilul public</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('specialist.social.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Instagram -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fab fa-instagram fa-2x me-3" style="color: #E4405F;"></i>
                                    <div>
                                        <h6 class="mb-0">Instagram</h6>
                                        <small class="text-muted">Conectează-te cu clienții prin Instagram</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">URL Instagram</label>
                                        <input type="url" class="form-control" name="platforms[instagram][url]" 
                                               value="{{ old('platforms.instagram.url', $socialLinks['instagram']->url ?? '') }}"
                                               placeholder="https://www.instagram.com/username">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Username (fără @)</label>
                                        <input type="text" class="form-control" name="platforms[instagram][username]" 
                                               value="{{ old('platforms.instagram.username', $socialLinks['instagram']->username ?? '') }}"
                                               placeholder="username">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="platforms[instagram][is_active]" value="1"
                                                   {{ old('platforms.instagram.is_active', $socialLinks['instagram']->is_active ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label">Activ</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Facebook -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fab fa-facebook fa-2x me-3" style="color: #1877F2;"></i>
                                    <div>
                                        <h6 class="mb-0">Facebook</h6>
                                        <small class="text-muted">Pagina ta de Facebook pentru afaceri</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">URL Facebook</label>
                                        <input type="url" class="form-control" name="platforms[facebook][url]" 
                                               value="{{ old('platforms.facebook.url', $socialLinks['facebook']->url ?? '') }}"
                                               placeholder="https://www.facebook.com/pagename">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nume Pagină</label>
                                        <input type="text" class="form-control" name="platforms[facebook][username]" 
                                               value="{{ old('platforms.facebook.username', $socialLinks['facebook']->username ?? '') }}"
                                               placeholder="Numele paginii">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="platforms[facebook][is_active]" value="1"
                                                   {{ old('platforms.facebook.is_active', $socialLinks['facebook']->is_active ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label">Activ</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TikTok -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fab fa-tiktok fa-2x me-3" style="color: #000000;"></i>
                                    <div>
                                        <h6 class="mb-0">TikTok</h6>
                                        <small class="text-muted">Profilul tău TikTok pentru conținut video</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">URL TikTok</label>
                                        <input type="url" class="form-control" name="platforms[tiktok][url]" 
                                               value="{{ old('platforms.tiktok.url', $socialLinks['tiktok']->url ?? '') }}"
                                               placeholder="https://www.tiktok.com/@username">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Username (fără @)</label>
                                        <input type="text" class="form-control" name="platforms[tiktok][username]" 
                                               value="{{ old('platforms.tiktok.username', $socialLinks['tiktok']->username ?? '') }}"
                                               placeholder="username">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="platforms[tiktok][is_active]" value="1"
                                                   {{ old('platforms.tiktok.is_active', $socialLinks['tiktok']->is_active ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label">Activ</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- YouTube -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fab fa-youtube fa-2x me-3" style="color: #FF0000;"></i>
                                    <div>
                                        <h6 class="mb-0">YouTube</h6>
                                        <small class="text-muted">Canalul tău YouTube pentru tutoriale</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">URL YouTube</label>
                                        <input type="url" class="form-control" name="platforms[youtube][url]" 
                                               value="{{ old('platforms.youtube.url', $socialLinks['youtube']->url ?? '') }}"
                                               placeholder="https://www.youtube.com/channel/...">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nume Canal</label>
                                        <input type="text" class="form-control" name="platforms[youtube][username]" 
                                               value="{{ old('platforms.youtube.username', $socialLinks['youtube']->username ?? '') }}"
                                               placeholder="Numele canalului">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="platforms[youtube][is_active]" value="1"
                                                   {{ old('platforms.youtube.is_active', $socialLinks['youtube']->is_active ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label">Activ</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp Business -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fab fa-whatsapp fa-2x me-3" style="color: #25D366;"></i>
                                    <div>
                                        <h6 class="mb-0">WhatsApp Business</h6>
                                        <small class="text-muted">Numărul tău WhatsApp Business pentru contactul rapid</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Număr WhatsApp</label>
                                        <input type="tel" class="form-control" name="platforms[whatsapp][url]" 
                                               value="{{ old('platforms.whatsapp.url', $socialLinks['whatsapp']->url ?? '') }}"
                                               placeholder="+40712345678">
                                        <small class="text-muted">Format international (ex: +40712345678)</small>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nume Afișat</label>
                                        <input type="text" class="form-control" name="platforms[whatsapp][username]" 
                                               value="{{ old('platforms.whatsapp.username', $socialLinks['whatsapp']->username ?? '') }}"
                                               placeholder="WhatsApp Business">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="platforms[whatsapp][is_active]" value="1"
                                                   {{ old('platforms.whatsapp.is_active', $socialLinks['whatsapp']->is_active ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label">Activ</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Salvează Linkurile Social Media
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Previzualizare</h5>
                    <p class="text-muted mb-0">Așa vor apărea linkurile în profilul tău public</p>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($platforms as $platform => $name)
                            @if(isset($socialLinks[$platform]) && $socialLinks[$platform]->is_active && $socialLinks[$platform]->url)
                                <a href="{{ $socialLinks[$platform]->url }}" target="_blank" 
                                   class="btn btn-outline-secondary">
                                    @if($platform == 'instagram')
                                        <i class="fab fa-instagram" style="color: #E4405F;"></i>
                                    @elseif($platform == 'facebook')
                                        <i class="fab fa-facebook" style="color: #1877F2;"></i>
                                    @elseif($platform == 'tiktok')
                                        <i class="fab fa-tiktok" style="color: #000000;"></i>
                                    @elseif($platform == 'youtube')
                                        <i class="fab fa-youtube" style="color: #FF0000;"></i>
                                    @elseif($platform == 'whatsapp')
                                        <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                                    @endif
                                    {{ $socialLinks[$platform]->username ?? $name }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                    @if(!$socialLinks->where('is_active', true)->where('url', '!=', null)->count())
                        <p class="text-muted mb-0">Nu ai linkuri active configurate încă.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection