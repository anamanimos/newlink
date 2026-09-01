@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
    if (!function_exists('renderCardIcon')) {
        function renderCardIcon($iconName, $color = 'primary') {
            $iconClass = 'ki-element-11';
            if ($iconName == 'hash') {
                $iconClass = 'ki-abstract-26';
            } elseif ($iconName == 'app') {
                $iconClass = 'ki-profile-user';
            } elseif ($iconName == 'link') {
                $iconClass = 'ki-disconnect';
            } elseif ($iconName == 'qrcode') {
                $iconClass = 'ki-scan-barcode';
            } elseif ($iconName == 'card') {
                $iconClass = 'ki-credit-cart';
            } elseif ($iconName == 'clicks') {
                $iconClass = 'ki-chart-simple';
            } elseif ($iconName == 'calendar') {
                $iconClass = 'ki-calendar';
            } elseif ($iconName == 'chart') {
                $iconClass = 'ki-chart-pie-simple';
            }
            return '<i class="ki-outline ' . $iconClass . ' fs-2 text-' . $color . '"></i>';
        }
    }
@endphp

<!-- Page Header (Title & Actions) -->
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
            @if($type == 'link')
                Shortened Links
            @elseif($type == 'biolink')
                Biolink Pages
            @elseif($type == 'warotator')
                WhatsApp Rotator
            @elseif($type == 'qrcode')
                QR Codes
            @else
                Links & Biolinks
            @endif
        </h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">
            @if($type == 'link') Shortlinks @elseif($type == 'biolink') Biolinks @elseif($type == 'warotator') Rotator @else Overview @endif
        </span>
    </div>

    <div class="d-flex align-items-center gap-3">
        <button id="toggleStatsBtn" class="btn btn-icon btn-sm btn-light-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Toggle Statistics">
            <i class="ki-outline ki-eye fs-2"></i>
        </button>

        @if($type == 'biolink')
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createBiolinkModal">
                <i class="ki-outline ki-plus fs-2"></i> Create Biolink
            </button>
        @elseif($type == 'warotator')
            <a href="{{ route('warotators.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2 text-decoration-none">
                <i class="ki-outline ki-plus fs-2"></i> Create WA Rotator
            </a>
        @else
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createLinkModal">
                <i class="ki-outline ki-plus fs-2"></i> Create Link
            </button>
        @endif
    </div>
</div>

<!-- Stats & Chart Section Wrapper -->
<div id="dashboardStatsWrapper">
    <div class="row g-5 g-xl-8 mb-6">
        <!-- Left Column: 4 Stat Cards Stacked -->
        <div class="col-lg-4">
            <div class="row g-5 g-xl-8">
                <!-- Card 1 -->
                <div class="col-6">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-body d-flex flex-column align-items-start p-5">
                            <div class="symbol symbol-45px symbol-circle mb-3">
                                <span class="symbol-label bg-light-primary">
                                    {!! renderCardIcon($card1_icon, 'primary') !!}
                                </span>
                            </div>
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $card1_val }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">{{ $card1_lbl }}</span>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-6">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-body d-flex flex-column align-items-start p-5">
                            <div class="symbol symbol-45px symbol-circle mb-3">
                                <span class="symbol-label bg-light-success">
                                    {!! renderCardIcon($card2_icon, 'success') !!}
                                </span>
                            </div>
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $card2_val }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">{{ $card2_lbl }}</span>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-6">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-body d-flex flex-column align-items-start p-5">
                            <div class="symbol symbol-45px symbol-circle mb-3">
                                <span class="symbol-label bg-light-info">
                                    {!! renderCardIcon($card3_icon, 'info') !!}
                                </span>
                            </div>
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $card3_val }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">{{ $card3_lbl }}</span>
                        </div>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-6">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-body d-flex flex-column align-items-start p-5">
                            <div class="symbol symbol-45px symbol-circle mb-3">
                                <span class="symbol-label bg-light-warning">
                                    {!! renderCardIcon($card4_icon, 'warning') !!}
                                </span>
                            </div>
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $card4_val }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">{{ $card4_lbl }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Chart Area -->
        <div class="col-lg-8">
            <div class="card card-flush shadow-sm border-0 h-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900 fs-5">Link Performance</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Clicks & visitors statistics</span>
                    </h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary fw-semibold fs-8">Last 30 Days</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-5 d-flex align-items-center">
                    <div style="height: 260px; width: 100%;">
                        <canvas id="clicksChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Links List Section Card -->
