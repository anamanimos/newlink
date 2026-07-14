@extends('layouts.app')

@section('title', 'Tracking Pixels')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <div>
            <h2 class="fw-bold tracking-tight mb-1">Tracking Pixels</h2>
            <p class="text-secondary small">Integrate Facebook Pixel, Google Analytics, and other tracking tools.</p>
        </div>
        <button class="btn btn-primary rounded-3 px-3 fw-semibold shadow-sm d-flex align-items-center">
            <span data-duo-icons="add-circle" class="me-1" style="width: 14px; height: 14px;"></span>Add Pixel
        </button>
    </div>
</div>

<div class="row g-4">
    @if($pixels->isEmpty())
        <div class="col-12">
            <div class="glass-card p-5 text-center">
                <span data-duo-icons="target" style="width: 48px; height: 48px;" class="mb-3"></span>
                <p class="text-secondary mb-0">No pixels found. Connect Facebook or Google pixel to start tracking.</p>
            </div>
        </div>
    @else
        @foreach($pixels as $pixel)
            <div class="col-12 col-md-4">
                <div class="glass-card p-4 glass-card-hover">
                    <h5 class="fw-bold mb-1">{{ $pixel->name }}</h5>
                    <div class="text-secondary small mb-3">Type: <span class="text-uppercase fw-semibold">{{ $pixel->type }}</span></div>
                    <div class="p-2 rounded-3 bg-secondary bg-opacity-10 font-mono text-center small mb-3">
                        ID: {{ $pixel->pixel }}
                    </div>
                    <div class="text-muted small">
                        Created: {{ $pixel->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
