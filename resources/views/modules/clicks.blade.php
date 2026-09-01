@extends('layouts.app')

@section('title', 'Click Activity')

@section('content')
@php
    $activeFilters = [];
    if (request('search')) {
        $activeFilters[] = [
            'type' => 'search',
            'label' => 'Keyword: "' . request('search') . '"',
            'param' => 'search'
        ];
    }
    if (request('link_id')) {
        $selectedLink = $userLinks->firstWhere('id', request('link_id'));
        $activeFilters[] = [
            'type' => 'link_id',
            'label' => 'Link: /' . ($selectedLink ? $selectedLink->url : request('link_id')),
            'param' => 'link_id'
        ];
    }
    if (request('device_type')) {
        $activeFilters[] = [
            'type' => 'device_type',
            'label' => 'Device: ' . ucfirst(request('device_type')),
            'param' => 'device_type'
        ];
    }
    if (request('country_code')) {
        $activeFilters[] = [
            'type' => 'country_code',
            'label' => 'Country: ' . strtoupper(request('country_code')),
            'param' => 'country_code'
        ];
    }
    if (request('referrer')) {
        $activeFilters[] = [
            'type' => 'referrer',
            'label' => 'Referrer: ' . request('referrer'),
            'param' => 'referrer'
        ];
    }
    if (request('date_from')) {
        $activeFilters[] = [
            'type' => 'date_from',
            'label' => 'From: ' . request('date_from'),
            'param' => 'date_from'
        ];
    }
    if (request('date_to')) {
        $activeFilters[] = [
            'type' => 'date_to',
            'label' => 'To: ' . request('date_to'),
            'param' => 'date_to'
        ];
    }
@endphp

<!-- Page Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Aktivitas Klik Terakhir</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Real-time Stream</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <button type="button" class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-2 position-relative" data-bs-toggle="modal" data-bs-target="#clicksFilterModal">
            <i class="ki-outline ki-filter fs-3"></i> Filters
            @if(!empty($activeFilters))
                <span class="badge badge-circle badge-primary fs-9 ms-1" style="width: 18px; height: 18px; line-height: 18px; padding: 0;">{{ count($activeFilters) }}</span>
            @endif
        </button>
        <a href="{{ route('clicks.index') }}" class="btn btn-sm btn-light fw-bold d-flex align-items-center gap-2">
            <i class="ki-outline ki-arrows-circle fs-3"></i> Refresh
        </a>
    </div>
</div>

