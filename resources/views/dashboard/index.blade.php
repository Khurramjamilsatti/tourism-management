@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stats Cards --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                    <i class="bi bi-people-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ number_format($totalVisitors) }}</h3>
                    <small class="text-muted">Total Visitors</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                    <i class="bi bi-flag-fill text-success fs-4"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $totalCountries }}</h3>
                    <small class="text-muted">Countries Represented</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                    <i class="bi bi-bookmark-fill text-warning fs-4"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $totalPurposes }}</h3>
                    <small class="text-muted">Visit Purposes</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Visitors by Country</h6>
            </div>
            <div class="card-body">
                <canvas id="countryChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Purpose of Visit</h6>
            </div>
            <div class="card-body">
                <canvas id="purposeChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-graph-up"></i> Monthly Visitor Trends</h6>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- New Charts Row: Age Group, Travel Type, Budget --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-person-lines-fill"></i> Age Group Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="ageGroupChart" height="280"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-airplane"></i> Travel Type Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="travelTypeChart" height="280"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-wallet2"></i> Budget Category Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="budgetChart" height="280"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Recent Entries --}}
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-clock-history"></i> Recent Entries</h6>
        <a href="{{ route('tourism-data.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Visitor</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Purpose</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEntries as $entry)
                <tr>
                    <td>{{ $entry->visitor_name ?? 'Anonymous' }}</td>
                    <td>{{ $entry->country->name }}</td>
                    <td>{{ $entry->city_visited }}</td>
                    <td><span class="badge bg-info">{{ $entry->purpose->name }}</span></td>
                    <td>{{ $entry->visit_date ? $entry->visit_date->format('d M Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No data collected yet. <a href="{{ route('tourism-data.create') }}">Add your first record</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const colors = ['#2c5282','#38a169','#d69e2e','#e53e3e','#805ad5','#dd6b20','#319795','#d53f8c','#718096','#4299e1'];

    // Country Bar Chart
    const countryData = @json($visitorsByCountry);
    new Chart(document.getElementById('countryChart'), {
        type: 'bar',
        data: {
            labels: countryData.map(d => d.country),
            datasets: [{
                label: 'Visitors',
                data: countryData.map(d => d.total),
                backgroundColor: colors,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Purpose Pie Chart
    const purposeData = @json($purposeDistribution);
    new Chart(document.getElementById('purposeChart'), {
        type: 'doughnut',
        data: {
            labels: purposeData.map(d => d.purpose),
            datasets: [{
                data: purposeData.map(d => d.total),
                backgroundColor: colors,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } }
        }
    });

    // Monthly Trend Line Chart
    const trendData = @json($monthlyTrends);
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendData.map(d => d.month),
            datasets: [{
                label: 'Visitors',
                data: trendData.map(d => d.total),
                borderColor: '#2c5282',
                backgroundColor: 'rgba(44,82,130,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Age Group Doughnut Chart
    const ageData = @json($ageGroupDistribution);
    new Chart(document.getElementById('ageGroupChart'), {
        type: 'doughnut',
        data: {
            labels: ageData.map(d => d.age_group),
            datasets: [{
                data: ageData.map(d => d.total),
                backgroundColor: colors,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } }
        }
    });

    // Travel Type Bar Chart
    const travelData = @json($travelTypeDistribution);
    new Chart(document.getElementById('travelTypeChart'), {
        type: 'bar',
        data: {
            labels: travelData.map(d => d.travel_type),
            datasets: [{
                label: 'Visitors',
                data: travelData.map(d => d.total),
                backgroundColor: ['#805ad5','#38a169','#d69e2e','#e53e3e','#2c5282','#dd6b20'],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Budget Category Pie Chart
    const budgetData = @json($budgetDistribution);
    new Chart(document.getElementById('budgetChart'), {
        type: 'pie',
        data: {
            labels: budgetData.map(d => d.budget),
            datasets: [{
                data: budgetData.map(d => d.total),
                backgroundColor: ['#38a169','#d69e2e','#e53e3e','#805ad5','#2c5282'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } }
        }
    });
</script>
@endpush
