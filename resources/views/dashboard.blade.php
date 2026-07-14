@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
    if (!function_exists('renderCardIcon')) {
        function renderCardIcon($iconName) {
            if ($iconName == 'hash') {
                return '<span class="fw-bold fs-5">#</span>';
            } elseif ($iconName == 'link') {
                return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path><line x1="8" y1="12" x2="16" y2="12"></line></svg>';
            } elseif ($iconName == 'qrcode') {
                return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect><rect x="14" y="14" width="3" height="3"></rect></svg>';
            } elseif ($iconName == 'card') {
                return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>';
            } elseif ($iconName == 'clicks') {
                return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>';
            } elseif ($iconName == 'calendar') {
                return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
            } elseif ($iconName == 'chart') {
                return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path></svg>';
            }
            return '';
        }
    }
    
    if (!function_exists('renderCardColor')) {
        function renderCardColor($iconName) {
            if ($iconName == 'hash') return 'rgba(164, 229, 189, 0.2); color: #166534;';
            if ($iconName == 'link') return 'rgba(164, 229, 189, 0.2); color: #166534;';
            if ($iconName == 'qrcode') return 'rgba(164, 229, 189, 0.2); color: #166534;';
            if ($iconName == 'card') return 'rgba(164, 229, 189, 0.2); color: #166534;';
            if ($iconName == 'clicks') return 'rgba(164, 229, 189, 0.2); color: #166534;';
            if ($iconName == 'calendar') return 'rgba(164, 229, 189, 0.2); color: #166534;';
            if ($iconName == 'chart') return 'rgba(164, 229, 189, 0.2); color: #166534;';
            return 'rgba(164, 229, 189, 0.2); color: #166534;';
        }
    }
@endphp

<!-- Page Header (Title & Actions) -->
<div class="d-flex align-items-center justify-content-between mb-4 mt-2">
    <h4 class="fw-bold mb-0 d-flex align-items-center text-dark-custom" style="font-size: 1.5rem; letter-spacing: -0.5px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-muted" style="margin-right: 12px;">
            <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
            <line x1="8" y1="12" x2="16" y2="12"></line>
        </svg>
        @if($type == 'link')
            Shortened Links
        @elseif($type == 'biolink')
            Biolink Pages
        @elseif($type == 'qrcode')
            QR Codes
        @else
            Links
        @endif
        <span class="ms-2 text-muted" style="cursor: help;" data-bs-toggle="tooltip" title="Daftar tautan biolink dan tautan pendek Anda.">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </span>
    </h4>
    <div class="d-flex align-items-center gap-2">
        <button id="toggleStatsBtn" class="btn btn-outline-secondary p-0 d-flex align-items-center justify-content-center rounded-3 border-opacity-15" style="width: 38px; height: 38px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Sembunyikan Statistik">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        </button>
        @if($type == 'biolink')
            <button class="btn btn-primary d-flex align-items-center gap-1.5 py-2 px-3.5 fw-semibold rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createBiolinkModal" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                Create Biolink
            </button>
        @else
            <button class="btn btn-primary d-flex align-items-center gap-1.5 py-2 px-3.5 fw-semibold rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createLinkModal" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                Create Link
            </button>
        @endif
    </div>
</div>



