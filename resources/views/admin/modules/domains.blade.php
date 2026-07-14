@extends('layouts.app')

@section('title', 'Manage Domains')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <div>
            <h2 class="fw-bold tracking-tight mb-1">Domains</h2>
            <p class="text-secondary small">Manage and approve user submitted custom domains.</p>
        </div>
        <button class="btn btn-primary rounded-3 px-3 fw-semibold shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createDomainModal">
            <span data-duo-icons="add-circle" class="me-1" style="width: 14px; height: 14px;"></span>Add System Domain
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="glass-card p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-secondary small">
                            <th class="border-0 px-2 py-3">Host Domain</th>
                            <th class="border-0 px-2 py-3">User</th>
                            <th class="border-0 px-2 py-3">Type</th>
                            <th class="border-0 px-2 py-3">Status</th>
                            <th class="border-0 px-2 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($domains as $domain)
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td class="px-2 py-3 fw-bold text-primary">
                                    {{ $domain->host }}
                                </td>
                                <td class="px-2 py-3">
                                    @if($domain->user)
                                        <div class="fw-bold small">{{ $domain->user->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $domain->user->email }}</div>
                                    @else
                                        <span class="text-muted small">System / Null</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3">
                                    <span class="badge {{ $domain->type == 1 ? 'bg-secondary text-secondary' : 'bg-primary text-primary' }} bg-opacity-10 rounded-pill px-3 py-1.5 small">
                                        {{ $domain->type == 1 ? 'System' : 'Custom' }}
                                    </span>
                                </td>
                                <td class="px-2 py-3">
                                    @if($domain->is_enabled)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1.5 small">Active</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2.5 py-1.5 small">Pending</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3 text-end">
                                    <button class="btn btn-sm btn-light border me-1" data-bs-toggle="modal" data-bs-target="#editDomainModal{{ $domain->id }}">Edit / Approve</button>
                                    <button class="btn btn-sm btn-light border text-danger" data-bs-toggle="modal" data-bs-target="#deleteDomainModal{{ $domain->id }}">Delete</button>
                                </td>
                            </tr>

                            <!-- Edit Domain Modal -->
                            <div class="modal fade" id="editDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content glass-card border-0">
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h5 class="modal-title fw-bold">Manage Domain: {{ $domain->host }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('admin.domains.update', $domain->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div class="mb-3">
                                                    <label class="form-label text-secondary small fw-medium">Status</label>
                                                    <select class="form-select" name="is_enabled">
                                                        <option value="1" {{ $domain->is_enabled ? 'selected' : '' }}>Active / Approved</option>
                                                        <option value="0" {{ !$domain->is_enabled ? 'selected' : '' }}>Pending / Disabled</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label text-secondary small fw-medium">Custom Index URL</label>
                                                    <input type="url" class="form-control" name="custom_index_url" value="{{ $domain->custom_index_url }}" placeholder="Optional">
                                                </div>

                                                <div class="mb-4">
                                                    <label class="form-label text-secondary small fw-medium">Custom 404 URL</label>
                                                    <input type="url" class="form-control" name="custom_not_found_url" value="{{ $domain->custom_not_found_url }}" placeholder="Optional">
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Domain Modal -->
                            <div class="modal fade" id="deleteDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content glass-card border-0">
                                        <div class="modal-body text-center p-4">
                                            <span data-duo-icons="warning" class="text-danger mb-3" style="width: 48px; height: 48px;"></span>
                                            <h5 class="fw-bold mb-2">Delete Domain?</h5>
                                            <p class="text-secondary small mb-4">Are you sure you want to delete {{ $domain->host }}? This will break any links using this domain.</p>
                                            <form action="{{ route('admin.domains.destroy', $domain->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-light w-100 mb-2" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger w-100 fw-semibold">Yes, Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">
                                    No domains found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create System Domain Modal -->
<div class="modal fade" id="createDomainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Add System Domain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.domains.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Host (e.g. link.yourdomain.com)</label>
                        <input type="text" class="form-control" name="host" placeholder="link.yourdomain.com" required>
                        <div class="form-text">System domains can be used globally by all users.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Custom Index URL (Optional)</label>
                        <input type="url" class="form-control" name="custom_index_url" placeholder="https://yourwebsite.com">
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-medium">Custom 404 URL (Optional)</label>
                        <input type="url" class="form-control" name="custom_not_found_url" placeholder="https://yourwebsite.com/404">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold">Add System Domain</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
