@extends('layouts.app')

@section('title', 'Manajemen Tautan & Bio Link')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Manajemen Tautan & Sumber Daya</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Biolink, Shortlink, WA Rotator, QR Code</span>
    </div>
</div>

@php
    // Extract selected multi-filters
    $selectedTypes = array_values(array_filter((array) request('types', request('type'))));
    $selectedDomains = array_values(array_filter((array) request('domain_ids', request('domain_id')), fn($v) => $v !== null && $v !== ''));
    $selectedUsers = array_values(array_filter((array) request('user_ids', request('user_id'))));
    $selectedStatuses = array_values(array_filter((array) request('statuses', request('status'))));
    $selectedVerified = array_values(array_filter((array) request('verified_statuses', request('verified'))));

    $activeFilterCount = count($selectedTypes)
        + count($selectedDomains)
        + count($selectedUsers)
        + count($selectedStatuses)
        + count($selectedVerified)
        + (request('sort') && request('sort') !== 'latest' ? 1 : 0);

    // Helper to generate remove URL for individual filter
    function makeFilterRemoveUrl($key, $valToRemove = null) {
        $queryParams = request()->query();
        unset($queryParams['page']);

        if ($valToRemove === null) {
            unset($queryParams[$key]);
            if ($key === 'type') unset($queryParams['types']);
            if ($key === 'domain_id') unset($queryParams['domain_ids']);
            if ($key === 'user_id') unset($queryParams['user_ids']);
            if ($key === 'status') unset($queryParams['statuses']);
            if ($key === 'verified') unset($queryParams['verified_statuses']);
        } else {
            foreach ([$key, $key . 's', $key . '_ids', $key . '_statuses'] as $k) {
                if (isset($queryParams[$k])) {
                    if (is_array($queryParams[$k])) {
                        $queryParams[$k] = array_values(array_filter($queryParams[$k], fn($v) => (string)$v !== (string)$valToRemove));
                        if (empty($queryParams[$k])) unset($queryParams[$k]);
                    } else {
                        if ((string)$queryParams[$k] === (string)$valToRemove) {
                            unset($queryParams[$k]);
                        }
                    }
                }
            }
        }
        return route('admin.links', $queryParams);
    }
@endphp

