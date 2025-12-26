@extends('layout')

@section('title', 'Raport Specialist - ' . $specialist->name)

@section('content')
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            @include('salon.partials.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-user-circle"></i> {{ $specialist->name }}</h2>
                <div>
                    <a href="{{ route('salon.reports.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Înapoi
                    </a>
                    <a href="{{ route('salon.reports.export-csv', ['start_date' => $startDate, 'end_date' => $endDate, 'specialist_id' => $specialist->id]) }}" 
                       class="btn btn-success">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                </div>
            </div>

            {{-- Filtru Perioadă --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('salon.reports.specialist-detail', $specialist->id) }}" class="row g-3">
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
                            <h3 class="mb-0">{{ $stats['completed'] }}</h3>
                            <p class="mb-0">Finalizate</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                            <p class="mb-0">În Așteptare</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['cancelled'] }}</h3>
                            <p class="mb-0">Anulate</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Revenue --}}
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-success h-100">
                        <div class="card-body text-center">
                            <h2 class="text-success mb-0">{{ number_format($stats['total_revenue'], 0, ',', '.') }} lei</h2>
                            <p class="mb-0">Revenue Total</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-info h-100">
                        <div class="card-body text-center">
                            <h2 class="text-info mb-0">{{ number_format($stats['avg_appointment_value'], 0, ',', '.') }} lei</h2>
                            <p class="mb-0">Valoare Medie</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-primary h-100">
                        <div class="card-body text-center">
                            <h2 class="text-primary mb-0">{{ $stats['noshow_rate'] }}%</h2>
                            <p class="mb-0">No-Show Rate</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Servicii pentru acest Specialist --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Top Servicii</h5>
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

            {{-- Programări pe Ziua Săptămânii --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-week"></i> Programări pe Zile Săptămână</h5>
                </div>
                <div class="card-body">
                    <canvas id="weekdayChart" height="100"></canvas>
                </div>
            </div>

            {{-- Programări pe Oră --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Programări pe Oră</h5>
                </div>
                <div class="card-body">
                    <canvas id="hourChart" height="100"></canvas>
                </div>
            </div>

            {{-- Evoluție în Timp --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Evoluție Programări & Revenue</h5>
                </div>
                <div class="card-body">
                    <canvas id="timelineChart" height="80"></canvas>
                </div>
            </div>

            {{-- Programări Recente --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Ultimele 20 Programări</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Ora</th>
                                    <th>Client</th>
                                    <th>Serviciu</th>
                                    <th>Preț</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAppointments as $apt)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d.m.Y') }}</td>
                                    <td>{{ $apt->appointment_time }}</td>
                                    <td>{{ $apt->client_name }}</td>
                                    <td>{{ $apt->service->name ?? 'N/A' }}</td>
                                    <td><strong>{{ number_format($apt->price, 0, ',', '.') }} lei</strong></td>
                                    <td>
                                        @if($apt->status == 'completed')
                                            <span class="badge bg-success">Finalizat</span>
                                        @elseif($apt->status == 'cancelled')
                                            <span class="badge bg-danger">Anulat</span>
                                        @elseif($apt->status == 'noshow')
                                            <span class="badge bg-warning">No-Show</span>
                                        @else
                                            <span class="badge bg-info">{{ ucfirst($apt->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Nu există programări</td>
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

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Programări pe Ziua Săptămânii
const weekdayData = @json($appointmentsByWeekday);
const weekdayNames = @json($weekdayNames);
const weekdayLabels = weekdayData.map(d => weekdayNames[d.weekday]);
const weekdayCount = weekdayData.map(d => d.count);

new Chart(document.getElementById('weekdayChart'), {
    type: 'bar',
    data: {
        labels: weekdayLabels,
        datasets: [{
            label: 'Programări',
            data: weekdayCount,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Număr Programări' }
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
            backgroundColor: 'rgba(75, 192, 192, 0.7)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Număr Programări' }
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
