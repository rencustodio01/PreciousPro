@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <small>Welcome back, {{ auth()->user()->full_name }} · {{ now()->format('F d, Y') }}</small>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-xl-2">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-gem"></i></div>
            <div class="kpi-value">{{ $totalProducts }}</div>
            <div class="kpi-label">Active Products</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-gear"></i></div>
            <div class="kpi-value">{{ $pendingProduction }}</div>
            <div class="kpi-label">Pending Production</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="kpi-card" style="--gold-light:#2ECC71">
            <div class="kpi-icon" style="background:#EAFAF1"><i class="bi bi-shield-check" style="color:#2ECC71"></i></div>
            <div class="kpi-value">{{ $passedInspections }}</div>
            <div class="kpi-label">QC Passed (Month)</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="kpi-card" style="--gold-light:#E74C3C">
            <div class="kpi-icon" style="background:#FDEDEC"><i class="bi bi-x-circle" style="color:#E74C3C"></i></div>
            <div class="kpi-value">{{ $failedInspections }}</div>
            <div class="kpi-label">QC Failed (Month)</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="kpi-card" style="--gold-light:#F39C12">
            <div class="kpi-icon" style="background:#FEF9E7"><i class="bi bi-exclamation-triangle" style="color:#F39C12"></i></div>
            <div class="kpi-value">{{ $lowStockItems }}</div>
            <div class="kpi-label">Low Stock Items</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="kpi-value">₱{{ number_format($totalMonthCost, 0) }}</div>
            <div class="kpi-label">Monthly Cost</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="data-card p-4">
            <h5 style="font-family:'Playfair Display',serif;margin-bottom:20px">Monthly Production Volume</h5>
            <canvas id="productionChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="data-card p-4">
            <h5 style="font-family:'Playfair Display',serif;margin-bottom:20px">Cost Breakdown (This Month)</h5>
            <canvas id="costChart" height="200"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('productionChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{ label: 'Units Produced',
            data: @json(collect(range(1,12))->map(fn($m) => $monthlyProduction[$m] ?? 0)),
            backgroundColor: 'rgba(184,150,12,0.7)', borderColor: '#B8960C',
            borderWidth: 1, borderRadius: 6 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('costChart'), {
    type: 'doughnut',
    data: {
        labels: @json($costBreakdown->keys()),
        datasets: [{ data: @json($costBreakdown->values()),
            backgroundColor: ['#B8960C','#2ECC71','#3498DB'], borderWidth: 0 }]
    },
    options: { cutout: '70%', plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
