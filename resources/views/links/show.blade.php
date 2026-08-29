@extends('layouts.app')

@section('title', 'Link Analytics: ' . $link->url)

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('links.index') }}" class="btn btn-sm btn-icon btn-light me-2">
            <i class="ki-outline ki-arrow-left fs-2"></i>
        </a>
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
            Link: {{ $link->url }}
        </h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Analytics</span>
    </div>

    <!-- Filter Date range & Navigation actions -->
    <div class="d-flex align-items-center gap-3">
        <form method="GET" action="{{ route('links.show', $link->id) }}" id="dateFilterForm" class="d-flex align-items-center gap-2">
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
                    <i class="ki-outline ki-paper-clip fs-2x text-primary"></i>
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
            <div class="d-flex align-items-center gap-6">
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

    // Initialize Chart
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
});
</script>
@endsection
