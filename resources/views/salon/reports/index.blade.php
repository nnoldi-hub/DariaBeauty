@extends('layout')

@section('title', 'Rapoarte & Statistici Salon')

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('salon.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-chart-line"></i> Rapoarte & Statistici</h2>
                    @if($isSalonOwner && $specialists->count() > 1)
                        <p class="text-muted mb-0"><i class="fas fa-building"></i> Vizualizare salon: <strong>{{ $specialists->count() }} specialiști</strong></p>
                    @elseif($isSalonOwner)
                        <p class="text-muted mb-0"><i class="fas fa-info-circle"></i> Invită specialiști să se alăture salonului tău pentru a vedea rapoarte combinate</p>
                    @else
                        <p class="text-muted mb-0"><i class="fas fa-user"></i> Rapoarte personale</p>
                    @endif
                </div>
                <a href="{{ route('salon.reports.export-csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   class="btn btn-success">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>

            {{-- Filtru Perioadă --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('salon.reports.index') }}" class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">De la</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Până la</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filtrează
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Statistici Generale --}}
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['total_appointments'] }}</h3>
                            <p class="mb-0">Total Programări</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['completed_appointments'] }}</h3>
                            <p class="mb-0">Finalizate</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['pending_appointments'] }}</h3>
                            <p class="mb-0">În Așteptare</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['noshow_rate'] }}%</h3>
                            <p class="mb-0">No-Show Rate</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Revenue --}}
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card border-success h-100">
                        <div class="card-body text-center">
                            <h2 class="text-success mb-0">{{ number_format($stats['total_revenue'], 0, ',', '.') }} lei</h2>
                            <p class="mb-0">Revenue Total</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-info h-100">
                        <div class="card-body text-center">
                            <h2 class="text-info mb-0">{{ number_format($stats['avg_appointment_value'], 0, ',', '.') }} lei</h2>
                            <p class="mb-0">Valoare Medie / Programare</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Predicție Săptămâna Viitoare --}}
            <div class="card mb-4 border-warning">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-crystal-ball"></i> Predicție: {{ $prediction['next_week_start'] }} - {{ $prediction['next_week_end'] }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Programări estimate:</strong> {{ $prediction['estimated_appointments'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Revenue estimat:</strong> {{ number_format($prediction['estimated_revenue'], 0, ',', '.') }} lei</p>
                        </div>
                    </div>
                    <small class="text-muted">* Bazat pe media ultimelor 4 săptămâni</small>
                </div>
            </div>

            {{-- Clienți Noi vs Recurenți --}}
            @if($newVsReturning)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Clienți Noi vs Recurenți</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ $newVsReturning->total > 0 ? ($newVsReturning->new_clients / $newVsReturning->total) * 100 : 0 }}%">
                                    Noi: {{ $newVsReturning->new_clients }} ({{ $newVsReturning->total > 0 ? round(($newVsReturning->new_clients / $newVsReturning->total) * 100) : 0 }}%)
                                </div>
                                <div class="progress-bar bg-info" 
                                     style="width: {{ $newVsReturning->total > 0 ? ($newVsReturning->returning_clients / $newVsReturning->total) * 100 : 0 }}%">
                                    Recurenți: {{ $newVsReturning->returning_clients }} ({{ $newVsReturning->total > 0 ? round(($newVsReturning->returning_clients / $newVsReturning->total) * 100) : 0 }}%)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Top Servicii --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Top 10 Servicii (Cele Mai Rezervate)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Serviciu</th>
                                    <th>Programări</th>
                                    <th>Revenue</th>
                                    <th>Preț Mediu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topServices as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->service->name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-primary">{{ $item->total_bookings }}</span></td>
                                    <td><strong>{{ number_format($item->revenue, 0, ',', '.') }} lei</strong></td>
                                    <td>{{ number_format($item->revenue / $item->total_bookings, 0, ',', '.') }} lei</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Nu există date în această perioadă</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Performance Specialiști --}}
            @if($isSalonOwner && $specialists->count() > 1)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-users-cog"></i> Performance Specialiști ({{ $specialists->count() }} specialiști în salon)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Specialist</th>
                                    <th>Programări</th>
                                    <th>Finalizate</th>
                                    <th>Anulate</th>
                                    <th>Revenue</th>
                                    <th>Rată Succes</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($specialistPerformance as $perf)
                                <tr>
                                    <td><strong>{{ $perf->specialist->name ?? 'N/A' }}</strong></td>
                                    <td>{{ $perf->total_appointments }}</td>
                                    <td><span class="badge bg-success">{{ $perf->completed }}</span></td>
                                    <td><span class="badge bg-danger">{{ $perf->cancelled }}</span></td>
                                    <td><strong>{{ number_format($perf->revenue, 0, ',', '.') }} lei</strong></td>
                                    <td>
                                        @php
                                            $successRate = $perf->total_appointments > 0 ? round(($perf->completed / $perf->total_appointments) * 100) : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $successRate >= 80 ? 'bg-success' : ($successRate >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                                 style="width: {{ $successRate }}%">
                                                {{ $successRate }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('salon.reports.specialist-detail', $perf->specialist_id) }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detalii
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Nu există specialiști în salon</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Programări pe Ziua Săptămânii --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-week"></i> Programări pe Zile Săptămână</h5>
                </div>
                <div class="card-body">
                    <canvas id="weekdayChart" height="100"></canvas>
                </div>
            </div>

            {{-- Programări pe Oră (Identifică Ore Moarte) --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Programări pe Oră (Identifică Ore Moarte)</h5>
                </div>
                <div class="card-body">
                    <canvas id="hourChart" height="100"></canvas>
                </div>
            </div>

            {{-- Evoluție Programări în Timp --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-area"></i> Evoluție Programări & Revenue</h5>
                </div>
                <div class="card-body">
                    <canvas id="timelineChart" height="80"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Programări pe Ziua Săptămânii
const weekdayData = @json($appointmentsByWeekday);
const weekdayNames = @json($weekdayNames);
const weekdayLabels = weekdayData.map(d => weekdayNames[d.weekday]);
const weekdayCount = weekdayData.map(d => d.count);
const weekdayRevenue = weekdayData.map(d => d.revenue);

new Chart(document.getElementById('weekdayChart'), {
    type: 'bar',
    data: {
        labels: weekdayLabels,
        datasets: [{
            label: 'Programări',
            data: weekdayCount,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            yAxisID: 'y'
        }, {
            label: 'Revenue (lei)',
            data: weekdayRevenue,
            backgroundColor: 'rgba(75, 192, 192, 0.7)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            type: 'line',
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: { display: true, text: 'Număr Programări' }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: { display: true, text: 'Revenue (lei)' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});

// Programări pe Oră
const hourData = @json($appointmentsByHour);
const hourLabels = hourData.map(d => d.hour + ':00');
const hourCount = hourData.map(d => d.count);

new Chart(document.getElementById('hourChart'), {
    type: 'bar',
    data: {
        labels: hourLabels,
        datasets: [{
            label: 'Programări',
            data: hourCount,
            backgroundColor: hourCount.map(count => {
                // Colorează diferit orele moarte (sub media)
                const avg = hourCount.reduce((a, b) => a + b, 0) / hourCount.length;
                return count < avg * 0.5 ? 'rgba(255, 99, 132, 0.7)' : 'rgba(75, 192, 192, 0.7)';
            }),
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                title: { display: true, text: 'Număr Programări' },
                beginAtZero: true
            },
            x: {
                title: { display: true, text: 'Ora' }
            }
        }
    }
});

// Evoluție în timp
const timelineData = @json($appointmentsByDay);
const timelineLabels = timelineData.map(d => {
    const date = new Date(d.date);
    return date.toLocaleDateString('ro-RO', { day: '2-digit', month: 'short' });
});
const timelineCount = timelineData.map(d => d.count);
const timelineRevenue = timelineData.map(d => d.revenue);

new Chart(document.getElementById('timelineChart'), {
    type: 'line',
    data: {
        labels: timelineLabels,
        datasets: [{
            label: 'Programări',
            data: timelineCount,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y'
        }, {
            label: 'Revenue (lei)',
            data: timelineRevenue,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: { display: true, text: 'Programări' }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: { display: true, text: 'Revenue (lei)' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});
</script>
@endsection
