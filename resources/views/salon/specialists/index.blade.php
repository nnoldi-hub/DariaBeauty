@extends('layout')

@section('title', 'Gestionare Specialiști Salon')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('salon.partials.sidebar')
        </div>
        <div class="col-md-9">
            <h2 class="mb-4"><i class="fas fa-users"></i> Specialiști din Salon</h2>

            {{-- Mesaje success/error/warning --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Atenție!</strong><br>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Statistici rapide --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $specialists->count() }}</h3>
                            <p class="mb-0">Total Specialiști</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $specialists->where('is_active', true)->count() }}</h3>
                            <p class="mb-0">Activi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $specialists->where('is_active', false)->count() }}</h3>
                            <p class="mb-0">În Așteptare</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buton adaugă specialist --}}
            <div class="card mb-4 border-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2"><i class="fas fa-user-plus"></i> Adaugă Specialist în Salon</h5>
                            <p class="mb-0 text-muted">Caută și asociază specialiști înregistrați deja pe platformă cu salonul tău</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSpecialistModal">
                                <i class="fas fa-user-plus"></i> Adaugă Specialist
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lista specialiști --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lista Specialiști</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Specialist</th>
                                    <th>Email</th>
                                    <th>Telefon</th>
                                    <th>Status</th>
                                    <th>Programări (ultima lună)</th>
                                    <th>Revenue (ultima lună)</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($specialists as $specialist)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($specialist->profile_image)
                                                <img src="{{ asset('storage/' . $specialist->profile_image) }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    {{ substr($specialist->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <strong>{{ $specialist->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $specialist->email }}</td>
                                    <td>{{ $specialist->phone ?? 'N/A' }}</td>
                                    <td>
                                        @if($specialist->is_active)
                                            <span class="badge bg-success">Activ</span>
                                        @else
                                            <span class="badge bg-warning">În așteptare</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $lastMonthAppointments = $specialist->appointments()
                                                ->where('appointment_date', '>=', now()->subMonth())
                                                ->count();
                                        @endphp
                                        <span class="badge bg-info">{{ $lastMonthAppointments }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $lastMonthRevenue = $specialist->appointments()
                                                ->where('appointment_date', '>=', now()->subMonth())
                                                ->where('status', 'completed')
                                                ->sum('total_amount');
                                        @endphp
                                        <strong>{{ number_format($lastMonthRevenue, 0, ',', '.') }} lei</strong>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('salon.reports.specialist-detail', $specialist->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Vezi raport">
                                                <i class="fas fa-chart-line"></i>
                                            </a>
                                            <a href="{{ route('specialists.show', $specialist->slug ?? $specialist->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="Vezi profil public" 
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                        <p>Nu ai specialiști în salon încă. Adaugă primul specialist folosind butonul de mai sus!</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Adaugă Specialist --}}
<div class="modal fade" id="addSpecialistModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Adaugă Specialist în Salon</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Caută specialiști după nume, email sau telefon. Vor apărea doar specialiștii care NU sunt deja în alt salon.
                </div>

                {{-- Căutare specialist --}}
                <div class="mb-3">
                    <label class="form-label">Caută Specialist <span class="text-danger">*</span></label>
                    <input type="text" id="searchSpecialist" class="form-control" placeholder="Introdu nume, email sau telefon...">
                    <small class="text-muted">Minim 3 caractere pentru căutare</small>
                </div>

                {{-- Loading --}}
                <div id="searchLoading" class="d-none text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Se caută...</span>
                    </div>
                </div>

                {{-- Rezultate căutare --}}
                <div id="searchResults" class="d-none">
                    <h6 class="mb-3">Rezultate:</h6>
                    <div id="resultsList" class="list-group mb-3" style="max-height: 300px; overflow-y: auto;"></div>
                </div>

                {{-- Specialist selectat --}}
                <div id="selectedSpecialist" class="d-none">
                    <h6 class="mb-3">Specialist selectat:</h6>
                    <div class="card border-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1" id="selectedName"></h5>
                                    <p class="mb-1"><i class="fas fa-envelope text-muted"></i> <span id="selectedEmail"></span></p>
                                    <p class="mb-1"><i class="fas fa-phone text-muted"></i> <span id="selectedPhone"></span></p>
                                    <span id="selectedBrand"></span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSelection()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('salon.specialists.associate') }}" method="POST" id="associateForm">
                    @csrf
                    <input type="hidden" name="specialist_id" id="specialist_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
                <button type="button" class="btn btn-success" id="btnAssociate" disabled onclick="submitAssociation()">
                    <i class="fas fa-check"></i> Adaugă în Salon
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let searchTimeout;
let selectedSpecialistId = null;

