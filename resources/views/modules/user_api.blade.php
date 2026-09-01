@extends('layouts.app')

@section('title', 'API & Integrations')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">API & Integrations</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Developer Tools</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('api-docs') }}" target="_blank" class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-2">
            <i class="ki-outline ki-document fs-4"></i> Buka Dokumentasi API
            <i class="ki-outline ki-exit-right-corner fs-6"></i>
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3 shadow-sm">
        <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
        <div class="d-flex flex-column">
            <span class="fs-7 text-gray-900 fw-semibold">{{ session('success') }}</span>
        </div>
    </div>
@endif

<!-- API Key Management Card -->
<div class="card card-flush shadow-sm border-0 mb-8">
    <div class="card-header pt-6 pb-2">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900 fs-4">Kunci API Anda (API Key)</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <p class="text-muted fs-7 mb-4">
            Gunakan Kunci API ini untuk mengautentikasi setiap permintaan ke REST API platform. Jaga kerahasiaan kunci ini dan jangan bagikan ke publik.
        </p>

        <div class="row g-4 align-items-center">
            <div class="col-12 col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-key fs-3 text-primary"></i>
                    </span>
                    <input type="password" id="apiKeyInput" class="form-control form-control-solid fw-bold font-monospace fs-6" value="{{ $user->api_key }}" readonly />
                    <button class="btn btn-light-secondary" type="button" id="toggleApiKeyBtn" title="Lihat / Sembunyikan">
                        <i class="ki-outline ki-eye fs-4" id="toggleApiKeyIcon"></i>
                    </button>
                    <button class="btn btn-primary fw-bold" type="button" id="copyApiKeyBtn">
                        <i class="ki-outline ki-copy fs-4 me-1"></i> Salin Kunci
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <button type="button" class="btn btn-light-danger fw-bold btn-sm" data-bs-toggle="modal" data-bs-target="#regenerateKeyModal">
                    <i class="ki-outline ki-arrows-circle fs-4 me-1"></i> Perbarui Kunci (Regenerate)
                </button>
            </div>
        </div>

        <!-- Quick Code Example -->
        <div class="bg-light-primary rounded-3 p-4 mt-5 border border-primary border-dashed">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="fs-8 fw-bolder text-primary text-uppercase">Contoh Autentikasi Request (cURL)</span>
                <span class="badge badge-primary fs-9">Base URL: {{ url('/api/v1') }}</span>
            </div>
            <pre class="bg-dark text-white rounded p-3 mb-0 fs-8 font-monospace"><code>curl -X GET "{{ url('/api/v1/user') }}" \
     -H "Authorization: Bearer {{ $user->api_key }}" \
     -H "Accept: application/json"</code></pre>
        </div>
    </div>
</div>

