@extends('layout')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('specialist.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Galeria Mea</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-plus"></i> Adaugă Imagine
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Gallery Grid -->
            <div class="row">
                @forelse($gallery as $image)
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" 
                                 alt="{{ $image->caption }}" style="height: 250px; object-fit: cover;">
                            <div class="card-body">
                                @if($image->caption)
                                    <p class="card-text">{{ $image->caption }}</p>
                                @endif
                                
                                @if($image->service)
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-tags"></i> {{ $image->service->name }}
                                        </small>
                                    </p>
                                @endif

                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> {{ $image->created_at->format('d.m.Y') }}
                                    </small>
                                </p>

                                @if($image->before_after && $image->before_after !== 'single')
                                    <span class="badge bg-info">{{ ucfirst($image->before_after) }}</span>
                                @endif

                                @if($image->is_featured)
                                    <span class="badge bg-warning text-dark">Evidențiat</span>
                                @endif
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#editModal{{ $image->id }}">
                                        <i class="fas fa-edit"></i> Editează
                                    </button>
                                    <form method="POST" action="{{ route('specialist.gallery.destroy', $image->id) }}" 
                                          class="d-inline" onsubmit="return confirm('Sigur doriți să ștergeți această imagine?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Șterge
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal for each image -->
                    <div class="modal fade" id="editModal{{ $image->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editează Imagine</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('specialist.gallery.update', $image->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="caption{{ $image->id }}" class="form-label">Descriere</label>
                                            <textarea class="form-control" id="caption{{ $image->id }}" 
                                                      name="caption" rows="2">{{ $image->caption }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="service_id{{ $image->id }}" class="form-label">Serviciu Asociat</label>
                                            <select class="form-select" id="service_id{{ $image->id }}" name="service_id">
                                                <option value="">Fără serviciu asociat</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" 
                                                            {{ $image->service_id == $service->id ? 'selected' : '' }}>
                                                        {{ $service->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="before_after{{ $image->id }}" class="form-label">Tip Imagine</label>
                                            <select class="form-select" id="before_after{{ $image->id }}" name="before_after">
                                                <option value="single" {{ $image->before_after == 'single' ? 'selected' : '' }}>Imagine Simplă</option>
                                                <option value="before" {{ $image->before_after == 'before' ? 'selected' : '' }}>Înainte</option>
                                                <option value="after" {{ $image->before_after == 'after' ? 'selected' : '' }}>După</option>
                                            </select>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="is_featured{{ $image->id }}" name="is_featured" value="1"
                                                   {{ $image->is_featured ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured{{ $image->id }}">
                                                Evidențiază în galeria principală
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                                        <button type="submit" class="btn btn-primary">Salvează Modificările</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nu aveți imagini în galerie încă. 
                            <button class="btn btn-link p-0 align-baseline" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                Adăugați prima imagine
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

            @if(isset($gallery) && method_exists($gallery, 'links'))
                <div class="mt-3">
                    {{ $gallery->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adaugă Imagine Nouă</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('specialist.gallery.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="image" class="form-label">Selectează Imaginea <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format acceptat: JPG, PNG, WebP. Dimensiune maximă: 5MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="caption" class="form-label">Descriere</label>
                        <textarea class="form-control @error('caption') is-invalid @enderror" 
                                  id="caption" name="caption" rows="2" 
                                  placeholder="Adaugă o descriere pentru această imagine...">{{ old('caption') }}</textarea>
                        @error('caption')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="service_id" class="form-label">Serviciu Asociat</label>
                        <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id">
                            <option value="">Fără serviciu asociat</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="before_after" class="form-label">Tip Imagine</label>
                        <select class="form-select @error('before_after') is-invalid @enderror" id="before_after" name="before_after" required>
                            <option value="single" {{ old('before_after') == 'single' ? 'selected' : '' }}>Imagine Simplă</option>
                            <option value="before" {{ old('before_after') == 'before' ? 'selected' : '' }}>Înainte</option>
                            <option value="after" {{ old('before_after') == 'after' ? 'selected' : '' }}>După</option>
                        </select>
                        @error('before_after')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tag-uri (opțional)</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                               id="tags" name="tags" value="{{ old('tags') }}"
                               placeholder="De ex: manichiură, gel, french, etc. (separate prin virgulă)">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            Evidențiază în galeria principală
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Încarcă Imagine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection