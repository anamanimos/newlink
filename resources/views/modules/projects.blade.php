@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <div>
            <h2 class="fw-bold tracking-tight mb-1">Projects</h2>
            <p class="text-secondary small">Organize your links and biolinks into projects.</p>
        </div>
        <button class="btn btn-primary rounded-3 px-3 fw-semibold shadow-sm d-flex align-items-center">
            <span data-duo-icons="add-circle" class="me-1" style="width: 14px; height: 14px;"></span>Create Project
        </button>
    </div>
</div>

<div class="row g-4">
    @if($projects->isEmpty())
        <div class="col-12">
            <div class="glass-card p-5 text-center">
                <span data-duo-icons="folder-open" style="width: 48px; height: 48px;" class="mb-3"></span>
                <p class="text-secondary mb-0">No projects found. Create one to organize your links!</p>
            </div>
        </div>
    @else
        @foreach($projects as $project)
            <div class="col-12 col-md-4">
                <div class="glass-card p-4 glass-card-hover position-relative">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2.5 rounded-3 me-3" style="background-color: {{ $project->color }}15;">
                            <span data-duo-icons="folder-open" style="width: 24px; height: 24px; color: {{ $project->color }};"></span>
                        </div>
                        <h5 class="fw-bold mb-0 text-truncate" style="max-width: 180px;">{{ $project->name }}</h5>
                    </div>
                    <div class="text-muted small">
                        Created: {{ $project->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
