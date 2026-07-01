@extends('layouts.app')

@section('title', 'About Project')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 border-top border-primary border-3">
            <div class="card-header bg-white text-center pt-4 pb-0 border-0">
                <h3 class="card-title float-none font-weight-bold text-primary mb-2">
                    <i class="fas fa-bus-alt fa-2x d-block mb-3"></i>
                    {{ $projectData['Project Name'] }}
                </h3>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush text-muted">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold"><i class="fas fa-file-csv mr-2"></i> Dataset Final</span>
                        <span class="badge bg-light text-dark border">{{ $projectData['Dataset'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold"><i class="fas fa-brain mr-2"></i> Metode Machine Learning</span>
                        <span class="badge bg-light text-dark border">{{ $projectData['Method'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold"><i class="fas fa-database mr-2"></i> Total Baris Observasi</span>
                        <span class="badge bg-light text-dark border">{{ number_format($projectData['Total Rows']) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold"><i class="fas fa-project-diagram mr-2"></i> Total Segmen (Cluster)</span>
                        <span class="badge bg-light text-dark border">{{ $projectData['Total Cluster'] }}</span>
                    </li>
                    <li class="list-group-item">
                        <div class="font-weight-bold mb-2"><i class="fas fa-layer-group mr-2"></i> Tech Stack</div>
                        <div>
                            @foreach($projectData['Tech Stack'] as $tech)
                                <span class="badge bg-primary mr-1">{{ $tech }}</span>
                            @endforeach
                        </div>
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-light text-center">
                <small class="text-muted">Dikembangkan untuk Analisis Operasional dan Strategis</small>
            </div>
        </div>
    </div>
</div>
@endsection
