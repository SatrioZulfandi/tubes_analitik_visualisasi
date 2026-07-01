@extends('layouts.app')

@section('title', 'Cluster Analysis')

@section('content')
@include('partials.global_filter')

@if($clusterSummary->isEmpty())
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
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-project-diagram mr-2 text-primary"></i> Tabel Ringkasan Cluster</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Cluster</th>
                                <th>Total Anggota</th>
                                <th>Avg Umur</th>
                                <th>Avg Durasi</th>
                                <th>Avg Halte</th>
                                <th>Avg Pendapatan</th>
                                <th>Peak Hour (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clusterSummary as $summary)
                            <tr>
                                <td class="font-weight-bold">{{ $summary->cluster->cluster_label ?? 'Unknown' }}</td>
                                <td><span class="kpi-counter">{{ $summary->total_members }}</span></td>
                                <td>{{ round($summary->avg_age) }} thn</td>
                                <td>{{ round($summary->avg_duration) }} mnt</td>
                                <td>{{ round($summary->avg_stops) }}</td>
                                <td>Rp <span class="kpi-counter">{{ round($summary->avg_pay) }}</span></td>
                                <td>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" style="width: {{ $summary->peak_hour_percentage }}%"></div>
                                    </div>
                                    <small>{{ round($summary->peak_hour_percentage, 1) }}%</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header border-0"><h3 class="card-title font-weight-bold text-dark">Distribusi Anggota</h3></div>
                <div class="card-body">
                    <div id="clusterDistribution" style="min-height: 250px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header border-0"><h3 class="card-title font-weight-bold text-dark">Karakteristik Radar</h3></div>
                <div class="card-body">
                    <div id="clusterRadar" style="min-height: 250px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header border-0"><h3 class="card-title font-weight-bold text-dark">Centroid Analysis</h3></div>
                <div class="card-body">
                    <div id="clusterCentroid" style="min-height: 250px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
@if($clusterSummary->isNotEmpty())
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.skeleton-loader').forEach(el => el.remove());

    const clusterColors = ['#0d6efd', '#198754', '#fd7e14', '#dc3545']; // Blue, Green, Orange, Red
    const numberFormatter = new Intl.NumberFormat('id-ID');
    const animConfig = { enabled: true, easing: 'easeinout', speed: 800 };

    const rawSummary = @json($clusterSummary);
    
    const labels = rawSummary.map(item => item.cluster ? item.cluster.cluster_name : 'Unknown');
    const popSeries = rawSummary.map(item => parseInt(item.total_members));

    // 1. Cluster Population (Donut)
    const distOptions = {
        chart: { type: 'donut', height: 280, animations: animConfig },
        series: popSeries,
        labels: labels,
        colors: clusterColors,
        tooltip: {
            y: { formatter: function (val) { return numberFormatter.format(val) + " anggota" } }
        },
        legend: { position: 'bottom' }
    };
    new ApexCharts(document.querySelector("#clusterDistribution"), distOptions).render();

    // 2. Centroid Analysis (Horizontal Bar)
    const avgDurationSeries = rawSummary.map(item => parseFloat(item.avg_duration).toFixed(1));
    const centroidOptions = {
        chart: { type: 'bar', height: 280, toolbar: { show: false }, animations: animConfig },
        plotOptions: { bar: { horizontal: true, distributed: true } },
        series: [{ name: 'Avg Duration (Mins)', data: avgDurationSeries }],
        xaxis: { categories: labels },
        colors: clusterColors,
        dataLabels: { enabled: true },
        legend: { show: false }
    };
    new ApexCharts(document.querySelector("#clusterCentroid"), centroidOptions).render();

    // 3. Radar Chart (DNA)
    const radarCategories = ['Umur', 'Durasi', 'Halte', 'Peak Hour %', 'Tap In Jam'];
    const radarSeries = rawSummary.map(item => {
        return {
            name: item.cluster ? item.cluster.cluster_name : 'Unknown',
            data: [
                parseFloat(item.avg_age),
                parseFloat(item.avg_duration),
                parseFloat(item.avg_stops),
                parseFloat(item.peak_hour_percentage),
                parseFloat(item.avg_hour)
            ]
        }
    });

    const radarOptions = {
        chart: { type: 'radar', height: 280, toolbar: { show: false }, animations: animConfig },
        series: radarSeries,
        labels: radarCategories,
        colors: clusterColors,
        stroke: { width: 2 },
        fill: { opacity: 0.1 },
        markers: { size: 4 }
    };
    new ApexCharts(document.querySelector("#clusterRadar"), radarOptions).render();
});
</script>
@endif
@endpush
