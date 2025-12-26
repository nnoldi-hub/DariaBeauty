@extends('layout')

@section('title', 'Dashboard Salon')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('salon.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-building"></i> Dashboard Salon</h2>
                    <p class="text-muted mb-0">{{ Auth::user()->name }}</p>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">Ultima actualizare</small>
                    <strong>{{ now()->format('d M Y, H:i') }}</strong>
                </div>
            </div>

            {{-- Alert dacă nu are specialiști --}}
            @if($specialists->count() == 0)
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-1">Adaugă primul tău specialist!</h5>
                        <p class="mb-2">Salonul tău nu are încă specialiști. Caută și adaugă specialiști înregistrați pe platformă pentru a începe să primești rezervări.</p>
                        <a href="{{ route('salon.specialists.index') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-user-plus"></i> Adaugă Specialist
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Statistici Generale Salon --}}
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <h3 class="mb-0">{{ $stats['total_specialists'] }}</h3>
                            <p class="mb-0 text-muted">Specialiști Activi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                            <h3 class="mb-0">{{ $stats['total_appointments_today'] }}</h3>
                            <p class="mb-0 text-muted">Programări Astăzi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                            <h3 class="mb-0">{{ $stats['pending_appointments'] }}</h3>
                            <p class="mb-0 text-muted">În Așteptare</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-coins fa-2x text-info mb-2"></i>
                            <h3 class="mb-0">{{ number_format($stats['revenue_today'], 0) }}</h3>
                            <p class="mb-0 text-muted">Revenue Astăzi (lei)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Luna Curentă --}}
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">PROGRAMĂRI LUNA ACEASTA</h6>
                                    <h2 class="mb-0">{{ $stats['appointments_this_month'] }}</h2>
                                    @if($stats['appointments_growth'] > 0)
                                        <small><i class="fas fa-arrow-up"></i> +{{ $stats['appointments_growth'] }}% vs luna trecută</small>
                                    @elseif($stats['appointments_growth'] < 0)
                                        <small><i class="fas fa-arrow-down"></i> {{ $stats['appointments_growth'] }}% vs luna trecută</small>
                                    @else
                                        <small><i class="fas fa-minus"></i> Fără schimbare</small>
                                    @endif
                                </div>
                                <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">REVENUE LUNA ACEASTA</h6>
                                    <h2 class="mb-0">{{ number_format($stats['revenue_this_month'], 0) }}</h2>
                                    <small>lei</small>
                                    @if($stats['revenue_growth'] > 0)
                                        <small class="d-block"><i class="fas fa-arrow-up"></i> +{{ $stats['revenue_growth'] }}%</small>
                                    @endif
                                </div>
                                <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">VALOARE MEDIE PROGRAMARE</h6>
                                    <h2 class="mb-0">{{ number_format($stats['avg_appointment_value'], 0) }}</h2>
                                    <small>lei</small>
                                </div>
                                <i class="fas fa-chart-line fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Performeri --}}
            @if($specialists->count() > 0)
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-trophy text-warning"></i> Top Performeri Luna Aceasta</h5>
                        <a href="{{ route('salon.reports.index') }}" class="btn btn-sm btn-outline-primary">
                            Vezi toate rapoartele <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Poziție</th>
                                    <th>Specialist</th>
                                    <th>Programări</th>
                                    <th>Finalizate</th>
                                    <th>Revenue</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topPerformers as $index => $performer)
                                <tr>
                                    <td>
                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark"><i class="fas fa-crown"></i> #1</span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary"><i class="fas fa-medal"></i> #2</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-secondary"><i class="fas fa-medal"></i> #3</span>
                                        @else
                                            <span class="text-muted">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($performer->profile_image)
                                                <img src="{{ asset('storage/' . $performer->profile_image) }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 35px; height: 35px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 35px; height: 35px; font-size: 14px;">
                                                    {{ substr($performer->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <strong>{{ $performer->name }}</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">{{ $performer->total_appointments }}</span></td>
                                    <td><span class="badge bg-success">{{ $performer->completed_appointments }}</span></td>
                                    <td><strong>{{ number_format($performer->total_revenue, 0) }} lei</strong></td>
                                    <td>
                                        @if($performer->avg_rating)
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $performer->avg_rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <small class="text-muted">({{ number_format($performer->avg_rating, 1) }})</small>
                                            </span>
                                        @else
                                            <small class="text-muted">Fără rating</small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <div class="row mb-4">
                {{-- Programări Astăzi --}}
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0"><i class="fas fa-calendar-day"></i> Programări Astăzi</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            @forelse($todayAppointments as $appointment)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <strong>{{ $appointment->appointment_time }}</strong>
                                    <p class="mb-0 text-muted small">
                                        {{ $appointment->specialist->name ?? 'N/A' }} - {{ $appointment->service->name ?? 'N/A' }}
                                    </p>
                                    <small class="text-muted">{{ $appointment->client_name }}</small>
                                </div>
                                <div class="text-end">
                                    @if($appointment->status == 'completed')
                                        <span class="badge bg-success">Finalizat</span>
                                    @elseif($appointment->status == 'pending')
                                        <span class="badge bg-warning">În așteptare</span>
                                    @elseif($appointment->status == 'confirmed')
                                        <span class="badge bg-info">Confirmat</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                    @endif
                                    <div><strong>{{ number_format($appointment->price, 0) }} lei</strong></div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-3x mb-2"></i>
                                <p>Nu sunt programări astăzi</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0"><i class="fas fa-bolt"></i> Acțiuni Rapide</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <a href="{{ route('salon.specialists.index') }}" class="btn btn-outline-primary btn-lg text-start">
                                    <i class="fas fa-users me-2"></i>
                                    Gestionează Specialiști
                                    <span class="badge bg-primary float-end">{{ $specialists->count() }}</span>
                                </a>
                                
                                <a href="{{ route('salon.reports.index') }}" class="btn btn-outline-success btn-lg text-start">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Vezi Rapoarte Complete
                                </a>
                                
                                <a href="{{ route('specialist.appointments.index') }}" class="btn btn-outline-warning btn-lg text-start">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Toate Programările
                                    @if($stats['pending_appointments'] > 0)
                                        <span class="badge bg-warning text-dark float-end">{{ $stats['pending_appointments'] }}</span>
                                    @endif
                                </a>
                                
                                <a href="{{ route('specialist.profile') }}" class="btn btn-outline-info btn-lg text-start">
                                    <i class="fas fa-cog me-2"></i>
                                    Setări Salon
                                </a>
                            </div>

                            @if($specialists->count() == 0)
                            <div class="alert alert-warning mt-3 mb-0">
                                <small><i class="fas fa-exclamation-triangle"></i> <strong>Atenție:</strong> Invită specialiști pentru a începe să primești rezervări!</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grafic Evoluție Ultimele 7 Zile --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-area"></i> Evoluție Ultimele 7 Zile</h5>
                </div>
                <div class="card-body">
                    <canvas id="weeklyChart" height="80"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Grafic ultimele 7 zile
const weeklyData = @json($weeklyData);
const labels = weeklyData.map(d => {
    const date = new Date(d.date);
    return date.toLocaleDateString('ro-RO', { weekday: 'short', day: 'numeric', month: 'short' });
});
const appointmentsData = weeklyData.map(d => d.appointments);
const revenueData = weeklyData.map(d => d.revenue);

new Chart(document.getElementById('weeklyChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Programări',
            data: appointmentsData,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y'
        }, {
            label: 'Revenue (lei)',
            data: revenueData,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Programări'
                },
                beginAtZero: true
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Revenue (lei)'
                },
                grid: {
                    drawOnChartArea: false,
                },
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
