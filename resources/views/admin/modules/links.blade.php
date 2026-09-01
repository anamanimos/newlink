@extends('layouts.app')

@section('title', 'Manajemen Tautan & Bio Link')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Manajemen Tautan & Sumber Daya</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Biolink, Shortlink, WA Rotator, QR Code</span>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-6">
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.links') }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ !request('type') ? 'border-primary border-2' : '' }}">
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
        <a href="{{ request()->fullUrlWithQuery(['type' => 'biolink']) }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ request('type') === 'biolink' ? 'border-primary border-2' : '' }}">
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
        <a href="{{ request()->fullUrlWithQuery(['type' => 'link']) }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ request('type') === 'link' ? 'border-primary border-2' : '' }}">
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
        <a href="{{ request()->fullUrlWithQuery(['type' => 'warotator']) }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ request('type') === 'warotator' ? 'border-primary border-2' : '' }}">
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
        <a href="{{ request()->fullUrlWithQuery(['type' => 'qrcode']) }}" class="card card-flush shadow-sm border-0 h-100 text-hover-primary {{ request('type') === 'qrcode' ? 'border-primary border-2' : '' }}">
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
    <!-- Card Header: Search, Per Page, Filter Button -->
    <div class="card-header align-items-center py-5 gap-3 flex-wrap">
        <!-- Left: Search & Per Page -->
        <div class="d-flex align-items-center flex-wrap gap-3">
            <form method="GET" action="{{ route('admin.links') }}" id="searchForm" class="d-flex align-items-center">
                <!-- Keep existing query params except search -->
                @foreach(request()->except(['search', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                @endforeach

                <div class="d-flex align-items-center position-relative">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-3 text-gray-500"></i>
                    <input type="text" name="search" id="searchInput" class="form-control form-control-sm form-control-solid ps-10 w-225px w-md-275px" placeholder="Cari slug, tujuan, atau user..." value="{{ request('search') }}" />
                </div>
            </form>

            <div class="d-flex align-items-center gap-2">
                <label class="text-muted fs-8 fw-semibold text-nowrap d-none d-sm-inline">Tampil:</label>
                <select name="per_page" id="perPageSelect" class="form-select form-select-sm form-select-solid w-80px">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>

        <!-- Right: Filter Button & Reset -->
        <div class="d-flex align-items-center gap-2 ms-auto">
            @php
                $activeFilterCount = 0;
                if (request('type')) $activeFilterCount++;
                if (request('domain_id') !== null && request('domain_id') !== '') $activeFilterCount++;
                if (request('user_id')) $activeFilterCount++;
                if (request('status')) $activeFilterCount++;
                if (request('verified')) $activeFilterCount++;
                if (request('sort') && request('sort') !== 'latest') $activeFilterCount++;
            @endphp

            <button type="button" class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modal_filter_links">
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
    </div>

    <!-- Active Filters Bar -->
    @php
        $activePills = [];
        if (request('search')) {
            $activePills[] = [
                'param' => 'search',
                'label' => 'Kata Kunci: "' . request('search') . '"'
            ];
        }
        if (request('type')) {
            $typeNames = [
                'biolink' => 'Bio Link',
                'link' => 'Short Link',
                'warotator' => 'WA Rotator',
                'qrcode' => 'QR Code'
            ];
            $activePills[] = [
                'param' => 'type',
                'label' => 'Tipe: ' . ($typeNames[request('type')] ?? request('type'))
            ];
        }
        if (request('domain_id') !== null && request('domain_id') !== '') {
            if (request('domain_id') === '0' || request('domain_id') === 'default') {
                $activePills[] = [
                    'param' => 'domain_id',
                    'label' => 'Domain: Domain Utama (Default)'
                ];
            } else {
                $domObj = $domains->firstWhere('id', request('domain_id'));
                if ($domObj) {
                    $activePills[] = [
                        'param' => 'domain_id',
                        'label' => 'Domain: ' . $domObj->host
                    ];
                }
            }
        }
        if (request('user_id')) {
            $usrObj = $users->firstWhere('id', request('user_id'));
            if ($usrObj) {
                $activePills[] = [
                    'param' => 'user_id',
                    'label' => 'User: ' . $usrObj->name
                ];
            }
        }
        if (request('status')) {
            $activePills[] = [
                'param' => 'status',
                'label' => 'Status: ' . (request('status') === 'active' || request('status') === '1' ? 'Aktif' : 'Nonaktif')
            ];
        }
        if (request('verified')) {
            $activePills[] = [
                'param' => 'verified',
                'label' => 'Verifikasi: ' . (request('verified') === 'yes' || request('verified') === '1' ? 'Terverifikasi' : 'Belum')
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
                'param' => 'sort',
                'label' => 'Urutan: ' . ($sortNames[request('sort')] ?? request('sort'))
            ];
        }
    @endphp

    @if(!empty($activePills))
        <div class="card-body pt-0 pb-3">
            <div class="d-flex flex-wrap align-items-center gap-2 p-3 bg-light-primary rounded-3 border border-primary border-dashed">
                <a href="{{ route('admin.links') }}" class="btn btn-xs btn-danger fw-bold py-1 px-3 d-flex align-items-center gap-1">
                    <i class="ki-outline ki-trash fs-8"></i> Reset Filter
                </a>
                <span class="text-muted fs-8 fw-semibold ms-2">Filter Aktif:</span>
                @foreach($activePills as $pill)
                    <span class="badge badge-white text-gray-800 border shadow-xs d-inline-flex align-items-center gap-1 py-1 px-2 fs-8">
                        {{ $pill['label'] }}
                        <a href="{{ request()->fullUrlWithQuery([$pill['param'] => null, 'page' => null]) }}" class="text-hover-danger text-muted ms-1" title="Hapus Filter">
                            <i class="ki-outline ki-cross fs-8"></i>
                        </a>
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-7 gy-4 mb-0" id="kt_table_admin_links">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                        <th class="min-w-220px">Tautan / Slug</th>
                        <th class="min-w-160px">Tujuan / Konten</th>
                        <th class="min-w-140px">Pemilik</th>
                        <th class="min-w-120px">Domain</th>
                        <th class="min-w-80px text-center">Klik</th>
                        <th class="min-w-90px text-center">Status</th>
                        <th class="min-w-90px text-center">Verifikasi</th>
                        <th class="min-w-100px">Dibuat</th>
                        <th class="text-end min-w-120px pe-3 text-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    @forelse($links as $link)
                        @php
                            $fullPublicUrl = $link->full_url;
                        @endphp
                        <tr id="link-row-{{ $link->id }}">
                            <!-- Slug & Link Title -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-35px symbol-circle me-3">
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
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="{{ $fullPublicUrl }}" target="_blank" class="fw-bolder text-gray-900 text-hover-primary fs-6">
                                                /{{ $link->url }}
                                            </a>
                                            <button type="button" class="btn btn-icon btn-sm btn-light-secondary ms-1 py-0 px-1 btn-copy-url" data-url="{{ $fullPublicUrl }}" title="Salin Tautan Lengkap" style="width: 22px; height: 22px;">
                                                <i class="ki-outline ki-copy fs-7 text-muted"></i>
                                            </button>
                                            <span id="verified-badge-{{ $link->id }}" class="badge-verify-container d-inline-flex {{ $link->is_verified ? '' : 'd-none' }}" title="Terverifikasi">
                                                <i class="ki-outline ki-verify fs-6 text-primary"></i>
                                            </span>
                                        </div>
                                        <a href="{{ $fullPublicUrl }}" target="_blank" class="text-muted text-hover-primary fs-8 text-truncate d-inline-block" style="max-width: 250px;" title="{{ $fullPublicUrl }}">
                                            {{ $fullPublicUrl }}
                                            <i class="ki-outline ki-exit-right-corner fs-9 text-muted ms-1"></i>
                                        </a>
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
                            <td colspan="9" class="text-center py-10 text-muted">
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

<!-- Modal Multi-Filter Links -->
<div class="modal fade" id="modal_filter_links" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <form method="GET" action="{{ route('admin.links') }}" id="filterModalForm">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}" />
                @endif

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold fs-4 mb-0">Filter Tautan & Sumber Daya</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </div>
                </div>

                <div class="modal-body py-5 px-lg-8">
                    <!-- Tipe Resource -->
                    <div class="fv-row mb-5">
                        <label class="fs-7 fw-semibold mb-2">Tipe Resource</label>
                        <select name="type" class="form-select form-select-solid form-select-sm">
                            <option value="">Semua Tipe Resource</option>
                            <option value="biolink" {{ request('type') === 'biolink' ? 'selected' : '' }}>Bio Link (Halaman Profil)</option>
                            <option value="link" {{ request('type') === 'link' ? 'selected' : '' }}>Short Link (Pemendek Tautan)</option>
                            <option value="warotator" {{ request('type') === 'warotator' ? 'selected' : '' }}>WhatsApp Rotator</option>
                            <option value="qrcode" {{ request('type') === 'qrcode' ? 'selected' : '' }}>QR Code Link</option>
                        </select>
                    </div>

                    <!-- Domain -->
                    <div class="fv-row mb-5">
                        <label class="fs-7 fw-semibold mb-2">Domain Terhubung</label>
                        <select name="domain_id" class="form-select form-select-solid form-select-sm">
                            <option value="">Semua Domain</option>
                            <option value="0" {{ request('domain_id') === '0' || request('domain_id') === 'default' ? 'selected' : '' }}>Domain Utama Platform (Default)</option>
                            @foreach($domains as $dom)
                                <option value="{{ $dom->id }}" {{ request('domain_id') == $dom->id ? 'selected' : '' }}>{{ $dom->host }} ({{ $dom->type == 1 ? 'System' : 'Custom' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pemilik / Pengguna -->
                    <div class="fv-row mb-5">
                        <label class="fs-7 fw-semibold mb-2">Pemilik / Pengguna</label>
                        <select name="user_id" class="form-select form-select-solid form-select-sm">
                            <option value="">Semua Pengguna</option>
                            @foreach($users as $usr)
                                <option value="{{ $usr->id }}" {{ request('user_id') == $usr->id ? 'selected' : '' }}>{{ $usr->name }} ({{ $usr->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-4 mb-5">
                        <!-- Status -->
                        <div class="col-6">
                            <label class="fs-7 fw-semibold mb-2">Status Link</label>
                            <select name="status" class="form-select form-select-solid form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') === 'active' || request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') === 'inactive' || request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <!-- Verifikasi -->
                        <div class="col-6">
                            <label class="fs-7 fw-semibold mb-2">Verifikasi</label>
                            <select name="verified" class="form-select form-select-solid form-select-sm">
                                <option value="">Semua</option>
                                <option value="yes" {{ request('verified') === 'yes' || request('verified') === '1' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="no" {{ request('verified') === 'no' || request('verified') === '0' ? 'selected' : '' }}>Belum</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4 mb-3">
                        <!-- Urutan (Sort) -->
                        <div class="col-6">
                            <label class="fs-7 fw-semibold mb-2">Urutan Tampilan</label>
                            <select name="sort" class="form-select form-select-solid form-select-sm">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
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
                    <a href="{{ route('admin.links') }}" class="btn btn-sm btn-light">Reset Filter</a>
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

    // Delete Link Handler
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
