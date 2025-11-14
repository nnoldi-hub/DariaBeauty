@extends('layout')

@section('title', 'Utilizatori - Admin DariaBeauty')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Gestionare Utilizatori</h3>
                <a href="{{ route('admin.users-crud.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Adaugă Utilizator
                </a>
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

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.users-crud.index') }}" class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Caută utilizator..." 
                                   value="{{ request('search') }}" id="searchUser">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="role" id="filterRole">
                                <option value="">Toate rolurile</option>
                                <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>Client</option>
                                <option value="specialist" {{ request('role') === 'specialist' ? 'selected' : '' }}>Specialist</option>
                                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="is_active" id="filterStatus">
                                <option value="">Toate statusurile</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Activ</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactiv</option>
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
                                    <th>Nume</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Status</th>
                                    <th>Înregistrat</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td><strong>{{ $user->name }}</strong></td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role === 'superadmin' ? 'danger' : ($user->role === 'admin' ? 'warning' : ($user->role === 'specialist' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                {{ $user->is_active ? 'Activ' : 'Inactiv' }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.users-crud.edit', $user) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Editează">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->role !== 'superadmin' && $user->id !== auth()->id())
                                                <form action="{{ route('admin.users-crud.destroy', $user) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Sigur ștergi acest utilizator?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Șterge">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Nu există utilizatori care să corespundă criteriilor de căutare.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($users->hasPages())
                        <div class="mt-3">
                            {!! $users->appends(request()->query())->links() !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
