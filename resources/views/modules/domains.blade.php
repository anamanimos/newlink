@extends('layouts.app')

@section('title', 'Custom Domains')

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Custom Domains</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Branding</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        @if($domainLimit !== -1)
            <span class="badge badge-light fw-bold fs-7">
                {{ $domains->count() }} / {{ $domainLimit }} Used
            </span>
        @endif
        <button class="btn btn-sm btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createDomainModal" {{ ($domainLimit !== -1 && $domains->count() >= $domainLimit) ? 'disabled' : '' }}>
            <i class="ki-outline ki-plus fs-2"></i> Connect Domain
        </button>
    </div>
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

<div class="row g-6 g-xl-9">
    @if($domains->isEmpty())
        <div class="col-12">
            <div class="card card-flush shadow-sm border-0 p-10 text-center">
                <i class="ki-outline ki-geolocation fs-4x text-muted mb-3"></i>
                <p class="text-gray-600 fw-semibold fs-6 mb-0">No domains connected yet.</p>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Host Domain</th>
                                    <th class="min-w-100px">Scheme</th>
                                    <th class="min-w-100px">Type</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="text-end min-w-120px pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach($domains as $domain)
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
                                            <span class="badge badge-light fs-7">{{ $domain->scheme }}</span>
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
                                            @if($domain->type == 0)
                                                <button class="btn btn-sm btn-icon btn-light-primary me-2" data-bs-toggle="modal" data-bs-target="#editDomainModal{{ $domain->id }}" title="Edit">
                                                    <i class="ki-outline ki-pencil fs-5"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-light-danger" data-bs-toggle="modal" data-bs-target="#deleteDomainModal{{ $domain->id }}" title="Delete">
                                                    <i class="ki-outline ki-trash fs-5"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Domain Modal -->
                                    <div class="modal fade" id="editDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-3 border-0 shadow-lg">
                                                <div class="modal-header pb-0 border-0 justify-content-between">
                                                    <h3 class="modal-title fw-bold text-gray-900">Edit Domain Settings</h3>
                                                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                        <i class="ki-outline ki-cross fs-1"></i>
                                                    </div>
                                                </div>
                                                <form action="{{ route('domains.update', $domain->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body py-6 px-lg-8">
                                                        <div class="fv-row mb-5">
                                                            <label class="form-label fs-6 fw-semibold text-gray-900">Host</label>
                                                            <input type="text" class="form-control form-control-solid" value="{{ $domain->host }}" disabled />
                                                            <div class="form-text text-muted fs-8">Host cannot be changed once created.</div>
                                                        </div>

                                                        <div class="fv-row mb-5">
                                                            <label class="form-label fs-6 fw-semibold text-gray-900">Custom Index URL (Optional)</label>
                                                            <input type="url" class="form-control form-control-solid" name="custom_index_url" value="{{ $domain->custom_index_url }}" placeholder="https://yourwebsite.com" />
                                                            <div class="form-text text-muted fs-8">Redirect root visits ({{ $domain->host }}) to this URL.</div>
                                                        </div>

                                                        <div class="fv-row mb-2">
                                                            <label class="form-label fs-6 fw-semibold text-gray-900">Custom 404 URL (Optional)</label>
                                                            <input type="url" class="form-control form-control-solid" name="custom_not_found_url" value="{{ $domain->custom_not_found_url }}" placeholder="https://yourwebsite.com/404" />
                                                            <div class="form-text text-muted fs-8">Redirect not found links to this URL.</div>
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
                                                    <p class="text-gray-600 fs-7 mb-5">Are you sure you want to delete <strong>{{ $domain->host }}</strong>? This action cannot be undone.</p>
                                                    <form action="{{ route('domains.destroy', $domain->id) }}" method="POST">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Create Domain Modal -->
<div class="modal fade" id="createDomainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">Connect Custom Domain</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form action="{{ route('domains.store') }}" method="POST">
                @csrf
                <div class="modal-body py-6 px-lg-8">
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900 required">Host</label>
                        <input type="text" class="form-control form-control-solid" name="host" placeholder="link.yourdomain.com" required />
                        <div class="form-text text-muted fs-8">Make sure you have pointed an A record to our server IP.</div>
                    </div>

                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Custom Index URL (Optional)</label>
                        <input type="url" class="form-control form-control-solid" name="custom_index_url" placeholder="https://yourwebsite.com" />
                        <div class="form-text text-muted fs-8">Redirect root visits to this URL.</div>
                    </div>

                    <div class="fv-row mb-2">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Custom 404 URL (Optional)</label>
                        <input type="url" class="form-control form-control-solid" name="custom_not_found_url" placeholder="https://yourwebsite.com/404" />
                        <div class="form-text text-muted fs-8">Redirect not found links to this URL.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Connect Domain</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
