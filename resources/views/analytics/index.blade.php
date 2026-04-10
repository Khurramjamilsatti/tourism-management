@extends('layouts.app')

@section('title', 'Analytics')
@section('page-title', 'Data Analytics & Visualisation')

@section('content')
{{-- Visitors by Country - Bar Chart --}}
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Visitors by Country</h6>
            </div>
            <div class="card-body">
                <canvas id="countryBarChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Purpose of Visit</h6>
            </div>
            <div class="card-body">
                <canvas id="purposePieChart" height="300"></canvas>
                <hr class="my-3">
                <h6 class="text-muted mb-2"><i class="bi bi-percent"></i> Purpose of Visit (%)</h6>
                <div id="purposePercentageTable"></div>
            </div>
        </div>
    </div>
</div>

{{-- Monthly Trends - Line Chart --}}
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-graph-up"></i> Monthly Visitor Trends (Last 12 Months)</h6>
            </div>
            <div class="card-body">
                <canvas id="trendLineChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Top Cities --}}
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-building"></i> Top 10 Most Visited Cities</h6>
            </div>
            <div class="card-body">
                <canvas id="citiesChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-list-ol"></i> Top Cities Summary</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>City</th>
                            <th>Visitors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCities as $index => $city)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $city->city_visited }}</td>
                            <td><span class="badge bg-primary">{{ $city->total }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No data available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Age Group, Travel Type, Budget Charts --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-person-lines-fill"></i> Age Group Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="ageGroupChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-airplane"></i> Travel Type Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="travelTypeChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-wallet2"></i> Budget Category Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="budgetChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const colors = ['#2c5282','#38a169','#d69e2e','#e53e3e','#805ad5','#dd6b20','#319795','#d53f8c','#718096','#4299e1','#48bb78','#ed8936'];

    // Country Bar Chart
    const countryData = @json($visitorsByCountry);
    new Chart(document.getElementById('countryBarChart'), {
        type: 'bar',
        data: {
            labels: countryData.map(d => d.label),
            datasets: [{
                label: 'Visitors',
                data: countryData.map(d => d.value),
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
    new Chart(document.getElementById('purposePieChart'), {
        type: 'pie',
        data: {
            labels: purposeData.map(d => d.label),
            datasets: [{
                data: purposeData.map(d => d.value),
                backgroundColor: colors,
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12 } },
                datalabels: {
                    display: true,
                    color: '#fff',
                    font: { weight: 'bold', size: 11 },
                    formatter: (value, ctx) => {
                        const sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        return sum > 0 ? ((value / sum) * 100).toFixed(1) + '%' : '';
                    }
                }
            }
        }
    });

    // Purpose Percentage Table
    (function() {
        const total = purposeData.reduce((sum, d) => sum + d.value, 0);
        let html = '<table class="table table-sm table-borderless mb-0">';
        purposeData.forEach((d, i) => {
            const pct = total > 0 ? ((d.value / total) * 100).toFixed(1) : '0.0';
            html += '<tr>' +
                '<td><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:' + colors[i % colors.length] + ';margin-right:6px;"></span>' + d.label + '</td>' +
                '<td class="text-end fw-bold">' + pct + '%</td>' +
                '</tr>';
        });
        html += '</table>';
        document.getElementById('purposePercentageTable').innerHTML = html;
    })();

    // Monthly Trend Line Chart
    const trendData = @json($monthlyTrends);
    new Chart(document.getElementById('trendLineChart'), {
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
                pointRadius: 5,
                pointBackgroundColor: '#2c5282',
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Top Cities Horizontal Bar
    const citiesData = @json($topCities);
    new Chart(document.getElementById('citiesChart'), {
        type: 'bar',
        data: {
            labels: citiesData.map(d => d.city_visited),
            datasets: [{
                label: 'Visitors',
                data: citiesData.map(d => d.total),
                backgroundColor: '#63b3ed',
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
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
