@extends('layouts.app')

@section('title', 'Dataset Eksplorasi')

@section('content')
@if($dataset->isEmpty())
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm border-0 py-5">
                <i class="fas fa-box-open fa-5x text-muted mb-3 opacity-50"></i>
                <h3 class="text-dark font-weight-bold">No Data</h3>
                <p class="text-muted">Tidak ada data yang sesuai dengan filter yang dipilih.</p>
                <div class="mt-3">
                    <a href="{{ url()->current() }}" class="btn btn-primary px-4 shadow-sm" aria-label="Reset Filter">
                        <i class="fas fa-undo mr-1"></i> Reset Filter
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white pt-3 pb-2 border-0">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-table text-primary mr-2"></i> Data Transaksi Transjakarta
                    </h3>
                </div>
                <div class="card-body">
                    <table id="datasetTable" class="table table-bordered table-striped table-hover text-nowrap align-middle w-100">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Cluster</th>
                                <th>Koridor</th>
                                <th>Umur</th>
                                <th>Durasi</th>
                                <th>Halte</th>
                                <th>Tipe Hari</th>
                                <th>Bank</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataset as $row)
                            <tr>
                                <td><span class="badge bg-info">{{ $row->cluster->cluster_code ?? '-' }}</span></td>
                                <td>{{ $row->corridor_name }}</td>
                                <td data-sort="{{ $row->age }}">{{ $row->age }} thn</td>
                                <td data-sort="{{ $row->travel_duration }}">{{ $row->travel_duration }} mnt</td>
                                <td>{{ $row->stops_passed }}</td>
                                <td>{{ $row->day_type }}</td>
                                <td>{{ $row->pay_card_bank }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
@if($dataset->isNotEmpty())
<script>
$(document).ready(function() {
    $('#datasetTable').DataTable({
        responsive: true,
        processing: true,
        pageLength: 20,
        lengthMenu: [10, 20, 50, 100],
        stateSave: true,
        ordering: true,
        searching: true,
        autoWidth: false,
        dom: "<'row mb-3'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4 text-center'B><'col-sm-12 col-md-4'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            { extend: 'copy', className: 'btn btn-sm btn-outline-secondary', text: '<i class="fas fa-copy mr-1"></i> Copy' },
            { extend: 'csv', className: 'btn btn-sm btn-outline-info', text: '<i class="fas fa-file-csv mr-1"></i> CSV' },
            { extend: 'excel', className: 'btn btn-sm btn-outline-success', text: '<i class="fas fa-file-excel mr-1"></i> Excel' },
            { extend: 'print', className: 'btn btn-sm btn-outline-primary', text: '<i class="fas fa-print mr-1"></i> Print' }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Menampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data yang sesuai",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari total _MAX_ data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            processing: "Sedang memproses..."
        }
    });
});
</script>
@endif
@endpush