<!-- Statistics Cards -->
<div class="row g-4 mb-6">
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.links') }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ empty($selectedTypes) ? 'border-primary border-2' : '' }}">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="symbol symbol-40px symbol-circle bg-light-primary">
                    <span class="symbol-label"><i class="ki-outline ki-abstract-26 fs-2 text-primary"></i></span>
                </div>
                <div>
                    <div class="fs-4 fw-bolder text-gray-900">{{ number_format($totalAll) }}</div>
                    <div class="fs-8 fw-semibold text-muted">Semua Tautan</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.links', array_merge(request()->except(['page']), ['types' => ['biolink']])) }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ in_array('biolink', $selectedTypes) ? 'border-primary border-2' : '' }}">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="symbol symbol-40px symbol-circle bg-light-info">
                    <span class="symbol-label"><i class="ki-outline ki-profile-user fs-2 text-info"></i></span>
                </div>
                <div>
                    <div class="fs-4 fw-bolder text-gray-900">{{ number_format($totalBiolink) }}</div>
                    <div class="fs-8 fw-semibold text-muted">Bio Link</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.links', array_merge(request()->except(['page']), ['types' => ['link']])) }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ in_array('link', $selectedTypes) ? 'border-primary border-2' : '' }}">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="symbol symbol-40px symbol-circle bg-light-success">
                    <span class="symbol-label"><i class="ki-outline ki-link fs-2 text-success"></i></span>
                </div>
                <div>
                    <div class="fs-4 fw-bolder text-gray-900">{{ number_format($totalShortlink) }}</div>
                    <div class="fs-8 fw-semibold text-muted">Short Link</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.links', array_merge(request()->except(['page']), ['types' => ['warotator']])) }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ in_array('warotator', $selectedTypes) ? 'border-primary border-2' : '' }}">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="symbol symbol-40px symbol-circle bg-light-warning">
                    <span class="symbol-label"><i class="ki-outline ki-whatsapp fs-2 text-warning"></i></span>
                </div>
                <div>
                    <div class="fs-4 fw-bolder text-gray-900">{{ number_format($totalWaRotator) }}</div>
                    <div class="fs-8 fw-semibold text-muted">WA Rotator</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.links', array_merge(request()->except(['page']), ['types' => ['qrcode']])) }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ in_array('qrcode', $selectedTypes) ? 'border-primary border-2' : '' }}">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="symbol symbol-40px symbol-circle bg-light-danger">
                    <span class="symbol-label"><i class="ki-outline ki-scan-barcode fs-2 text-danger"></i></span>
                </div>
                <div>
                    <div class="fs-4 fw-bolder text-gray-900">{{ number_format($totalQrCode) }}</div>
                    <div class="fs-8 fw-semibold text-muted">QR Code</div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="card card-flush shadow-sm border-0 mb-6">
    <!-- Card Header: Per Page, Filter Button, Search on Right -->
    <div class="card-header align-items-center py-5 gap-3 flex-wrap">
        <!-- Left: Per Page & Filter Button -->
        <div class="d-flex align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <label class="text-muted fs-8 fw-semibold text-nowrap d-none d-sm-inline">Tampil:</label>
                <select name="per_page" id="perPageSelect" class="form-select form-select-sm form-select-solid w-80px">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <button type="button" class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-2" id="btn_open_filter_modal" data-bs-toggle="modal" data-bs-target="#modal_filter_links">
                <i class="ki-outline ki-filter fs-4"></i>
                Filter
                @if($activeFilterCount > 0)
                    <span class="badge badge-primary badge-circle fs-8" style="width: 18px; height: 18px; line-height: 18px;">{{ $activeFilterCount }}</span>
                @endif
            </button>

            @if($activeFilterCount > 0 || request('search'))
                <a href="{{ route('admin.links') }}" class="btn btn-sm btn-light-danger fw-bold d-flex align-items-center gap-1" title="Reset Semua Filter">
                    <i class="ki-outline ki-cross-circle fs-4"></i>
                    <span class="d-none d-sm-inline">Reset</span>
                </a>
            @endif
        </div>

        <!-- Right: Search Form on the far right -->
        <div class="d-flex align-items-center gap-2 ms-auto">
            <form method="GET" action="{{ route('admin.links') }}" id="searchForm" class="d-flex align-items-center">
                <!-- Keep existing query params including arrays except search & page -->
                @foreach(request()->except(['search', 'page']) as $k => $v)
                    @if(is_array($v))
                        @foreach($v as $subV)
                            <input type="hidden" name="{{ $k }}[]" value="{{ $subV }}" />
                        @endforeach
                    @elseif($v !== null && $v !== '')
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                    @endif
                @endforeach

                <div class="d-flex align-items-center position-relative">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-3 text-gray-500"></i>
                    <input type="text" name="search" id="searchInput" class="form-control form-control-sm form-control-solid ps-10 w-225px w-md-275px" placeholder="Cari judul, slug, tujuan, user..." value="{{ request('search') }}" />
                </div>
            </form>
        </div>
    </div>

    <!-- Active Filters Bar -->
    @php
        $activePills = [];
        if (request('search')) {
            $activePills[] = [
                'label' => 'Kata Kunci: "' . request('search') . '"',
                'url' => makeFilterRemoveUrl('search')
            ];
        }

        $typeNames = [
            'biolink' => 'Bio Link',
            'link' => 'Short Link',
            'warotator' => 'WA Rotator',
            'qrcode' => 'QR Code'
        ];
        foreach ($selectedTypes as $typeVal) {
            $activePills[] = [
                'label' => 'Tipe: ' . ($typeNames[$typeVal] ?? $typeVal),
                'url' => makeFilterRemoveUrl('types', $typeVal)
            ];
        }

        foreach ($selectedDomains as $domId) {
            if ($domId === '0' || $domId === 'default') {
                $domLabel = 'Domain Utama (Default)';
            } else {
                $domObj = $domains->firstWhere('id', $domId);
                $domLabel = $domObj ? $domObj->host : 'Domain #' . $domId;
            }
            $activePills[] = [
                'label' => 'Domain: ' . $domLabel,
                'url' => makeFilterRemoveUrl('domain_ids', $domId)
            ];
        }

        foreach ($selectedUsers as $usrId) {
            $usrObj = $users->firstWhere('id', $usrId);
            $activePills[] = [
                'label' => 'User: ' . ($usrObj ? $usrObj->name : '#' . $usrId),
                'url' => makeFilterRemoveUrl('user_ids', $usrId)
            ];
        }

        foreach ($selectedStatuses as $stVal) {
            $activePills[] = [
                'label' => 'Status: ' . ($stVal === 'active' || $stVal === '1' ? 'Aktif' : 'Nonaktif'),
                'url' => makeFilterRemoveUrl('statuses', $stVal)
            ];
        }

        foreach ($selectedVerified as $vrVal) {
            $activePills[] = [
                'label' => 'Verifikasi: ' . ($vrVal === 'yes' || $vrVal === '1' ? 'Terverifikasi' : 'Belum'),
                'url' => makeFilterRemoveUrl('verified_statuses', $vrVal)
            ];
        }

        if (request('sort') && request('sort') !== 'latest') {
            $sortNames = [
                'oldest' => 'Terlama',
                'clicks_desc' => 'Klik Tertinggi',
                'clicks_asc' => 'Klik Terendah',
                'url_asc' => 'Slug A-Z',
                'url_desc' => 'Slug Z-A'
            ];
            $activePills[] = [
                'label' => 'Urutan: ' . ($sortNames[request('sort')] ?? request('sort')),
                'url' => makeFilterRemoveUrl('sort')
            ];
        }
    @endphp

    @if(!empty($activePills))
        <div class="card-body pt-0 pb-3">
            <div class="d-flex flex-wrap align-items-center gap-2 p-3 bg-light-primary rounded-3 border border-primary border-dashed">
                <a href="{{ route('admin.links') }}" class="btn btn-xs btn-danger fw-bold py-1 px-3 d-flex align-items-center gap-1">
                    <i class="ki-outline ki-trash fs-8"></i> Reset Filter
                </a>
                <span class="text-muted fs-8 fw-semibold ms-2">Filter Aktif ({{ count($activePills) }}):</span>
                @foreach($activePills as $pill)
                    <span class="badge badge-white text-gray-800 border shadow-xs d-inline-flex align-items-center gap-1 py-1 px-2 fs-8">
                        {{ $pill['label'] }}
                        <a href="{{ $pill['url'] }}" class="text-hover-danger text-muted ms-1" title="Hapus Filter">
                            <i class="ki-outline ki-cross fs-8"></i>
                        </a>
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card-body pt-0">
        <!-- Bulk Actions Toolbar -->
        <div id="bulk_actions_toolbar" class="d-none d-flex flex-wrap align-items-center justify-content-between gap-2 p-3 mb-4 bg-light-warning rounded-3 border border-warning">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span class="fs-7 fw-bolder text-gray-900 me-2">
                    <i class="ki-outline ki-check-square fs-4 text-warning me-1"></i>
                    <span id="selected_count">0</span> tautan dipilih
                </span>
                <button type="button" class="btn btn-xs btn-light-warning text-warning-emphasis fw-bold d-flex align-items-center gap-1 btn-bulk-action" data-action="disable">
                    <i class="ki-outline ki-cross-circle fs-7"></i> Nonaktifkan
                </button>
                <button type="button" class="btn btn-xs btn-light-success text-success fw-bold d-flex align-items-center gap-1 btn-bulk-action" data-action="enable">
                    <i class="ki-outline ki-check-circle fs-7"></i> Aktifkan
                </button>
                <button type="button" class="btn btn-xs btn-danger fw-bold d-flex align-items-center gap-1 btn-bulk-action" data-action="delete">
                    <i class="ki-outline ki-trash fs-7"></i> Hapus Terpilih
                </button>
            </div>
            <button type="button" class="btn btn-xs btn-link text-muted p-0 text-decoration-none" id="btn_cancel_bulk">
                Batal Pilih
            </button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-7 gy-4 mb-0" id="kt_table_admin_links">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-1">
                                <input class="form-check-input" type="checkbox" id="selectAllLinks" title="Pilih Semua" />
                            </div>
                        </th>
                        <th class="min-w-220px">Judul & Tautan</th>
                        <th class="min-w-160px">Tujuan / Konten</th>
                        <th class="min-w-140px">Pemilik</th>
                        <th class="min-w-120px">Domain</th>
                        <th class="min-w-80px text-center">Klik</th>
                        <th class="min-w-90px text-center">Status</th>
                        <th class="min-w-90px text-center">Verifikasi</th>
                        <th class="min-w-100px">Dibuat</th>
                        <th class="text-end min-w-130px pe-3 text-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    @forelse($links as $link)
                        @php
                            $fullPublicUrl = $link->full_url;
                            $customTitle = $link->custom_title;
                            $displayTitle = $link->display_title;
                        @endphp
                        <tr id="link-row-{{ $link->id }}">
                            <!-- Bulk Checkbox -->
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input link-checkbox" type="checkbox" value="{{ $link->id }}" data-slug="{{ $link->url }}" />
                                </div>
                            </td>

                            <!-- Judul & Slug / Live URL -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px symbol-circle me-3 flex-shrink-0">
                                        @if($link->type === 'biolink')
                                            <span class="symbol-label bg-light-primary" title="Bio Link">
                                                <i class="ki-outline ki-profile-user fs-3 text-primary"></i>
                                            </span>
                                        @elseif($link->type === 'warotator')
                                            <span class="symbol-label bg-light-success" title="WhatsApp Rotator">
                                                <i class="ki-outline ki-whatsapp fs-3 text-success"></i>
                                            </span>
                                        @elseif($link->type === 'qrcode')
                                            <span class="symbol-label bg-light-warning" title="QR Code">
                                                <i class="ki-outline ki-scan-barcode fs-3 text-warning"></i>
                                            </span>
                                        @else
                                            <span class="symbol-label bg-light-info" title="Short Link">
                                                <i class="ki-outline ki-link fs-3 text-info"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column">
                                        <!-- Primary: Judul Link -->
                                        <div class="d-flex align-items-center gap-1 mb-1">
                                            <span class="fw-bolder text-gray-900 fs-6 text-truncate" id="link-title-{{ $link->id }}" style="max-width: 220px;" title="{{ $displayTitle }}">
                                                {{ $displayTitle }}
                                            </span>
                                            <span id="verified-badge-{{ $link->id }}" class="badge-verify-container d-inline-flex {{ $link->is_verified ? '' : 'd-none' }}" title="Terverifikasi">
                                                <i class="ki-outline ki-verify fs-6 text-primary"></i>
                                            </span>
                                        </div>

                                        <!-- Secondary: Slug & Live Link with Copy -->
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge badge-light-secondary text-gray-700 fs-8 fw-bold" id="link-slug-badge-{{ $link->id }}">/{{ $link->url }}</span>
                                            <button type="button" class="btn btn-icon btn-sm btn-light-secondary py-0 px-1 btn-copy-url" data-url="{{ $fullPublicUrl }}" title="Salin Tautan Lengkap" style="width: 20px; height: 20px;">
                                                <i class="ki-outline ki-copy fs-8 text-muted"></i>
                                            </button>
                                            <a href="{{ $fullPublicUrl }}" target="_blank" id="link-live-url-{{ $link->id }}" class="text-muted text-hover-primary fs-8 text-truncate d-inline-block ms-1" style="max-width: 140px;" title="{{ $fullPublicUrl }}">
                                                {{ $fullPublicUrl }}
                                                <i class="ki-outline ki-exit-right-corner fs-9 text-muted"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Destination / Target URL / Type Details -->
                            <td>
                                @if($link->type === 'biolink')
                                    <div class="d-flex flex-column">
                                        <span class="badge badge-light-primary fw-bold fs-8 align-self-start mb-1">
                                            <i class="ki-outline ki-profile-user fs-8 me-1 text-primary"></i>Bio Link
                                        </span>
                                        <span class="text-muted fs-8">{{ $link->biolink_blocks_count ?? 0 }} Blok Konten</span>
                                    </div>
                                @elseif($link->type === 'warotator')
                                    <div class="d-flex flex-column">
                                        <span class="badge badge-light-success fw-bold fs-8 align-self-start mb-1">
                                            <i class="ki-outline ki-whatsapp fs-8 me-1 text-success"></i>WA Rotator
                                        </span>
                                        <span class="text-muted fs-8">Rotator CS WhatsApp</span>
                                    </div>
                                @elseif($link->type === 'qrcode')
                                    <div class="d-flex flex-column">
                                        <span class="badge badge-light-warning fw-bold fs-8 align-self-start mb-1">
                                            <i class="ki-outline ki-scan-barcode fs-8 me-1 text-warning"></i>QR Code
                                        </span>
                                        <span class="text-muted fs-8 text-truncate" style="max-width: 200px;" title="{{ $link->location_url }}">{{ $link->location_url ?: 'QR Code Link' }}</span>
                                    </div>
                                @else
                                    <div class="d-flex flex-column">
                                        <span class="badge badge-light-info fw-bold fs-8 align-self-start mb-1">
                                            <i class="ki-outline ki-link fs-8 me-1 text-info"></i>Short Link
                                        </span>
                                        <a href="{{ $link->location_url }}" target="_blank" class="text-gray-700 text-hover-primary fs-8 text-truncate d-inline-block" style="max-width: 220px;" title="{{ $link->location_url }}">
                                            {{ $link->location_url }}
                                        </a>
                                    </div>
                                @endif
                            </td>

                            <!-- Owner / User -->
                            <td>
                                @if($link->user)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-gray-900 fs-7">{{ $link->user->name }}</span>
                                        <span class="text-muted fs-8">{{ $link->user->email }}</span>
                                    </div>
                                @else
                                    <span class="badge badge-light-dark fs-8">System / Deleted</span>
                                @endif
                            </td>

                            <!-- Domain -->
                            <td>
                                @if($link->domain)
                                    <span class="badge badge-light-primary fw-semibold fs-8" title="Custom Domain: {{ $link->domain->host }}">
                                        {{ $link->domain->host }}
                                    </span>
                                @else
                                    <span class="badge badge-light-secondary fw-semibold fs-8" title="Domain Utama Platform">
                                        Default Domain
                                    </span>
                                @endif
                            </td>

                            <!-- Clicks -->
                            <td class="text-center">
                                <span class="badge badge-light fw-bolder text-gray-800 fs-7">
                                    {{ number_format($link->clicks) }}
                                </span>
                            </td>

                            <!-- Status (Active / Inactive) -->
                            <td class="text-center" id="status-col-{{ $link->id }}">
                                @if($link->is_enabled)
                                    <span class="badge badge-light-success fw-bold fs-8">Aktif</span>
                                @else
                                    <span class="badge badge-light-danger fw-bold fs-8">Nonaktif</span>
                                @endif
                            </td>

                            <!-- Verified Badge & Toggle -->
                            <td class="text-center">
                                <button type="button" class="btn btn-xs btn-verify-toggle {{ $link->is_verified ? 'btn-light-danger' : 'btn-light-primary' }} fw-bold fs-8 py-1 px-2" data-id="{{ $link->id }}" title="{{ $link->is_verified ? 'Klik untuk cabut verifikasi' : 'Klik untuk verifikasi' }}">
                                    <i class="ki-outline {{ $link->is_verified ? 'ki-cross-circle text-danger' : 'ki-verify text-primary' }} fs-8 me-1"></i>
                                    {{ $link->is_verified ? 'Unverify' : 'Verify' }}
                                </button>
                            </td>

                            <!-- Created Date -->
                            <td class="text-muted fs-8">
                                {{ $link->created_at ? $link->created_at->format('d M Y') : '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="text-end pe-3 text-nowrap">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                    <!-- Edit Link & Title -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-primary btn-edit-link" 
                                        data-id="{{ $link->id }}"
                                        data-title="{{ $customTitle ?? $displayTitle }}"
                                        data-url="{{ $link->url }}"
                                        data-domain-id="{{ $link->domain_id ?? 0 }}"
                                        data-location-url="{{ $link->location_url }}"
                                        data-type="{{ $link->type }}"
                                        data-update-url="{{ route('admin.links.update', $link->id) }}"
                                        title="Edit Judul & Tautan">
                                        <i class="ki-outline ki-pencil fs-5"></i>
                                    </button>

                                    <!-- View/Open Link -->
                                    <a href="{{ $fullPublicUrl }}" target="_blank" class="btn btn-icon btn-sm btn-light-info" title="Buka Tautan">
                                        <i class="ki-outline ki-exit-right-corner fs-5"></i>
                                    </a>

                                    <!-- Toggle Active / Inactive -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-{{ $link->is_enabled ? 'warning' : 'success' }} btn-toggle-status" 
                                        id="btn-status-{{ $link->id }}"
                                        data-id="{{ $link->id }}" 
                                        data-url="{{ route('admin.links.toggle-status', $link->id) }}"
                                        title="{{ $link->is_enabled ? 'Nonaktifkan Tautan' : 'Aktifkan Tautan' }}">
                                        <i class="ki-outline {{ $link->is_enabled ? 'ki-cross' : 'ki-check' }} fs-4"></i>
                                    </button>

                                    <!-- Delete Link -->
                                    <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-delete-link" 
                                        data-id="{{ $link->id }}" 
                                        data-slug="{{ $link->url }}" 
                                        data-url="{{ route('admin.links.destroy', $link->id) }}"
                                        title="Hapus Tautan">
                                        <i class="ki-outline ki-trash fs-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-10 text-muted">
                                <i class="ki-outline ki-disconnect fs-4x text-muted mb-3"></i>
                                <p class="fs-6 fw-semibold mb-1">Tidak ada data tautan ditemukan.</p>
                                <p class="fs-8 text-muted mb-0">Coba ubah kata kunci pencarian atau reset filter Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        @if($links->hasPages())
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-6 pt-4 border-top">
                <div class="text-muted fs-7">
                    Menampilkan {{ $links->firstItem() ?? 0 }} sampai {{ $links->lastItem() ?? 0 }} dari total {{ $links->total() }} tautan
                </div>
                <div>
                    {{ $links->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Edit Link (Judul, Slug, Domain, Target) -->
<div class="modal fade" id="modal_edit_link" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-550px">
        <div class="modal-content">
            <form id="editLinkForm">
                <input type="hidden" id="edit_link_id" />
                <input type="hidden" id="edit_link_update_url" />

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <div>
                        <h2 class="fw-bold fs-4 mb-0">Edit Judul & Tautan</h2>
                        <span class="text-muted fs-8">Ubah judul khusus, alias/slug, dan domain tautan</span>
                    </div>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </div>
                </div>

                <div class="modal-body py-5 px-lg-8">
                    <!-- Judul Tautan -->
                    <div class="fv-row mb-5">
                        <label class="required fs-7 fw-bolder mb-2">Judul Tautan (Title):</label>
                        <input type="text" name="title" id="edit_link_title" class="form-control form-control-solid form-control-sm" placeholder="Contoh: Promo Diskon 50%, My Bio Link, CS Sales 1..." required />
                        <div class="text-muted fs-9 mt-1">Judul yang akan ditampilkan sebagai nama utama link ini.</div>
                    </div>

                    <!-- Slug / Alias -->
                    <div class="fv-row mb-5">
                        <label class="required fs-7 fw-bolder mb-2">Slug / Alias URL:</label>
                        <div class="input-group input-group-sm input-group-solid">
                            <span class="input-group-text fw-semibold" id="edit_domain_prefix">/</span>
                            <input type="text" name="url" id="edit_link_slug" class="form-control" placeholder="my-custom-slug" required />
                        </div>
                        <div class="text-muted fs-9 mt-1">Hanya gunakan huruf, angka, tanda minus (-), dan underscore (_).</div>
                    </div>

                    <!-- Domain -->
                    <div class="fv-row mb-5">
                        <label class="fs-7 fw-bolder mb-2">Domain Terhubung:</label>
                        <select name="domain_id" id="edit_link_domain_id" class="form-select form-select-solid form-select-sm">
                            <option value="0">Domain Utama Platform (Default)</option>
                            @foreach($domains as $dom)
                                <option value="{{ $dom->id }}">{{ $dom->host }} ({{ $dom->type == 1 ? 'System' : 'Custom' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Destination Target (for Short Links) -->
                    <div class="fv-row mb-3" id="edit_location_url_container">
                        <label class="fs-7 fw-bolder mb-2">URL Target Asli (Destination URL):</label>
                        <input type="url" name="location_url" id="edit_link_location_url" class="form-control form-control-solid form-control-sm" placeholder="https://..." />
                    </div>
                </div>

                <div class="modal-footer justify-content-between pt-0 border-0">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold" id="btn_save_edit_link">
                        <span class="indicator-label">Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Multi-Filter Links -->
<div class="modal fade" id="modal_filter_links" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <form method="GET" action="{{ route('admin.links') }}" id="filterModalForm">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}" />
                @endif

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <div>
                        <h2 class="fw-bold fs-4 mb-0">Multi-Filter Tautan & Sumber Daya</h2>
                        <span class="text-muted fs-8">Pilih satu atau beberapa kriteria filter di bawah</span>
                    </div>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </div>
                </div>

                <div class="modal-body py-5 px-lg-8">
                    <!-- 1. Tipe Resource (Multi-Select Checkboxes) -->
                    <div class="fv-row mb-5">
                        <label class="fs-7 fw-bolder mb-2 d-block">Tipe Resource (Multi-Pilih):</label>
                        <div class="row g-2">
                            <div class="col-6 col-sm-3">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex align-items-center gap-2 p-2 w-100 {{ in_array('biolink', $selectedTypes) ? 'active bg-light-primary border-primary' : '' }}">
                                    <input type="checkbox" name="types[]" value="biolink" class="form-check-input form-check-input-sm" {{ in_array('biolink', $selectedTypes) ? 'checked' : '' }} />
                                    <span class="fs-8 fw-semibold">Bio Link</span>
                                </label>
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-success d-flex align-items-center gap-2 p-2 w-100 {{ in_array('link', $selectedTypes) ? 'active bg-light-success border-success' : '' }}">
                                    <input type="checkbox" name="types[]" value="link" class="form-check-input form-check-input-sm" {{ in_array('link', $selectedTypes) ? 'checked' : '' }} />
                                    <span class="fs-8 fw-semibold">Short Link</span>
                                </label>
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-warning d-flex align-items-center gap-2 p-2 w-100 {{ in_array('warotator', $selectedTypes) ? 'active bg-light-warning border-warning' : '' }}">
                                    <input type="checkbox" name="types[]" value="warotator" class="form-check-input form-check-input-sm" {{ in_array('warotator', $selectedTypes) ? 'checked' : '' }} />
                                    <span class="fs-8 fw-semibold">WA Rotator</span>
                                </label>
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-danger d-flex align-items-center gap-2 p-2 w-100 {{ in_array('qrcode', $selectedTypes) ? 'active bg-light-danger border-danger' : '' }}">
                                    <input type="checkbox" name="types[]" value="qrcode" class="form-check-input form-check-input-sm" {{ in_array('qrcode', $selectedTypes) ? 'checked' : '' }} />
                                    <span class="fs-8 fw-semibold">QR Code</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Domain Terhubung (Multi-Select) -->
                    <div class="fv-row mb-5">
                        <label class="fs-7 fw-bolder mb-2 d-block">Domain Terhubung (Multi-Pilih):</label>
                        <select name="domain_ids[]" id="filter_domain_ids" class="form-select form-select-solid form-select-sm" data-control="select2" data-placeholder="Pilih satu atau beberapa domain..." data-close-on-select="false" multiple="multiple" data-dropdown-parent="#modal_filter_links">
                            <option value="0" {{ in_array('0', $selectedDomains) ? 'selected' : '' }}>Domain Utama Platform (Default)</option>
                            @foreach($domains as $dom)
                                <option value="{{ $dom->id }}" {{ in_array((string)$dom->id, $selectedDomains) ? 'selected' : '' }}>{{ $dom->host }} ({{ $dom->type == 1 ? 'System' : 'Custom' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Pemilik / Pengguna (Multi-Select) -->
                    <div class="fv-row mb-5">
                        <label class="fs-7 fw-bolder mb-2 d-block">Pemilik / Pengguna (Multi-Pilih):</label>
                        <select name="user_ids[]" id="filter_user_ids" class="form-select form-select-solid form-select-sm" data-control="select2" data-placeholder="Pilih satu atau beberapa pengguna..." data-close-on-select="false" multiple="multiple" data-dropdown-parent="#modal_filter_links">
                            @foreach($users as $usr)
                                <option value="{{ $usr->id }}" {{ in_array((string)$usr->id, $selectedUsers) ? 'selected' : '' }}>{{ $usr->name }} ({{ $usr->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-4 mb-5">
                        <!-- 4. Status Link (Multi-Select) -->
                        <div class="col-6">
                            <label class="fs-7 fw-bolder mb-2 d-block">Status Link (Multi-Pilih):</label>
                            <div class="d-flex flex-column gap-2">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-success d-flex align-items-center gap-2 p-2 w-100 {{ in_array('active', $selectedStatuses) || in_array('1', $selectedStatuses) ? 'active bg-light-success border-success' : '' }}">
                                    <input type="checkbox" name="statuses[]" value="active" class="form-check-input form-check-input-sm" {{ in_array('active', $selectedStatuses) || in_array('1', $selectedStatuses) ? 'checked' : '' }} />
                                    <span class="fs-8 fw-semibold">Aktif</span>
                                </label>
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-danger d-flex align-items-center gap-2 p-2 w-100 {{ in_array('inactive', $selectedStatuses) || in_array('0', $selectedStatuses) ? 'active bg-light-danger border-danger' : '' }}">
                                    <input type="checkbox" name="statuses[]" value="inactive" class="form-check-input form-check-input-sm" {{ in_array('inactive', $selectedStatuses) || in_array('0', $selectedStatuses) ? 'checked' : '' }} />
                                    <span class="fs-8 fw-semibold">Nonaktif</span>
                                </label>
                            </div>
                        </div>

                        <!-- 5. Status Verifikasi (Multi-Select) -->
                        <div class="col-6">
                            <label class="fs-7 fw-bolder mb-2 d-block">Verifikasi (Multi-Pilih):</label>
                            <div class="d-flex flex-column gap-2">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex align-items-center gap-2 p-2 w-100 {{ in_array('yes', $selectedVerified) || in_array('1', $selectedVerified) ? 'active bg-light-primary border-primary' : '' }}">
                                    <input type="checkbox" name="verified_statuses[]" value="yes" class="form-check-input form-check-input-sm" {{ in_array('yes', $selectedVerified) || in_array('1', $selectedVerified) ? 'checked' : '' }} />
                                    <span class="fs-8 fw-semibold">Terverifikasi</span>
                                </label>
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-secondary d-flex align-items-center gap-2 p-2 w-100 {{ in_array('no', $selectedVerified) || in_array('0', $selectedVerified) ? 'active bg-light-secondary border-secondary' : '' }}">
                                    <input type="checkbox" name="verified_statuses[]" value="no" class="form-check-input form-check-input-sm" {{ in_array('no', $selectedVerified) || in_array('0', $selectedVerified) ? 'checked' : '' }} />
                                    <span class="fs-8 fw-semibold">Belum Verifikasi</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-3">
                        <!-- Urutan (Sort) -->
                        <div class="col-6">
                            <label class="fs-7 fw-semibold mb-2">Urutan Tampilan</label>
                            <select name="sort" class="form-select form-select-solid form-select-sm">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort', 'oldest') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="clicks_desc" {{ request('sort') === 'clicks_desc' ? 'selected' : '' }}>Klik Tertinggi</option>
                                <option value="clicks_asc" {{ request('sort') === 'clicks_asc' ? 'selected' : '' }}>Klik Terendah</option>
                                <option value="url_asc" {{ request('sort') === 'url_asc' ? 'selected' : '' }}>Slug (A-Z)</option>
                                <option value="url_desc" {{ request('sort') === 'url_desc' ? 'selected' : '' }}>Slug (Z-A)</option>
                            </select>
                        </div>

                        <!-- Per Page -->
                        <div class="col-6">
                            <label class="fs-7 fw-semibold mb-2">Per Halaman</label>
                            <select name="per_page" class="form-select form-select-solid form-select-sm">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                                <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 Baris</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between pt-0 border-0">
                    <a href="{{ route('admin.links') }}" class="btn btn-sm btn-light">Reset Semua</a>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-sm btn-primary fw-bold">Terapkan Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ----------------------------------------------------
    // BULK ACTIONS HANDLER
    // ----------------------------------------------------
    const selectAllLinks = document.getElementById('selectAllLinks');
    const bulkToolbar = document.getElementById('bulk_actions_toolbar');
    const selectedCountSpan = document.getElementById('selected_count');
    const btnCancelBulk = document.getElementById('btn_cancel_bulk');

    function getSelectedCheckboxes() {
        return Array.from(document.querySelectorAll('.link-checkbox:checked'));
    }

    function updateBulkToolbar() {
        const selected = getSelectedCheckboxes();
        const totalCheckboxes = document.querySelectorAll('.link-checkbox').length;

        if (selectedCountSpan) {
            selectedCountSpan.textContent = selected.length;
        }

        if (selected.length > 0) {
            bulkToolbar.classList.remove('d-none');
        } else {
            bulkToolbar.classList.add('d-none');
        }

        if (selectAllLinks) {
            selectAllLinks.checked = totalCheckboxes > 0 && selected.length === totalCheckboxes;
            selectAllLinks.indeterminate = selected.length > 0 && selected.length < totalCheckboxes;
        }
    }

    if (selectAllLinks) {
        selectAllLinks.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.link-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateBulkToolbar();
        });
    }

    document.querySelectorAll('.link-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkToolbar);
    });

    if (btnCancelBulk) {
        btnCancelBulk.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.link-checkbox').forEach(cb => cb.checked = false);
            if (selectAllLinks) selectAllLinks.checked = false;
            updateBulkToolbar();
        });
    }

    // Execute Bulk Action
    document.querySelectorAll('.btn-bulk-action').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.getAttribute('data-action');
            const selected = getSelectedCheckboxes();
            const ids = selected.map(cb => cb.value);

            if (ids.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Pilih minimal satu tautan terlebih dahulu.'
                    });
                } else {
                    alert('Pilih minimal satu tautan.');
                }
                return;
            }

            let confirmTitle = 'Konfirmasi Aksi';
            let confirmText = `Jalankan aksi pada ${ids.length} tautan terpilih?`;
            let confirmBtnText = 'Ya, Lanjutkan';
            let confirmBtnClass = 'btn btn-primary';

            if (action === 'delete') {
                confirmTitle = 'Hapus Tautan Terpilih?';
                confirmText = `Apakah Anda yakin ingin menghapus ${ids.length} tautan terpilih secara permanen?`;
                confirmBtnText = 'Ya, Hapus Semua!';
                confirmBtnClass = 'btn btn-danger';
            } else if (action === 'disable') {
                confirmTitle = 'Nonaktifkan Tautan Terpilih?';
                confirmText = `Nonaktifkan status ${ids.length} tautan terpilih?`;
                confirmBtnText = 'Ya, Nonaktifkan!';
                confirmBtnClass = 'btn btn-warning';
            } else if (action === 'enable') {
                confirmTitle = 'Aktifkan Tautan Terpilih?';
                confirmText = `Aktifkan status ${ids.length} tautan terpilih?`;
                confirmBtnText = 'Ya, Aktifkan!';
                confirmBtnClass = 'btn btn-success';
            }

            const executeBulk = () => {
                fetch("{{ route('admin.links.bulk-action') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ action: action, ids: ids })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            alert(res.message);
                            window.location.reload();
                        }
                    } else {
                        alert(res.message || 'Gagal memproses aksi massal.');
                    }
                })
                .catch(err => {
                    alert('Terjadi kesalahan koneksi.');
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: confirmTitle,
                    text: confirmText,
                    icon: action === 'delete' ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonText: confirmBtnText,
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: confirmBtnClass,
                        cancelButton: 'btn btn-light'
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        executeBulk();
                    }
                });
            } else {
                if (confirm(confirmText)) {
                    executeBulk();
                }
            }
        });
    });

    // ----------------------------------------------------
    // EDIT LINK & TITLE MODAL HANDLER
    // ----------------------------------------------------
    document.querySelectorAll('.btn-edit-link').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const slug = this.getAttribute('data-url');
            const domainId = this.getAttribute('data-domain-id') || '0';
            const locationUrl = this.getAttribute('data-location-url') || '';
            const type = this.getAttribute('data-type');
            const updateUrl = this.getAttribute('data-update-url');

            document.getElementById('edit_link_id').value = id;
            document.getElementById('edit_link_update_url').value = updateUrl;
            document.getElementById('edit_link_title').value = title || '';
            document.getElementById('edit_link_slug').value = slug || '';
            document.getElementById('edit_link_domain_id').value = domainId;
            document.getElementById('edit_link_location_url').value = locationUrl;

            const locContainer = document.getElementById('edit_location_url_container');
            if (locContainer) {
                if (type === 'link') {
                    locContainer.classList.remove('d-none');
                } else {
                    locContainer.classList.add('d-none');
                }
            }

            const modalEl = document.getElementById('modal_edit_link');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });
    });

    const editLinkForm = document.getElementById('editLinkForm');
    if (editLinkForm) {
        editLinkForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const updateUrl = document.getElementById('edit_link_update_url').value;
            const submitBtn = document.getElementById('btn_save_edit_link');
            const title = document.getElementById('edit_link_title').value;
            const slug = document.getElementById('edit_link_slug').value;
            const domainId = document.getElementById('edit_link_domain_id').value;
            const locationUrl = document.getElementById('edit_link_location_url').value;

            submitBtn.disabled = true;

            fetch(updateUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: title,
                    url: slug,
                    domain_id: domainId,
                    location_url: locationUrl
                })
            })
            .then(res => res.json())
            .then(res => {
                submitBtn.disabled = false;
                if (res.success) {
                    const modalEl = document.getElementById('modal_edit_link');
                    if (modalEl) {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(res.message);
                        window.location.reload();
                    }
                } else {
                    alert(res.message || 'Gagal menyimpan perubahan.');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                alert('Terjadi kesalahan saat menyimpan data.');
            });
        });
    }

    // ----------------------------------------------------
    // FILTER MODAL HANDLER
    // ----------------------------------------------------
    const btnOpenFilterModal = document.getElementById('btn_open_filter_modal');
    if (btnOpenFilterModal) {
        btnOpenFilterModal.addEventListener('click', function(e) {
            e.preventDefault();
            const modalEl = document.getElementById('modal_filter_links');
            if (modalEl) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $('#modal_filter_links').modal('show');
                }
            }
        });
    }

    // Toggle active state styling on checkbox label clicks inside modal
    document.querySelectorAll('#modal_filter_links label.btn input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', function() {
            const parentLabel = this.closest('label');
            if (!parentLabel) return;
            if (this.checked) {
                parentLabel.classList.add('active');
            } else {
                parentLabel.classList.remove('active');
            }
        });
    });

    // Re-initialize Select2 inside modal when shown
    const modalFilterEl = document.getElementById('modal_filter_links');
    if (modalFilterEl && typeof $ !== 'undefined' && $.fn.select2) {
        modalFilterEl.addEventListener('shown.bs.modal', function() {
            $('#filter_domain_ids, #filter_user_ids').select2({
                dropdownParent: $('#modal_filter_links'),
                width: '100%'
            });
        });
    }

    // Clean submission of multi-filter form
    const filterModalForm = document.getElementById('filterModalForm');
    if (filterModalForm) {
        filterModalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams();

            for (const [key, value] of formData.entries()) {
                if (value !== '' && value !== null) {
                    params.append(key, value);
                }
            }

            window.location.href = this.action + (params.toString() ? '?' + params.toString() : '');
        });
    }

    // Per-page change submit
    const perPageSelect = document.getElementById('perPageSelect');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }

    // Copy URL to Clipboard
    document.querySelectorAll('.btn-copy-url').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            if (!url) return;

            navigator.clipboard.writeText(url).then(() => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Tautan disalin ke clipboard!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    alert('Tautan disalin: ' + url);
                }
            });
        });
    });

    // Toggle Verification Handler
    document.querySelectorAll('.btn-verify-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const self = this;
            self.disabled = true;

            fetch(`/admin/links/${id}/toggle-verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                self.disabled = false;
                if (res.success) {
                    const badge = document.getElementById(`verified-badge-${id}`);
                    if (res.is_verified) {
                        self.className = 'btn btn-xs btn-verify-toggle btn-light-danger fw-bold fs-8 py-1 px-2';
                        self.innerHTML = '<i class="ki-outline ki-cross-circle text-danger fs-8 me-1"></i>Unverify';
                        if (badge) badge.classList.remove('d-none');
                    } else {
                        self.className = 'btn btn-xs btn-verify-toggle btn-light-primary fw-bold fs-8 py-1 px-2';
                        self.innerHTML = '<i class="ki-outline ki-verify text-primary fs-8 me-1"></i>Verify';
                        if (badge) badge.classList.add('d-none');
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            })
            .catch(err => {
                self.disabled = false;
                alert('Gagal memproses verifikasi.');
            });
        });
    });

    // Toggle Active / Inactive Status Handler
    document.querySelectorAll('.btn-toggle-status').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const url = this.getAttribute('data-url');
            const self = this;
            self.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                self.disabled = false;
                if (res.success) {
                    const statusCol = document.getElementById(`status-col-${id}`);
                    if (res.is_enabled) {
                        if (statusCol) statusCol.innerHTML = '<span class="badge badge-light-success fw-bold fs-8">Aktif</span>';
                        self.className = 'btn btn-icon btn-sm btn-light-warning btn-toggle-status';
                        self.title = 'Nonaktifkan Tautan';
                        self.innerHTML = '<i class="ki-outline ki-cross fs-4"></i>';
                    } else {
                        if (statusCol) statusCol.innerHTML = '<span class="badge badge-light-danger fw-bold fs-8">Nonaktif</span>';
                        self.className = 'btn btn-icon btn-sm btn-light-success btn-toggle-status';
                        self.title = 'Aktifkan Tautan';
                        self.innerHTML = '<i class="ki-outline ki-check fs-4"></i>';
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            })
            .catch(err => {
                self.disabled = false;
                alert('Gagal mengubah status link.');
            });
        });
    });

    // Delete Single Link Handler
    document.querySelectorAll('.btn-delete-link').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const slug = this.getAttribute('data-slug');
            const url = this.getAttribute('data-url');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Tautan?',
                    text: `Tautan "/${slug}" akan dihapus permanen dari sistem.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-light'
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        performDelete(id, url);
                    }
                });
            } else {
                if (confirm(`Hapus tautan "/${slug}"?`)) {
                    performDelete(id, url);
                }
            }
        });
    });

    function performDelete(id, url) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const row = document.getElementById(`link-row-${id}`);
                if (row) row.remove();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            } else {
                alert(res.message || 'Gagal menghapus tautan.');
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan koneksi.');
        });
    }
});
</script>
@endpush