<!-- 4 Stat Cards -->
<div class="row g-5 g-xl-8 mb-8">
    <div class="col-6 col-xl-3">
        <div class="card card-flush shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-outline ki-code fs-2x text-primary"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($totalAllTime) }}</span>
                    <span class="text-gray-600 fw-semibold fs-7 text-truncate">Total Permintaan</span>
                    <span class="text-muted fs-8">Sepanjang waktu</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card card-flush shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-outline ki-calendar-tick fs-2x text-success"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($todayCalls) }}</span>
                    <span class="text-gray-600 fw-semibold fs-7 text-truncate">Hari Ini</span>
                    <span class="text-muted fs-8">Permintaan 24 jam</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card card-flush shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-info">
                        <i class="ki-outline ki-chart-line-star fs-2x text-info"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($monthCalls) }}</span>
                    <span class="text-gray-600 fw-semibold fs-7 text-truncate">Bulan Ini</span>
                    <span class="text-muted fs-8">{{ date('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card card-flush shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-warning">
                        <i class="ki-outline ki-shield-tick fs-2x text-warning"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $successRate }}%</span>
                    <span class="text-gray-600 fw-semibold fs-7 text-truncate">Tingkat Keberhasilan</span>
                    <span class="text-muted fs-8">Status 2xx OK</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- API Traffic Chart -->
<div class="card card-flush shadow-sm border-0 mb-8">
    <div class="card-header pt-6 pb-2">
        <h3 class="card-title fw-bold text-gray-900 fs-4">Aktivitas Permintaan API (30 Hari Terakhir)</h3>
    </div>
    <div class="card-body pt-0">
        <div id="kt_user_api_chart" style="height: 280px;"></div>
    </div>
</div>

<!-- Recent API Request Logs -->
<div class="card card-flush shadow-sm border-0 mb-6">
    <div class="card-header pt-6 pb-2">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900 fs-4">Log Permintaan Terakhir</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-7 gy-3">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                        <th>Method</th>
                        <th>Endpoint</th>
                        <th>Status Code</th>
                        <th>Latensi</th>
                        <th>Alamat IP</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    @forelse($logs as $log)
                        @php
                            $badgeColor = 'success';
                            if ($log->status_code >= 400 && $log->status_code < 500) $badgeColor = 'warning';
                            if ($log->status_code >= 500) $badgeColor = 'danger';
                        @endphp
                        <tr>
                            <td>
                                <span class="badge badge-light-{{ $log->method == 'GET' ? 'primary' : ($log->method == 'POST' ? 'success' : ($log->method == 'PUT' ? 'warning' : 'danger')) }} fw-bold">
                                    {{ $log->method }}
                                </span>
                            </td>
                            <td>
                                <span class="font-monospace text-gray-900 fw-bold">/{{ ltrim($log->endpoint, '/') }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light-{{ $badgeColor }} fw-bold">{{ $log->status_code }}</span>
                            </td>
                            <td>
                                <span class="text-muted fs-8">{{ round($log->response_time_ms, 1) }} ms</span>
                            </td>
                            <td>
                                <span class="text-gray-600 font-monospace fs-8">{{ $log->ip_address }}</span>
                            </td>
                            <td>
                                <span class="text-muted fs-8">{{ $log->created_at ? $log->created_at->format('d M Y, H:i:s') : '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="ki-outline ki-code fs-2hx text-gray-400 d-block mb-2"></i>
                                Belum ada riwayat panggilan API. Coba buat permintaan pertama Anda menggunakan Kunci API di atas!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= REGENERATE MODAL ================= -->
<div class="modal fade" id="regenerateKeyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">Perbarui Kunci API?</h3>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <div class="modal-body py-4">
                <div class="alert alert-warning d-flex align-items-center p-4 rounded-3 mb-0">
                    <i class="ki-outline ki-information fs-2hx text-warning me-3"></i>
                    <div class="fs-7 text-gray-800">
                        <strong>Perhatian:</strong> Kunci API lama Anda akan segera dinonaktifkan. Seluruh integrasi atau aplikasi eksternal yang menggunakan kunci lama harus diperbarui dengan kunci baru.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-between">
                <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('user.api.regenerate') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger fw-bold">Ya, Perbarui Kunci Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Copy API Key
    var copyBtn = document.getElementById('copyApiKeyBtn');
    var apiKeyInput = document.getElementById('apiKeyInput');
    if (copyBtn && apiKeyInput) {
        copyBtn.addEventListener('click', function () {
            navigator.clipboard.writeText(apiKeyInput.value).then(function () {
                var origHtml = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="ki-outline ki-check fs-4 me-1"></i> Tersalin!';
                copyBtn.classList.replace('btn-primary', 'btn-success');
                setTimeout(function () {
                    copyBtn.innerHTML = origHtml;
                    copyBtn.classList.replace('btn-success', 'btn-primary');
                }, 2000);
            });
        });
    }

    // Toggle reveal key
    var toggleBtn = document.getElementById('toggleApiKeyBtn');
    var toggleIcon = document.getElementById('toggleApiKeyIcon');
    if (toggleBtn && apiKeyInput && toggleIcon) {
        toggleBtn.addEventListener('click', function () {
            if (apiKeyInput.type === 'password') {
                apiKeyInput.type = 'text';
                toggleIcon.classList.replace('ki-eye', 'ki-eye-slash');
            } else {
                apiKeyInput.type = 'password';
                toggleIcon.classList.replace('ki-eye-slash', 'ki-eye');
            }
        });
    }

    // ApexChart for User API
    var chartElement = document.getElementById('kt_user_api_chart');
    if (chartElement) {
        var categories = {!! json_encode($chartCategories) !!};
        var dataSeries = {!! json_encode($chartSeries) !!};

        var options = {
            series: [{
                name: 'Permintaan API',
                data: dataSeries
            }],
            chart: {
                fontFamily: 'inherit',
                type: 'area',
                height: 280,
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            fill: {
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
                width: 3,
                colors: ['#3e97ff']
            },
            xaxis: {
                categories: categories,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#a1a5b7', fontSize: '12px' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#a1a5b7', fontSize: '12px' },
                    formatter: function (val) { return Number(val).toLocaleString(); }
                }
            },
            colors: ['#3e97ff'],
            grid: {
                borderColor: '#f1f1f4',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                style: { fontSize: '12px' },
                y: {
                    formatter: function (val) { return Number(val).toLocaleString() + ' requests'; }
                }
            }
        };

        var chart = new ApexCharts(chartElement, options);
        chart.render();
    }
});
</script>
@endpush
