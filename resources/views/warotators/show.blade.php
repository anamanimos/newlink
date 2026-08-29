@extends('layouts.app')

@section('title', 'WhatsApp Rotator Analytics')

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('warotators.index') }}" class="btn btn-sm btn-icon btn-light me-2">
            <i class="ki-outline ki-arrow-left fs-2"></i>
        </a>
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
            WA Rotator: {{ $link->settings['title'] ?? $link->url }}
        </h1>
        <span class="badge badge-light-success fw-semibold fs-8 px-2 py-1 ms-2">Analytics</span>
    </div>

    <!-- Filter Date range & Navigation actions -->
    <div class="d-flex align-items-center gap-3">
        <form action="{{ route('warotators.show', $link->id) }}" method="GET" id="filterDateForm" class="d-flex align-items-center gap-2">
            <div class="position-relative d-flex align-items-center">
                <i class="ki-outline ki-calendar fs-3 position-absolute ms-4 text-gray-500"></i>
                <input type="text" id="dateRange" class="form-control form-control-sm form-control-solid ps-11 w-225px cursor-pointer" placeholder="Select Date Range" />
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
                <div class="symbol symbol-50px symbol-circle bg-light-success d-flex align-items-center justify-content-center">
                    <i class="ki-outline ki-whatsapp fs-2x text-success"></i>
                </div>
                <div class="d-flex flex-column">
                    @php
                        $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
                    @endphp
                    <a href="{{ $fullUrl }}" target="_blank" class="fw-bolder text-gray-900 text-hover-primary fs-5">
                        {{ preg_replace('#^https?://#', '', $fullUrl) }}
                    </a>
                    <span class="text-muted fs-7 mt-1 text-truncate" style="max-width: 500px;">
                        Rotated numbers: {{ $link->settings['numbers'] ?? '' }}
                    </span>
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
<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold mb-6" id="rotatorDetailTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary py-3 {{ request('tab', 'analytics') == 'analytics' ? 'active' : '' }}" id="analytics-tab" data-bs-toggle="tab" href="#analytics" role="tab" onclick="document.getElementById('active_tab').value='analytics'">
            Analytics Summary
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary py-3 {{ request('tab') == 'leads' ? 'active' : '' }}" id="leads-tab" data-bs-toggle="tab" href="#leads" role="tab" onclick="document.getElementById('active_tab').value='leads'">
            WhatsApp Leads
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary py-3 {{ request('tab') == 'clicks' ? 'active' : '' }}" id="clicks-tab" data-bs-toggle="tab" href="#clicks" role="tab" onclick="document.getElementById('active_tab').value='clicks'">
            Visitor Click Logs
        </a>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="rotatorDetailTabsContent">
    <!-- Tab 1: Analytics Summary -->
    <div class="tab-pane fade {{ request('tab', 'analytics') == 'analytics' ? 'show active' : '' }}" id="analytics" role="tabpanel">
        <div class="row g-5 g-xl-8 mb-6">
            <!-- Stat 1 -->
            <div class="col-md-4">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px symbol-circle me-4">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-outline ki-eye fs-2x text-primary"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($totalClicks) }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">Total Clicks (Views)</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stat 2 -->
            <div class="col-md-4">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px symbol-circle me-4">
                            <span class="symbol-label bg-light-success">
                                <i class="ki-outline ki-whatsapp fs-2x text-success"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($whatsappLeads->total()) }}</span>
                            <span class="text-gray-500 fw-semibold fs-7">Leads Captured</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="col-md-4">
                <div class="card card-flush shadow-sm border-0 h-100">
                    @php
                        $conversionRate = $totalClicks > 0 ? round(($whatsappLeads->total() / $totalClicks) * 100, 1) : 0;
                    @endphp
                    <div class="card-body d-flex align-items-center p-6">
                        <div class="symbol symbol-50px symbol-circle me-4">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-outline ki-chart-line-up fs-2x text-warning"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $conversionRate }}%</span>
                            <span class="text-gray-500 fw-semibold fs-7">Conversion Rate (CTR)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clicks Trend Line Graph Chart -->
        <div class="card card-flush shadow-sm border-0 mb-6">
            <div class="card-header pt-6">
                <h3 class="card-title fw-bold text-gray-900 fs-4">Pageviews Trend</h3>
            </div>
            <div class="card-body pt-0">
                <div style="height: 300px; position: relative;">
                    <canvas id="clicksChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Regional & Browser metadata tables -->
        <div class="row g-6 g-xl-9">
            <div class="col-md-6">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-header pt-6">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">Visitor Countries</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-3 mb-0">
                                <tbody>
                                    @forelse($topCountries as $country)
                                        <tr>
                                            <td class="fw-semibold text-gray-800">
                                                <span class="badge badge-light fw-bold fs-8 me-2">{{ $country->country_code ?: 'Unknown' }}</span>
                                                {{ $country->country_code ?: 'Direct / Unknown' }}
                                            </td>
                                            <td class="text-end fw-bold text-gray-900">{{ number_format($country->count) }} clicks</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-6 fs-7">No country data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-flush shadow-sm border-0 h-100">
                    <div class="card-header pt-6">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">Operating Systems</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-3 mb-0">
                                <tbody>
                                    @forelse($topOs as $os)
                                        <tr>
                                            <td class="fw-semibold text-gray-800">{{ $os->os ?: 'Unknown OS' }}</td>
                                            <td class="text-end fw-bold text-gray-900">{{ number_format($os->count) }} clicks</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-6 fs-7">No OS data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: WhatsApp Leads Entries list -->
    <div class="tab-pane fade {{ request('tab') == 'leads' ? 'show active' : '' }}" id="leads" role="tabpanel">
        <div class="card card-flush shadow-sm border-0 mb-6">
            <div class="card-header pt-6">
                <h3 class="card-title fw-bold text-gray-900 fs-4">Leads Captured via Form</h3>
                <div class="card-toolbar">
                    <a href="{{ route('warotators.leads.export', $link->id) }}" class="btn btn-sm btn-light-success fw-bold d-flex align-items-center gap-2">
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

    <!-- Tab 3: Detailed Click Logs -->
    <div class="tab-pane fade {{ request('tab') == 'clicks' ? 'show active' : '' }}" id="clicks" role="tabpanel">
        <div class="card card-flush shadow-sm border-0 mb-6">
            <div class="card-header pt-6">
                <h3 class="card-title fw-bold text-gray-900 fs-4">Detailed Click History</h3>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-140px">Date & Time</th>
                                <th class="min-w-120px">IP Address</th>
                                <th class="min-w-100px">Country</th>
                                <th class="min-w-100px">City</th>
                                <th class="min-w-140px">OS & Browser</th>
                                <th class="min-w-140px">Referrer</th>
                                <th class="text-center min-w-80px pe-3">Unique</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($rawClicks as $click)
                                <tr>
                                    <td class="text-muted fs-7">
                                        {{ date('d M Y H:i:s', strtotime($click->datetime)) }}
                                    </td>
                                    <td class="fw-bold text-gray-800 fs-7">{{ $click->ip }}</td>
                                    <td>{{ $click->country_code ?: 'Direct / Unknown' }}</td>
                                    <td>{{ $click->city_name ?: '-' }}</td>
                                    <td>{{ $click->os }} / {{ $click->browser }}</td>
                                    <td class="text-truncate text-muted fs-7" style="max-width: 150px;">{{ $click->referrer_host ?: 'Direct' }}</td>
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
                                    <td colspan="7" class="text-center py-10 text-muted">
                                        <i class="ki-outline ki-disconnect fs-4x text-muted mb-3"></i>
                                        <p class="fs-6 fw-semibold mb-0">No clicks recorded in this date range.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($rawClicks instanceof \Illuminate\Pagination\AbstractPaginator && $rawClicks->hasPages())
                    <div class="d-flex justify-content-center mt-6">
                        {{ $rawClicks->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

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

    const ctx = document.getElementById('clicksChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartDates) !!},
                datasets: [{
                    label: 'Page Clicks',
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