<div class="row g-6 g-xl-9">
    <!-- Left Column: 3 Columns Statistics -->
    <div class="col-12 col-lg-4 col-xl-3">
        <div class="d-flex flex-column gap-6">
            
            <!-- Stat 1: Clicks Today -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px symbol-circle bg-light-primary me-4 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-mouse fs-2x text-primary"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ number_format($totalClicksToday) }}</span>
                            <span class="text-gray-600 fw-semibold fs-7 mt-1">Klik Hari Ini</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-4"></div>
                    <div class="d-flex align-items-center justify-content-between text-muted fs-8">
                        <span>Kemarin</span>
                        <span class="fw-bold text-gray-800">{{ number_format($totalClicksYesterday) }} klik</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between text-muted fs-8 mt-1">
                        <span>Bulan Ini</span>
                        <span class="fw-bold text-primary">{{ number_format($totalClicksMonth) }} klik</span>
                    </div>
                </div>
            </div>

            <!-- Stat 2: Top Referrer & Location -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-40px symbol-circle bg-light-success me-3 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-geolocation fs-3 text-success"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-900">Top Insights</span>
                            <span class="text-muted fs-8">Sumber & Lokasi Terbanyak</span>
                        </div>
                    </div>
                    
                    <div class="bg-light rounded-3 p-3 mb-3">
                        <div class="text-muted fs-8 fw-semibold mb-1">Top Referrer:</div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-gray-900 fs-7">
                                <i class="ki-outline ki-exit-right-corner fs-6 text-primary me-1"></i>
                                {{ $topReferrer ? $topReferrer->referrer_host : 'Direct / None' }}
                            </span>
                            <span class="badge badge-light-primary fw-bold fs-8">{{ $topReferrer ? $topReferrer->count : 0 }}</span>
                        </div>
                    </div>

                    <div class="bg-light rounded-3 p-3">
                        <div class="text-muted fs-8 fw-semibold mb-1">Top Country:</div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-gray-900 fs-7">
                                <i class="ki-outline ki-flag fs-6 text-success me-1"></i>
                                {{ $topCountry ? strtoupper($topCountry->country_code) : 'Indonesia (ID)' }}
                            </span>
                            <span class="badge badge-light-success fw-bold fs-8">{{ $topCountry ? $topCountry->count : 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat 3: Device Breakdown -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-40px symbol-circle bg-light-info me-3 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-devices fs-3 text-info"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-900">Perangkat Pengunjung</span>
                            <span class="text-muted fs-8">Distribusi Device</span>
                        </div>
                    </div>

                    <!-- Mobile -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between fs-8 fw-bold mb-1">
                            <span class="text-gray-700"><i class="ki-outline ki-phone fs-6 text-primary me-1"></i> Mobile</span>
                            <span class="text-gray-900">{{ $mobilePct }}% ({{ $mobileCount }})</span>
                        </div>
                        <div class="progress h-6px bg-light-primary">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $mobilePct }}%" aria-valuenow="{{ $mobilePct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!-- Desktop -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between fs-8 fw-bold mb-1">
                            <span class="text-gray-700"><i class="ki-outline ki-screen fs-6 text-success me-1"></i> Desktop</span>
                            <span class="text-gray-900">{{ $desktopPct }}% ({{ $desktopCount }})</span>
                        </div>
                        <div class="progress h-6px bg-light-success">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $desktopPct }}%" aria-valuenow="{{ $desktopPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!-- Tablet -->
                    <div>
                        <div class="d-flex justify-content-between fs-8 fw-bold mb-1">
                            <span class="text-gray-700"><i class="ki-outline ki-tablet fs-6 text-warning me-1"></i> Tablet</span>
                            <span class="text-gray-900">{{ $tabletPct }}% ({{ $tabletCount }})</span>
                        </div>
                        <div class="progress h-6px bg-light-warning">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $tabletPct }}%" aria-valuenow="{{ $tabletPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

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
                    <form method="GET" action="{{ route('clicks.index') }}" class="d-flex align-items-center position-relative my-1">
                        @if(request('link_id')) <input type="hidden" name="link_id" value="{{ request('link_id') }}"> @endif
                        @if(request('device_type')) <input type="hidden" name="device_type" value="{{ request('device_type') }}"> @endif
                        @if(request('country_code')) <input type="hidden" name="country_code" value="{{ request('country_code') }}"> @endif
                        @if(request('referrer')) <input type="hidden" name="referrer" value="{{ request('referrer') }}"> @endif
                        @if(request('date_from')) <input type="hidden" name="date_from" value="{{ request('date_from') }}"> @endif
                        @if(request('date_to')) <input type="hidden" name="date_to" value="{{ request('date_to') }}"> @endif
                        @if(request('results_per_page')) <input type="hidden" name="results_per_page" value="{{ request('results_per_page') }}"> @endif
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                        <input type="text" name="search" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Cari IP, Kota, Browser, URL..." value="{{ request('search') }}" />
                        @if(request('search'))
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="btn btn-sm btn-icon btn-light ms-2" title="Reset Search">
                                <i class="ki-outline ki-cross fs-4"></i>
                            </a>
                        @endif
                    </form>
                </div>
                <div class="card-toolbar gap-2">
                    <button type="button" class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#clicksFilterModal">
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
                    <span class="text-gray-600 fs-8 fw-bold text-uppercase">Filter Aktif:</span>
                    @foreach($activeFilters as $filter)
                        <span class="badge badge-light-primary d-inline-flex align-items-center gap-2 py-1.5 px-3 fs-8 fw-semibold">
                            {{ $filter['label'] }}
                            <a href="{{ request()->fullUrlWithQuery([$filter['param'] => null]) }}" class="btn-close p-0 m-0 bg-none border-0 text-muted d-inline-flex align-items-center" aria-label="Clear filter" style="font-size: 0.6rem; width: 0.6rem; height: 0.6rem; line-height: 1;">
                                <i class="ki-outline ki-cross fs-8"></i>
                            </a>
                        </span>
                    @endforeach
                    
                    <a href="{{ route('clicks.index') }}" class="btn btn-link text-danger text-decoration-none p-0 fs-8 fw-bold ms-2">
                        Reset Filter
                    </a>
                </div>
            @endif

            <!-- Card Body: Table -->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-200px">Target Link</th>
                                <th class="min-w-140px">Lokasi & IP</th>
                                <th class="min-w-150px">Device / Browser</th>
                                <th class="min-w-120px">Referrer</th>
                                <th class="text-end min-w-120px pe-4">Waktu Klik</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($clickLogs as $log)
                                @php
                                    $link = $log->link;
                                    $linkType = $link ? $link->type : 'link';
                                    $typeBadge = 'badge-light-primary';
                                    $typeIcon = 'ki-disconnect';
                                    if ($linkType === 'biolink') {
                                        $typeBadge = 'badge-light-success';
                                        $typeIcon = 'ki-abstract-26';
                                    } elseif ($linkType === 'warotator') {
                                        $typeBadge = 'badge-light-warning';
                                        $typeIcon = 'ki-whatsapp';
                                    }

                                    // Device Icon
                                    $deviceIcon = 'ki-screen';
                                    if ($log->device_type === 'mobile') $deviceIcon = 'ki-phone';
                                    elseif ($log->device_type === 'tablet') $deviceIcon = 'ki-tablet';

                                    // Referrer Icon
                                    $refIcon = 'ki-exit-right-corner';
                                    $refText = $log->referrer_host ?: 'Direct / None';
                                    if (stripos($refText, 'instagram') !== false) $refIcon = 'ki-instagram';
                                    elseif (stripos($refText, 'whatsapp') !== false) $refIcon = 'ki-whatsapp';
                                    elseif (stripos($refText, 'facebook') !== false) $refIcon = 'ki-facebook';
                                    elseif (stripos($refText, 'tiktok') !== false) $refIcon = 'ki-music';
                                    elseif (stripos($refText, 'google') !== false) $refIcon = 'ki-magnifier';
                                @endphp
                                <tr>
                                    <!-- Link Info -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40px symbol-circle me-3 flex-shrink-0">
                                                <span class="symbol-label {{ $typeBadge }}">
                                                    <i class="ki-outline {{ $typeIcon }} fs-3"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column min-w-0">
                                                @if($link)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="fw-bold text-gray-900 fs-6 text-truncate">
                                                            /{{ $link->url }}
                                                        </span>
                                                        @if($link->project)
                                                            <span class="badge badge-light fw-bold fs-9" style="color: {{ $link->project->color }};">
                                                                {{ $link->project->name }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="text-muted fs-8 text-truncate" style="max-width: 180px;">
                                                        @if($log->biolinkBlock)
                                                            Tombol: {{ $log->biolinkBlock->settings['name'] ?? 'Biolink Block' }}
                                                        @else
                                                            {{ $link->location_url ?: 'Landing / Public Page' }}
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-muted fs-7">Link Dihapus</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Location & IP -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center gap-1.5 mb-1">
                                                <span class="badge badge-light-dark fw-bold fs-9 text-uppercase">
                                                    {{ $log->country_code ?: 'ID' }}
                                                </span>
                                                <span class="fw-semibold text-gray-900 fs-7">
                                                    {{ $log->city_name ?: 'Indonesia' }}
                                                </span>
                                            </div>
                                            @if($log->ip && $log->ip !== '127.0.0.1' && $log->ip !== '::1')
                                                <span class="text-muted fs-8 font-monospace">
                                                    {{ $log->ip }}
                                                </span>
                                            @elseif($log->ip === '127.0.0.1' || $log->ip === '::1')
                                                <span class="text-muted fs-8 font-monospace">
                                                    127.0.0.1 (Lokal)
                                                </span>
                                            @else
                                                <span class="badge badge-light-secondary fs-9 py-0.5 px-2 text-muted fw-semibold" title="Alamat IP dianonimkan untuk privasi dari database aplikasi lama">
                                                    <i class="ki-outline ki-shield-tick fs-8 me-1 text-muted"></i> Anonim
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Device / OS / Browser -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center gap-1.5 mb-1">
                                                <i class="ki-outline {{ $deviceIcon }} fs-6 text-gray-700"></i>
                                                <span class="fw-semibold text-gray-900 fs-7">
                                                    {{ ucfirst($log->device_type ?: 'Desktop') }}
                                                </span>
                                            </div>
                                            <span class="text-muted fs-8">
                                                {{ $log->os ?: 'OS' }} • {{ $log->browser ?: 'Browser' }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Referrer -->
                                    <td>
                                        <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded bg-light border">
                                            <i class="ki-outline {{ $refIcon }} fs-6 text-gray-600"></i>
                                            <span class="text-gray-800 fs-8 fw-semibold text-truncate" style="max-width: 110px;">
                                                {{ $refText }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Time -->
                                    <td class="text-end pe-4">
                                        <span class="text-gray-900 fw-bold fs-7 d-block">
                                            {{ $log->datetime ? $log->datetime->format('H:i:s') : '-' }}
                                        </span>
                                        <span class="text-muted fs-8" data-bs-toggle="tooltip" title="{{ $log->datetime ? $log->datetime->format('d M Y H:i:s') : '' }}">
                                            {{ $log->datetime ? $log->datetime->diffForHumans() : '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-muted">
                                        <div class="symbol symbol-65px symbol-circle bg-light-primary mb-4 d-inline-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-mouse fs-2x text-primary"></i>
                                        </div>
                                        <h5 class="fs-6 fw-bold text-gray-800 mb-1">Belum ada aktivitas klik</h5>
                                        <p class="fs-7 text-muted mb-4">Aktivitas klik pengunjung link Anda akan muncul di sini secara real-time.</p>
                                        <a href="{{ route('links.index') }}" class="btn btn-sm btn-primary fw-bold">
                                            <i class="ki-outline ki-plus fs-4 me-1"></i> Buat Tautan Baru
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($clickLogs->hasPages())
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-6 pt-4 border-top">
                        <span class="text-muted fs-7">Menampilkan {{ $clickLogs->firstItem() }} sampai {{ $clickLogs->lastItem() }} dari total {{ $clickLogs->total() }} klik</span>
                        <div>
                            {{ $clickLogs->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="clicksFilterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">
                    <i class="ki-outline ki-filter fs-2 text-primary me-2"></i> Filter Aktivitas Klik
                </h3>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <form method="GET" action="{{ route('clicks.index') }}">
                <div class="modal-body py-6 px-lg-8">
                    <div class="row g-5">
                        
                        <!-- Search Keyword -->
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Kata Kunci Pencarian</label>
                            <input type="text" name="search" class="form-control form-control-solid form-control-sm" placeholder="IP, Kota, OS, Browser, URL..." value="{{ request('search') }}" />
                        </div>

                        <!-- Specific Link -->
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Tautan Spesifik</label>
                            <select name="link_id" class="form-select form-select-solid form-select-sm">
                                <option value="">Semua Tautan</option>
                                @foreach($userLinks as $uLink)
                                    <option value="{{ $uLink->id }}" {{ request('link_id') == $uLink->id ? 'selected' : '' }}>
                                        /{{ $uLink->url }} ({{ ucfirst($uLink->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Device Type -->
                        <div class="col-md-4 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Tipe Perangkat</label>
                            <select name="device_type" class="form-select form-select-solid form-select-sm">
                                <option value="">Semua Device</option>
                                <option value="mobile" {{ request('device_type') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="desktop" {{ request('device_type') == 'desktop' ? 'selected' : '' }}>Desktop</option>
                                <option value="tablet" {{ request('device_type') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                            </select>
                        </div>

                        <!-- Referrer Platform -->
                        <div class="col-md-4 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Sumber Referrer</label>
                            <select name="referrer" class="form-select form-select-solid form-select-sm">
                                <option value="">Semua Sumber</option>
                                <option value="Instagram" {{ request('referrer') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                <option value="WhatsApp" {{ request('referrer') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="Facebook" {{ request('referrer') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                <option value="TikTok" {{ request('referrer') == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                                <option value="Twitter" {{ request('referrer') == 'Twitter' ? 'selected' : '' }}>Twitter (X)</option>
                                <option value="Google" {{ request('referrer') == 'Google' ? 'selected' : '' }}>Google Search</option>
                            </select>
                        </div>

                        <!-- Results Per Page -->
                        <div class="col-md-4 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Hasil Per Halaman</label>
                            <select name="results_per_page" class="form-select form-select-solid form-select-sm">
                                <option value="15" {{ !request('results_per_page') || request('results_per_page') == 15 ? 'selected' : '' }}>15 baris</option>
                                <option value="25" {{ request('results_per_page') == 25 ? 'selected' : '' }}>25 baris</option>
                                <option value="50" {{ request('results_per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                                <option value="100" {{ request('results_per_page') == 100 ? 'selected' : '' }}>100 baris</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control form-control-solid form-control-sm" value="{{ request('date_from') }}" />
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control form-control-solid form-control-sm" value="{{ request('date_to') }}" />
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6 justify-content-between">
                    <a href="{{ route('clicks.index') }}" class="btn btn-sm btn-light fw-bold">Reset Filter</a>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Terapkan Filter</button>
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
