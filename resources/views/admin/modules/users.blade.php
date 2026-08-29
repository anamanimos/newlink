@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
@php
    $activeFilters = [];
    if (request('search')) {
        $activeFilters[] = [
            'type' => 'search',
            'label' => 'Search: "' . request('search') . '"',
            'param' => 'search'
        ];
    }
    if (request('status') !== null && request('status') !== '') {
        $activeFilters[] = [
            'type' => 'status',
            'label' => 'Status: ' . (request('status') == '1' ? 'Active' : 'Inactive'),
            'param' => 'status'
        ];
    }
    if (request('type') !== null && request('type') !== '') {
        $activeFilters[] = [
            'type' => 'type',
            'label' => 'Role: ' . (request('type') == '1' ? 'Admin' : 'Regular User'),
            'param' => 'type'
        ];
    }
    if (request('plan_id')) {
        $activeFilters[] = [
            'type' => 'plan_id',
            'label' => 'Plan: ' . ucfirst(request('plan_id')),
            'param' => 'plan_id'
        ];
    }
    if (request('order_by') && request('order_by') !== 'created_at') {
        $orderNames = ['id' => 'ID', 'name' => 'Name', 'email' => 'Email', 'last_activity' => 'Last Activity'];
        $activeFilters[] = [
            'type' => 'order_by',
            'label' => 'Sort: ' . ($orderNames[request('order_by')] ?? request('order_by')) . ' (' . (request('order_type') == 'asc' ? 'Asc' : 'Desc') . ')',
            'param' => 'order_by'
        ];
    }
    if (request('results_per_page') && request('results_per_page') != 25) {
        $activeFilters[] = [
            'type' => 'results_per_page',
            'label' => request('results_per_page') . ' / page',
            'param' => 'results_per_page'
        ];
    }
@endphp

<!-- Page Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Users</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Registered Accounts</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-primary d-flex align-items-center gap-2 fw-bold" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="ki-outline ki-plus fs-2"></i> Create user
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

@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3 shadow-sm">
        <i class="ki-outline ki-information fs-2hx text-danger me-4"></i>
        <div class="d-flex flex-column">
            <span class="fs-7 text-gray-900 fw-semibold">{{ session('error') }}</span>
        </div>
    </div>
@endif

