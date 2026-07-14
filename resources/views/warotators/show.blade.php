@extends('layouts.app')

@section('title', 'Analitik WA Rotator')

@section('content')
<!-- Page Header (Title & Actions) -->
<div class="d-flex align-items-center justify-content-between mb-4 mt-2 flex-wrap gap-3">
    <div>
        <span class="text-secondary small fw-semibold d-inline-flex align-items-center gap-1.5 mb-1.5">
            Analitik & Detail Data
        </span>
        <h4 class="fw-bold mb-0 text-dark-custom" style="font-size: 1.5rem; letter-spacing: -0.5px;">
            WA Rotator: {{ $link->settings['title'] ?? $link->url }}
        </h4>
    </div>
    
    <!-- Filter Date range & Navigation actions -->
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <form action="{{ route('warotators.show', $link->id) }}" method="GET" id="filterDateForm" class="d-flex align-items-center gap-2">
            <!-- Datepicker Input -->
            <div class="input-group glass-input-group align-items-center px-2 py-1 rounded-3" style="width: 250px; height: 42px; border: 1px solid var(--glass-border); background: var(--glass-bg);">
                <span class="text-muted pe-1.5 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </span>
                <input type="text" name="date_range" id="filter_date_range" class="form-control border-0 bg-transparent py-0 text-secondary fw-semibold small" style="outline: none; box-shadow: none;" placeholder="Pilih Tanggal" value="{{ request('date_range', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')) }}">
                <input type="hidden" name="start_date" id="start_date_val" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" id="end_date_val" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            
            <!-- Maintain active tab in query -->
            <input type="hidden" name="tab" id="active_tab" value="{{ request('tab', 'analytics') }}">
            
            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 px-3 shadow-sm" style="height: 42px; border-radius: 8px; font-weight: 500;">
                Filter
            </button>
        </form>

        <a href="{{ route('warotators.index') }}" class="btn btn-light d-flex align-items-center justify-content-center gap-2 px-3 shadow-sm" style="height: 42px; border-radius: 8px; background: var(--glass-bg); border-color: var(--glass-border); color: var(--text-primary); font-weight: 500;">
            Kembali
        </a>
    </div>
</div>

