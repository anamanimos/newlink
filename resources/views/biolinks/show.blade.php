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
                <li class="breadcrumb-item"><a href="{{ route('biolinks.index') }}" class="text-decoration-none" style="color: var(--primary-color);">Biolinks</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--text-secondary);">{{ $link->url }}</li>
            </ol>
        </nav>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <!-- Date Filter Form -->
        <form method="GET" action="{{ route('biolinks.show', $link->id) }}" id="dateFilterForm" class="d-flex align-items-center gap-2">
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
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab') == 'leads' ? 'active' : '' }}" id="leads-tab" data-bs-toggle="tab" data-bs-target="#leads" type="button" role="tab" aria-controls="leads" aria-selected="{{ request('tab') == 'leads' ? 'true' : 'false' }}" onclick="document.getElementById('active_tab').value='leads'">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg> 
                WhatsApp Leads
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

        @if($link->type === 'biolink' && count($biolinkBlocks) > 0)
            <!-- Button Clicks Statistics Section -->
            <div class="detail-card p-4 mb-4">
                <h5 class="fw-bold mb-4 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary" style="color: var(--primary-color);">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    <span>Statistik Klik per Tombol Biolink</span>
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-dark-custom" style="font-size: 0.9rem;">
                        <thead>
                            <tr class="text-secondary small fw-bold" style="border-bottom: 2px solid var(--glass-border);">
                                <th class="pb-3" style="width: 32%;">Judul Tombol</th>
                                <th class="pb-3" style="width: 28%;">URL Tujuan</th>
                                <th class="pb-3 text-center" style="width: 10%;">Klik</th>
                                <th class="pb-3 text-center" style="width: 10%;">CTR</th>
                                <th class="pb-3" style="width: 12%;">Kontribusi</th>
                                <th class="pb-3 text-end" style="width: 8%;">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($biolinkBlocks as $block)
                                @php
                                    $blockPercent = $totalClicks > 0 ? ($block->clicks / $totalClicks) * 100 : 0;
                                    // CTR calculation: Button Clicks / Total Page Clicks (unique views fallback)
                                    $blockCTR = $totalClicks > 0 ? ($block->clicks / $totalClicks) * 100 : 0;
                                @endphp
                                <tr style="border-bottom: 1px solid var(--glass-border);">
                                    <td class="py-3 fw-semibold text-dark-custom">
                                        {{ $block->settings['title'] ?? 'Tautan Tanpa Judul' }}
                                    </td>
                                    <td class="py-3 text-muted text-truncate" style="max-width: 220px;">
                                        <a href="{{ $block->location_url }}" target="_blank" rel="noopener" class="text-secondary text-decoration-none hover-primary">
                                            {{ $block->location_url }}
                                        </a>
                                    </td>
                                    <td class="py-3 text-center fw-bold text-primary">
                                        {{ number_format($block->clicks) }}
                                    </td>
                                    <td class="py-3 text-center fw-semibold text-secondary">
                                        {{ number_format($blockCTR, 1) }}%
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress-bar-custom mb-1" style="width: 60px; height: 6px; margin-top: 0;">
                                                <div class="progress-bar-fill" style="width: {{ $blockPercent }}%; background-color: var(--primary-color);"></div>
                                            </div>
                                            <span class="small fw-semibold text-muted" style="font-size: 0.75rem;">
                                                {{ number_format($blockPercent, 0) }}%
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-end">
                                        <button class="btn btn-sm btn-outline-primary p-2 d-inline-flex align-items-center justify-content-center btn-block-analytics" data-id="{{ $block->id }}" style="width: 32px; height: 32px; border-radius: 8px;" title="Lihat Detail Analitik Tombol">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="20" x2="18" y2="10" opacity="0.3"></line>
                                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                                <line x1="6" y1="20" x2="6" y2="14" opacity="0.3"></line>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Modal Block Analytics Details -->
        <div class="modal fade" id="blockAnalyticsModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                    <div class="modal-header border-bottom-0 pb-1">
                        <h5 class="modal-title fw-bold text-dark-custom" id="blockAnalyticsTitle">Detail Statistik Tombol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-2">
                        <!-- Top Stats Summary -->
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="detail-card stat-card p-3 d-flex align-items-center gap-3">
                                    <div class="stat-icon" style="background: rgba(56, 232, 173, 0.15); color: var(--primary-color);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14 9 11"/></svg>
                                    </div>
                                    <div>
                                        <div class="stat-label" style="font-size: 0.75rem;">Total Klik Tombol</div>
                                        <div class="stat-value" id="blockTotalClicks" style="font-size: 1.35rem; font-weight: 700;">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="detail-card stat-card p-3 d-flex align-items-center gap-3">
                                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                                    </div>
                                    <div>
                                        <div class="stat-label" style="font-size: 0.75rem;">CTR (Click-Through Rate)</div>
                                        <div class="stat-value" id="blockCTR" style="font-size: 1.35rem; font-weight: 700;">0%</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trend Line Chart -->
                        <div class="detail-card p-4 mb-4">
                            <h6 class="fw-bold mb-3 text-dark-custom" id="blockClicksChartTitle">Tren Klik Tombol</h6>
                            <div style="height: 200px; position: relative;">
                                <canvas id="blockClicksChart"></canvas>
                            </div>
                        </div>

                        <!-- Referrers Breakdown -->
                        <div class="detail-card p-4">
                            <h6 class="fw-bold mb-3 text-dark-custom d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                                <span>Sumber Klik Tombol (Top Referrers)</span>
                            </h6>
                            <div class="info-list" id="blockReferrersList">
                                <!-- Dynamic Referrer Items go here -->
                            </div>
                            <div id="blockNoReferrers" class="text-center text-muted py-4 d-none" style="font-size: 0.875rem;">
                                Belum ada data sumber referal untuk tombol ini.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-1">
                        <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
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

    <!-- Tab 3: WhatsApp Leads -->
    <div class="tab-pane fade {{ request('tab') == 'leads' ? 'show active' : '' }}" id="leads" role="tabpanel" aria-labelledby="leads-tab">
        <div class="glass-card mb-4 border border-secondary border-opacity-10 rounded-3" style="background: var(--card-bg-blur);">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-color: var(--glass-border) !important;">
                <h6 class="fw-bold mb-0 text-dark-custom">Data Pengisian Form WhatsApp Rotator</h6>
                <a href="{{ route('biolinks.leads.export', $link->id) }}" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1.5 fw-semibold px-3 py-1.5 rounded-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Unduh CSV
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-dark-custom" style="font-size: 0.85rem;">
                    <thead>
                        <tr class="text-secondary small fw-bold" style="border-bottom: 2px solid var(--glass-border);">
                            <th class="ps-3 py-3">Waktu</th>
                            <th class="py-3">Nama</th>
                            <th class="py-3">Kota/Kabupaten</th>
                            <th class="py-3">Nomor Visitor</th>
                            <th class="py-3">Pesan</th>
                            <th class="py-3">Admin Terpilih (Rotasi)</th>
                            <th class="py-3">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($whatsappLeads as $lead)
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td class="ps-3 py-3 text-muted">
                                    {{ $lead->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="py-3 fw-semibold">
                                    {{ $lead->name }}
                                </td>
                                <td class="py-3">
                                    {{ $lead->city }}
                                </td>
                                <td class="py-3 text-secondary">
                                    62{{ $lead->phone }}
                                </td>
                                <td class="py-3 text-muted" style="max-width: 250px; word-break: break-word;">
                                    {{ $lead->message ?: '-' }}
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 fw-semibold">
                                        {{ $lead->whatsapp_number_used }}
                                    </span>
                                </td>
                                <td class="py-3 text-muted small">
                                    {{ $lead->ip ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                    </div>
                                    <div class="text-muted">Belum ada data pengisian form WhatsApp Rotator.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($whatsappLeads instanceof \Illuminate\Pagination\AbstractPaginator && $whatsappLeads->hasPages())
                <div class="p-3 border-top d-flex justify-content-end" style="border-color: var(--glass-border) !important;">
                    {{ $whatsappLeads->appends(request()->except('leads_page'))->links('pagination::bootstrap-5') }}
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
        // ──────────────────────────────────────────────────────────────────────────
        // Biolink Button Level Analytics Modal Controller (Vanilla JS)
        // ──────────────────────────────────────────────────────────────────────────
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
            document.getElementById('blockAnalyticsTitle').textContent = 'Statistik Tombol: Loading...';
            document.getElementById('blockTotalClicks').textContent = '0';
            document.getElementById('blockCTR').textContent = '0%';
            document.getElementById('blockClicksChartTitle').textContent = `Tren Klik Tombol (${dateRangeVal})`;
            
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
                        document.getElementById('blockAnalyticsTitle').textContent = `Statistik Tombol: "${response.title}"`;
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
                                        label: 'Klik Tombol',
                                        data: response.chartData,
                                        borderColor: '#6366f1',
                                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                        borderWidth: 2.2,
                                        tension: 0.35,
                                        fill: true,
                                        pointBackgroundColor: '#ffffff',
                                        pointBorderColor: '#6366f1',
                                        pointBorderWidth: 2,
                                        pointRadius: 3,
                                        pointHoverRadius: 5
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                            titleColor: '#fff',
                                            bodyColor: '#fff',
                                            padding: 10,
                                            cornerRadius: 8,
                                            displayColors: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: { color: 'rgba(148, 163, 184, 0.08)', drawBorder: false },
                                            ticks: { precision: 0, color: '#64748b', font: { size: 10 } }
                                        },
                                        x: {
                                            grid: { display: false, drawBorder: false },
                                            ticks: { color: '#64748b', font: { size: 10 }, maxTicksLimit: 7 }
                                        }
                                    },
                                    interaction: { intersect: false, mode: 'index' }
                                }
                            });
                        }

                        // Populate referrers list
                        if (refList) {
                            if (response.referrers && response.referrers.length > 0) {
                                response.referrers.forEach(ref => {
                                    const refItem = `
                                        <div class="info-list-item d-block">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-truncate me-2" style="color: var(--text-primary); font-size: 0.825rem;">${ref.referrer}</span>
                                                <span class="fw-bold text-primary" style="font-size: 0.825rem;">${ref.count.toLocaleString()}</span>
                                            </div>
                                            <div class="progress-bar-custom mb-1" style="height: 5px; margin-top: 0;">
                                                <div class="progress-bar-fill" style="width: ${ref.percent}%; background-color: #6366f1;"></div>
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
                    document.getElementById('blockAnalyticsTitle').textContent = 'Gagal memuat statistik.';
                });
        });
    });
</script>
@endsection
