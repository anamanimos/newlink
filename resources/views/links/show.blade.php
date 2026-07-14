@extends('layouts.app')

@section('title', 'Detail Link: ' . $link->url)

@section('content')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .detail-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .stat-card {
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(164, 229, 189, 0.2);
        color: var(--primary-color);
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }
    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }
    .info-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--glass-border);
    }
    .info-list-item:last-child {
        border-bottom: none;
    }
    .progress-bar-custom {
        height: 6px;
        background: var(--glass-border);
        border-radius: 4px;
        overflow: hidden;
        margin-top: 8px;
    }
    .progress-bar-fill {
        height: 100%;
        background: var(--primary-color);
        border-radius: 4px;
    }

    /* Tabs Styling */
    .nav-tabs.glass-tabs {
        border-bottom: 2px solid var(--glass-border);
        gap: 16px;
    }
    .nav-tabs.glass-tabs .nav-link {
        border: none;
        background: transparent;
        color: var(--text-secondary);
        font-weight: 600;
        padding: 12px 4px;
        position: relative;
    }
    .nav-tabs.glass-tabs .nav-link:hover {
        color: var(--text-primary);
    }
    .nav-tabs.glass-tabs .nav-link.active {
        color: var(--primary-color);
    }
    .nav-tabs.glass-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary-color);
        border-radius: 2px 2px 0 0;
    }

    /* Table styling for Data Klik */
    .table-glass {
        color: var(--text-primary);
    }
    .table-glass th {
        background: transparent;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--glass-border);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem;
    }
    .table-glass td {
        background: transparent;
        border-bottom: 1px solid var(--glass-border);
        padding: 1rem;
        font-size: 0.875rem;
    }
</style>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--text-primary);">Analitik Tautan</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 0.875rem;">
                <li class="breadcrumb-item"><a href="{{ route('links.index') }}" class="text-decoration-none" style="color: var(--primary-color);">Shortened Links</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--text-secondary);">{{ $link->url }}</li>
            </ol>
        </nav>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <!-- Date Filter Form -->
        <form method="GET" action="{{ route('links.show', $link->id) }}" id="dateFilterForm" class="d-flex align-items-center gap-2">
            <div class="position-relative">
                <input type="text" id="dateRange" class="form-control shadow-sm" placeholder="Pilih rentang tanggal" 
                    style="height: 42px; background: var(--glass-bg); border-color: var(--glass-border); color: var(--text-primary); padding-left: 38px; min-width: 260px; font-size: 0.875rem; border-radius: 8px;">
                <span data-duo-icons="calendar" class="position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-secondary);"></span>
            </div>
            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
            <!-- Maintain active tab in query -->
            <input type="hidden" name="tab" id="active_tab" value="{{ request('tab', 'analytics') }}">
            
            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 px-3 shadow-sm" style="height: 42px; border-radius: 8px; font-weight: 500;">
                <span data-duo-icons="align-bottom" style="width: 16px; height: 16px;"></span> Filter
            </button>
        </form>

        <a href="{{ route('links.index') }}" class="btn btn-light d-flex align-items-center justify-content-center gap-2 px-3 shadow-sm" style="height: 42px; border-radius: 8px; background: var(--glass-bg); border-color: var(--glass-border); color: var(--text-primary); font-weight: 500;">
            Kembali
        </a>
    </div>
</div>

