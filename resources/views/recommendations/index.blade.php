@extends('layouts.app')

@section('title', 'Action Recommendations')

@section('content')
<div class="row">
    <!-- High Priority -->
    <div class="col-md-4">
        <div class="card shadow-sm border-top border-danger border-3">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold text-danger"><i class="fas fa-fire mr-1"></i> High Priority</h3>
            </div>
            <div class="card-body bg-light p-2" style="min-height: 500px;">
                @foreach($recommendations->get('High', []) as $rec)
                <div class="card mb-2 shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="font-weight-bold text-dark mb-1">{{ $rec->title }}</h6>
                        <p class="text-muted small mb-0">{{ $rec->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Medium Priority -->
    <div class="col-md-4">
        <div class="card shadow-sm border-top border-warning border-3">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold text-warning"><i class="fas fa-exclamation-triangle mr-1"></i> Medium Priority</h3>
            </div>
            <div class="card-body bg-light p-2" style="min-height: 500px;">
                @foreach($recommendations->get('Medium', []) as $rec)
                <div class="card mb-2 shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="font-weight-bold text-dark mb-1">{{ $rec->title }}</h6>
                        <p class="text-muted small mb-0">{{ $rec->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Low Priority -->
    <div class="col-md-4">
        <div class="card shadow-sm border-top border-success border-3">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold text-success"><i class="fas fa-check-circle mr-1"></i> Low Priority</h3>
            </div>
            <div class="card-body bg-light p-2" style="min-height: 500px;">
                @foreach($recommendations->get('Low', []) as $rec)
                <div class="card mb-2 shadow-sm border-0">
                    <div class="card-body p-3">
                        <h6 class="font-weight-bold text-dark mb-1">{{ $rec->title }}</h6>
                        <p class="text-muted small mb-0">{{ $rec->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