<!-- Header Card Summary -->
<div class="detail-card p-4 mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="p-2 rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                </div>
                <div>
                    @php
                        $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
                    @endphp
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                        <a href="{{ $fullUrl }}" target="_blank" class="text-decoration-none text-success">{{ preg_replace('#^https?://#', '', $fullUrl) }}</a>
                    </h5>
                    <div class="text-muted small mt-1 d-flex align-items-center gap-2 text-truncate" style="max-width: 500px;">
                        <span>Daftar admin dirotasi: {{ $link->settings['numbers'] ?? '' }}</span>
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
<ul class="nav nav-tabs glass-tabs mb-4" id="rotatorDetailTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab', 'analytics') == 'analytics' ? 'active' : '' }}" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab" aria-selected="{{ request('tab', 'analytics') == 'analytics' ? 'true' : 'false' }}" onclick="document.getElementById('active_tab').value='analytics'">
            Ringkasan Analitik
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab') == 'leads' ? 'active' : '' }}" id="leads-tab" data-bs-toggle="tab" data-bs-target="#leads" type="button" role="tab" aria-selected="{{ request('tab') == 'leads' ? 'true' : 'false' }}" onclick="document.getElementById('active_tab').value='leads'">
            WhatsApp Leads (Form entries)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab') == 'clicks' ? 'active' : '' }}" id="clicks-tab" data-bs-toggle="tab" data-bs-target="#clicks" type="button" role="tab" aria-selected="{{ request('tab') == 'clicks' ? 'true' : 'false' }}" onclick="document.getElementById('active_tab').value='clicks'">
            Data Klik Pengunjung
        </button>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="rotatorDetailTabsContent">
    
    <!-- Tab 1: Analytics Summary -->
    <div class="tab-pane fade {{ request('tab', 'analytics') == 'analytics' ? 'show active' : '' }}" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
        <div class="row g-4 mb-4">
            <!-- Stat 1 -->
            <div class="col-md-4">
                <div class="detail-card stat-card h-100">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                    <div>
                        <div class="stat-label">Total Clicks (Views)</div>
                        <div class="stat-value">{{ number_format($totalClicks) }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Stat 2 -->
            <div class="col-md-4">
                <div class="detail-card stat-card h-100">
                    <div class="stat-icon" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <div>
                        <div class="stat-label">WhatsApp Leads Captured</div>
                        <div class="stat-value">{{ number_format($whatsappLeads->total()) }}</div>
                    </div>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="col-md-4">
                <div class="detail-card stat-card h-100">
                    @php
                        $conversionRate = $totalClicks > 0 ? round(($whatsappLeads->total() / $totalClicks) * 100, 1) : 0;
                    @endphp
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                    </div>
                    <div>
                        <div class="stat-label">Conversion Rate (CTR)</div>
                        <div class="stat-value">{{ $conversionRate }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clicks Trend Line Graph Chart -->
        <div class="detail-card p-4 mb-4">
            <h6 class="fw-bold text-dark-custom mb-3">Tren Kunjungan Halaman</h6>
            <div style="height: 300px; position: relative;">
                <canvas id="clicksChart"></canvas>
            </div>
        </div>

        <!-- Regional & Browser metadata tables -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="detail-card p-4 h-100">
                    <h6 class="fw-bold text-dark-custom mb-3">Negara Asal Pengunjung</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0 text-dark-custom">
                            <tbody>
                                @forelse($topCountries as $country)
                                    <tr>
                                        <td class="fw-medium py-2">
                                            <span class="badge bg-light border text-secondary px-2 py-1 rounded small me-2">{{ $country->country_code ?: 'Unknown' }}</span>
                                            {{ $country->country_code ?: 'Direct / Unknown' }}
                                        </td>
                                        <td class="text-end fw-bold py-2">{{ number_format($country->count) }} klik</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4 small">Tidak ada data negara.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="detail-card p-4 h-100">
                    <h6 class="fw-bold text-dark-custom mb-3">Browser & Sistem Operasi</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0 text-dark-custom">
                            <tbody>
                                @forelse($topOs as $os)
                                    <tr>
                                        <td class="fw-medium py-2">{{ $os->os ?: 'Unknown OS' }}</td>
                                        <td class="text-end fw-bold py-2">{{ number_format($os->count) }} klik</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4 small">Tidak ada data OS.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: WhatsApp Leads Entries list -->
    <div class="tab-pane fade {{ request('tab') == 'leads' ? 'show active' : '' }}" id="leads" role="tabpanel" aria-labelledby="leads-tab">
        <div class="glass-card mb-4 border border-secondary border-opacity-10 rounded-3" style="background: var(--card-bg-blur);">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-color: var(--glass-border) !important;">
                <h6 class="fw-bold mb-0 text-dark-custom">Data Pengisian Form WA Rotator</h6>
                <a href="{{ route('warotators.leads.export', $link->id) }}" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1.5 fw-semibold px-3 py-1.5 rounded-3">
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
                                <td colspan="7" class="text-center py-5 text-muted">
                                    Belum ada data pengisian form WhatsApp Rotator.
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

    <!-- Tab 3: Detailed Click Logs -->
    <div class="tab-pane fade {{ request('tab') == 'clicks' ? 'show active' : '' }}" id="clicks" role="tabpanel" aria-labelledby="clicks-tab">
        <div class="glass-card mb-4 border border-secondary border-opacity-10 rounded-3" style="background: var(--card-bg-blur);">
            <div class="p-3 border-bottom" style="border-color: var(--glass-border) !important;">
                <h6 class="fw-bold mb-0 text-dark-custom">Log Aktivitas Klik Detail</h6>
            </div>
            
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-dark-custom" style="font-size: 0.85rem;">
                    <thead>
                        <tr class="text-secondary small fw-bold" style="border-bottom: 2px solid var(--glass-border);">
                            <th class="ps-3 py-3">Waktu</th>
                            <th class="py-3">IP Address</th>
                            <th class="py-3">Negara</th>
                            <th class="py-3">Kota</th>
                            <th class="py-3">OS & Browser</th>
                            <th class="py-3">Referrer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rawClicks as $click)
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td class="ps-3 py-3 text-muted">
                                    {{ date('d M Y H:i:s', strtotime($click->datetime)) }}
                                </td>
                                <td class="py-3 fw-semibold">{{ $click->ip }}</td>
                                <td class="py-3">{{ $click->country_code ?: 'Direct / Unknown' }}</td>
                                <td class="py-3">{{ $click->city_name ?: '-' }}</td>
                                <td class="py-3">{{ $click->os }} / {{ $click->browser }}</td>
                                <td class="py-3 text-muted text-truncate" style="max-width: 150px;">{{ $click->referrer_host ?: 'Direct' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Tidak ada data klik pada rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($rawClicks instanceof \Illuminate\Pagination\AbstractPaginator && $rawClicks->hasPages())
                <div class="p-3 border-top d-flex justify-content-end" style="border-color: var(--glass-border) !important;">
                    {{ $rawClicks->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Scripts Chart & Datepicker -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init Datepicker range bounds
        flatpickr('#filter_date_range', {
            mode: 'range',
            dateFormat: 'Y-m-d',
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const start = instance.formatDate(selectedDates[0], 'Y-m-d');
                    const end = instance.formatDate(selectedDates[1], 'Y-m-d');
                    document.getElementById('start_date_val').value = start;
                    document.getElementById('end_date_val').value = end;
                }
            }
        });

        // Initialize Trend line chart
        const ctx = document.getElementById('clicksChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartDates) !!},
                    datasets: [{
                        label: 'Klik Halaman',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#2ac3a6',
                        backgroundColor: 'rgba(42, 195, 166, 0.05)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#2ac3a6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 1.5,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.04)' },
                            ticks: { font: { family: 'Outfit', size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Outfit', size: 10 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