<!-- Header Card -->
<div class="detail-card p-4 mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="p-2 rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center">
                    <span data-duo-icons="bookmark" style="width: 24px; height: 24px;"></span>
                </div>
                <div>
                    @php
                        $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
                    @endphp
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                        <a href="{{ $fullUrl }}" target="_blank" class="text-decoration-none text-primary">{{ preg_replace('#^https?://#', '', $fullUrl) }}</a>
                    </h5>
                    <div class="text-muted small mt-1 d-flex align-items-center gap-2 text-truncate" style="max-width: 500px;">
                        <a href="{{ $link->location_url }}" target="_blank" class="text-muted text-decoration-none">{{ $link->location_url }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="d-flex align-items-center justify-content-md-end gap-3">
                <div class="text-start">
                    <div class="text-muted small mb-1">Dibuat pada</div>
                    <div class="fw-medium text-primary" style="font-size: 0.9rem;">{{ $link->created_at->format('d M Y') }}</div>
                </div>
                <div class="ms-3 text-start">
                    <div class="text-muted small mb-1">Status</div>
                    @if($link->is_enabled)
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">Aktif</span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1 rounded-pill">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs glass-tabs mb-4" id="linkDetailTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab', 'analytics') == 'analytics' ? 'active' : '' }}" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab" aria-controls="analytics" aria-selected="{{ request('tab', 'analytics') == 'analytics' ? 'true' : 'false' }}" onclick="document.getElementById('active_tab').value='analytics'">
            <div class="d-flex align-items-center gap-2">
                <span data-duo-icons="chart-pie" style="width: 18px; height: 18px;"></span> Ringkasan Analitik
            </div>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab') == 'data' ? 'active' : '' }}" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="{{ request('tab') == 'data' ? 'true' : 'false' }}" onclick="document.getElementById('active_tab').value='data'">
            <div class="d-flex align-items-center gap-2">
                <span data-duo-icons="dashboard" style="width: 18px; height: 18px;"></span> Data Klik
            </div>
        </button>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="linkDetailTabsContent">
    
    <!-- Tab 1: Analytics Summary -->
    <div class="tab-pane fade {{ request('tab', 'analytics') == 'analytics' ? 'show active' : '' }}" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
        <div class="row g-4 mb-4">
            <!-- Stat 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="detail-card stat-card h-100">
                    <div class="stat-icon">
                        <span data-duo-icons="target" style="width: 24px; height: 24px;"></span>
                    </div>
                    <div>
                        <div class="stat-label">Total Klik</div>
                        <div class="stat-value">{{ number_format($totalClicks) }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Stat 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="detail-card stat-card h-100">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <span data-duo-icons="user" style="width: 24px; height: 24px;"></span>
                    </div>
                    <div>
                        <div class="stat-label">Pengunjung Unik</div>
                        <div class="stat-value">{{ number_format($uniqueClicks) }}</div>
                    </div>
                </div>
            </div>

            <!-- Empty Stats Fillers -->
            <div class="col-md-6 col-lg-3">
                <div class="detail-card stat-card h-100 opacity-50">
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <span data-duo-icons="smartphone" style="width: 24px; height: 24px;"></span>
                    </div>
                    <div>
                        <div class="stat-label">Klik via Mobile</div>
                        <div class="stat-value text-muted">-</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="detail-card stat-card h-100 opacity-50">
                    <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <span data-duo-icons="world" style="width: 24px; height: 24px;"></span>
                    </div>
                    <div>
                        <div class="stat-label">Top Negara</div>
                        <div class="stat-value text-muted">-</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="detail-card p-4 mb-4">
            <h5 class="fw-bold mb-4 d-flex justify-content-between align-items-center" style="color: var(--text-primary);">
                <span>Statistik Pengunjung</span>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-normal px-3 py-2 rounded-pill">
                    {{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}
                </span>
            </h5>
            <div style="height: 300px; position: relative;">
                <canvas id="linkClicksChart"></canvas>
            </div>
        </div>

        <!-- Breakdown Section -->
        <div class="row g-4">
            <!-- Referrers -->
            <div class="col-md-4">
                <div class="detail-card p-4 h-100">
                    <h6 class="fw-bold mb-4 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                        <span data-duo-icons="bookmark" style="width: 18px; height: 18px; color: var(--text-secondary);"></span> Referrer Top
                    </h6>
                    @if(count($topReferrers) > 0)
                        <div class="info-list">
                            @foreach($topReferrers as $ref)
                                @php 
                                    $host = empty($ref->referrer_host) ? 'Direct / Unknown' : $ref->referrer_host;
                                    $percent = $totalClicks > 0 ? ($ref->count / $totalClicks) * 100 : 0;
                                @endphp
                                <div class="info-list-item d-block">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-truncate me-2" style="color: var(--text-primary); font-size: 0.875rem;">{{ $host }}</span>
                                        <span class="fw-medium text-primary" style="font-size: 0.875rem;">{{ number_format($ref->count) }}</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-bar-fill" style="width: {{ $percent }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4" style="font-size: 0.875rem;">Belum ada data referrer.</div>
                    @endif
                </div>
            </div>

            <!-- Countries -->
            <div class="col-md-4">
                <div class="detail-card p-4 h-100">
                    <h6 class="fw-bold mb-4 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                        <span data-duo-icons="world" style="width: 18px; height: 18px; color: var(--text-secondary);"></span> Negara
                    </h6>
                    @if(count($topCountries) > 0)
                        <div class="info-list">
                            @foreach($topCountries as $country)
                                @php 
                                    $name = empty($country->country_code) ? 'Unknown' : $country->country_code;
                                    $percent = $totalClicks > 0 ? ($country->count / $totalClicks) * 100 : 0;
                                @endphp
                                <div class="info-list-item d-block">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-truncate me-2" style="color: var(--text-primary); font-size: 0.875rem;">{{ strtoupper($name) }}</span>
                                        <span class="fw-medium text-primary" style="font-size: 0.875rem;">{{ number_format($country->count) }}</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-bar-fill" style="width: {{ $percent }}%; background-color: #10b981;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4" style="font-size: 0.875rem;">Belum ada data negara.</div>
                    @endif
                </div>
            </div>

            <!-- Browsers -->
            <div class="col-md-4">
                <div class="detail-card p-4 h-100">
                    <h6 class="fw-bold mb-4 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                        <span data-duo-icons="app" style="width: 18px; height: 18px; color: var(--text-secondary);"></span> Browser
                    </h6>
                    @if(count($topBrowsers) > 0)
                        <div class="info-list">
                            @foreach($topBrowsers as $browser)
                                @php 
                                    $name = empty($browser->browser) ? 'Unknown' : $browser->browser;
                                    $percent = $totalClicks > 0 ? ($browser->count / $totalClicks) * 100 : 0;
                                @endphp
                                <div class="info-list-item d-block">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-truncate me-2" style="color: var(--text-primary); font-size: 0.875rem;">{{ $name }}</span>
                                        <span class="fw-medium text-primary" style="font-size: 0.875rem;">{{ number_format($browser->count) }}</span>
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-bar-fill" style="width: {{ $percent }}%; background-color: #8b5cf6;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4" style="font-size: 0.875rem;">Belum ada data browser.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Raw Click Data -->
    <div class="tab-pane fade {{ request('tab') == 'data' ? 'show active' : '' }}" id="data" role="tabpanel" aria-labelledby="data-tab">
        <div class="detail-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--glass-border) !important;">
                <h5 class="fw-bold mb-0 d-flex justify-content-between align-items-center" style="color: var(--text-primary);">
                    <span>Log Data Klik</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-normal px-3 py-2 rounded-pill">
                        {{ $rawClicks->total() }} Data Ditemukan
                    </span>
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-glass mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Waktu & Tanggal</th>
                            <th>Negara/Kota</th>
                            <th>OS & Browser</th>
                            <th>Perangkat</th>
                            <th>Referrer</th>
                            <th class="text-center">Unik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rawClicks as $click)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ \Carbon\Carbon::parse($click->datetime)->format('d M Y') }}</div>
                                    <div class="text-muted small">{{ \Carbon\Carbon::parse($click->datetime)->format('H:i:s') }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ empty($click->country_code) ? 'Unknown' : strtoupper($click->country_code) }}</div>
                                    <div class="text-muted small">{{ empty($click->city_name) ? '-' : $click->city_name }}</div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">{{ empty($click->ip) ? '-' : $click->ip }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ empty($click->os) ? 'Unknown OS' : $click->os }}</div>
                                    <div class="text-muted small">{{ empty($click->browser) ? 'Unknown Browser' : $click->browser }}</div>
                                </td>
                                <td>
                                    <div class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 py-1">
                                        {{ empty($click->device_type) ? 'Unknown' : ucfirst($click->device_type) }}
                                    </div>
                                </td>
                                <td style="max-width: 200px;">
                                    <div class="text-truncate" title="{{ empty($click->referrer_host) ? 'Direct' : $click->referrer_host }}">
                                        {{ empty($click->referrer_host) ? 'Direct' : $click->referrer_host }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($click->is_unique)
                                        <span data-duo-icons="check-circle" style="width: 18px; height: 18px; color: #10b981;"></span>
                                    @else
                                        <span data-duo-icons="alert-octagon" style="width: 18px; height: 18px; color: #ef4444;"></span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-2">
                                        <span data-duo-icons="folder-open" style="width: 32px; height: 32px;"></span>
                                    </div>
                                    <div class="text-muted">Tidak ada data klik pada rentang tanggal ini.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($rawClicks->hasPages())
                <div class="p-3 border-top d-flex justify-content-end" style="border-color: var(--glass-border) !important;">
                    {{ $rawClicks->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Initialize Flatpickr Date Range
        flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: ["{{ request('start_date', $startDate->format('Y-m-d')) }}", "{{ request('end_date', $endDate->format('Y-m-d')) }}"],
            onChange: function(selectedDates, dateStr, instance) {
                if(selectedDates.length === 2) {
                    // Update hidden inputs
                    document.getElementById('start_date').value = instance.formatDate(selectedDates[0], "Y-m-d");
                    document.getElementById('end_date').value = instance.formatDate(selectedDates[1], "Y-m-d");
                }
            }
        });

        // Initialize Chart
        const ctx = document.getElementById('linkClicksChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartDates) !!},
                    datasets: [{
                        label: 'Pageviews',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#5ec489',
                        backgroundColor: 'rgba(164, 229, 189, 0.15)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#5ec489',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
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
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0,
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                },
                                maxTicksLimit: 10
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        }
    });
</script>
@endsection
