@extends('layouts.app')

@section('title', 'Insights Data Science')

@section('content')
<div class="row">
    @forelse($insights as $insight)
    <div class="col-md-6">
        <div class="info-box shadow-sm mb-4 border-left border-info border-3">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-lightbulb"></i></span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold text-dark">{{ $insight->title }}</span>
                <span class="info-box-number text-muted font-weight-normal mb-2" style="font-size: 13px;">
                    <span class="badge bg-secondary">Prioritas: {{ $insight->priority }}</span>
                </span>
                <span class="info-box-text text-wrap text-muted" style="white-space: normal;">
                    {{ $insight->description }}
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-light text-center shadow-sm">
            Belum ada insights yang tercatat.
        </div>
    </div>
    @endforelse
</div>
@endsection