<!-- Users Table Card -->
<div class="card card-flush shadow-sm border-0 mb-6">
    
    <!-- Card Header: Search Toolbar -->
    <div class="card-header pt-6 pb-2 gap-2 gap-md-5">
        <div class="card-title">
            <form method="GET" action="{{ route('admin.users') }}" class="d-flex align-items-center position-relative my-1">
                @if(request('status') !== null) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                @if(request('plan_id')) <input type="hidden" name="plan_id" value="{{ request('plan_id') }}"> @endif
                @if(request('type') !== null) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                @if(request('order_by')) <input type="hidden" name="order_by" value="{{ request('order_by') }}"> @endif
                @if(request('order_type')) <input type="hidden" name="order_type" value="{{ request('order_type') }}"> @endif
                @if(request('results_per_page')) <input type="hidden" name="results_per_page" value="{{ request('results_per_page') }}"> @endif
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                <input type="text" name="search" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Search user or email..." value="{{ request('search') }}" />
                @if(request('search'))
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="btn btn-sm btn-icon btn-light ms-2" title="Reset Search">
                        <i class="ki-outline ki-cross fs-4"></i>
                    </a>
                @endif
            </form>
        </div>
        <div class="card-toolbar gap-2">
            <button type="button" class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#usersFilterModal">
                <i class="ki-outline ki-filter fs-3"></i> Filters
                @if(!empty($activeFilters))
                    <span class="badge badge-primary fs-9 ms-1 px-1.5 py-0.5">{{ count($activeFilters) }}</span>
                @endif
            </button>
        </div>
    </div>

    <!-- Active Filters Row -->
    @if(!empty($activeFilters))
        <div class="d-flex flex-wrap align-items-center gap-2 px-6 pt-2 pb-3 border-bottom border-light">
            <span class="text-gray-600 fs-8 fw-bold text-uppercase">Active Filters:</span>
            @foreach($activeFilters as $filter)
                @php
                    $removeUrl = request()->fullUrlWithQuery([$filter['param'] => null]);
                    if($filter['param'] === 'order_by') {
                        $removeUrl = request()->fullUrlWithQuery(['order_by' => null, 'order_type' => null]);
                    }
                @endphp
                <span class="badge badge-light-primary d-inline-flex align-items-center gap-2 py-1.5 px-3 fs-8 fw-semibold">
                    {{ $filter['label'] }}
                    <a href="{{ $removeUrl }}" class="btn-close p-0 m-0 bg-none border-0 text-muted d-inline-flex align-items-center" aria-label="Clear filter" style="font-size: 0.6rem; width: 0.6rem; height: 0.6rem; line-height: 1;">
                        <i class="ki-outline ki-cross fs-8"></i>
                    </a>
                </span>
            @endforeach
            
            <a href="{{ route('admin.users') }}" class="btn btn-link text-danger text-decoration-none p-0 fs-8 fw-bold ms-2">
                Clear All
            </a>
        </div>
    @endif

    <!-- Card Body: Table -->
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-220px">User</th>
                        <th class="min-w-100px text-center">Status</th>
                        <th class="min-w-160px">Plan</th>
                        <th class="min-w-220px">Details</th>
                        <th class="text-end min-w-80px pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($users as $user)
                        <tr>
                            <!-- User Column -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-45px symbol-circle me-3 flex-shrink-0">
                                        <span class="symbol-label bg-light-primary text-primary fw-bold fs-5">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column min-w-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" class="text-gray-900 fw-bold text-hover-primary fs-6 text-truncate mb-0">
                                                {{ $user->name }}
                                            </a>
                                            @if($user->type == 1)
                                                <span class="badge badge-light-danger fw-bold fs-8 py-0.5 px-1.5">Admin</span>
                                            @endif
                                        </div>
                                        <span class="text-muted fs-8 text-truncate">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Status Column -->
                            <td class="text-center">
                                @if($user->status == 1)
                                    <span class="badge badge-light-success fw-bold fs-8 px-3 py-1.5">
                                        <i class="ki-outline ki-check fs-8 text-success me-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-light-danger fw-bold fs-8 px-3 py-1.5">
                                        <i class="ki-outline ki-cross fs-8 text-danger me-1"></i> Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Plan Column -->
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="mb-1">
                                        @php
                                            $planBadge = 'badge-light-primary';
                                            if(strtolower($user->plan_id) === 'free') $planBadge = 'badge-light-secondary';
                                            elseif(strtolower($user->plan_id) === 'custom') $planBadge = 'badge-light-warning';
                                            elseif(strtolower($user->plan_id) === 'pro') $planBadge = 'badge-light-success';
                                        @endphp
                                        <span class="badge {{ $planBadge }} fw-bold fs-8 text-uppercase px-2.5 py-1">
                                            {{ $user->plan_id ?: 'Free' }}
                                        </span>
                                    </div>
                                    <span class="text-muted fs-8 font-monospace">
                                        @if($user->plan_expiration_date)
                                            {{ date('Y-m-d H:i:s', strtotime($user->plan_expiration_date)) }}
                                        @else
                                            <span class="text-muted">Lifetime</span>
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Details Column (AltumCode Quick Tooltip Icons) -->
                            <td>
                                <div class="d-flex align-items-center flex-wrap gap-1">
                                    <!-- Registration Date Tooltip -->
                                    <span class="btn btn-icon btn-sm btn-light h-30px w-30px" 
                                          data-bs-toggle="tooltip" 
                                          data-bs-html="true" 
                                          title="Registration date<br><b>{{ $user->created_at ? $user->created_at->format('d M, Y H:i:s') : '-' }}</b><br>({{ $user->created_at ? $user->created_at->diffForHumans() : '-' }})">
                                        <i class="ki-outline ki-calendar fs-5 text-gray-700"></i>
                                    </span>

                                    <!-- Last Activity Tooltip -->
                                    <span class="btn btn-icon btn-sm btn-light h-30px w-30px" 
                                          data-bs-toggle="tooltip" 
                                          data-bs-html="true" 
                                          title="Last Activity<br><b>{{ $user->last_activity ? \Carbon\Carbon::parse($user->last_activity)->diffForHumans() : ($user->updated_at ? $user->updated_at->diffForHumans() : 'Never') }}</b>">
                                        <i class="ki-outline ki-exit-right-corner fs-5 text-gray-700"></i>
                                    </span>

                                    <!-- Total Biolinks Tooltip -->
                                    <span class="btn btn-icon btn-sm btn-light h-30px w-30px" 
                                          data-bs-toggle="tooltip" 
                                          data-bs-html="true" 
                                          title="Biolink Pages<br><b>{{ $user->biolinks_count ?? 0 }} biolinks</b>">
                                        <i class="ki-outline ki-abstract-26 fs-5 text-primary"></i>
                                    </span>

                                    <!-- Total Shortlinks Tooltip -->
                                    <span class="btn btn-icon btn-sm btn-light h-30px w-30px" 
                                          data-bs-toggle="tooltip" 
                                          data-bs-html="true" 
                                          title="Shortened Links<br><b>{{ $user->shortlinks_count ?? 0 }} links</b>">
                                        <i class="ki-outline ki-disconnect fs-5 text-success"></i>
                                    </span>

                                    <!-- Custom Domains Tooltip -->
                                    <span class="btn btn-icon btn-sm btn-light h-30px w-30px" 
                                          data-bs-toggle="tooltip" 
                                          data-bs-html="true" 
                                          title="Custom Domains<br><b>{{ $user->domains_count ?? 0 }} domains</b>">
                                        <i class="ki-outline ki-globe fs-5 text-info"></i>
                                    </span>

                                    <!-- Timezone / Geolocation Tooltip -->
                                    <span class="btn btn-icon btn-sm btn-light h-30px w-30px" 
                                          data-bs-toggle="tooltip" 
                                          data-bs-html="true" 
                                          title="Timezone<br><b>{{ $user->timezone ?? 'Asia/Jakarta' }}</b>">
                                        <i class="ki-outline ki-geolocation fs-5 text-danger"></i>
                                    </span>
                                </div>
                            </td>

                            <!-- Actions Column -->
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-light btn-active-light-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ki-outline ki-dots-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end menu menu-sub menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-3 shadow-lg border-0">
                                        <li class="menu-item px-3">
                                            <a href="javascript:void(0)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                <i class="ki-outline ki-pencil fs-5 me-2 text-primary"></i> Edit
                                            </a>
                                        </li>
                                        <li class="menu-item px-3">
                                            <form action="{{ route('admin.users.login-as', $user->id) }}" method="POST" class="w-100">
                                                @csrf
                                                <button type="submit" class="dropdown-item menu-link px-3 bg-transparent border-0 text-start w-100">
                                                    <i class="ki-outline ki-switch fs-5 me-2 text-success"></i> Login as user
                                                </button>
                                            </form>
                                        </li>
                                        @if(auth()->id() !== $user->id)
                                            <li class="menu-item px-3">
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="w-100" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini beserta seluruh datanya?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item menu-link px-3 bg-transparent border-0 text-start w-100 text-danger">
                                                        <i class="ki-outline ki-trash fs-5 me-2 text-danger"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content rounded-3 border-0 shadow-lg">
                                    <div class="modal-header pb-0 border-0 justify-content-between">
                                        <h3 class="modal-title fw-bold text-gray-900">
                                            <i class="ki-outline ki-pencil fs-2 text-primary me-2"></i> Edit User: {{ $user->name }}
                                        </h3>
                                        <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                                            <i class="ki-outline ki-cross fs-2"></i>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body py-6 px-lg-8">
                                            <div class="row g-5">
                                                <div class="col-md-6 fv-row">
                                                    <label class="form-label fs-7 fw-semibold text-gray-900 required">Full Name</label>
                                                    <input type="text" name="name" class="form-control form-control-solid form-control-sm" value="{{ $user->name }}" required />
                                                </div>
                                                <div class="col-md-6 fv-row">
                                                    <label class="form-label fs-7 fw-semibold text-gray-900 required">Email Address</label>
                                                    <input type="email" name="email" class="form-control form-control-solid form-control-sm" value="{{ $user->email }}" required />
                                                </div>
                                                <div class="col-md-6 fv-row">
                                                    <label class="form-label fs-7 fw-semibold text-gray-900">Password (Leave blank to keep current)</label>
                                                    <input type="password" name="password" class="form-control form-control-solid form-control-sm" placeholder="••••••••" minlength="6" />
                                                </div>
                                                <div class="col-md-6 fv-row">
                                                    <label class="form-label fs-7 fw-semibold text-gray-900 required">Status</label>
                                                    <select name="status" class="form-select form-select-solid form-select-sm" required>
                                                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive / Disabled</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 fv-row">
                                                    <label class="form-label fs-7 fw-semibold text-gray-900 required">Account Role</label>
                                                    <select name="type" class="form-select form-select-solid form-select-sm" required>
                                                        <option value="0" {{ $user->type == 0 ? 'selected' : '' }}>Regular User</option>
                                                        <option value="1" {{ $user->type == 1 ? 'selected' : '' }}>Administrator</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 fv-row">
                                                    <label class="form-label fs-7 fw-semibold text-gray-900 required">Subscription Plan</label>
                                                    <select name="plan_id" class="form-select form-select-solid form-select-sm" required>
                                                        <option value="free" {{ strtolower($user->plan_id) == 'free' ? 'selected' : '' }}>Free</option>
                                                        <option value="custom" {{ strtolower($user->plan_id) == 'custom' ? 'selected' : '' }}>Custom</option>
                                                        <option value="pro" {{ strtolower($user->plan_id) == 'pro' ? 'selected' : '' }}>Pro</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 fv-row">
                                                    <label class="form-label fs-7 fw-semibold text-gray-900">Plan Expiration Date (Leave empty for Lifetime)</label>
                                                    <input type="datetime-local" name="plan_expiration_date" class="form-control form-control-solid form-control-sm" value="{{ $user->plan_expiration_date ? date('Y-m-d\TH:i', strtotime($user->plan_expiration_date)) : '' }}" />
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
                            <td colspan="5" class="text-center py-12 text-muted">
                                <div class="symbol symbol-65px symbol-circle bg-light-primary mb-4 d-inline-flex align-items-center justify-content-center">
                                    <i class="ki-outline ki-profile-user fs-2x text-primary"></i>
                                </div>
                                <h5 class="fs-6 fw-bold text-gray-800 mb-1">No users found</h5>
                                <p class="fs-7 text-muted mb-5">No user accounts match your search or filter criteria.</p>
                                <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                    <i class="ki-outline ki-plus fs-4 me-1"></i> Create User
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center flex-wrap mt-6 pt-4 border-top">
                <span class="text-muted fs-7">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users</span>
                <div>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">
                    <i class="ki-outline ki-user-plus fs-2 text-primary me-2"></i> Create New User
                </h3>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body py-6 px-lg-8">
                    <div class="row g-5">
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900 required">Full Name</label>
                            <input type="text" name="name" class="form-control form-control-solid form-control-sm" placeholder="e.g. John Doe" required />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900 required">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-solid form-control-sm" placeholder="john@example.com" required />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900 required">Password</label>
                            <input type="password" name="password" class="form-control form-control-solid form-control-sm" placeholder="••••••••" minlength="6" required />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900 required">Status</label>
                            <select name="status" class="form-select form-select-solid form-select-sm" required>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive / Disabled</option>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900 required">Account Role</label>
                            <select name="type" class="form-select form-select-solid form-select-sm" required>
                                <option value="0" selected>Regular User</option>
                                <option value="1">Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900 required">Subscription Plan</label>
                            <select name="plan_id" class="form-select form-select-solid form-select-sm" required>
                                <option value="free">Free</option>
                                <option value="custom" selected>Custom</option>
                                <option value="pro">Pro</option>
                            </select>
                        </div>
                        <div class="col-md-12 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Plan Expiration Date (Leave empty for Lifetime)</label>
                            <input type="datetime-local" name="plan_expiration_date" class="form-control form-control-solid form-control-sm" />
                            <div class="form-text fs-8 text-muted mt-1">If left empty, the user will have an unlimited/lifetime subscription on the selected plan.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                    <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filter Users Modal -->
