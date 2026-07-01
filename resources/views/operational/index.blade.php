@extends('layouts.app')

@section('title', 'Operational Analysis')

@section('content')
@include('partials.global_filter')

@if($peakHourDist->isEmpty())
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
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0"><h3 class="card-title font-weight-bold text-dark"><i class="fas fa-clock mr-2 text-info"></i> Peak Hour</h3></div>
                <div class="card-body">
                    <div id="peakHourChart" style="min-height: 200px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0"><h3 class="card-title font-weight-bold text-dark"><i class="fas fa-calendar-day mr-2 text-info"></i> Day Type</h3></div>
                <div class="card-body">
                    <div id="dayTypeChart" style="min-height: 200px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0"><h3 class="card-title font-weight-bold text-dark"><i class="fas fa-users mr-2 text-info"></i> Age Group</h3></div>
                <div class="card-body">
                    <div id="ageGroupChart" style="min-height: 200px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0"><h3 class="card-title font-weight-bold text-dark"><i class="fas fa-suitcase mr-2 text-info"></i> Travel Type</h3></div>
                <div class="card-body">
                    <div id="travelTypeChart" style="min-height: 300px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0"><h3 class="card-title font-weight-bold text-dark"><i class="fas fa-clock mr-2 text-info"></i> Time Category</h3></div>
                <div class="card-body">
                    <div id="timeCategoryChart" style="min-height: 300px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0"><h3 class="card-title font-weight-bold text-dark"><i class="fas fa-route mr-2 text-primary"></i> Top Corridor</h3></div>
                <div class="card-body">
                    <div id="corridorChart" style="min-height: 350px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0"><h3 class="card-title font-weight-bold text-dark"><i class="fas fa-map-marker-alt mr-2 text-primary"></i> Stops Distribution</h3></div>
                <div class="card-body">
                    <div id="stopsChart" style="min-height: 350px;"><div class="skeleton-loader">Memuat Grafik...</div></div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
@if($peakHourDist->isNotEmpty())
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.skeleton-loader').forEach(el => el.remove());

    const defaultColor = '#0d6efd';
    const numberFormatter = new Intl.NumberFormat('id-ID');
    const animConfig = { enabled: true, easing: 'easeinout', speed: 800 };

    // 1. Peak Hour (Bar)
    const peakData = @json($peakHourDist);
    new ApexCharts(document.querySelector("#peakHourChart"), {
        chart: { type: 'bar', height: 230, toolbar: { show: false }, animations: animConfig },
        series: [{ name: 'Transaksi', data: peakData.map(item => parseInt(item.total)) }],
        xaxis: { categories: peakData.map(item => item.peak_hour ? 'Peak Hour' : 'Non Peak') },
        colors: ['#dc3545'],
        plotOptions: { bar: { columnWidth: '50%' } },
        dataLabels: { enabled: false }
    }).render();

    // 2. Day Type (Pie)
    const dayData = @json($dayTypeDist);
    new ApexCharts(document.querySelector("#dayTypeChart"), {
        chart: { type: 'pie', height: 230, animations: animConfig },
        series: dayData.map(item => parseInt(item.total)),
        labels: dayData.map(item => item.day_type),
        colors: ['#198754', '#fd7e14'],
        legend: { position: 'bottom' }
    }).render();

    // 3. Age Group (Bar)
    const ageData = @json($ageGroupDist);
    new ApexCharts(document.querySelector("#ageGroupChart"), {
        chart: { type: 'bar', height: 230, toolbar: { show: false }, animations: animConfig },
        series: [{ name: 'Transaksi', data: ageData.map(item => parseInt(item.total)) }],
        xaxis: { categories: ageData.map(item => item.age_group) },
        colors: ['#6f42c1'],
        dataLabels: { enabled: false }
    }).render();

    // 4. Travel Type (Bar)
    const travelData = @json($travelTypeDist);
    new ApexCharts(document.querySelector("#travelTypeChart"), {
        chart: { type: 'bar', height: 320, toolbar: { show: false }, animations: animConfig },
        series: [{ name: 'Transaksi', data: travelData.map(item => parseInt(item.total)) }],
        xaxis: { categories: travelData.map(item => item.travel_type) },
        colors: ['#20c997'],
        dataLabels: { enabled: true, formatter: function(val) { return numberFormatter.format(val); } }
    }).render();

    // 5. Time Category (Bar)
    const timeData = @json($timeCategoryDist);
    new ApexCharts(document.querySelector("#timeCategoryChart"), {
        chart: { type: 'bar', height: 320, toolbar: { show: false }, animations: animConfig },
        series: [{ name: 'Transaksi', data: timeData.map(item => parseInt(item.total)) }],
        xaxis: { categories: timeData.map(item => item.time_category) },
        colors: ['#ffc107'],
        dataLabels: { enabled: true, formatter: function(val) { return numberFormatter.format(val); } }
    }).render();

    // 6. Top Corridor (Horizontal Bar)
    const corridorData = @json($topCorridor);
    new ApexCharts(document.querySelector("#corridorChart"), {
        chart: { type: 'bar', height: 350, toolbar: { show: false }, animations: animConfig },
        plotOptions: { bar: { horizontal: true, dataLabels: { position: 'top' } } },
        series: [{ name: 'Transaksi', data: corridorData.map(item => parseInt(item.total)) }],
        xaxis: { categories: corridorData.map(item => item.corridor_name) },
        colors: [defaultColor],
        dataLabels: { enabled: true, offsetX: 20, style: { fontSize: '10px', colors: ['#304758'] }, formatter: function(val) { return numberFormatter.format(val); } },
        tooltip: { y: { formatter: function (val) { return numberFormatter.format(val); } } }
    }).render();

    // 7. Stops Distribution (Horizontal Bar)
    const stopsData = @json($stopsDist);
    new ApexCharts(document.querySelector("#stopsChart"), {
        chart: { type: 'bar', height: 350, toolbar: { show: false }, animations: animConfig },
        plotOptions: { bar: { horizontal: true, dataLabels: { position: 'top' } } },
        series: [{ name: 'Transaksi', data: stopsData.map(item => parseInt(item.total)) }],
        xaxis: { categories: stopsData.map(item => item.stops_passed + " Halte") },
        colors: ['#0dcaf0'],
        dataLabels: { enabled: true, offsetX: 20, style: { fontSize: '10px', colors: ['#304758'] }, formatter: function(val) { return numberFormatter.format(val); } },
        tooltip: { y: { formatter: function (val) { return numberFormatter.format(val); } } }
    }).render();
});
</script>
@endif
@endpush
