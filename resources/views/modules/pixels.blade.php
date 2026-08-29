@extends('layouts.app')

@section('title', 'Tracking Pixels')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Tracking Pixels</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Retargeting & Ad Analytics</span>
    </div>
    <div>
        <button class="btn btn-sm btn-primary d-flex align-items-center gap-2 fw-bold" data-bs-toggle="modal" data-bs-target="#createPixelModal">
            <i class="ki-outline ki-plus fs-2"></i> Add Pixel
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
    <!-- Left Column: 3 Columns Statistics & Guide -->
    <div class="col-12 col-lg-4 col-xl-3">
        <div class="d-flex flex-column gap-6">
            
            <!-- Stat 1: Total Pixels -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px symbol-circle bg-light-primary me-4 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-radar fs-2x text-primary"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ number_format($totalPixels) }}</span>
                            <span class="text-gray-600 fw-semibold fs-7 mt-1">Active Pixels</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-4"></div>
                    <div class="d-flex align-items-center justify-content-between text-muted fs-8">
                        <span>Retargeting</span>
                        <span class="badge badge-light-success fw-bold">Ready</span>
                    </div>
                </div>
            </div>

            <!-- Stat 2: Supported Providers -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px symbol-circle bg-light-info me-3 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-code fs-3 text-info"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-900">Supported Platforms</span>
                            <span class="text-muted fs-8">Connect across networks</span>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-1.5 pt-1">
                        <span class="badge badge-light-primary fs-8 py-1 px-2">Meta (FB)</span>
                        <span class="badge badge-light-warning fs-8 py-1 px-2">Google GA4</span>
                        <span class="badge badge-light-info fs-8 py-1 px-2">GTM</span>
                        <span class="badge badge-light-dark fs-8 py-1 px-2">TikTok</span>
                        <span class="badge badge-light-primary fs-8 py-1 px-2">Twitter / X</span>
                        <span class="badge badge-light-danger fs-8 py-1 px-2">Pinterest</span>
                        <span class="badge badge-light-info fs-8 py-1 px-2">LinkedIn</span>
                    </div>
                </div>
            </div>

            <!-- Action Card: Guide -->
            <div class="card card-flush shadow-sm border-0 bg-light-primary">
                <div class="card-body p-6 text-center">
                    <div class="symbol symbol-50px symbol-circle bg-white shadow-xs mb-3 d-inline-flex align-items-center justify-content-center">
                        <i class="ki-outline ki-chart-line-up fs-2 text-primary"></i>
                    </div>
                    <h5 class="fs-6 fw-bold text-gray-900 mb-1">Boost Ad Conversions</h5>
                    <p class="text-muted fs-8 mb-4">Attach pixels to your short links and biolink pages to build custom audiences for remarketing.</p>
                    <button class="btn btn-sm btn-primary w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#createPixelModal">
                        + Add New Pixel
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Right Column: 9 Columns Table -->
    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-flush shadow-sm border-0">
            
            <!-- Card Header: Search & Toolbar -->
            <div class="card-header pt-6 pb-2 gap-2 gap-md-5">
                <div class="card-title">
                    <form method="GET" action="{{ route('pixels.index') }}" class="d-flex align-items-center position-relative my-1">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                        <input type="text" name="search" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Search pixel or ID..." value="{{ request('search') }}" />
                        @if(request('search') || request('type'))
                            <a href="{{ route('pixels.index') }}" class="btn btn-sm btn-icon btn-light ms-2" title="Reset Search">
                                <i class="ki-outline ki-cross fs-4"></i>
                            </a>
                        @endif
                    </form>
                </div>
                <div class="card-toolbar gap-2">
                    <button class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createPixelModal">
                        <i class="ki-outline ki-plus fs-3"></i> Add Pixel
                    </button>
                </div>
            </div>

            <!-- Card Body: Table -->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-200px">Pixel Name</th>
                                <th class="text-center min-w-140px">Platform Type</th>
                                <th class="min-w-160px">Pixel ID / Tag</th>
                                <th class="text-end min-w-100px pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($pixels as $pixel)
                                @php
                                    $platform = $supportedPlatforms[$pixel->type] ?? [
                                        'name' => ucfirst($pixel->type),
                                        'icon' => 'ki-code',
                                        'color' => '#3b82f6'
                                    ];
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px symbol-circle me-3 flex-shrink-0">
                                                <span class="symbol-label" style="background-color: {{ $platform['color'] }}18; border: 1.5px solid {{ $platform['color'] }}35;">
                                                    <i class="ki-outline {{ $platform['icon'] }} fs-2" style="color: {{ $platform['color'] }};"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column min-w-0">
                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editPixelModal{{ $pixel->id }}" class="text-gray-900 fw-bold text-hover-primary fs-6 text-truncate mb-0">
                                                    {{ $pixel->name }}
                                                </a>
                                                <span class="text-muted fs-8">Added {{ $pixel->created_at ? $pixel->created_at->diffForHumans() : '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-primary fw-bold fs-8 px-3 py-1.5">
                                            {{ $platform['name'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded bg-light border font-monospace fs-7 text-gray-800">
                                            <span>{{ $pixel->pixel }}</span>
                                            <button type="button" class="btn btn-icon btn-sm btn-light h-20px w-20px copy-btn" data-clipboard-text="{{ $pixel->pixel }}" title="Copy ID">
                                                <i class="ki-outline ki-copy fs-7 text-gray-600"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-icon btn-light-primary me-1" data-bs-toggle="modal" data-bs-target="#editPixelModal{{ $pixel->id }}" title="Edit Pixel">
                                            <i class="ki-outline ki-pencil fs-5"></i>
                                        </button>
                                        <form action="{{ route('pixels.destroy', $pixel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tracking pixel ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Delete Pixel">
                                                <i class="ki-outline ki-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editPixelModal{{ $pixel->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-3 border-0 shadow-lg">
                                            <div class="modal-header pb-0 border-0 justify-content-between">
                                                <h3 class="modal-title fw-bold text-gray-900">
                                                    <i class="ki-outline ki-pencil fs-3 text-primary me-2"></i> Edit Tracking Pixel
                                                </h3>
                                                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                                                    <i class="ki-outline ki-cross fs-2"></i>
                                                </div>
                                            </div>
                                            <form action="{{ route('pixels.update', $pixel->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body py-6 px-lg-8">
                                                    <div class="fv-row mb-5">
                                                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Platform Provider</label>
                                                        <select name="type" class="form-select form-select-solid form-select-sm" required>
                                                            @foreach($supportedPlatforms as $pKey => $pData)
                                                                <option value="{{ $pKey }}" {{ $pixel->type == $pKey ? 'selected' : '' }}>
                                                                    {{ $pData['name'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="fv-row mb-5">
                                                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Pixel Name</label>
                                                        <input type="text" name="name" class="form-control form-control-solid form-control-sm" value="{{ $pixel->name }}" required placeholder="e.g. Main Facebook Pixel" />
                                                    </div>
                                                    <div class="fv-row mb-2">
                                                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Pixel / Tag ID</label>
                                                        <input type="text" name="pixel" class="form-control form-control-solid form-control-sm font-monospace" value="{{ $pixel->pixel }}" required placeholder="Enter pixel ID" />
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
                                        <div class="symbol symbol-65px symbol-circle bg-light-primary mb-4 d-inline-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-radar fs-2x text-primary"></i>
                                        </div>
                                        <h5 class="fs-6 fw-bold text-gray-800 mb-1">No tracking pixels found</h5>
                                        <p class="fs-7 text-muted mb-5">Connect Facebook, Google, or TikTok pixels to retarget visitors and track ad events.</p>
                                        <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createPixelModal">
                                            <i class="ki-outline ki-plus fs-4 me-1"></i> Add Your First Pixel
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pixels->hasPages())
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-6 pt-4 border-top">
                        <span class="text-muted fs-7">Showing {{ $pixels->firstItem() }} to {{ $pixels->lastItem() }} of {{ $pixels->total() }} pixels</span>
                        <div>
                            {{ $pixels->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createPixelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">
                    <i class="ki-outline ki-plus-circle fs-2 text-primary me-2"></i> Add Tracking Pixel
                </h3>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <form action="{{ route('pixels.store') }}" method="POST">
                @csrf
                <div class="modal-body py-6 px-lg-8">
                    <div class="fv-row mb-5">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Platform Provider</label>
                        <select name="type" class="form-select form-select-solid form-select-sm" id="createPixelTypeSelect" required>
                            @foreach($supportedPlatforms as $pKey => $pData)
                                <option value="{{ $pKey }}" data-placeholder="{{ $pData['placeholder'] }}">
                                    {{ $pData['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-5">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Pixel Name</label>
                        <input type="text" name="name" class="form-control form-control-solid form-control-sm" placeholder="e.g. My Meta Ads Pixel" required />
                    </div>
                    <div class="fv-row mb-2">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Pixel ID / Measurement ID</label>
                        <input type="text" name="pixel" id="createPixelIdInput" class="form-control form-control-solid form-control-sm font-monospace" placeholder="e.g. 123456789012345" required />
                        <div class="form-text fs-8 text-muted mt-1">Paste your pixel ID or container tag code without script tags.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                    <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Add Pixel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic placeholder on provider selection
    const typeSelect = document.getElementById('createPixelTypeSelect');
    const pixelInput = document.getElementById('createPixelIdInput');
    if (typeSelect && pixelInput) {
        typeSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const placeholder = opt.getAttribute('data-placeholder');
            if (placeholder) pixelInput.placeholder = placeholder;
        });
    }

    // Copy to clipboard
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const text = this.getAttribute('data-clipboard-text');
            if (text && navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.className = 'ki-outline ki-check fs-7 text-success';
                        setTimeout(() => {
                            icon.className = 'ki-outline ki-copy fs-7 text-gray-600';
                        }, 1500);
                    }
                });
            }
        });
    });
});
</script>
@endsection