<div class="card card-flush shadow-sm border-0 mb-6">
    <!-- Card Header: Pagination Limit & Filters -->
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <!-- Left Side: Pagination size selector -->
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <span class="text-gray-600 fs-7 fw-semibold me-2">Show</span>
                <form method="GET" action="{{ request()->url() }}" class="d-inline-block m-0">
                    @foreach(request()->except(['per_page', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="per_page" class="form-select form-select-sm form-select-solid w-75px" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 25) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
                <span class="text-gray-600 fs-7 fw-semibold ms-2">entries</span>
            </div>
        </div>

        <!-- Right Side: Search Box & Filter Icon Dropdown -->
        <div class="card-toolbar flex-row-fluid justify-content-end gap-3">
            <!-- Search Box Form -->
            <form id="searchForm" method="GET" action="{{ request()->url() }}" class="d-flex align-items-center position-relative my-1">
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('project_id'))
                    <input type="hidden" name="project_id" value="{{ request('project_id') }}">
                @endif
                @if(request('domain_id'))
                    <input type="hidden" name="domain_id" value="{{ request('domain_id') }}">
                @endif
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                <input type="text" name="search" class="form-control form-control-sm form-control-solid w-200px w-md-250px ps-11" placeholder="Search links..." value="{{ request('search') }}" />
            </form>

            <!-- Filter Dropdown Container -->
            <div class="dropdown">
                <button class="btn btn-sm btn-icon btn-light-secondary dropdown-toggle no-caret" type="button" id="filterDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Filter data">
                    <i class="ki-outline ki-filter fs-3"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-5 shadow-lg border-0 rounded-3 w-300px" aria-labelledby="filterDropdownBtn" onclick="event.stopPropagation()">
                    <form id="filterForm" method="GET" action="{{ request()->url() }}" class="m-0">
                        @if(request('type'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif
                        @if(request('per_page'))
                            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="mb-4">
                            <label class="form-label fs-7 fw-bold text-gray-700">Status</label>
                            <select name="status" class="form-select form-select-sm form-select-solid">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fs-7 fw-bold text-gray-700">Project</label>
                            <select name="project_id" class="form-select form-select-sm form-select-solid">
                                <option value="">All Projects</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fs-7 fw-bold text-gray-700">Domain</label>
                            <select name="domain_id" class="form-select form-select-sm form-select-solid">
                                <option value="">All Domains</option>
                                <option value="0" {{ request('domain_id') === '0' ? 'selected' : '' }}>Default Domain</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" {{ request('domain_id') == $domain->id ? 'selected' : '' }}>{{ $domain->host }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fs-7 fw-bold text-gray-700">Sort By</label>
                            <select name="sort" class="form-select form-select-sm form-select-solid">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="clicks_desc" {{ request('sort') == 'clicks_desc' ? 'selected' : '' }}>Most Clicks</option>
                                <option value="clicks_asc" {{ request('sort') == 'clicks_asc' ? 'selected' : '' }}>Least Clicks</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <a id="btnResetFilters" href="{{ request()->url() }}?type={{ request('type', 'link') }}&per_page={{ request('per_page', 25) }}" class="btn btn-sm btn-light fw-bold flex-grow-1">Reset</a>
                            <button type="submit" class="btn btn-sm btn-primary fw-bold flex-grow-1">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Body -->
    <div class="card-body pt-0">
        <!-- Bulk Action Panel -->
        <div id="bulkActionsBar" class="d-none align-items-center justify-content-between p-4 bg-light-primary rounded mb-4">
            <div class="d-flex align-items-center gap-2">
                <span class="fs-7 fw-bold text-gray-800 d-flex align-items-center gap-2">
                    <i class="ki-outline ki-check fs-4 text-primary"></i>
                    <span id="selectedCount" class="badge badge-primary fs-8 fw-bolder">0</span> item terpilih
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-primary fw-bold" id="btn_user_bulk_move_domain">
                    <i class="ki-outline ki-geolocation fs-5 me-1"></i> Pindah Domain
                </button>
                <button type="button" class="btn btn-sm btn-light-success fw-bold bulk-btn" data-action="enable">
                    <i class="ki-outline ki-check-circle fs-5 me-1"></i> Aktifkan
                </button>
                <button type="button" class="btn btn-sm btn-light-secondary fw-bold bulk-btn" data-action="disable">
                    <i class="ki-outline ki-cross-circle fs-5 me-1"></i> Nonaktifkan
                </button>
                <button type="button" class="btn btn-sm btn-light-danger fw-bold bulk-btn" data-action="delete">
                    <i class="ki-outline ki-trash fs-5 me-1"></i> Hapus
                </button>
            </div>
        </div>

        <!-- Table Wrapper for AJAX dynamic updates -->
        <div id="linksTableWrapper">
            @include('partials.links_table')
        </div>
    </div>
</div>

<!-- Create Link Modal -->
<div class="modal fade" id="createLinkModal" tabindex="-1" aria-labelledby="createLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900" id="createLinkModalLabel">Create Shortlink</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form action="{{ route('links.store') }}" method="POST">
                @csrf
                <div class="modal-body py-6 px-lg-8">
                    <!-- Judul Tautan / Title -->
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Judul Tautan (Opsional)</label>
                        <input type="text" name="title" class="form-control form-control-solid" placeholder="Contoh: Promo Diskon 50%, My Bio Link..." value="{{ old('title') }}" />
                        <div class="form-text text-muted fs-8">Nama judul untuk memudahkan identifikasi tautan Anda.</div>
                    </div>

                    <!-- Destination URL -->
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900 required">Target URL</label>
                        <div class="position-relative">
                            <i class="ki-outline ki-disconnect fs-3 position-absolute ms-4 top-50 translate-middle-y text-gray-500"></i>
                            <input type="url" name="location_url" class="form-control form-control-solid ps-12" placeholder="https://example.com/long-page-url" required value="{{ old('location_url') }}" />
                        </div>
                    </div>

                    <!-- Custom Domain -->
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Domain</label>
                        <select name="domain_id" id="create_domain_id" class="form-select form-select-solid">
                            <option value="0" selected>Default Domain ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->host }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Alias Path -->
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Custom Alias (Optional)</label>
                        <div class="input-group input-group-solid">
                            <span class="input-group-text text-gray-600 fw-bold" id="create_domain_prefix">
                                {{ parse_url(url('/'), PHP_URL_HOST) }}/
                            </span>
                            <input type="text" name="url" id="create_url" class="form-control form-control-solid" placeholder="custom-alias" value="{{ old('url') }}" />
                        </div>
                        <div id="create_alias_feedback" class="mt-1 fs-7"></div>
                        <div class="form-text text-muted fs-8">Leave empty to generate an automatic random alias.</div>
                    </div>

                    <!-- Project -->
                    <div class="fv-row mb-2">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Project</label>
                        <select name="project_id" class="form-select form-select-solid">
                            <option value="" selected>No Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="create_submit_btn" class="btn btn-primary fw-bold">Create Link</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Biolink Modal -->
<div class="modal fade" id="createBiolinkModal" tabindex="-1" aria-labelledby="createBiolinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900" id="createBiolinkModalLabel">Create Biolink Page</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form action="{{ route('links.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="biolink">
                <div class="modal-body py-6 px-lg-8">
                    <!-- Judul Biolink -->
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Judul Biolink (Opsional)</label>
                        <input type="text" name="title" class="form-control form-control-solid" placeholder="Contoh: Official Profile, Brand Bio..." value="{{ old('title') }}" />
                        <div class="form-text text-muted fs-8">Nama judul untuk halaman biolink ini.</div>
                    </div>

                    <!-- Custom Domain -->
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Domain</label>
                        <select name="domain_id" id="create_bio_domain_id" class="form-select form-select-solid">
                            <option value="0" selected>Default Domain ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->host }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Alias Path -->
                    <div class="fv-row mb-3">
                        <label class="form-label fs-6 fw-semibold text-gray-900 required">Custom Alias</label>
                        <div class="input-group input-group-solid">
                            <span class="input-group-text text-gray-600 fw-bold" id="create_bio_domain_prefix">
                                {{ parse_url(url('/'), PHP_URL_HOST) }}/
                            </span>
                            <input type="text" name="url" id="create_bio_url" class="form-control form-control-solid" placeholder="my-page" required value="{{ old('url') }}" />
                        </div>
                        <div id="create_bio_alias_feedback" class="mt-1 fs-7"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Create Biolink</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Link Modal -->
<div class="modal fade" id="editLinkModal" tabindex="-1" aria-labelledby="editLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900" id="editLinkModalLabel">Edit Link Settings</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form id="editLinkForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body py-6 px-lg-8">
                    <!-- Judul Tautan / Title -->
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Judul Tautan (Title)</label>
                        <input type="text" name="title" id="edit_title" class="form-control form-control-solid" placeholder="Judul / nama tautan..." />
                    </div>

                    <!-- Destination URL -->
                    <div class="fv-row mb-5" id="edit_location_wrapper">
                        <label class="form-label fs-6 fw-semibold text-gray-900 required">Target URL</label>
                        <div class="position-relative">
                            <i class="ki-outline ki-disconnect fs-3 position-absolute ms-4 top-50 translate-middle-y text-gray-500"></i>
                            <input type="url" name="location_url" id="edit_location_url" class="form-control form-control-solid ps-12" placeholder="https://example.com/target" required />
                        </div>
                    </div>

                    <!-- Custom Domain -->
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Domain</label>
                        <select name="domain_id" id="edit_domain_id" class="form-select form-select-solid">
                            <option value="0">Default Domain ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->host }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Alias Path -->
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900 required">Custom Alias</label>
                        <div class="input-group input-group-solid">
                            <span class="input-group-text text-gray-600 fw-bold" id="edit_domain_prefix">
                                {{ parse_url(url('/'), PHP_URL_HOST) }}/
                            </span>
                            <input type="text" name="url" id="edit_url" class="form-control form-control-solid" placeholder="custom-alias" required />
                        </div>
                        <div id="edit_alias_feedback" class="mt-1 fs-7"></div>
                    </div>

                    <!-- Project -->
                    <div class="fv-row mb-2">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Project</label>
                        <select name="project_id" id="edit_project_id" class="form-select form-select-solid">
                            <option value="">No Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="edit_submit_btn" class="btn btn-primary fw-bold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteLinkForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Modal Bulk Move Domain (User Panel) -->
