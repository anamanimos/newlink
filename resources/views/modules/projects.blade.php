@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Projects</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Folders</span>
    </div>
    <button class="btn btn-sm btn-primary d-flex align-items-center gap-2">
        <i class="ki-outline ki-plus fs-2"></i> Create Project
    </button>
</div>

<div class="row g-6 g-xl-9">
    @if($projects->isEmpty())
        <div class="col-12">
            <div class="card card-flush shadow-sm border-0 p-10 text-center">
                <i class="ki-outline ki-folder fs-4x text-muted mb-3"></i>
                <p class="text-gray-600 fw-semibold fs-6 mb-0">No projects found. Create one to organize your links!</p>
            </div>
        </div>
    @else
        @foreach($projects as $project)
            <div class="col-md-6 col-xl-4">
                <div class="card card-flush shadow-sm border-0 h-100 hover-elevate-up">
                    <div class="card-body d-flex flex-column p-6">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-45px symbol-circle me-4 d-flex align-items-center justify-content-center" style="background-color: {{ $project->color }}18;">
                                <i class="ki-outline ki-folder fs-2" style="color: {{ $project->color }};"></i>
                            </div>
                            <div class="d-flex flex-column min-w-0">
                                <span class="fs-5 fw-bold text-gray-900 text-truncate">{{ $project->name }}</span>
                                <span class="text-muted fs-7">Created: {{ $project->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
