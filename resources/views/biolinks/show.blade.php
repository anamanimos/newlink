@extends('layouts.app')

@section('title', 'Biolink Analytics: ' . $link->url)

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('biolinks.index') }}" class="btn btn-sm btn-icon btn-light me-2">
            <i class="ki-outline ki-arrow-left fs-2"></i>
        </a>
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
            Biolink: {{ $link->url }}
        </h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Analytics</span>
    </div>

    <!-- Filter Date range & Navigation actions -->
    <div class="d-flex align-items-center gap-3">
        <form method="GET" action="{{ route('biolinks.show', $link->id) }}" id="dateFilterForm" class="d-flex align-items-center gap-2">
            <div class="position-relative d-flex align-items-center">
                <i class="ki-outline ki-calendar fs-3 position-absolute ms-4 text-gray-500"></i>
                <input type="text" id="dateRange" class="form-control form-control-sm form-control-solid ps-11 w-225px" placeholder="Select Date Range" />
                <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
            </div>
            
            <input type="hidden" name="tab" id="active_tab" value="{{ request('tab', 'analytics') }}">
            <button type="submit" class="btn btn-sm btn-primary fw-bold">Filter</button>
        </form>
    </div>
</div>

<!-- Header Card Summary -->
<div class="card card-flush shadow-sm border-0 mb-6">
    <div class="card-body p-6">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
            <div class="d-flex align-items-center gap-4">
                <div class="symbol symbol-50px symbol-circle bg-light-primary d-flex align-items-center justify-content-center">
                    <i class="ki-outline ki-profile-circle fs-2x text-primary"></i>
                </div>
                <div class="d-flex flex-column">
                    @php
                        $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
                    @endphp
                    <a href="{{ $fullUrl }}" target="_blank" class="fw-bolder text-gray-900 text-hover-primary fs-5">
                        {{ preg_replace('#^https?://#', '', $fullUrl) }}
                    </a>
                    <a href="{{ $link->location_url }}" target="_blank" class="text-muted fs-7 mt-1 text-truncate" style="max-width: 500px;">
                        {{ $link->location_url }}
                    </a>
                </div>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="d-flex flex-column">
                    <span class="text-muted fs-8">Created On</span>
                    <span class="fw-bold text-gray-800 fs-7">{{ $link->created_at->format('d M Y') }}</span>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-muted fs-8">Status</span>
                    @if($link->is_enabled)
                        <span class="badge badge-light-success fw-bold fs-8">Active</span>
                    @else
                        <span class="badge badge-light-secondary fw-bold fs-8">Disabled</span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2 ms-2">
                    <form action="{{ route('biolinks.duplicate', $link->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Duplikat Biolink ini beserta seluruh blok kontennya?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light-info fw-bold d-inline-flex align-items-center gap-1.5" title="Duplikat Biolink">
                            <i class="ki-outline ki-copy fs-4"></i> Duplikat
                        </button>
                    </form>
                    <a href="{{ route('biolinks.builder', $link->id) }}" class="btn btn-sm btn-light-primary fw-bold d-inline-flex align-items-center gap-1.5">
                        <i class="ki-outline ki-pencil fs-4"></i> Edit Biolink
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold mb-6" id="linkDetailTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary py-3 {{ request('tab', 'analytics') == 'analytics' ? 'active' : '' }}" id="analytics-tab" data-bs-toggle="tab" href="#analytics" role="tab" onclick="document.getElementById('active_tab').value='analytics'">
            Analytics Summary
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary py-3 {{ request('tab') == 'data' ? 'active' : '' }}" id="data-tab" data-bs-toggle="tab" href="#data" role="tab" onclick="document.getElementById('active_tab').value='data'">
            Click Logs
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary py-3 {{ request('tab') == 'leads' ? 'active' : '' }}" id="leads-tab" data-bs-toggle="tab" href="#leads" role="tab" onclick="document.getElementById('active_tab').value='leads'">
            WhatsApp Leads
        </a>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="linkDetailTabsContent">
    <!-- Tab 1: Analytics Summary -->
    <div class="tab-pane fade {{ request('tab', 'analytics') == 'analytics' ? 'show active' : '' }}" id="analytics" role="tabpanel">
        <div class="row g-5 g-xl-8 mb-6">
            <!-- Stat 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px symbol-circle me-4">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-outline ki-mouse fs-2x text-primary"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($totalClicks) }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">Total Clicks</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stat 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px symbol-circle me-4">
                            <span class="symbol-label bg-light-success">
                                <i class="ki-outline ki-profile-user fs-2x text-success"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($uniqueClicks) }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">Unique Visitors</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px symbol-circle me-4">
                            <span class="symbol-label bg-light-info">
                                <i class="ki-outline ki-devices fs-2x text-info"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ count($topDevices ?? []) }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">Device Types</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px symbol-circle me-4">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-outline ki-geolocation fs-2x text-warning"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ count($topCountries ?? []) }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">Countries</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="card card-flush shadow-sm border-0 mb-6">
            <div class="card-header pt-6">
                <h3 class="card-title fw-bold text-gray-900 fs-4">Clicks Trend</h3>
                <div class="card-toolbar">
                    <span class="badge badge-light-primary fw-bold">
                        {{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}
                    </span>
                </div>
            </div>
            <div class="card-body pt-0">
                <div style="height: 300px; position: relative;">
                    <canvas id="linkClicksChart"></canvas>
                </div>
            </div>
        </div>

        @if($link->type === 'biolink' && count($biolinkBlocks) > 0)
            <!-- Button Clicks Statistics Section -->
            <div class="card card-flush shadow-sm border-0 mb-6">
                <div class="card-header pt-6">
                    <h3 class="card-title fw-bold text-gray-900 fs-4">Clicks per Biolink Button</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Button Title</th>
                                    <th class="min-w-200px">Destination URL</th>
                                    <th class="text-center min-w-80px">Clicks</th>
                                    <th class="text-center min-w-80px">CTR</th>
                                    <th class="min-w-150px">Share</th>
                                    <th class="text-end min-w-80px pe-3">Details</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach($biolinkBlocks as $block)
                                    @php
                                        $blockPercent = $totalClicks > 0 ? ($block->clicks / $totalClicks) * 100 : 0;
                                        $blockCTR = $totalClicks > 0 ? ($block->clicks / $totalClicks) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-gray-800 fs-6">
                                            {{ $block->settings['title'] ?? 'Untitled Link' }}
                                        </td>
                                        <td class="text-truncate" style="max-width: 220px;">
                                            <a href="{{ $block->location_url }}" target="_blank" rel="noopener" class="text-muted text-hover-primary fs-7">
                                                {{ $block->location_url }}
                                            </a>
                                        </td>
                                        <td class="text-center fw-bold text-gray-900">
                                            {{ number_format($block->clicks) }}
                                        </td>
                                        <td class="text-center fw-semibold text-gray-700">
                                            {{ number_format($blockCTR, 1) }}%
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress h-6px w-80px bg-light">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $blockPercent }}%;"></div>
                                                </div>
                                                <span class="fs-8 text-muted fw-bold">{{ number_format($blockPercent, 0) }}%</span>
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <button class="btn btn-sm btn-icon btn-light-primary btn-block-analytics" data-id="{{ $block->id }}" title="View Button Analytics">
                                                <i class="ki-outline ki-chart-line-up fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Block Analytics Details -->
        <div class="modal fade" id="blockAnalyticsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow-lg">
                    <div class="modal-header pb-0 border-0 justify-content-between">
                        <h3 class="modal-title fw-bold text-gray-900" id="blockAnalyticsTitle">Button Statistics</h3>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>
                    <div class="modal-body py-6 px-lg-8">
                        <!-- Top Stats Summary -->
                        <div class="row g-4 mb-6">
                            <div class="col-6">
                                <div class="card card-flush shadow-sm border-0">
                                    <div class="card-body d-flex align-items-center p-4">
                                        <div class="symbol symbol-40px symbol-circle bg-light-primary me-3 d-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-mouse fs-2 text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="fs-8 text-muted fw-semibold d-block">Total Button Clicks</span>
                                            <span class="fs-4 fw-bold text-gray-900" id="blockTotalClicks">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card card-flush shadow-sm border-0">
                                    <div class="card-body d-flex align-items-center p-4">
                                        <div class="symbol symbol-40px symbol-circle bg-light-success me-3 d-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-chart-line-up fs-2 text-success"></i>
                                        </div>
                                        <div>
                                            <span class="fs-8 text-muted fw-semibold d-block">CTR</span>
                                            <span class="fs-4 fw-bold text-gray-900" id="blockCTR">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trend Line Chart -->
                        <div class="card card-flush shadow-sm border-0 mb-6">
                            <div class="card-header pt-4">
                                <h4 class="card-title fw-bold text-gray-900 fs-6" id="blockClicksChartTitle">Button Clicks Trend</h4>
                            </div>
                            <div class="card-body pt-0">
                                <div style="height: 200px; position: relative;">
                                    <canvas id="blockClicksChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Referrers Breakdown -->
                        <div class="card card-flush shadow-sm border-0">
                            <div class="card-header pt-4">
                                <h4 class="card-title fw-bold text-gray-900 fs-6">Top Referrers for Button</h4>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-3" id="blockReferrersList"></div>
                                <div id="blockNoReferrers" class="text-center text-muted py-4 d-none fs-7">
                                    No referrer data recorded for this button.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-lg-8 pb-6">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown Section -->
        <div class="row g-6 g-xl-9">
            <!-- Referrers -->
            <div class="col-md-4">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-header pt-6">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">Top Referrers</h3>
                    </div>
                    <div class="card-body pt-0">
                        @if(count($topReferrers ?? []) > 0)
                            <div class="d-flex flex-column gap-4">
                                @foreach(($topReferrers ?? []) as $ref)
                                    @php 
                                        $host = empty($ref->referrer_host) ? 'Direct / Unknown' : $ref->referrer_host;
                                        $percent = $totalClicks > 0 ? ($ref->count / $totalClicks) * 100 : 0;
                                    @endphp
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-truncate fw-semibold text-gray-800 fs-7 me-2">{{ $host }}</span>
                                            <span class="fw-bold text-gray-900 fs-7">{{ number_format($ref->count) }}</span>
                                        </div>
                                        <div class="progress h-6px bg-light">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent }}%;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-6 fs-7">No referrer data available.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Countries -->
            <div class="col-md-4">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-header pt-6">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">Top Countries</h3>
                    </div>
                    <div class="card-body pt-0">
                        @if(count($topCountries ?? []) > 0)
                            <div class="d-flex flex-column gap-4">
                                @foreach(($topCountries ?? []) as $country)
                                    @php 
                                        $name = empty($country->country_code) ? 'Unknown' : $country->country_code;
                                        $percent = $totalClicks > 0 ? ($country->count / $totalClicks) * 100 : 0;
                                    @endphp
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-truncate fw-semibold text-gray-800 fs-7 me-2">{{ strtoupper($name) }}</span>
                                            <span class="fw-bold text-gray-900 fs-7">{{ number_format($country->count) }}</span>
                                        </div>
                                        <div class="progress h-6px bg-light">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-6 fs-7">No country data available.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Browsers -->
            <div class="col-md-4">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-header pt-6">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">Top Browsers</h3>
                    </div>
                    <div class="card-body pt-0">
                        @if(count($topBrowsers ?? []) > 0)
                            <div class="d-flex flex-column gap-4">
                                @foreach(($topBrowsers ?? []) as $browser)
                                    @php 
                                        $name = empty($browser->browser) ? 'Unknown' : $browser->browser;
                                        $percent = $totalClicks > 0 ? ($browser->count / $totalClicks) * 100 : 0;
                                    @endphp
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-truncate fw-semibold text-gray-800 fs-7 me-2">{{ $name }}</span>
                                            <span class="fw-bold text-gray-900 fs-7">{{ number_format($browser->count) }}</span>
                                        </div>
                                        <div class="progress h-6px bg-light">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percent }}%;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-6 fs-7">No browser data available.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Raw Click Data -->
    <div class="tab-pane fade {{ request('tab') == 'data' ? 'show active' : '' }}" id="data" role="tabpanel">
        <div class="card card-flush shadow-sm border-0 mb-6">
            <div class="card-header pt-6">
                <h3 class="card-title fw-bold text-gray-900 fs-4">Detailed Click History</h3>
                <div class="card-toolbar">
                    <span class="badge badge-light-primary fw-bold">{{ $rawClicks->total() }} Records Found</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-140px">Date & Time</th>
                                <th class="min-w-120px">Location / IP</th>
                                <th class="min-w-120px">OS & Browser</th>
                                <th class="min-w-100px">Device</th>
                                <th class="min-w-140px">Referrer</th>
                                <th class="text-center min-w-80px pe-3">Unique</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($rawClicks as $click)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-gray-800">{{ \Carbon\Carbon::parse($click->datetime)->format('d M Y') }}</div>
                                        <div class="text-muted fs-8">{{ \Carbon\Carbon::parse($click->datetime)->format('H:i:s') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-gray-800">{{ empty($click->country_code) ? 'Unknown' : strtoupper($click->country_code) }} {{ empty($click->city_name) ? '' : ' - ' . $click->city_name }}</div>
                                        <div class="text-muted fs-8">{{ empty($click->ip) ? '-' : $click->ip }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-gray-800">{{ empty($click->os) ? 'Unknown OS' : $click->os }}</div>
                                        <div class="text-muted fs-8">{{ empty($click->browser) ? 'Unknown Browser' : $click->browser }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light fw-bold fs-8">
                                            {{ empty($click->device_type) ? 'Unknown' : ucfirst($click->device_type) }}
                                        </span>
                                    </td>
                                    <td class="text-truncate text-muted fs-7" style="max-width: 150px;">
                                        {{ empty($click->referrer_host) ? 'Direct' : $click->referrer_host }}
                                    </td>
                                    <td class="text-center pe-3">
                                        @if($click->is_unique)
                                            <span class="badge badge-light-success fw-bold fs-8">Unique</span>
                                        @else
                                            <span class="badge badge-light-secondary fw-bold fs-8">Repeat</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-muted">
                                        <i class="ki-outline ki-disconnect fs-4x text-muted mb-3"></i>
                                        <p class="fs-6 fw-semibold mb-0">No clicks recorded in this date range.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($rawClicks->hasPages())
                    <div class="d-flex justify-content-center mt-6">
                        {{ $rawClicks->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tab 3: WhatsApp Leads -->
    <div class="tab-pane fade {{ request('tab') == 'leads' ? 'show active' : '' }}" id="leads" role="tabpanel">
        <div class="card card-flush shadow-sm border-0 mb-6">
            <div class="card-header pt-6">
                <h3 class="card-title fw-bold text-gray-900 fs-4">Leads Captured via Form</h3>
                <div class="card-toolbar">
                    <a href="{{ route('biolinks.leads.export', $link->id) }}" class="btn btn-sm btn-light-success fw-bold d-flex align-items-center gap-2">
                        <i class="ki-outline ki-file-down fs-2"></i> Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-120px">Date & Time</th>
                                <th class="min-w-150px">Name</th>
                                <th class="min-w-120px">City</th>
                                <th class="min-w-120px">Visitor Phone</th>
                                <th class="min-w-180px">Message</th>
                                <th class="min-w-120px">Assigned Admin</th>
                                <th class="min-w-100px text-end pe-3">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($whatsappLeads as $lead)
                                <tr>
                                    <td class="text-muted fs-7">
                                        {{ $lead->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="fw-bold text-gray-800 fs-6">
                                        {{ $lead->name }}
                                    </td>
                                    <td>{{ $lead->city }}</td>
                                    <td>62{{ $lead->phone }}</td>
                                    <td class="text-truncate text-muted fs-7" style="max-width: 250px;">
                                        {{ $lead->message ?: '-' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-light-success fw-bold fs-8">
                                            {{ $lead->whatsapp_number_used }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3 text-muted fs-8">
                                        {{ $lead->ip ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10 text-muted">
                                        <i class="ki-outline ki-profile-user fs-4x text-muted mb-3"></i>
                                        <p class="fs-6 fw-semibold mb-0">No lead submissions recorded yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($whatsappLeads instanceof \Illuminate\Pagination\AbstractPaginator && $whatsappLeads->hasPages())
                    <div class="d-flex justify-content-center mt-6">
                        {{ $whatsappLeads->appends(request()->except('leads_page'))->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flatpickr Date Range Picker
    const dateRangeEl = document.getElementById('dateRange');
    if (dateRangeEl && typeof flatpickr !== 'undefined') {
        flatpickr(dateRangeEl, {
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            mode: "range",
            altInputClass: "form-control form-control-sm form-control-solid ps-11 w-225px cursor-pointer",
            defaultDate: ["{{ $startDate->format('Y-m-d') }}", "{{ $endDate->format('Y-m-d') }}"],
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const start = instance.formatDate(selectedDates[0], "Y-m-d");
                    const end = instance.formatDate(selectedDates[1], "Y-m-d");
                    document.getElementById('start_date').value = start;
                    document.getElementById('end_date').value = end;
                }
            }
        });
    }

    // Keep active tab synced with form
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('href') || e.target.getAttribute('data-bs-target');
            if (target) {
                const tabName = target.replace('#', '');
                const tabInput = document.getElementById('active_tab');
                if (tabInput) tabInput.value = tabName;
            }
        });
    });

    // Initialize Main Chart
    const ctx = document.getElementById('linkClicksChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartDates) !!},
                datasets: [{
                    label: 'Clicks',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1.5,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.04)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Biolink Button Level Analytics Modal Controller
    let blockChartInstance = null;
    const blockModalElement = document.getElementById('blockAnalyticsModal');
    let blockModal = null;
    if (blockModalElement) {
        blockModal = new bootstrap.Modal(blockModalElement);
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-block-analytics');
        if (!btn) return;

        const blockId = btn.getAttribute('data-id');
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const dateRangeVal = document.getElementById('dateRange').value;
        
        // Set Loading state
        document.getElementById('blockAnalyticsTitle').textContent = 'Button Statistics: Loading...';
        document.getElementById('blockTotalClicks').textContent = '0';
        document.getElementById('blockCTR').textContent = '0%';
        document.getElementById('blockClicksChartTitle').textContent = `Clicks Trend (${dateRangeVal})`;
        
        const refList = document.getElementById('blockReferrersList');
        if (refList) refList.innerHTML = '';
        
        const noRef = document.getElementById('blockNoReferrers');
        if (noRef) noRef.classList.add('d-none');
        
        if (blockChartInstance) {
            blockChartInstance.destroy();
            blockChartInstance = null;
        }

        if (blockModal) {
            blockModal.show();
        }

        fetch(`/biolink/block/${blockId}/analytics?start_date=${startDate}&end_date=${endDate}`)
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    document.getElementById('blockAnalyticsTitle').textContent = `Button Statistics: "${response.title}"`;
                    document.getElementById('blockTotalClicks').textContent = response.clicks;
                    document.getElementById('blockCTR').textContent = response.ctr;

                    // Draw chart
                    const blockCtx = document.getElementById('blockClicksChart');
                    if (blockCtx) {
                        blockChartInstance = new Chart(blockCtx, {
                            type: 'line',
                            data: {
                                labels: response.chartDates,
                                datasets: [{
                                    label: 'Button Clicks',
                                    data: response.chartData,
                                    borderColor: '#2563eb',
                                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                                    borderWidth: 2,
                                    tension: 0.35,
                                    fill: true,
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
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: 'rgba(0, 0, 0, 0.04)' }
                                    },
                                    x: {
                                        grid: { display: false }
                                    }
                                }
                            }
                        });
                    }

                    // Populate referrers list
                    if (refList) {
                        if (response.referrers && response.referrers.length > 0) {
                            response.referrers.forEach(ref => {
                                const refItem = `
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-truncate fw-semibold text-gray-800 fs-7 me-2">${ref.referrer}</span>
                                            <span class="fw-bold text-gray-900 fs-7">${ref.count.toLocaleString()}</span>
                                        </div>
                                        <div class="progress h-6px bg-light">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: ${ref.percent}%;"></div>
                                        </div>
                                    </div>
                                `;
                                refList.insertAdjacentHTML('beforeend', refItem);
                            });
                        } else if (noRef) {
                            noRef.classList.remove('d-none');
                        }
                    }
                }
            })
            .catch(err => {
                document.getElementById('blockAnalyticsTitle').textContent = 'Failed to load statistics.';
            });
    });
});
</script>
@endsection