<div class="modal fade" id="modal_bulk_move_domain_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content rounded-3 shadow-lg border-0">
            <form id="userBulkMoveDomainForm">
                @csrf
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h4 class="fw-bolder text-gray-900 mb-0 d-flex align-items-center gap-2">
                        <i class="ki-outline ki-geolocation fs-2 text-primary"></i>
                        Pindah Domain Masal
                    </h4>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>

                <div class="modal-body py-4 px-6">
                    <div class="p-3 bg-light-primary rounded-3 border border-primary border-dashed mb-4">
                        <div class="d-flex align-items-center gap-2 text-primary fw-bold fs-7">
                            <i class="ki-outline ki-information-2 fs-4 text-primary"></i>
                            Memindahkan <span id="user_modal_bulk_move_count" class="fw-bolder fs-6 px-1 badge badge-primary">0</span> tautan terpilih ke domain baru.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Pilih Domain Tujuan</label>
                        <select name="target_domain_id" id="user_bulk_target_domain_id" class="form-select form-select-solid form-select-sm" required>
                            <option value="0">Default Domain ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                            @if(isset($domains))
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}">
                                        {{ $domain->host }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="text-muted fs-8 mt-1">Seluruh tautan yang dipilih akan dialihkan ke domain ini.</div>
                    </div>
                </div>

                <div class="modal-footer pt-0 border-0 px-6 pb-6">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold d-flex align-items-center gap-1" id="btn_user_submit_bulk_move">
                        <i class="ki-outline ki-check fs-6"></i> Pindahkan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Select2 Assets -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('load', function() {

        // ─── SweetAlert2 Global Theme Config ───
        const swalTheme = {
            customClass: {
                popup: 'swal-glass-popup',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn'
            },
            buttonsStyling: false
        };

        // Helper: Show SweetAlert2 toast/popup
        window.showSwal = function(type, message, isToast) {
            if (isToast) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'swal-glass-toast'
                    },
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: type,
                    title: message
                });
            } else {
                Swal.fire({
                    icon: type,
                    title: type === 'success' ? 'Berhasil!' : (type === 'error' ? 'Gagal!' : 'Info'),
                    text: message,
                    ...swalTheme,
                    confirmButtonText: 'OK'
                });
            }
        };

        // Helper: Show SweetAlert2 confirmation dialog
        window.showSwalConfirm = function(title, text, confirmText, cancelText) {
            return Swal.fire({
                icon: 'warning',
                title: title,
                text: text,
                showCancelButton: true,
                confirmButtonText: confirmText || 'Ya, Lanjutkan',
                cancelButtonText: cancelText || 'Batal',
                reverseButtons: true,
                ...swalTheme
            });
        };

        // ─── Fire session flash via SweetAlert2 ───
        @if(session('success'))
            showSwal('success', '{{ session('success') }}', true);
        @endif
        @if($errors->any())
            showSwal('error', '{{ $errors->first() }}', false);
        @endif

        // Initialize Select2 on modal shown to prevent width calculation bugs
        $('#createLinkModal').on('shown.bs.modal', function () {
            $('#createLinkModal select').select2({
                dropdownParent: $('#createLinkModal'),
                width: '100%'
            });
        });
        
        $('#editLinkModal').on('shown.bs.modal', function () {
            $('#editLinkModal select').select2({
                dropdownParent: $('#editLinkModal'),
                width: '100%'
            });
        });

        // Initialize simple Select2 filters
        $('.select2-simple').select2({
            minimumResultsForSearch: -1,
            width: '100%'
        });

        const ctx = document.getElementById('clicksChart');
        if (!ctx) return;

        // Custom chart gradients
        const chartBg = ctx.getContext('2d');
        const gradient = chartBg.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.15)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Clicks',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#2563eb',
                    borderWidth: 2.5,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1.5,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: '#0f172a',
                        titleColor: '#f8fafc',
                        bodyColor: '#94a3b8',
                        borderColor: 'rgba(255,255,255,0.08)',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' Clicks';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 9
                            },
                            color: '#94a3b8',
                            maxRotation: 0
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(148, 163, 184, 0.08)',
                            lineWidth: 1
                        },
                        ticks: {
                            font: {
                                size: 9
                            },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });

        // Copy Clipboard Handlers (Dynamic Delegation)
        $(document).on('click', '.btn-copy-link', function(e) {
            e.preventDefault();
            const btn = $(this);
            const url = btn.attr('data-url');
            navigator.clipboard.writeText(url).then(() => {
                const originalHTML = btn.html();
                btn.html('<span style="color:#166534; font-size:0.675rem; font-weight:bold;">Copied!</span>');
                setTimeout(() => {
                    btn.html(originalHTML);
                }, 1200);
            });
        });

        // Edit link modal populator (Dynamic Delegation)
        $(document).on('click', '.btn-edit-link', function(e) {
            e.preventDefault();
            const btn = $(this);
            const id = btn.attr('data-id');
            const type = btn.attr('data-type') || 'link';
            const url = btn.attr('data-url');
            const title = btn.attr('data-title') || '';
            const location = btn.attr('data-location') || '';
            const project = btn.attr('data-project');
            const domain = btn.attr('data-domain');
            
            // Set form action
            document.getElementById('editLinkForm').action = `/link/${id}`;
            
            // Populate inputs
            const editTitleEl = document.getElementById('edit_title');
            if (editTitleEl) editTitleEl.value = title;
            document.getElementById('edit_location_url').value = location;
            document.getElementById('edit_url').value = url;
            
            // Show/hide and require Target URL depending on link type
            if (type !== 'link') {
                $('#edit_location_url').closest('.mb-3').hide();
                $('#edit_location_url').removeAttr('required');
                $('#editLinkModalLabel').text(type === 'biolink' ? 'Perbarui Pengaturan Biolink' : 'Perbarui Pengaturan Rotator');
            } else {
                $('#edit_location_url').closest('.mb-3').show();
                $('#edit_location_url').attr('required', 'required');
                $('#editLinkModalLabel').text('Perbarui Tautan Pendek');
            }

            // Populate Select2 controls using jQuery triggers
            $('#edit_project_id').val(project || "").trigger('change');
            $('#edit_domain_id').val(domain || "0").trigger('change');
            
            // Update prefix and reset feedback
            setTimeout(() => {
                updateDomainPrefix('#edit_domain_id', '#edit_domain_prefix');
            }, 100);
            $('#edit_alias_feedback').html('');
            $('#edit_submit_btn').prop('disabled', false);
        });

        // Delete link confirmation (Dynamic Delegation)
        $(document).on('click', '.btn-delete-link', function(e) {
            e.preventDefault();
            const id = $(this).attr('data-id');
            showSwalConfirm(
                'Hapus Tautan?',
                'Apakah Anda yakin ingin menghapus tautan pendek ini secara permanen?',
                'Ya, Hapus!',
                'Batal'
            ).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = document.getElementById('deleteLinkForm');
                    deleteForm.action = `/link/${id}`;
                    deleteForm.submit();
                }
            });
        });

        // Toggle link status (AJAX - Dynamic Delegation)
        $(document).on('change', '.link-status-toggle', function() {
            const checkbox = $(this);
            const id = checkbox.attr('data-id');
            const isEnabled = checkbox.is(':checked') ? 1 : 0;
            
            fetch(`/link/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    is_enabled: isEnabled
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSwal('success', data.message, true);
                } else {
                    showSwal('error', data.message || 'Gagal mengubah status link', true);
                    checkbox.prop('checked', !checkbox.is(':checked'));
                }
            })
            .catch(err => {
                console.error('Gagal mengubah status link', err);
                showSwal('error', 'Terjadi kesalahan koneksi', true);
                checkbox.prop('checked', !checkbox.is(':checked'));
            });
        });

        // Helper: Update Domain Prefix text in modals
        window.updateDomainPrefix = function(selectEl, prefixEl) {
            const text = $(selectEl).find('option:selected').text();
            if (text.includes('Domain Bawaan')) {
                $(prefixEl).text('{{ parse_url(url('/'), PHP_URL_HOST) }}/');
            } else {
                $(prefixEl).text(text + '/');
            }
        };

        // Helper: Check Alias availability via AJAX API
        let checkTimeout;
        window.checkAlias = function(alias, domainId, excludeId, feedbackEl, submitEl) {
            clearTimeout(checkTimeout);
            
            if (!alias) {
                $(feedbackEl).html('');
                $(submitEl).prop('disabled', false);
                return;
            }
            
            $(feedbackEl).html('<span class="text-muted"><i class="spinner-border spinner-border-sm me-1" style="width: 10px; height: 10px;"></i> Memeriksa ketersediaan...</span>');
            
            checkTimeout = setTimeout(() => {
                const url = `/link/check-availability?url=${encodeURIComponent(alias)}&domain_id=${domainId}` + (excludeId ? `&exclude_id=${excludeId}` : '');
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            $(feedbackEl).html('<span class="text-success small fw-semibold">✓ Alias tersedia pada domain ini!</span>');
                            $(submitEl).prop('disabled', false);
                        } else {
                            $(feedbackEl).html('<span class="text-danger small fw-semibold">✗ Alias sudah digunakan pada domain ini!</span>');
                            $(submitEl).prop('disabled', true);
                        }
                    })
                    .catch(err => {
                        console.error('Availability check failed', err);
                        $(feedbackEl).html('');
                        $(submitEl).prop('disabled', false);
                    });
            }, 300);
        };

        // Event listeners for Create Modal availability check
        $('#create_url').on('input', function() {
            const alias = $(this).val();
            const domainId = $('#create_domain_id').val();
            checkAlias(alias, domainId, null, '#create_alias_feedback', '#create_submit_btn');
        });

        $('#create_domain_id').on('change', function() {
            updateDomainPrefix('#create_domain_id', '#create_domain_prefix');
            const alias = $('#create_url').val();
            const domainId = $(this).val();
            checkAlias(alias, domainId, null, '#create_alias_feedback', '#create_submit_btn');
        });

        // Event listeners for Create Biolink Modal availability check
        $('#create_bio_url').on('input', function() {
            const alias = $(this).val();
            const domainId = $('#create_bio_domain_id').val();
            checkAlias(alias, domainId, null, '#create_bio_alias_feedback', '#createBiolinkModal button[type="submit"]');
        });

        $('#create_bio_domain_id').on('change', function() {
            updateDomainPrefix('#create_bio_domain_id', '#create_bio_domain_prefix');
            const alias = $('#create_bio_url').val();
            const domainId = $(this).val();
            checkAlias(alias, domainId, null, '#create_bio_alias_feedback', '#createBiolinkModal button[type="submit"]');
        });

        // Event listeners for Edit Modal availability check
        $('#edit_url').on('input', function() {
            const alias = $(this).val();
            const domainId = $('#edit_domain_id').val();
            const actionPath = $('#editLinkForm').attr('action');
            const linkId = actionPath ? actionPath.split('/').pop() : null;
            checkAlias(alias, domainId, linkId, '#edit_alias_feedback', '#edit_submit_btn');
        });

        $('#edit_domain_id').on('change', function() {
            updateDomainPrefix('#edit_domain_id', '#edit_domain_prefix');
            const alias = $('#edit_url').val();
            const domainId = $(this).val();
            const actionPath = $('#editLinkForm').attr('action');
            const linkId = actionPath ? actionPath.split('/').pop() : null;
            checkAlias(alias, domainId, linkId, '#edit_alias_feedback', '#edit_submit_btn');
        });

        // Bulk Action selection handler (Dynamic delegation)
        function updateBulkBarState() {
            const bulkBar = document.getElementById('bulkActionsBar');
            const selectedCountEl = document.getElementById('selectedCount');
            const selectAllCheckbox = document.getElementById('selectAllLinks');
            
            const checkedBoxes = document.querySelectorAll('.link-checkbox:checked');
            const allBoxes = document.querySelectorAll('.link-checkbox');
            const count = checkedBoxes.length;
            
            if (count > 0 && bulkBar) {
                bulkBar.classList.remove('d-none');
                bulkBar.classList.add('d-flex');
                selectedCountEl.textContent = count;
            } else if (bulkBar) {
                bulkBar.classList.remove('d-flex');
                bulkBar.classList.add('d-none');
            }
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = (count === allBoxes.length && allBoxes.length > 0);
            }
        }

        $(document).on('change', '#selectAllLinks', function() {
            $('.link-checkbox').prop('checked', this.checked);
            updateBulkBarState();
        });

        $(document).on('change', '.link-checkbox', function() {
            updateBulkBarState();
        });

        // Bulk Action buttons click dispatcher (Dynamic delegation)
        $(document).on('click', '.bulk-btn', function(e) {
            e.preventDefault();
            const action = $(this).attr('data-action');
            const checkedBoxes = $('.link-checkbox:checked');
            const ids = checkedBoxes.map(function() { return $(this).val(); }).get();

            if (ids.length === 0) return;

            let confirmTitle = 'Konfirmasi Tindakan';
            let confirmMsg = 'Apakah Anda yakin ingin melakukan tindakan ini pada tautan terpilih?';
            let confirmBtn = 'Ya, Lanjutkan';
            if (action === 'delete') {
                confirmTitle = 'Hapus ' + ids.length + ' Tautan?';
                confirmMsg = 'Apakah Anda yakin ingin menghapus ' + ids.length + ' tautan pendek terpilih secara permanen?';
                confirmBtn = 'Ya, Hapus Semua!';
            }

            showSwalConfirm(confirmTitle, confirmMsg, confirmBtn, 'Batal').then((result) => {
                if (result.isConfirmed) {
                    fetch('/link/bulk-action', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ids: ids,
                            action: action
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSwal('success', data.message, true);
                            applyFilters();
                        } else {
                            showSwal('error', 'Terjadi kesalahan, silakan coba lagi.', false);
                        }
                    })
                    .catch(err => {
                        console.error('Bulk action failed', err);
                        showSwal('error', 'Terjadi kesalahan koneksi.', false);
                    });
                }
            });
        });

        // User Bulk Move Domain trigger
        $(document).on('click', '#btn_user_bulk_move_domain', function(e) {
            e.preventDefault();
            const checkedBoxes = $('.link-checkbox:checked');
            const ids = checkedBoxes.map(function() { return $(this).val(); }).get();

            if (ids.length === 0) return;

            $('#user_modal_bulk_move_count').text(ids.length);
            const modalEl = document.getElementById('modal_bulk_move_domain_user');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });

        // User Bulk Move Domain Form Submit
        const userBulkMoveDomainForm = document.getElementById('userBulkMoveDomainForm');
        if (userBulkMoveDomainForm) {
            userBulkMoveDomainForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const checkedBoxes = $('.link-checkbox:checked');
                const ids = checkedBoxes.map(function() { return $(this).val(); }).get();
                const targetDomainId = document.getElementById('user_bulk_target_domain_id').value;
                const submitBtn = document.getElementById('btn_user_submit_bulk_move');

                if (ids.length === 0) {
                    showSwal('error', 'Pilih minimal satu tautan.', false);
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memindahkan...';

                fetch('/link/bulk-action', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'move_domain',
                        ids: ids,
                        target_domain_id: targetDomainId
                    })
                })
                .then(res => res.json())
                .then(res => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ki-outline ki-check fs-6"></i> Pindahkan Sekarang';

                    if (res.success) {
                        const modalEl = document.getElementById('modal_bulk_move_domain_user');
                        if (modalEl) {
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        }
                        showSwal('success', res.message, true);
                        applyFilters();
                    } else {
                        showSwal('error', res.message || 'Gagal memindahkan domain.', false);
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ki-outline ki-check fs-6"></i> Pindahkan Sekarang';
                    showSwal('error', 'Terjadi kesalahan koneksi.', false);
                });
            });
        }

        // AJAX Table Load and Filter Engine
        window.loadTable = function(url) {
            $('#linksTableWrapper').css('opacity', '0.5');
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(html) {
                    $('#linksTableWrapper').html(html);
                    $('#linksTableWrapper').css('opacity', '1');
                    
                    // Reset bulk action bar in case elements were refreshed
                    updateBulkBarState();
                },
                error: function(xhr) {
                    console.error('Gagal memuat data tabel via AJAX', xhr);
                    $('#linksTableWrapper').css('opacity', '1');
                }
            });
        };

        window.applyFilters = function() {
            const search = $('input[name="search"]').val() || '';
            const status = $('select[name="status"]').val() || '';
            const project_id = $('select[name="project_id"]').val() || '';
            const domain_id = $('select[name="domain_id"]').val() || '';
            const sort = $('select[name="sort"]').val() || 'latest';
            const per_page = $('select[name="per_page"]').val() || '25';
            const type = '{{ request("type", "link") }}';

            const params = $.param({
                type: type,
                search: search,
                status: status,
                project_id: project_id,
                domain_id: domain_id,
                sort: sort,
                per_page: per_page
            });

            const url = '{{ request()->url() }}?' + params;
            loadTable(url);
        };

        // Form Submit interception for AJAX searches and filters
        $(document).on('submit', '#searchForm, #filterForm', function(e) {
            e.preventDefault();
            // Close dropdown if it is the filter dropdown
            const filterDropdown = bootstrap.Dropdown.getInstance(document.getElementById('filterDropdownBtn'));
            if (filterDropdown) {
                filterDropdown.hide();
            }
            applyFilters();
        });

        // Auto-search as typed (debounced to avoid overloading the server)
        let searchDebounceTimeout;
        $(document).on('input', 'input[name="search"]', function() {
            clearTimeout(searchDebounceTimeout);
            searchDebounceTimeout = setTimeout(() => {
                applyFilters();
            }, 300);
        });

        // Reset Filters Button AJAX logic
        $(document).on('click', '#btnResetFilters', function(e) {
            e.preventDefault();
            // Clear all filter values
            $('input[name="search"]').val('');
            $('select[name="status"]').val('').trigger('change');
            $('select[name="project_id"]').val('').trigger('change');
            $('select[name="domain_id"]').val('').trigger('change');
            $('select[name="sort"]').val('latest').trigger('change');
            
            // Close filter dropdown if open
            const filterDropdown = bootstrap.Dropdown.getInstance(document.getElementById('filterDropdownBtn'));
            if (filterDropdown) {
                filterDropdown.hide();
            }
            applyFilters();
        });

        // Remove individual filter badge AJAX logic
        $(document).on('click', '.btn-remove-filter', function(e) {
            e.preventDefault();
            const type = $(this).attr('data-filter-type');
            if (type === 'search') {
                $('input[name="search"]').val('');
            } else if (type === 'status') {
                $('select[name="status"]').val('').trigger('change');
            } else if (type === 'project') {
                $('select[name="project_id"]').val('').trigger('change');
            } else if (type === 'domain') {
                $('select[name="domain_id"]').val('').trigger('change');
            }
            applyFilters();
        });

        // Clear all active filters link badge AJAX logic
        $(document).on('click', '#btnClearAllFilters', function(e) {
            e.preventDefault();
            $('#btnResetFilters').trigger('click');
        });

        // Records Per Page Selector AJAX logic
        $(document).on('change', 'select[name="per_page"]', function(e) {
            e.preventDefault();
            applyFilters();
        });

        // Column Sorting Header Links AJAX logic
        $(document).on('click', '#linksTableWrapper th a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            loadTable(url);

            // Synchronize the sort order select dropdown in case they clicked a header
            const urlParams = new URLSearchParams(url.split('?')[1]);
            const sortVal = urlParams.get('sort');
            if (sortVal) {
                $('select[name="sort"]').val(sortVal).trigger('change');
            }
        });

        // Pagination Links click AJAX logic
        $(document).on('click', '#linksTableWrapper .pagination a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            loadTable(url);
        });

        // Show/Hide Stats and Chart toggle handler
        const toggleStatsBtn = document.getElementById('toggleStatsBtn');
        const statsWrapper = document.getElementById('dashboardStatsWrapper');

        if (toggleStatsBtn && statsWrapper) {
            const statsHidden = localStorage.getItem('dashboard_stats_hidden') === 'true';
            const iconEl = toggleStatsBtn.querySelector('i');
            
            function applyStatsState(hidden) {
                const newTitle = hidden ? 'Show Statistics' : 'Hide Statistics';
                toggleStatsBtn.setAttribute('title', newTitle);
                toggleStatsBtn.setAttribute('data-bs-title', newTitle);

                // Re-initialize Bootstrap tooltip
                if (window.bootstrap && bootstrap.Tooltip) {
                    const tooltipInstance = bootstrap.Tooltip.getInstance(toggleStatsBtn);
                    if (tooltipInstance) {
                        tooltipInstance.dispose();
                    }
                    new bootstrap.Tooltip(toggleStatsBtn);
                }

                if (hidden) {
                    statsWrapper.classList.add('d-none');
                    if (iconEl) {
                        iconEl.className = 'ki-outline ki-eye-slash fs-2';
                    }
                } else {
                    statsWrapper.classList.remove('d-none');
                    if (iconEl) {
                        iconEl.className = 'ki-outline ki-eye fs-2';
                    }
                }
            }

            applyStatsState(statsHidden);

            toggleStatsBtn.addEventListener('click', function() {
                const isCurrentlyHidden = statsWrapper.classList.contains('d-none');
                applyStatsState(!isCurrentlyHidden);
                localStorage.setItem('dashboard_stats_hidden', !isCurrentlyHidden);
            });
        }
    });
</script>

<style>
    /* Hide dropdown caret */
    .no-caret::after {
        display: none !important;
    }

    /* Select2 Metronic style overrides */
    .select2-container--default .select2-selection--single {
        background-color: var(--bs-gray-100) !important;
        border: 1px solid var(--bs-gray-300) !important;
        height: 42px !important;
        border-radius: .475rem !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--bs-gray-700) !important;
        font-size: .925rem !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    .select2-dropdown {
        background-color: var(--bs-body-bg) !important;
        border: 1px solid var(--bs-gray-300) !important;
        border-radius: .475rem !important;
        overflow: hidden;
        box-shadow: 0 .5rem 1.5rem .5rem rgba(0, 0, 0, .075) !important;
        z-index: 9999;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: var(--bs-gray-100) !important;
        border: 1px solid var(--bs-gray-300) !important;
        border-radius: .475rem !important;
        color: var(--bs-gray-800) !important;
        padding: 6px 10px !important;
        font-size: .925rem !important;
        outline: none !important;
    }
    .select2-results__option {
        font-size: .925rem !important;
        padding: 8px 12px !important;
        color: var(--bs-gray-700) !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--bs-primary) !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: var(--bs-primary-light) !important;
        color: var(--bs-primary) !important;
    }

    /* SweetAlert2 Metronic Styling */
    .swal-glass-popup {
        border-radius: .75rem !important;
        border: 1px solid var(--bs-gray-200) !important;
        box-shadow: 0 .5rem 1.5rem .5rem rgba(0, 0, 0, .075) !important;
    }
    .swal-glass-toast {
        border-radius: .475rem !important;
        border: 1px solid var(--bs-gray-200) !important;
        box-shadow: 0 .25rem .75rem .25rem rgba(0, 0, 0, .075) !important;
        font-size: .925rem !important;
    }
    .swal-confirm-btn {
        background-color: var(--bs-primary) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: .475rem !important;
        padding: 10px 24px !important;
        font-size: .925rem !important;
        font-weight: 600 !important;
        cursor: pointer !important;
    }
    .swal-confirm-btn:hover {
        opacity: 0.85 !important;
    }
    .swal-cancel-btn {
        background-color: var(--bs-gray-200) !important;
        color: var(--bs-gray-700) !important;
        border: none !important;
        border-radius: .475rem !important;
        padding: 10px 24px !important;
        font-size: .925rem !important;
        font-weight: 600 !important;
        margin-right: 8px !important;
        cursor: pointer !important;
    }
    .swal-cancel-btn:hover {
        background-color: var(--bs-gray-300) !important;
    }
</style>
@endsection
