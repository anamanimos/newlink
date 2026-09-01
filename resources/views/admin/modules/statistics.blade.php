@extends('layouts.app')

@section('title', 'Statistics - ' . ucwords(str_replace('-', ' ', $type)))

@section('content')
<!-- Page Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0 align-items-center">
            Statistics
            <i class="ki-outline ki-information fs-5 text-muted ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Laporan statistik analitik dan pertumbuhan sistem"></i>
        </h1>
    </div>

    <!-- Date Range Picker Dropdown -->
    <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
            <button class="btn btn-sm btn-light-primary fw-bold dropdown-toggle d-flex align-items-center gap-2" type="button" id="dateRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ki-outline ki-calendar fs-4"></i>
                <span>{{ $dateLabel }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm p-3 fs-7" style="min-width: 280px;" aria-labelledby="dateRangeDropdown">
                <li><h6 class="dropdown-header text-uppercase fs-8 fw-bolder text-muted px-2 py-1">Pilih Periode Waktu</h6></li>
                <li><a class="dropdown-item py-2 px-3 rounded {{ $range == 'all_time' ? 'active' : '' }}" href="{{ route('admin.statistics', ['type' => $type, 'range' => 'all_time']) }}"><i class="ki-outline ki-time fs-6 me-2"></i> Sepanjang Waktu (All Time)</a></li>
                <li><a class="dropdown-item py-2 px-3 rounded {{ $range == 'this_year' ? 'active' : '' }}" href="{{ route('admin.statistics', ['type' => $type, 'range' => 'this_year']) }}"><i class="ki-outline ki-calendar-tick fs-6 me-2"></i> Tahun Ini ({{ date('Y') }})</a></li>
                <li><a class="dropdown-item py-2 px-3 rounded {{ $range == 'this_month' ? 'active' : '' }}" href="{{ route('admin.statistics', ['type' => $type, 'range' => 'this_month']) }}"><i class="ki-outline ki-calendar-8 fs-6 me-2"></i> Bulan Ini ({{ date('M Y') }})</a></li>
                <li><a class="dropdown-item py-2 px-3 rounded {{ $range == 'last_month' ? 'active' : '' }}" href="{{ route('admin.statistics', ['type' => $type, 'range' => 'last_month']) }}"><i class="ki-outline ki-calendar-search fs-6 me-2"></i> Bulan Lalu</a></li>
                <li><a class="dropdown-item py-2 px-3 rounded {{ $range == 'last_30_days' ? 'active' : '' }}" href="{{ route('admin.statistics', ['type' => $type, 'range' => 'last_30_days']) }}"><i class="ki-outline ki-calendar fs-6 me-2"></i> 30 Hari Terakhir</a></li>
                <li><a class="dropdown-item py-2 px-3 rounded {{ $range == 'last_7_days' ? 'active' : '' }}" href="{{ route('admin.statistics', ['type' => $type, 'range' => 'last_7_days']) }}"><i class="ki-outline ki-calendar-tick fs-6 me-2"></i> 7 Hari Terakhir</a></li>
                <li><a class="dropdown-item py-2 px-3 rounded {{ $range == 'today' ? 'active' : '' }}" href="{{ route('admin.statistics', ['type' => $type, 'range' => 'today']) }}"><i class="ki-outline ki-sun fs-6 me-2"></i> Hari Ini</a></li>
                <li><hr class="dropdown-divider my-2"></li>
                <li>
                    <form method="GET" action="{{ route('admin.statistics', ['type' => $type]) }}" class="px-2 py-1">
                        <input type="hidden" name="range" value="custom">
                        <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Kustom Rentang Tanggal</label>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <input type="date" name="start_date" class="form-control form-control-sm form-control-solid" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" required>
                            </div>
                            <div class="col-6">
                                <input type="date" name="end_date" class="form-control form-control-sm form-control-solid" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-xs btn-primary w-100 fw-bold">Terapkan Filter</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-6 g-xl-9">
    <!-- Left Navigation Column (Sticky Tabs) -->
    <div class="col-12 col-lg-4 col-xl-3">
        <div class="card card-flush shadow-sm border-0 mb-6 position-sticky" style="top: 115px; z-index: 95;">
            <div class="card-body p-4">
                <div class="menu menu-column menu-rounded menu-gray-700 menu-state-bg-light-primary menu-state-title-primary fw-semibold fs-7 gap-1">
                    
                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'users-growth', 'range' => $range]) }}" class="menu-link {{ $type == 'users-growth' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-chart-simple-3 fs-4 me-3 text-success"></i> Users growth
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'users', 'range' => $range]) }}" class="menu-link {{ $type == 'users' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-people fs-4 me-3 text-primary"></i> Users stats
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'users-map', 'range' => $range]) }}" class="menu-link {{ $type == 'users-map' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-map fs-4 me-3 text-info"></i> Users map
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'database', 'range' => $range]) }}" class="menu-link {{ $type == 'database' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-data fs-4 me-3 text-warning"></i> Database
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'broadcasts', 'range' => $range]) }}" class="menu-link {{ $type == 'broadcasts' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-notification-status fs-4 me-3 text-danger"></i> Broadcasts
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'users-notifications', 'range' => $range]) }}" class="menu-link {{ $type == 'users-notifications' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-notification-bing fs-4 me-3 text-dark"></i> Users notifications
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'shortened-links', 'range' => $range]) }}" class="menu-link {{ $type == 'shortened-links' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-fasten fs-4 me-3 text-primary"></i> Shortened links
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'biolink-pages', 'range' => $range]) }}" class="menu-link {{ $type == 'biolink-pages' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-profile-circle fs-4 me-3 text-primary"></i> Biolink pages
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'links-statistics', 'range' => $range]) }}" class="menu-link {{ $type == 'links-statistics' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-chart-line-star fs-4 me-3 text-info"></i> Links statistics
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'biolinks-blocks', 'range' => $range]) }}" class="menu-link {{ $type == 'biolinks-blocks' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-element-11 fs-4 me-3 text-success"></i> Biolinks blocks
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'projects', 'range' => $range]) }}" class="menu-link {{ $type == 'projects' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-folder fs-4 me-3 text-warning"></i> Projects
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'domains', 'range' => $range]) }}" class="menu-link {{ $type == 'domains' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-geolocation fs-4 me-3 text-primary"></i> Domains
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'pixels', 'range' => $range]) }}" class="menu-link {{ $type == 'pixels' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-tag fs-4 me-3 text-danger"></i> Pixels
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'whatsapp-leads', 'range' => $range]) }}" class="menu-link {{ $type == 'whatsapp-leads' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-messages fs-4 me-3 text-success"></i> WhatsApp Leads
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('admin.statistics', ['type' => 'api', 'range' => $range]) }}" class="menu-link {{ $type == 'api' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-code fs-4 me-3 text-primary"></i> API Analytics
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Right Main Content Area -->
    <div class="col-12 col-lg-8 col-xl-9">
        
        <!-- Main Statistics Chart Card -->
        <div class="card card-flush shadow-sm border-0 mb-6">
            <div class="card-header pt-6 pb-2">
                <div class="card-title d-flex align-items-center gap-3">
                    @php
                        $tabTitles = [
                            'users-growth' => ['title' => 'Users Growth', 'icon' => 'ki-chart-simple-3', 'color' => 'success'],
                            'users' => ['title' => 'Users Statistics', 'icon' => 'ki-people', 'color' => 'primary'],
                            'users-map' => ['title' => 'Users Map & Geolocation', 'icon' => 'ki-map', 'color' => 'info'],
                            'database' => ['title' => 'Database Status & Size', 'icon' => 'ki-data', 'color' => 'warning'],
                            'broadcasts' => ['title' => 'Broadcasts & Announcements', 'icon' => 'ki-notification-status', 'color' => 'danger'],
                            'users-notifications' => ['title' => 'Users Notifications', 'icon' => 'ki-notification-bing', 'color' => 'dark'],
                            'shortened-links' => ['title' => 'Shortened links', 'icon' => 'ki-fasten', 'color' => 'primary'],
                            'biolink-pages' => ['title' => 'Biolink pages', 'icon' => 'ki-profile-circle', 'color' => 'primary'],
                            'links-statistics' => ['title' => 'Links statistics (Clicks)', 'icon' => 'ki-chart-line-star', 'color' => 'info'],
                            'biolinks-blocks' => ['title' => 'Biolinks blocks', 'icon' => 'ki-element-11', 'color' => 'success'],
                            'projects' => ['title' => 'Projects', 'icon' => 'ki-folder', 'color' => 'warning'],
                            'domains' => ['title' => 'Custom Domains', 'icon' => 'ki-geolocation', 'color' => 'primary'],
                            'pixels' => ['title' => 'Tracking Pixels', 'icon' => 'ki-tag', 'color' => 'danger'],
                            'whatsapp-leads' => ['title' => 'WhatsApp Leads Captured', 'icon' => 'ki-messages', 'color' => 'success'],
                            'api' => ['title' => 'REST API Traffic & Analytics', 'icon' => 'ki-code', 'color' => 'primary'],
                        ];
                        $curr = $tabTitles[$type] ?? ['title' => ucwords(str_replace('-', ' ', $type)), 'icon' => 'ki-chart-line', 'color' => 'primary'];
                    @endphp
                    <div class="symbol symbol-40px symbol-circle bg-light-{{ $curr['color'] }}">
                        <span class="symbol-label">
                            <i class="ki-outline {{ $curr['icon'] }} fs-3 text-{{ $curr['color'] }}"></i>
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="fw-bold text-gray-900 fs-4 mb-0">{{ $curr['title'] }}</h3>
                        <span class="text-muted fs-8">{{ $dateLabel }}</span>
                    </div>
                </div>
                <div class="card-toolbar">
                    <span class="badge badge-light-success fw-bolder fs-7 px-3 py-2">{{ $badgeChange }}</span>
                </div>
            </div>

            <div class="card-body pt-2 pb-6">
                <!-- Interactive Chart -->
                <div id="kt_statistics_chart" style="min-height: 320px;" class="mb-4"></div>

                <!-- 3 Mini Summary Indicators -->
                <div class="row g-4 pt-4 border-top">
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Dalam Periode Ini</span>
                            <span class="fs-4 fw-bolder text-gray-900">{{ number_format($totalInPeriod) }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Total Keseluruhan</span>
                            <span class="fs-4 fw-bolder text-gray-900">{{ number_format($totalAllTime) }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="text-muted fs-8 fw-semibold text-uppercase">Rata-rata / Status</span>
                            <span class="fs-4 fw-bolder text-primary">
                                @if($type === 'database')
                                    {{ $extraData['db_size_mb'] ?? 0 }} MB
                                @elseif($activeCount > 0)
                                    {{ number_format($activeCount) }} Aktif
                                @else
                                    {{ $totalInPeriod > 0 ? round($totalInPeriod / max(1, count($chartCategories)), 1) : 0 }} / periode
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Tab Breakdown Section -->
        @if($type === 'shortened-links' && isset($extraData['top_links']))
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-6 pb-2">
                    <h4 class="card-title fw-bold text-gray-900 fs-5">Tautan Pendek Terpopuler (Top Clicks)</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-7 gy-3">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                                    <th>Alias / URL</th>
                                    <th>Target URL</th>
                                    <th>Klik</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @forelse($extraData['top_links'] as $topLink)
                                    <tr>
                                        <td>
                                            <a href="{{ url($topLink->url) }}" target="_blank" class="text-primary fw-bold">/{{ $topLink->url }}</a>
                                        </td>
                                        <td class="text-truncate" style="max-width: 250px;">{{ $topLink->location_url }}</td>
                                        <td><span class="badge badge-light-primary fw-bold">{{ number_format($topLink->clicks) }}</span></td>
                                        <td>
                                            <span class="badge badge-light-{{ $topLink->is_enabled ? 'success' : 'danger' }} fs-9">
                                                {{ $topLink->is_enabled ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada tautan pendek.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @elseif($type === 'biolink-pages' && isset($extraData['top_biolinks']))
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-6 pb-2">
                    <h4 class="card-title fw-bold text-gray-900 fs-5">Halaman Biolink Terpopuler (Top Views)</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-7 gy-3">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                                    <th>Biolink Slug</th>
                                    <th>Total Tampilan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @forelse($extraData['top_biolinks'] as $topBio)
                                    <tr>
                                        <td>
                                            <a href="{{ url($topBio->url) }}" target="_blank" class="text-primary fw-bold">/{{ $topBio->url }}</a>
                                        </td>
                                        <td><span class="badge badge-light-primary fw-bold">{{ number_format($topBio->clicks) }}</span></td>
                                        <td>
                                            <span class="badge badge-light-{{ $topBio->is_enabled ? 'success' : 'danger' }} fs-9">
                                                {{ $topBio->is_enabled ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada halaman biolink.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @elseif(($type === 'users' || $type === 'users-growth') && isset($extraData['plan_distribution']))
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-6 pb-2">
                    <h4 class="card-title fw-bold text-gray-900 fs-5">Distribusi Paket Pengguna</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        @foreach($extraData['plan_distribution'] as $planId => $count)
                            <div class="col-md-4">
                                <div class="border border-dashed border-gray-300 rounded p-4 text-center">
                                    <span class="text-muted fs-8 fw-bold text-uppercase">{{ $planId ?: 'Free' }}</span>
                                    <div class="fs-2hx fw-bold text-gray-900">{{ number_format($count) }}</div>
                                    <span class="text-muted fs-8">Pengguna</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @elseif($type === 'users-map' && isset($extraData['top_countries']))
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-6 pb-2">
                    <h4 class="card-title fw-bold text-gray-900 fs-5">Top 10 Negara Pengunjung</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-7 gy-3">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                                    <th>Negara</th>
                                    <th>Kode Negara</th>
                                    <th>Total Klik</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @forelse($extraData['top_countries'] as $c)
                                    <tr>
                                        <td class="fw-bold text-gray-900">{{ strtoupper($c->country_code) }}</td>
                                        <td><span class="badge badge-light-secondary fw-bold">{{ $c->country_code }}</span></td>
                                        <td><span class="badge badge-light-info fw-bold">{{ number_format($c->total) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data geolokasi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @elseif($type === 'database' && isset($extraData['table_list']))
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-6 pb-2">
                    <h4 class="card-title fw-bold text-gray-900 fs-5">Daftar Tabel Database MySQL</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-7 gy-3">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                                    <th>Nama Tabel</th>
                                    <th>Engine</th>
                                    <th>Jumlah Baris (Rows)</th>
                                    <th>Ukuran Data</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @foreach($extraData['table_list'] as $table)
                                    <tr>
                                        <td class="fw-bold text-gray-900">{{ $table['name'] }}</td>
                                        <td><span class="badge badge-light-secondary fs-9">{{ $table['engine'] }}</span></td>
                                        <td>{{ number_format($table['rows']) }}</td>
                                        <td><span class="badge badge-light-primary fw-bold">{{ $table['size_mb'] }} MB</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @elseif($type === 'links-statistics' && isset($extraData['devices']))
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-6 pb-2">
                    <h4 class="card-title fw-bold text-gray-900 fs-5">Distribusi Perangkat Pengunjung</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        @foreach($extraData['devices'] as $dev => $cnt)
                            <div class="col-md-4">
                                <div class="border border-dashed border-gray-300 rounded p-4 text-center">
                                    <span class="text-muted fs-8 fw-bold text-uppercase">{{ $dev ?: 'Desktop' }}</span>
                                    <div class="fs-2hx fw-bold text-primary">{{ number_format($cnt) }}</div>
                                    <span class="text-muted fs-8">Klik</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @elseif($type === 'biolinks-blocks' && isset($extraData['block_types']))
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-6 pb-2">
                    <h4 class="card-title fw-bold text-gray-900 fs-5">Rincian Tipe Blok Biolink</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-7 gy-3">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                                    <th>Tipe Blok</th>
                                    <th>Jumlah Dibuat</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @foreach($extraData['block_types'] as $bt)
                                    <tr>
                                        <td class="fw-bold text-gray-900 text-capitalize">{{ str_replace('_', ' ', $bt->type) }}</td>
                                        <td><span class="badge badge-light-success fw-bold">{{ number_format($bt->total) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @elseif($type === 'pixels' && isset($extraData['pixel_types']))
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-6 pb-2">
                    <h4 class="card-title fw-bold text-gray-900 fs-5">Rincian Tipe Tracking Pixel</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        @foreach($extraData['pixel_types'] as $pt)
                            <div class="col-md-3">
                                <div class="border border-dashed border-gray-300 rounded p-4 text-center">
                                    <span class="text-muted fs-8 fw-bold text-uppercase">{{ $pt->type }}</span>
                                    <div class="fs-2hx fw-bold text-danger">{{ number_format($pt->total) }}</div>
                                    <span class="text-muted fs-8">Pixel Terpasang</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @elseif($type === 'api')
            <!-- API Breakdown Cards -->
            <div class="row g-6 mb-6">
                <!-- Status Codes -->
                <div class="col-md-6">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-6 pb-2">
                            <h4 class="card-title fw-bold text-gray-900 fs-5">Distribusi Status Kode HTTP</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column gap-3">
                                @forelse($extraData['status_codes'] ?? [] as $sc)
                                    @php
                                        $badgeColor = 'success';
                                        if ($sc->status_code >= 400 && $sc->status_code < 500) $badgeColor = 'warning';
                                        if ($sc->status_code >= 500) $badgeColor = 'danger';
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between border border-dashed border-gray-200 rounded p-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge badge-light-{{ $badgeColor }} fw-bold fs-7">{{ $sc->status_code }}</span>
                                            <span class="fs-7 fw-semibold text-gray-700">
                                                @if($sc->status_code == 200) 200 OK
                                                @elseif($sc->status_code == 201) 201 Created
                                                @elseif($sc->status_code == 401) 401 Unauthorized
                                                @elseif($sc->status_code == 404) 404 Not Found
                                                @elseif($sc->status_code == 422) 422 Unprocessable Entity
                                                @elseif($sc->status_code == 429) 429 Rate Limited
                                                @else HTTP {{ $sc->status_code }}
                                                @endif
                                            </span>
                                        </div>
                                        <span class="fs-6 fw-bold text-gray-900">{{ number_format($sc->total) }} req</span>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-4 fs-7">Belum ada data request API.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Users -->
                <div class="col-md-6">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-6 pb-2">
                            <h4 class="card-title fw-bold text-gray-900 fs-5">Pengguna API Teraktif</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column gap-3">
                                @forelse($extraData['top_api_users'] ?? [] as $tu)
                                    <div class="d-flex align-items-center justify-content-between border border-dashed border-gray-200 rounded p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="symbol symbol-35px symbol-circle bg-light-primary">
                                                <span class="symbol-label text-primary fw-bold">{{ substr($tu->user ? $tu->user->name : 'U', 0, 1) }}</span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="fs-7 fw-bold text-gray-900">{{ $tu->user ? $tu->user->name : 'User #' . $tu->user_id }}</span>
                                                <span class="text-muted fs-8">{{ $tu->user ? $tu->user->email : '-' }}</span>
                                            </div>
                                        </div>
                                        <span class="badge badge-light-primary fw-bold fs-7">{{ number_format($tu->total) }} calls</span>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-4 fs-7">Belum ada request dari pengguna.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Endpoints Table -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-6 pb-2">
                    <h4 class="card-title fw-bold text-gray-900 fs-5">Endpoint API Paling Banyak Diakses</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-7 gy-3">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                                    <th>Method & Endpoint</th>
                                    <th>Total Requests</th>
                                    <th>Rata-rata Latensi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @forelse($extraData['top_endpoints'] ?? [] as $ep)
                                    <tr>
                                        <td>
                                            <span class="badge badge-light-{{ $ep->method == 'GET' ? 'primary' : ($ep->method == 'POST' ? 'success' : ($ep->method == 'PUT' ? 'warning' : 'danger')) }} fw-bold me-2">{{ $ep->method }}</span>
                                            <span class="font-monospace text-gray-900 fw-bold">/{{ ltrim($ep->endpoint, '/') }}</span>
                                        </td>
                                        <td><span class="badge badge-light-info fw-bold">{{ number_format($ep->total) }}</span></td>
                                        <td><span class="text-muted fs-8">{{ round($ep->avg_latency, 1) }} ms</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada log akses endpoint.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var element = document.getElementById('kt_statistics_chart');
    if (!element) return;

    var categories = {!! json_encode($chartCategories) !!};
    var dataSeries = {!! json_encode($chartSeries) !!};

    var isBarChart = @json($type === 'users-map' || $type === 'database');

    var options = {
        series: [{
            name: @json($curr['title']),
            data: dataSeries
        }],
        chart: {
            fontFamily: 'inherit',
            type: isBarChart ? 'bar' : 'area',
            height: 320,
            toolbar: {
                show: false
            }
        },
        plotOptions: isBarChart ? {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                borderRadius: 4
            }
        } : {},
        legend: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        fill: isBarChart ? {} : {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            show: true,
            width: isBarChart ? 0 : 3,
            colors: ['#3e97ff']
        },
        xaxis: {
            categories: categories,
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            labels: {
                style: {
                    colors: '#a1a5b7',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#a1a5b7',
                    fontSize: '12px'
                },
                formatter: function (val) {
                    return Number(val).toLocaleString();
                }
            }
        },
        states: {
            normal: {
                filter: {
                    type: 'none',
                    value: 0
                }
            },
            hover: {
                filter: {
                    type: 'none',
                    value: 0
                }
            },
            active: {
                allowMultipleDataPointsSelection: false,
                filter: {
                    type: 'none',
                    value: 0
                }
            }
        },
        tooltip: {
            style: {
                fontSize: '12px'
            },
            y: {
                formatter: function (val) {
                    return Number(val).toLocaleString();
                }
            }
        },
        colors: ['#3e97ff'],
        grid: {
            borderColor: '#f1f1f4',
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true
                }
            }
        },
        markers: {
            colors: ['#3e97ff'],
            strokeColor: '#ffffff',
            strokeWidth: 3
        }
    };

    var chart = new ApexCharts(element, options);
    chart.render();
});
</script>
@endpush
