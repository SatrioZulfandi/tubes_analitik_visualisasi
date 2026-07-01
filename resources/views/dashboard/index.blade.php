@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
@include('partials.global_filter')

@if($totalTrips == 0)
    <!-- Empty State -->
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm border-0 py-5">
                <i class="fas fa-box-open fa-5x text-muted mb-3 opacity-50"></i>
                <h3 class="text-dark font-weight-bold">No Data</h3>
                <p class="text-muted">Tidak ada data yang sesuai dengan filter yang dipilih.</p>
                <div class="mt-3">
                    <a href="{{ url()->current() }}" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-undo mr-1"></i> Reset Filter
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Welcome Banner -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-primary bg-primary text-white shadow-sm border-0 mb-4" style="border-radius: 10px;">
                <h4 class="mb-2 font-weight-bold"><i class="fas fa-info-circle mr-2"></i> Selamat Datang di Dashboard Business Intelligence Transjakarta</h4>
                <p class="mb-1" style="font-size: 1.1rem;">Analisis Segmentasi Penumpang menggunakan Machine Learning K-Means Clustering</p>
                <hr style="border-top: 1px solid rgba(255,255,255,0.3);">
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <span class="mr-3"><i class="fas fa-database mr-1"></i> <span class="kpi-counter">{{ $totalTrips }}</span> Baris Data</span>
                        <span><i class="fas fa-project-diagram mr-1"></i> {{ $totalClusters }} Cluster</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row">
        <div class="col-lg-2 col-6">
            <div class="small-box bg-white shadow-sm border-top border-info border-3">
                <div class="inner">
                    <h3 class="text-info kpi-counter">{{ $totalTrips }}</h3>
                    <p class="text-muted font-weight-bold mb-0">Jumlah Transaksi</p>
                </div>
                <div class="icon"><i class="fas fa-ticket-alt text-info opacity-50"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-white shadow-sm border-top border-success border-3">
                <div class="inner">
                    <h3 class="text-success">{{ $totalClusters }}</h3>
                    <p class="text-muted font-weight-bold mb-0">Jumlah Cluster</p>
                </div>
                <div class="icon"><i class="fas fa-project-diagram text-success opacity-50"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-white shadow-sm border-top border-warning border-3">
                <div class="inner">
                    <h3 class="text-warning kpi-counter">{{ round($avgAge) }}</h3>
                    <p class="text-muted font-weight-bold mb-0">Rata-rata Umur</p>
                </div>
                <div class="icon"><i class="fas fa-users text-warning opacity-50"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-white shadow-sm border-top border-danger border-3">
                <div class="inner">
                    <h3 class="text-danger"><span class="kpi-counter">{{ round($avgTravelDuration) }}</span> <sup style="font-size: 14px">Mnt</sup></h3>
                    <p class="text-muted font-weight-bold mb-0">Rata-rata Durasi</p>
                </div>
                <div class="icon"><i class="fas fa-stopwatch text-danger opacity-50"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-white shadow-sm border-top border-secondary border-3">
                <div class="inner">
                    <h3 class="text-secondary kpi-counter">{{ round($avgStopsPassed) }}</h3>
                    <p class="text-muted font-weight-bold mb-0">Rata-rata Halte</p>
                </div>
                <div class="icon"><i class="fas fa-map-marker-alt text-secondary opacity-50"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-white shadow-sm border-top border-primary border-3">
                <div class="inner">
                    <h3 class="text-primary">Rp <span class="kpi-counter">{{ round($totalRevenue / 1000000, 1) }}</span>M</h3>
                    <p class="text-muted font-weight-bold mb-0">Total Pendapatan</p>
                </div>
                <div class="icon"><i class="fas fa-wallet text-primary opacity-50"></i></div>
            </div>
        </div>
    </div>

    <!-- Chart Area -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header border-0"><h3 class="card-title font-weight-bold text-dark">Distribusi Cluster</h3></div>
                <div class="card-body">
                    <div id="clusterChart" style="min-height: 300px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header border-0"><h3 class="card-title font-weight-bold text-dark">Distribusi Peak Hour</h3></div>
                <div class="card-body">
                    <div id="peakChart" style="min-height: 300px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header border-0"><h3 class="card-title font-weight-bold text-dark">Distribusi Weekday vs Weekend</h3></div>
                <div class="card-body">
                    <div id="weekdayChart" style="min-height: 300px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header border-0"><h3 class="card-title font-weight-bold text-dark">Top 10 Corridor</h3></div>
                <div class="card-body">
                    <div id="corridorChart" style="min-height: 300px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