<!-- Stats & Chart Section Wrapper -->
<div id="dashboardStatsWrapper">
    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <!-- Card 1 -->
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 d-flex align-items-center w-100 border border-secondary border-opacity-10 rounded-3" style="background: var(--card-bg-blur); height: 74px;">
                <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background: {!! renderCardColor($card1_icon) !!} width: 40px; height: 40px; flex-shrink: 0;">
                    {!! renderCardIcon($card1_icon) !!}
                </div>
                <div class="min-w-0">
                    <h4 class="fw-bold mb-0 text-truncate" style="line-height: 1.1; font-size: 1.25rem;">{{ $card1_val }}</h4>
                    <span class="text-secondary small text-truncate d-block" style="font-size: 0.675rem; font-weight: 500;">{{ $card1_lbl }}</span>
                </div>
            </div>
        </div>
        
        <!-- Card 2 -->
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 d-flex align-items-center w-100 border border-secondary border-opacity-10 rounded-3" style="background: var(--card-bg-blur); height: 74px;">
                <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background: {!! renderCardColor($card2_icon) !!} width: 40px; height: 40px; flex-shrink: 0;">
                    {!! renderCardIcon($card2_icon) !!}
                </div>
                <div class="min-w-0">
                    <h4 class="fw-bold mb-0 text-truncate" style="line-height: 1.1; font-size: 1.25rem;">{{ $card2_val }}</h4>
                    <span class="text-secondary small text-truncate d-block" style="font-size: 0.675rem; font-weight: 500;">{{ $card2_lbl }}</span>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 d-flex align-items-center w-100 border border-secondary border-opacity-10 rounded-3" style="background: var(--card-bg-blur); height: 74px;">
                <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background: {!! renderCardColor($card3_icon) !!} width: 40px; height: 40px; flex-shrink: 0;">
                    {!! renderCardIcon($card3_icon) !!}
                </div>
                <div class="min-w-0">
                    <h4 class="fw-bold mb-0 text-truncate" style="line-height: 1.1; font-size: 1.25rem;">{{ $card3_val }}</h4>
                    <span class="text-secondary small text-truncate d-block" style="font-size: 0.675rem; font-weight: 500;">{{ $card3_lbl }}</span>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 d-flex align-items-center w-100 border border-secondary border-opacity-10 rounded-3" style="background: var(--card-bg-blur); height: 74px;">
                <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="background: {!! renderCardColor($card4_icon) !!} width: 40px; height: 40px; flex-shrink: 0;">
                    {!! renderCardIcon($card4_icon) !!}
                </div>
                <div class="min-w-0">
                    <h4 class="fw-bold mb-0 text-truncate" style="line-height: 1.1; font-size: 1.25rem;">{{ $card4_val }}</h4>
                    <span class="text-secondary small text-truncate d-block" style="font-size: 0.675rem; font-weight: 500;">{{ $card4_lbl }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Area -->
    <div class="glass-card p-4 mb-4 rounded-3 border border-secondary border-opacity-10" style="background: var(--card-bg-blur);">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0">Link Performance</h6>
            <span class="badge rounded-pill" style="background: var(--primary-light); color: #166534; font-weight: 500; font-size: 0.75rem;">Last 30 Days</span>
        </div>
        <div style="height: 220px; width: 100%;">
            <canvas id="clicksChart"></canvas>
        </div>
    </div>
</div>


<!-- Links List Section -->
<div class="glass-card p-4 rounded-3 border border-secondary border-opacity-10 mb-5" style="background: var(--card-bg-blur);">
    <!-- Card Header: Pagination Limit & Filters -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--glass-border) !important;">
        <!-- Left Side: Pagination size selector -->
        <div class="d-flex align-items-center gap-2">
            <span class="text-secondary small fw-semibold">Tampilkan</span>
            <form method="GET" action="{{ request()->url() }}" class="d-inline-block m-0">
                @foreach(request()->except(['per_page', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <select name="per_page" class="form-select bg-transparent border rounded-3 py-1 ps-2.5 pe-4 text-secondary small" style="width: 80px; height: 32px; font-size: 0.775rem;">
                    <option value="10" {{ request('per_page', 25) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100</option>
                </select>
            </form>
            <span class="text-secondary small fw-semibold">data</span>
        </div>

        <!-- Right Side: Search Box & Filter Icon Dropdown -->
        <div class="d-flex align-items-center gap-2">
            <!-- Search Box Form -->
            <form id="searchForm" method="GET" action="{{ request()->url() }}" class="m-0">
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
                <div class="glass-input-group d-flex align-items-center border rounded-3 p-1 bg-transparent" style="width: 240px; height: 38px;">
                    <span class="px-2 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input type="text" name="search" class="form-control bg-transparent border-0 py-0.5 small" placeholder="Cari URL, alias atau target..." value="{{ request('search') }}" style="font-size: 0.8rem;">
                </div>
            </form>

            <!-- Filter Dropdown Container -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary p-0 d-flex align-items-center justify-content-center rounded-3 border-opacity-15 dropdown-toggle no-caret" type="button" id="filterDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="width: 38px; height: 38px;" title="Filter data" data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 1 4 10 12.46 10 19 14 21 14 12.46 23 4"></polyline>
                    </svg>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg border rounded-3" aria-labelledby="filterDropdownBtn" onclick="event.stopPropagation()" style="width: 280px; background: var(--header-bg); border-color: var(--glass-border);">
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

                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary">Status</label>
                            <select name="status" class="form-select bg-transparent border rounded-3 py-1.5 text-secondary small select2-simple">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary">Proyek</label>
                            <select name="project_id" class="form-select bg-transparent border rounded-3 py-1.5 text-secondary small select2-simple">
                                <option value="">Semua Proyek</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary">Domain</label>
                            <select name="domain_id" class="form-select bg-transparent border rounded-3 py-1.5 text-secondary small select2-simple">
                                <option value="">Semua Domain</option>
                                <option value="0" {{ request('domain_id') === '0' ? 'selected' : '' }}>Domain Bawaan</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" {{ request('domain_id') == $domain->id ? 'selected' : '' }}>{{ $domain->host }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Urutkan</label>
                            <select name="sort" class="form-select bg-transparent border rounded-3 py-1.5 text-secondary small select2-simple">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="clicks_desc" {{ request('sort') == 'clicks_desc' ? 'selected' : '' }}>Klik Terbanyak</option>
                                <option value="clicks_asc" {{ request('sort') == 'clicks_asc' ? 'selected' : '' }}>Klik Terkecil</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <a id="btnResetFilters" href="{{ request()->url() }}?type={{ request('type', 'link') }}&per_page={{ request('per_page', 25) }}" class="btn btn-light btn-sm rounded-3 py-1.5 px-3 fw-semibold flex-grow-1" style="font-size: 0.775rem;">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm rounded-3 py-1.5 px-3 fw-semibold flex-grow-1" style="font-size: 0.775rem; background-color: var(--primary-color); border-color: var(--primary-color);">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Action Panel -->
    <div id="bulkActionsBar" class="d-none mb-4 p-3 align-items-center justify-content-between" style="background: var(--primary-light); border-top: 1px solid rgba(164, 229, 189, 0.2); border-bottom: 1px solid rgba(164, 229, 189, 0.2); margin-left: -1.5rem; margin-right: -1.5rem; padding-left: 1.5rem; padding-right: 1.5rem;">
        <div class="d-flex align-items-center gap-2">
            <span class="small fw-semibold d-flex align-items-center gap-2" style="color: var(--text-primary);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-success"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span id="selectedCount" class="badge rounded-pill px-2.5 py-1" style="background-color: var(--primary-color) !important; color: var(--active-text) !important; font-size: 0.75rem; font-weight: 700;">0</span> item terpilih
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-theme btn-sm py-1.5 px-3 fw-semibold bulk-btn" data-action="enable" style="font-size: 0.75rem; border-radius: 8px !important;">Aktifkan</button>
            <button type="button" class="btn btn-outline-gray btn-sm py-1.5 px-3 fw-semibold bulk-btn" data-action="disable" style="font-size: 0.75rem; border-radius: 8px !important;">Nonaktifkan</button>
            <button type="button" class="btn btn-soft-danger btn-sm py-1.5 px-3 fw-semibold bulk-btn" data-action="delete" style="font-size: 0.75rem; border-radius: 8px !important;">Hapus</button>
        </div>
    </div>

    <!-- Table Wrapper for AJAX dynamic updates -->
    <div id="linksTableWrapper">
        @include('partials.links_table')
    </div>
</div>

<!-- Create Link Modal -->
<div class="modal fade" id="createLinkModal" tabindex="-1" aria-labelledby="createLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border border-secondary border-opacity-10 rounded-3 p-2" style="background: var(--header-bg); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
            <div class="modal-header border-0 pb-1">
                <h6 class="modal-title fw-bold text-dark-custom" id="createLinkModalLabel">Buat Tautan Pendek</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('links.store') }}" method="POST">
                @csrf
                <div class="modal-body py-2">
                    <!-- Destination URL -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Target URL <span class="text-danger">*</span></label>
                        <div class="glass-input-group d-flex align-items-center border rounded-3 p-1">
                            <span class="px-2 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            </span>
                            <input type="url" name="location_url" class="form-control bg-transparent border-0 py-1 small" placeholder="https://example.com/alamat-panjang" required value="{{ old('location_url') }}">
                        </div>
                    </div>

                    <!-- Custom Domain -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Domain</label>
                        <select name="domain_id" id="create_domain_id" class="form-select bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 text-secondary small">
                            <option value="0" selected>Domain Bawaan ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->host }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Alias Path -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Alias URL (Opsional)</label>
                        <div class="glass-input-group d-flex align-items-center border rounded-3 p-1">
                            <span class="px-2 text-muted small fw-bold" id="create_domain_prefix">
                                {{ parse_url(url('/'), PHP_URL_HOST) }}/
                            </span>
                            <input type="text" name="url" id="create_url" class="form-control bg-transparent border-0 py-1 small" placeholder="custom-alias" value="{{ old('url') }}">
                        </div>
                        <div id="create_alias_feedback" class="mt-1.5" style="font-size: 0.725rem;"></div>
                        <div class="form-text text-muted" style="font-size: 0.7rem;">Jika dikosongkan, alias acak akan dibuat otomatis.</div>
                    </div>

                    <!-- Project -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Proyek</label>
                        <select name="project_id" class="form-select bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 text-secondary small">
                            <option value="" selected>Tanpa Proyek</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-1">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="create_submit_btn" class="btn btn-primary btn-sm rounded-3 px-3 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Buat Tautan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Biolink Modal -->
<div class="modal fade" id="createBiolinkModal" tabindex="-1" aria-labelledby="createBiolinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border border-secondary border-opacity-10 rounded-3 p-2" style="background: var(--header-bg); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
            <div class="modal-header border-0 pb-1">
                <h6 class="modal-title fw-bold text-dark-custom" id="createBiolinkModalLabel">Buat Halaman Biolink</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('links.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="biolink">
                <div class="modal-body py-2">
                    <!-- Custom Domain -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Domain</label>
                        <select name="domain_id" id="create_bio_domain_id" class="form-select bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 text-secondary small">
                            <option value="0" selected>Domain Bawaan ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->host }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Alias Path -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Alias URL <span class="text-danger">*</span></label>
                        <div class="glass-input-group d-flex align-items-center border rounded-3 p-1">
                            <span class="px-2 text-muted small fw-bold" id="create_bio_domain_prefix">
                                {{ parse_url(url('/'), PHP_URL_HOST) }}/
                            </span>
                            <input type="text" name="url" id="create_bio_url" class="form-control bg-transparent border-0 py-1 small" placeholder="custom-alias" required value="{{ old('url') }}">
                        </div>
                        <div id="create_bio_alias_feedback" class="mt-1.5" style="font-size: 0.725rem;"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-1">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Buat Halaman Biolink</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Link Modal -->
<div class="modal fade" id="editLinkModal" tabindex="-1" aria-labelledby="editLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border border-secondary border-opacity-10 rounded-3 p-2" style="background: var(--header-bg); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
            <div class="modal-header border-0 pb-1">
                <h6 class="modal-title fw-bold text-dark-custom" id="editLinkModalLabel">Perbarui Tautan Pendek</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLinkForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body py-2">
                    <!-- Destination URL -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Target URL <span class="text-danger">*</span></label>
                        <div class="glass-input-group d-flex align-items-center border rounded-3 p-1">
                            <span class="px-2 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            </span>
                            <input type="url" name="location_url" id="edit_location_url" class="form-control bg-transparent border-0 py-1 small" placeholder="https://example.com/alamat-panjang" required>
                        </div>
                    </div>

                    <!-- Custom Domain -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Domain</label>
                        <select name="domain_id" id="edit_domain_id" class="form-select bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 text-secondary small">
                            <option value="0">Domain Bawaan ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->host }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Alias Path -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Alias URL <span class="text-danger">*</span></label>
                        <div class="glass-input-group d-flex align-items-center border rounded-3 p-1">
                            <span class="px-2 text-muted small fw-bold" id="edit_domain_prefix">
                                {{ parse_url(url('/'), PHP_URL_HOST) }}/
                            </span>
                            <input type="text" name="url" id="edit_url" class="form-control bg-transparent border-0 py-1 small" placeholder="custom-alias" required>
                        </div>
                        <div id="edit_alias_feedback" class="mt-1.5" style="font-size: 0.725rem;"></div>
                    </div>

                    <!-- Project -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Proyek</label>
                        <select name="project_id" id="edit_project_id" class="form-select bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 text-secondary small">
                            <option value="">Tanpa Proyek</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-1">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="edit_submit_btn" class="btn btn-primary btn-sm rounded-3 px-3 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteLinkForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

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
            background: getComputedStyle(document.documentElement).getPropertyValue('--header-bg').trim() || '#ffffff',
            color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim() || '#1e293b',
            backdrop: `
                rgba(0,0,0,0.4)
                url("")
                center center
                no-repeat
            `,
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
                    title: message,
                    background: 'transparent',
                    color: swalTheme.color
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
        const gradient = chartBg.createLinearGradient(0, 0, 0, 220);
        gradient.addColorStop(0, 'rgba(164, 229, 189, 0.35)');
        gradient.addColorStop(1, 'rgba(164, 229, 189, 0.02)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Pageviews',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#5ec489',
                    borderWidth: 2.2,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#5ec489',
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
            const url = btn.attr('data-url');
            const location = btn.attr('data-location');
            const project = btn.attr('data-project');
            const domain = btn.attr('data-domain');
            
            // Set form action
            document.getElementById('editLinkForm').action = `/link/${id}`;
            
            // Populate inputs
            document.getElementById('edit_location_url').value = location;
            document.getElementById('edit_url').value = url;
            
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
            
            function applyStatsState(hidden) {
                const newTitle = hidden ? 'Tampilkan Statistik' : 'Sembunyikan Statistik';
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
                    toggleStatsBtn.querySelector('svg').innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                } else {
                    statsWrapper.classList.remove('d-none');
                    toggleStatsBtn.querySelector('svg').innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
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

    /* Select2 glassmorphism styling overrides */
    .select2-container--default .select2-selection--single {
        background-color: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
        height: 42px !important;
        border-radius: 8px !important;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }
    [data-bs-theme="dark"] .select2-container--default .select2-selection--single {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--text-secondary) !important;
        font-size: 0.8rem !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: var(--text-secondary) transparent transparent transparent !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent var(--text-secondary) transparent !important;
    }
    .select2-dropdown {
        background-color: var(--header-bg) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid var(--glass-border) !important;
        border-radius: 8px !important;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        z-index: 9999;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--glass-border) !important;
        border-radius: 6px !important;
        color: var(--text-primary) !important;
        padding: 6px 10px !important;
        font-size: 0.8rem !important;
        outline: none !important;
    }
    .select2-results__option {
        font-size: 0.8rem !important;
        padding: 8px 12px !important;
        color: var(--text-secondary) !important;
        background-color: transparent !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary-color) !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: var(--primary-light) !important;
        color: var(--primary-color) !important;
    }
    .select2-container .select2-selection--single .select2-selection__placeholder {
        color: var(--text-secondary) !important;
    }

    /* Styling to match AltumCode row list spacing */
    table tbody tr:hover {
        background: rgba(148, 163, 184, 0.03) !important;
    }
    .hover-bg-light:hover {
        background: rgba(148, 163, 184, 0.1);
    }
    
    /* Pagination design corrections */
    .pagination {
        margin: 0;
    }
    .page-link {
        color: var(--text-secondary);
        background-color: transparent;
        border: 1px solid var(--glass-border);
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 500;
        border-radius: 6px !important;
        margin: 0 2px;
        transition: all 0.2s ease;
    }
    .page-link:hover {
        color: var(--primary-color);
        background-color: var(--primary-light);
        border-color: var(--glass-border);
    }
    .page-item.active .page-link {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
    }
    .page-item.disabled .page-link {
        background-color: transparent;
        color: #64748b;
        opacity: 0.5;
    }
    /* ─── SweetAlert2 Glassmorphic Styling ─── */
    .swal-glass-popup {
        border-radius: 16px !important;
        border: 1px solid var(--glass-border) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
    }
    [data-bs-theme="dark"] .swal-glass-popup {
        background: var(--header-bg) !important;
        color: var(--text-primary) !important;
    }
    .swal-glass-toast {
        background: transparent !important;
        border-radius: 12px !important;
        border: 1px solid var(--glass-border) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
        font-size: 0.875rem !important;
    }
    .swal-confirm-btn {
        background-color: var(--primary-color) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
    }
    .swal-confirm-btn:hover {
        opacity: 0.9 !important;
        transform: translateY(-1px) !important;
    }
    .swal-cancel-btn {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        margin-right: 8px !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
    }
    .swal-cancel-btn:hover {
        background-color: #e2e8f0 !important;
    }
    [data-bs-theme="dark"] .swal-cancel-btn {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border-color: #475569 !important;
    }
    [data-bs-theme="dark"] .swal-cancel-btn:hover {
        background-color: #475569 !important;
    }
    body:not(.swal2-toast-shown) .swal2-container.swal2-backdrop-show {
        background: rgba(255, 255, 255, 0.75) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
    }
    [data-bs-theme="dark"] body:not(.swal2-toast-shown) .swal2-container.swal2-backdrop-show {
        background: rgba(15, 23, 42, 0.85) !important;
    }
</style>
@endsection