<div class="modal fade" id="usersFilterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">
                    <i class="ki-outline ki-filter fs-2 text-primary me-2"></i> Filter & Sort Users
                </h3>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.users') }}">
                <div class="modal-body py-6 px-lg-8">
                    <div class="row g-5">
                        <!-- Search Keyword -->
                        <div class="col-12 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Search Keyword</label>
                            <div class="position-relative">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                <input type="text" name="search" class="form-control form-control-solid form-control-sm ps-11" placeholder="Search by name or email..." value="{{ request('search') }}" />
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Account Status</label>
                            <select name="status" class="form-select form-select-solid form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive / Disabled</option>
                            </select>
                        </div>

                        <!-- Plan Filter -->
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Subscription Plan</label>
                            <select name="plan_id" class="form-select form-select-solid form-select-sm">
                                <option value="">All Plans</option>
                                <option value="free" {{ request('plan_id') == 'free' ? 'selected' : '' }}>Free</option>
                                <option value="custom" {{ request('plan_id') == 'custom' ? 'selected' : '' }}>Custom</option>
                                <option value="pro" {{ request('plan_id') == 'pro' ? 'selected' : '' }}>Pro</option>
                            </select>
                        </div>

                        <!-- Role Filter -->
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Account Role</label>
                            <select name="type" class="form-select form-select-solid form-select-sm">
                                <option value="">All Roles</option>
                                <option value="0" {{ request('type') === '0' ? 'selected' : '' }}>Regular User</option>
                                <option value="1" {{ request('type') === '1' ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </div>

                        <!-- Results Per Page -->
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Results Per Page</label>
                            <select name="results_per_page" class="form-select form-select-solid form-select-sm">
                                <option value="10" {{ request('results_per_page') == 10 ? 'selected' : '' }}>10 results</option>
                                <option value="25" {{ !request('results_per_page') || request('results_per_page') == 25 ? 'selected' : '' }}>25 results</option>
                                <option value="50" {{ request('results_per_page') == 50 ? 'selected' : '' }}>50 results</option>
                                <option value="100" {{ request('results_per_page') == 100 ? 'selected' : '' }}>100 results</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Sort By</label>
                            <select name="order_by" class="form-select form-select-solid form-select-sm">
                                <option value="created_at" {{ !request('order_by') || request('order_by') == 'created_at' ? 'selected' : '' }}>Registration Date</option>
                                <option value="name" {{ request('order_by') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="email" {{ request('order_by') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="last_activity" {{ request('order_by') == 'last_activity' ? 'selected' : '' }}>Last Activity</option>
                                <option value="id" {{ request('order_by') == 'id' ? 'selected' : '' }}>User ID</option>
                            </select>
                        </div>

                        <!-- Sort Direction -->
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Sort Direction</label>
                            <select name="order_type" class="form-select form-select-solid form-select-sm">
                                <option value="desc" {{ !request('order_type') || request('order_type') == 'desc' ? 'selected' : '' }}>Descending (Newest / Z-A)</option>
                                <option value="asc" {{ request('order_type') == 'asc' ? 'selected' : '' }}>Ascending (Oldest / A-Z)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6 justify-content-between">
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-light fw-bold">Reset Filters</a>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection

