@extends('layouts.app')

@section('title', 'Custom Domains')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <div>
            <h2 class="fw-bold tracking-tight mb-1">Custom Domains</h2>
            <p class="text-secondary small">Connect your own custom domains for branded short links.</p>
        </div>
        <div>
            @if($domainLimit !== -1)
                <span class="badge bg-secondary bg-opacity-10 text-secondary me-2">
                    {{ $domains->count() }} / {{ $domainLimit }} Used
                </span>
            @endif
            <button class="btn btn-primary rounded-3 px-3 fw-semibold shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createDomainModal" {{ ($domainLimit !== -1 && $domains->count() >= $domainLimit) ? 'disabled' : '' }}>
                <span data-duo-icons="add-circle" class="me-1" style="width: 14px; height: 14px;"></span>Connect Domain
            </button>
        </div>
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

<div class="row g-4">
    @if($domains->isEmpty())
        <div class="col-12">
            <div class="glass-card p-5 text-center">
                <span data-duo-icons="world" style="width: 48px; height: 48px; color: #6c757d;" class="mb-3"></span>
                <p class="text-secondary mb-0">No domains connected yet.</p>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-secondary small">
                                <th class="border-0 px-2 py-3">Host Domain</th>
                                <th class="border-0 px-2 py-3">Scheme</th>
                                <th class="border-0 px-2 py-3">Type</th>
                                <th class="border-0 px-2 py-3">Status</th>
                                <th class="border-0 px-2 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($domains as $domain)
                                <tr style="border-bottom: 1px solid var(--glass-border);">
                                    <td class="px-2 py-3 fw-bold text-primary">
                                        {{ $domain->host }}
                                    </td>
                                    <td class="px-2 py-3 text-muted small">
                                        {{ $domain->scheme }}
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
                                        @if($domain->type == 0)
                                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="modal" data-bs-target="#editDomainModal{{ $domain->id }}">Edit</button>
                                            <button class="btn btn-sm btn-light border text-danger" data-bs-toggle="modal" data-bs-target="#deleteDomainModal{{ $domain->id }}">Delete</button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Edit Domain Modal -->
                                <div class="modal fade" id="editDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content glass-card border-0">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold">Edit Domain Settings</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('domains.update', $domain->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label text-secondary small fw-medium">Host</label>
                                                        <input type="text" class="form-control" value="{{ $domain->host }}" disabled>
                                                        <div class="form-text">Host cannot be changed once created.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label text-secondary small fw-medium">Custom Index URL (Optional)</label>
                                                        <input type="url" class="form-control" name="custom_index_url" value="{{ $domain->custom_index_url }}" placeholder="https://yourwebsite.com">
                                                        <div class="form-text">Redirect root visits ({{ $domain->host }}) to this URL.</div>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="form-label text-secondary small fw-medium">Custom 404 URL (Optional)</label>
                                                        <input type="url" class="form-control" name="custom_not_found_url" value="{{ $domain->custom_not_found_url }}" placeholder="https://yourwebsite.com/404">
                                                        <div class="form-text">Redirect not found links to this URL.</div>
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
                                                <p class="text-secondary small mb-4">Are you sure you want to delete {{ $domain->host }}? This action cannot be undone.</p>
                                                <form action="{{ route('domains.destroy', $domain->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-light w-100 mb-2" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger w-100 fw-semibold">Yes, Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Create Domain Modal -->
<div class="modal fade" id="createDomainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Connect Custom Domain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('domains.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Host (e.g. link.yourdomain.com)</label>
                        <input type="text" class="form-control" name="host" placeholder="link.yourdomain.com" required>
                        <div class="form-text">Make sure you have pointed an A record to our server IP.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-medium">Custom Index URL (Optional)</label>
                        <input type="url" class="form-control" name="custom_index_url" placeholder="https://yourwebsite.com">
                        <div class="form-text">Redirect root visits to this URL.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-medium">Custom 404 URL (Optional)</label>
                        <input type="url" class="form-control" name="custom_not_found_url" placeholder="https://yourwebsite.com/404">
                        <div class="form-text">Redirect not found links to this URL.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold">Connect Domain</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