document.getElementById('searchSpecialist')?.addEventListener('input', function(e) {
    const query = e.target.value.trim();
    
    if (query.length < 3) {
        document.getElementById('searchResults').classList.add('d-none');
        return;
    }

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchSpecialists(query);
    }, 500);
});

function searchSpecialists(query) {
    document.getElementById('searchLoading').classList.remove('d-none');
    document.getElementById('searchResults').classList.add('d-none');
    
    fetch(`{{ route('salon.specialists.search') }}?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('searchLoading').classList.add('d-none');
            const resultsList = document.getElementById('resultsList');
            const searchResults = document.getElementById('searchResults');
            
            resultsList.innerHTML = '';
            
            if (data.length === 0) {
                resultsList.innerHTML = '<div class="list-group-item text-muted text-center">Nu s-au găsit specialiști disponibili</div>';
            } else {
                data.forEach(specialist => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action';
                    item.onclick = (e) => {
                        e.preventDefault();
                        selectSpecialist(specialist);
                    };
                    
                    let brandBadge = '';
                    if (specialist.sub_brand === 'dariaNails') {
                        brandBadge = '<span class="badge bg-danger">dariaNails</span>';
                    } else if (specialist.sub_brand === 'dariaHair') {
                        brandBadge = '<span class="badge bg-primary">dariaHair</span>';
                    } else if (specialist.sub_brand === 'dariaGlow') {
                        brandBadge = '<span class="badge bg-warning text-dark">dariaGlow</span>';
                    }
                    
                    item.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${specialist.name}</h6>
                                <small class="text-muted"><i class="fas fa-envelope"></i> ${specialist.email}</small>
                                ${specialist.phone ? `<small class="text-muted ms-2"><i class="fas fa-phone"></i> ${specialist.phone}</small>` : ''}
                            </div>
                            ${brandBadge}
                        </div>
                    `;
                    
                    resultsList.appendChild(item);
                });
            }
            
            searchResults.classList.remove('d-none');
        })
        .catch(error => {
            document.getElementById('searchLoading').classList.add('d-none');
            console.error('Error:', error);
            alert('Eroare la căutare. Te rugăm să încerci din nou.');
        });
}

function selectSpecialist(specialist) {
    selectedSpecialistId = specialist.id;
    
    document.getElementById('selectedName').textContent = specialist.name;
    document.getElementById('selectedEmail').textContent = specialist.email;
    document.getElementById('selectedPhone').textContent = specialist.phone || 'N/A';
    document.getElementById('specialist_id').value = specialist.id;
    
    let brandBadge = '';
    if (specialist.sub_brand === 'dariaNails') {
        brandBadge = '<span class="badge bg-danger">dariaNails</span>';
    } else if (specialist.sub_brand === 'dariaHair') {
        brandBadge = '<span class="badge bg-primary">dariaHair</span>';
    } else if (specialist.sub_brand === 'dariaGlow') {
        brandBadge = '<span class="badge bg-warning text-dark">dariaGlow</span>';
    }
    document.getElementById('selectedBrand').innerHTML = brandBadge;
    
    document.getElementById('searchResults').classList.add('d-none');
    document.getElementById('selectedSpecialist').classList.remove('d-none');
    document.getElementById('btnAssociate').disabled = false;
}

function clearSelection() {
    selectedSpecialistId = null;
    document.getElementById('selectedSpecialist').classList.add('d-none');
    document.getElementById('btnAssociate').disabled = true;
    document.getElementById('searchSpecialist').value = '';
    document.getElementById('specialist_id').value = '';
}

function submitAssociation() {
    if (!selectedSpecialistId) {
        alert('Te rugăm să selectezi un specialist');
        return;
    }
    
    document.getElementById('associateForm').submit();
}

// Reset modal când se închide
document.getElementById('addSpecialistModal')?.addEventListener('hidden.bs.modal', function () {
    clearSelection();
    document.getElementById('searchResults').classList.add('d-none');
});
</script>
@endsection