@if($totalTrips > 0)
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Remove all skeleton loaders before rendering charts
    document.querySelectorAll('.skeleton-loader').forEach(el => el.remove());

    const clusterColors = ['#0d6efd', '#198754', '#fd7e14', '#dc3545'];
    const numberFormatter = new Intl.NumberFormat('id-ID');
    const animConfig = { enabled: true, easing: 'easeinout', speed: 1000, dynamicAnimation: { enabled: true, speed: 350 } };

    // 1. Cluster Distribution (Donut Chart)
    const clusterData = @json($clusterDistribution);
    const clusterLabels = clusterData.map(item => item.cluster ? item.cluster.cluster_name : 'Unknown');
    const clusterSeries = clusterData.map(item => parseInt(item.total));

    const clusterOptions = {
        chart: { type: 'donut', height: 320, animations: animConfig },
        series: clusterSeries,
        labels: clusterLabels,
        colors: clusterColors,
        tooltip: { y: { formatter: function (val) { return numberFormatter.format(val) + " transaksi" } } },
        legend: { position: 'bottom' }
    };
    new ApexCharts(document.querySelector("#clusterChart"), clusterOptions).render();

    // 2. Peak Hour Distribution (Bar Chart)
    const peakData = @json($peakHourDistribution);
    const peakLabels = peakData.map(item => item.peak_hour ? 'Peak Hour' : 'Non Peak Hour');
    const peakSeries = peakData.map(item => parseInt(item.total));

    const peakOptions = {
        chart: { type: 'bar', height: 320, toolbar: { show: false }, animations: animConfig },
        series: [{ name: 'Transaksi', data: peakSeries }],
        xaxis: { categories: peakLabels },
        colors: ['#0dcaf0'],
        dataLabels: { enabled: true, formatter: function (val) { return numberFormatter.format(val); } },
        tooltip: { y: { formatter: function (val) { return numberFormatter.format(val); } } }
    };
    new ApexCharts(document.querySelector("#peakChart"), peakOptions).render();

    // 3. Weekday vs Weekend (Pie Chart)
    const dayData = @json($dayTypeDistribution);
    const dayLabels = dayData.map(item => item.day_type);
    const daySeries = dayData.map(item => parseInt(item.total));

    const weekdayOptions = {
        chart: { type: 'pie', height: 320, animations: animConfig },
        series: daySeries,
        labels: dayLabels,
        colors: ['#6f42c1', '#20c997'],
        tooltip: { y: { formatter: function (val) { return numberFormatter.format(val) + " transaksi" } } },
        legend: { position: 'bottom' }
    };
    new ApexCharts(document.querySelector("#weekdayChart"), weekdayOptions).render();

    // 4. Top Corridor (Horizontal Bar)
    const corridorData = @json($topCorridors);
    const corridorLabels = corridorData.map(item => item.corridor_name);
    const corridorSeries = corridorData.map(item => parseInt(item.total));

    const corridorOptions = {
        chart: { type: 'bar', height: 350, toolbar: { show: false }, animations: animConfig },
        plotOptions: { bar: { horizontal: true, dataLabels: { position: 'top' } } },
        series: [{ name: 'Transaksi', data: corridorSeries }],
        xaxis: { categories: corridorLabels },
        colors: ['#0d6efd'],
        dataLabels: {
            enabled: true, offsetX: 30, style: { fontSize: '10px', colors: ['#304758'] },
            formatter: function (val) { return numberFormatter.format(val); }
        },
        tooltip: { y: { formatter: function (val) { return numberFormatter.format(val); } } }
    };
    new ApexCharts(document.querySelector("#corridorChart"), corridorOptions).render();
});
</script>
@endif
@endpush
