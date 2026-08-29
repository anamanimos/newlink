@extends('layouts.app')

@section('title', 'Manage Domains')

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Domains</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Platform & Custom Domains</span>
    </div>
    <button class="btn btn-sm btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createDomainModal">
        <i class="ki-outline ki-plus fs-2"></i> Add System Domain
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3">
        <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
        <div class="d-flex flex-column">
            <span class="fs-7 text-gray-800">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3">
        <i class="ki-outline ki-cross-circle fs-2hx text-danger me-4"></i>
        <div class="d-flex flex-column">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li class="fs-7 text-gray-800">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="card card-flush shadow-sm border-0 mb-6">
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-200px">Host Domain</th>
                        <th class="min-w-150px">User</th>
                        <th class="min-w-100px">Type</th>
                        <th class="min-w-100px">Status</th>
                        <th class="text-end min-w-120px pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($domains as $domain)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px symbol-circle me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-outline ki-geolocation fs-2 text-primary"></i>
                                        </span>
                                    </div>
                                    <span class="fw-bold text-gray-800 fs-6">{{ $domain->host }}</span>
                                </div>
                            </td>
                            <td>
                                @if($domain->user)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-gray-800 fs-7">{{ $domain->user->name }}</span>
                                        <span class="text-muted fs-8">{{ $domain->user->email }}</span>
                                    </div>
                                @else
                                    <span class="text-muted fs-7">System Domain</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $domain->type == 1 ? 'badge-light-secondary' : 'badge-light-primary' }} fw-semibold fs-8">
                                    {{ $domain->type == 1 ? 'System' : 'Custom' }}
                                </span>
                            </td>
                            <td>
                                @if($domain->is_enabled)
                                    <span class="badge badge-light-success fw-bold fs-8">Active</span>
                                @else
                                    <span class="badge badge-light-warning fw-bold fs-8">Pending</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <button class="btn btn-sm btn-icon btn-light-primary me-2" data-bs-toggle="modal" data-bs-target="#editDomainModal{{ $domain->id }}" title="Edit">
                                    <i class="ki-outline ki-pencil fs-5"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light-danger" data-bs-toggle="modal" data-bs-target="#deleteDomainModal{{ $domain->id }}" title="Delete">
                                    <i class="ki-outline ki-trash fs-5"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Domain Modal -->
                        <div class="modal fade" id="editDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-3 border-0 shadow-lg">
                                    <div class="modal-header pb-0 border-0 justify-content-between">
                                        <h3 class="modal-title fw-bold text-gray-900">Manage Domain: {{ $domain->host }}</h3>
                                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.domains.update', $domain->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body py-6 px-lg-8">
                                            <div class="fv-row mb-5">
                                                <label class="form-label fs-6 fw-semibold text-gray-900">Status</label>
                                                <select class="form-select form-select-solid" name="is_enabled">
                                                    <option value="1" {{ $domain->is_enabled ? 'selected' : '' }}>Active / Approved</option>
                                                    <option value="0" {{ !$domain->is_enabled ? 'selected' : '' }}>Pending / Disabled</option>
                                                </select>
                                            </div>

                                            <div class="fv-row mb-5">
                                                <label class="form-label fs-6 fw-semibold text-gray-900">Scheme</label>
                                                <select class="form-select form-select-solid" name="scheme">
                                                    <option value="https://" {{ $domain->scheme == 'https://' ? 'selected' : '' }}>https://</option>
                                                    <option value="http://" {{ $domain->scheme == 'http://' ? 'selected' : '' }}>http://</option>
                                                </select>
                                            </div>

                                            <div class="fv-row mb-5">
                                                <label class="form-label fs-6 fw-semibold text-gray-900">Custom Index URL</label>
                                                <input type="url" class="form-control form-control-solid" name="custom_index_url" value="{{ $domain->custom_index_url }}" placeholder="https://yourwebsite.com" />
                                            </div>

                                            <div class="fv-row mb-2">
                                                <label class="form-label fs-6 fw-semibold text-gray-900">Custom 404 URL</label>
                                                <input type="url" class="form-control form-control-solid" name="custom_not_found_url" value="{{ $domain->custom_not_found_url }}" placeholder="https://yourwebsite.com/404" />
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary fw-bold">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Domain Modal -->
                        <div class="modal fade" id="deleteDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content rounded-3 border-0 shadow-lg">
                                    <div class="modal-body text-center p-6">
                                        <i class="ki-outline ki-information-5 fs-4x text-danger mb-3"></i>
                                        <h4 class="fw-bold text-gray-900 mb-2">Delete Domain?</h4>
                                        <p class="text-gray-600 fs-7 mb-5">Are you sure you want to delete <strong>{{ $domain->host }}</strong>?</p>
                                        <form action="{{ route('admin.domains.destroy', $domain->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-light flex-grow-1 fw-bold" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-danger flex-grow-1 fw-bold">Yes, Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-muted">
                                <i class="ki-outline ki-geolocation fs-4x text-muted mb-3"></i>
                                <p class="fs-6 fw-semibold mb-0">No domains registered yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Domain Modal -->
<div class="modal fade" id="createDomainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">Add System Domain</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form action="{{ route('admin.domains.store') }}" method="POST">
                @csrf
                <div class="modal-body py-6 px-lg-8">
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900 required">Host</label>
                        <input type="text" class="form-control form-control-solid" name="host" placeholder="domain.com" required />
                    </div>

                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Custom Index URL (Optional)</label>
                        <input type="url" class="form-control form-control-solid" name="custom_index_url" placeholder="https://yourwebsite.com" />
                    </div>

                    <div class="fv-row mb-2">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Custom 404 URL (Optional)</label>
                        <input type="url" class="form-control form-control-solid" name="custom_not_found_url" placeholder="https://yourwebsite.com/404" />
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Add Domain</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
