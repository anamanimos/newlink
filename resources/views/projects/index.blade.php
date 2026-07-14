@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 mt-2">
    <h4 class="fw-bold mb-0 d-flex align-items-center text-dark-custom" style="font-size: 1.5rem; letter-spacing: -0.5px;">
        <span data-duo-icons="folder_open" style="width: 22px; height: 22px; margin-right: 12px;" class="text-muted"></span>
        Projects
    </h4>
    <button class="btn btn-primary d-flex align-items-center gap-1.5 py-2 px-3.5 fw-semibold rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createProjectModal" style="background-color: var(--primary-color); border-color: var(--primary-color);">
        <span data-duo-icons="add_circle" style="width: 16px; height: 16px;"></span>
        Create Project
    </button>
</div>

<div class="glass-card table-responsive p-0 border-0 rounded-4 mb-4">
    <table class="table table-hover mb-0 custom-table">
        <thead class="bg-light">
            <tr>
                <th class="ps-4">Project Name</th>
                <th class="text-center">Links</th>
                <th class="text-end pe-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
            <tr>
                <td class="ps-4">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 16px; height: 16px; border-radius: 50%; background-color: {{ $project->color }};"></div>
                        <span class="fw-semibold" style="color: var(--text-primary);">{{ $project->name }}</span>
                    </div>
                </td>
                <td class="text-center text-muted">
                    {{ $project->links_count ?? $project->links()->count() }}
                </td>
                <td class="text-end pe-4">
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                            <span data-duo-icons="menu" style="width: 18px; height: 18px;"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#" data-bs-toggle="modal" data-bs-target="#editProjectModal{{ $project->id }}">
                                    Edit
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus project ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade glass-modal" id="editProjectModal{{ $project->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">Edit Project</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('projects.update', $project->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body py-4">
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-secondary">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $project->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-secondary">Color</label>
                                    <input type="color" name="color" class="form-control form-control-color w-100" value="{{ $project->color }}" title="Choose your color">
                                </div>
                            </div>
                            <div class="modal-footer border-top-0 pt-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-4">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="3" class="text-center py-5 text-muted">
                    Belum ada project yang dibuat.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    {{ $projects->links() }}
</div>

<!-- Create Modal -->
<div class="modal fade glass-modal" id="createProjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Create Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Project Name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Color</label>
                        <input type="color" name="color" class="form-control form-control-color w-100" value="#000000" title="Choose your color">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
