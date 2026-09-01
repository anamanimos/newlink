@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Projects</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Folders & Organization</span>
    </div>
    <div>
        <button class="btn btn-sm btn-primary d-flex align-items-center gap-2 fw-bold" data-bs-toggle="modal" data-bs-target="#createProjectModal">
            <i class="ki-outline ki-plus fs-2"></i> Create Project
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3 shadow-sm">
        <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
        <div class="d-flex flex-column">
            <span class="fs-7 text-gray-900 fw-semibold">{{ session('success') }}</span>
        </div>
    </div>
@endif

<div class="row g-6 g-xl-9">
    <!-- Left Column: 3 Columns Statistics -->
    <div class="col-12 col-lg-4 col-xl-3">
        <div class="d-flex flex-column gap-6">
            
            <!-- Stat 1: Total Projects -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px symbol-circle bg-light-primary me-4 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-folder fs-2x text-primary"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ number_format($totalProjects) }}</span>
                            <span class="text-gray-600 fw-semibold fs-7 mt-1">Total Projects</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-4"></div>
                    <div class="d-flex align-items-center justify-content-between text-muted fs-8">
                        <span>Status</span>
                        <span class="badge badge-light-success fw-bold">Active Folders</span>
                    </div>
                </div>
            </div>

            <!-- Stat 2: Total Links Assigned -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px symbol-circle bg-light-success me-4 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-disconnect fs-2x text-success"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ number_format($totalProjectLinks) }}</span>
                            <span class="text-gray-600 fw-semibold fs-7 mt-1">Links Organized</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-4"></div>
                    <div class="d-flex align-items-center justify-content-between text-muted fs-8">
                        <span>Average</span>
                        <span class="fw-bold text-gray-800">{{ $avgLinksPerProject }} links / project</span>
                    </div>
                </div>
            </div>

            <!-- Stat 3: Top Project -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-circle bg-light-warning me-3 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-crown fs-3 text-warning"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-7 text-muted fw-semibold">Largest Project</span>
                            <span class="fs-6 fw-bolder text-gray-900 text-truncate" style="max-width: 140px;">
                                {{ $topProject ? $topProject->name : 'None yet' }}
                            </span>
                        </div>
                    </div>
                    <div class="bg-light rounded-3 p-3 text-center">
                        <span class="fs-4 fw-bolder text-warning">{{ $topProject ? $topProject->links_count : 0 }}</span>
                        <span class="fs-8 text-muted d-block">total links assigned</span>
                    </div>
                </div>
            </div>

            <!-- Action Card: Quick Create -->
            <div class="card card-flush shadow-sm border-0 bg-light-primary">
                <div class="card-body p-6 text-center">
                    <div class="symbol symbol-50px symbol-circle bg-white shadow-xs mb-3 d-inline-flex align-items-center justify-content-center">
                        <i class="ki-outline ki-folder-add fs-2 text-primary"></i>
                    </div>
                    <h5 class="fs-6 fw-bold text-gray-900 mb-1">Need a new folder?</h5>
                    <p class="text-muted fs-8 mb-4">Group your links and biolinks into custom projects for clean analytics.</p>
                    <button class="btn btn-sm btn-primary w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        + New Project
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Right Column: 9 Columns Table -->
    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-flush shadow-sm border-0">
            
            <!-- Card Header: Search & Actions -->
            <div class="card-header pt-6 pb-2 gap-2 gap-md-5">
                <div class="card-title">
                    <form method="GET" action="{{ route('projects.index') }}" class="d-flex align-items-center position-relative my-1">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                        <input type="text" name="search" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Search project name..." value="{{ request('search') }}" />
                        @if(request('search'))
                            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-icon btn-light ms-2" title="Reset Search">
                                <i class="ki-outline ki-cross fs-4"></i>
                            </a>
                        @endif
                    </form>
                </div>
                <div class="card-toolbar">
                    <button class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        <i class="ki-outline ki-plus fs-3"></i> Add Project
                    </button>
                </div>
            </div>

            <!-- Card Body: Table -->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-220px">Project Name</th>
                                <th class="text-center min-w-120px">Color & Badge</th>
                                <th class="text-center min-w-120px">Total Links</th>
                                <th class="text-end min-w-100px pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($projects as $project)
                                @php
                                    $projColor = $project->color && !in_array(strtolower($project->color), ['#000000', '#000', 'black']) ? $project->color : '#3e97ff';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px symbol-circle me-3 flex-shrink-0">
                                                <span class="symbol-label" style="background-color: {{ $projColor }}18; border: 1.5px solid {{ $projColor }}35;">
                                                    <i class="ki-outline ki-folder fs-2" style="color: {{ $projColor }};"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column min-w-0">
                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProjectModal{{ $project->id }}" class="text-gray-900 fw-bold text-hover-primary fs-6 text-truncate mb-0">
                                                    {{ $project->name }}
                                                </a>
                                                <span class="text-muted fs-8">Created {{ $project->created_at ? $project->created_at->diffForHumans() : '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill border bg-light">
                                            <span class="w-10px h-10px rounded-circle" style="background-color: {{ $projColor }};"></span>
                                            <span class="text-gray-700 fs-8 font-monospace fw-semibold">{{ strtoupper($project->color ?? '#3E97FF') }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-primary fw-bold fs-7 px-3 py-2">
                                            <i class="ki-outline ki-disconnect fs-6 text-primary me-1"></i>
                                            {{ $project->links_count ?? $project->links()->count() }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-icon btn-light-primary me-1" data-bs-toggle="modal" data-bs-target="#editProjectModal{{ $project->id }}" title="Edit Project">
                                            <i class="ki-outline ki-pencil fs-5"></i>
                                        </button>
                                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline" data-confirm="Apakah Anda yakin ingin menghapus project ini?" data-confirm-title="Hapus Project" data-confirm-btn="Ya, Hapus!">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Delete Project">
                                                <i class="ki-outline ki-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-3 border-0 shadow-lg">
                                            <div class="modal-header pb-0 border-0 justify-content-between">
                                                <h3 class="modal-title fw-bold text-gray-900">
                                                    <i class="ki-outline ki-pencil fs-3 text-primary me-2"></i> Edit Project
                                                </h3>
                                                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                                                    <i class="ki-outline ki-cross fs-2"></i>
                                                </div>
                                            </div>
                                            <form action="{{ route('projects.update', $project->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body py-6 px-lg-8">
                                                    <div class="fv-row mb-5">
                                                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Project Name</label>
                                                        <input type="text" name="name" class="form-control form-control-solid form-control-sm" value="{{ $project->name }}" required placeholder="e.g. Marketing Campaign" />
                                                    </div>
                                                    <div class="fv-row mb-2">
                                                        <label class="form-label fs-7 fw-semibold text-gray-900">Theme Color</label>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <input type="color" name="color" class="form-control form-control-color border-0 p-1 rounded-3" style="width: 44px; height: 38px;" value="{{ $project->color }}" />
                                                            <input type="text" class="form-control form-control-solid form-control-sm" value="{{ $project->color }}" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                                                    <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-muted">
                                        <div class="symbol symbol-60px symbol-circle bg-light-primary mb-3 d-inline-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-folder fs-2x text-primary"></i>
                                        </div>
                                        <p class="fs-6 fw-bold text-gray-800 mb-1">No projects found</p>
                                        <p class="fs-7 text-muted mb-4">Start by creating your first folder to organize your links.</p>
                                        <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                                            <i class="ki-outline ki-plus fs-4 me-1"></i> Create Project
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($projects->hasPages())
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-6 pt-4 border-top">
                        <span class="text-muted fs-7">Showing {{ $projects->firstItem() }} to {{ $projects->lastItem() }} of {{ $projects->total() }} projects</span>
                        <div>
                            {{ $projects->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">
                    <i class="ki-outline ki-folder-add fs-2 text-primary me-2"></i> Create New Project
                </h3>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="modal-body py-6 px-lg-8">
                    <div class="fv-row mb-5">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Project Name</label>
                        <input type="text" name="name" class="form-control form-control-solid form-control-sm" placeholder="e.g. Marketing Campaign, Client Links" required />
                    </div>
                    <div class="fv-row mb-4">
                        <label class="form-label fs-7 fw-semibold text-gray-900">Theme Color</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="color" class="form-control form-control-color border-0 p-1 rounded-3" style="width: 44px; height: 38px;" value="#3b82f6" id="createProjectColorInput" />
                            <input type="text" class="form-control form-control-solid form-control-sm" value="#3B82F6" id="createProjectColorHex" readonly />
                        </div>
                    </div>
                    <div class="fv-row">
                        <label class="form-label fs-8 fw-semibold text-muted mb-2">Preset Colors</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#111827'] as $pColor)
                                <button type="button" class="btn btn-sm btn-icon rounded-circle border-0 shadow-xs color-preset-btn" style="background-color: {{ $pColor }}; width: 28px; height: 28px;" data-color="{{ $pColor }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                    <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preset color buttons
    document.querySelectorAll('.color-preset-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const color = this.getAttribute('data-color');
            const colorInput = document.getElementById('createProjectColorInput');
            const colorHex = document.getElementById('createProjectColorHex');
            if (colorInput) colorInput.value = color;
            if (colorHex) colorHex.value = color.toUpperCase();
        });
    });

    const createColorInput = document.getElementById('createProjectColorInput');
    if (createColorInput) {
        createColorInput.addEventListener('input', function() {
            const colorHex = document.getElementById('createProjectColorHex');
            if (colorHex) colorHex.value = this.value.toUpperCase();
        });
    }
});
</script>
@endsection
