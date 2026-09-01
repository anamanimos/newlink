@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
            Welcome back, {{ Auth::user()->name }}! 👋
        </h1>
        <span class="badge badge-light-danger fw-semibold fs-8 px-2 py-1 ms-2">Admin Panel</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <button type="button" class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#syncLegacyModal">
            <i class="ki-outline ki-arrows-circle fs-3 text-primary"></i> Sync Data Aplikasi Lama
        </button>
    </div>
</div>

<!-- Admin Stats Grid (8 Cards) -->
<div class="row g-5 g-xl-8 mb-6">
    <!-- Biolink Pages -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-outline ki-profile-user fs-2x text-primary"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $biolinksCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Biolink Pages</span>
                    <span class="text-muted fs-8">{{ $biolinksThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Shortened Links -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-info">
                        <i class="ki-outline ki-disconnect fs-2x text-info"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $shortLinksCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Shortened Links</span>
                    <span class="text-muted fs-8">{{ $shortLinksThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pageviews Tracked -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-outline ki-chart-simple fs-2x text-success"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($totalPageviews) }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Pageviews</span>
                    <span class="text-muted fs-8">{{ $pageviewsThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Codes -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-warning">
                        <i class="ki-outline ki-scan-barcode fs-2x text-warning"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $qrCodesCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">QR Codes</span>
                    <span class="text-muted fs-8">{{ $qrCodesThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Domains -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-danger">
                        <i class="ki-outline ki-geolocation fs-2x text-danger"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $domainsCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Domains</span>
                    <span class="text-muted fs-8">{{ $domainsThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Users -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-outline ki-people fs-2x text-primary"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $usersCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Users</span>
                    <span class="text-muted fs-8">{{ $usersThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-outline ki-credit-cart fs-2x text-success"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $paymentsCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Payments</span>
                    <span class="text-muted fs-8">{{ $paymentsThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Earned -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-info">
                        <i class="ki-outline ki-dollar fs-2x text-info"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">${{ number_format($earnedCount, 2) }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Earned</span>
                    <span class="text-muted fs-8">${{ number_format($earnedThisMonth, 2) }} this month</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Users Card -->
<div class="card card-flush shadow-sm border-0 mb-6">
    <div class="card-header pt-6">
        <h3 class="card-title fw-bold text-gray-900 fs-4">Latest Users</h3>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-200px">User</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-100px">Plan</th>
                        <th class="text-end min-w-100px pe-3">Joined</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach($latestUsers as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px symbol-circle me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-outline ki-user fs-2 text-primary"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-gray-800 fs-6">{{ $user->name }}</span>
                                        <span class="text-muted fs-7">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->status == 1)
                                    <span class="badge badge-light-success fw-bold fs-8">Active</span>
                                @else
                                    <span class="badge badge-light-secondary fw-bold fs-8">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light-primary fw-semibold fs-8 text-uppercase">
                                    {{ $user->plan_id }}
                                </span>
                            </td>
                            <td class="text-end pe-3 text-muted fs-7">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Sync Legacy Database -->
<div class="modal fade" id="syncLegacyModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px symbol-circle bg-light-primary d-flex align-items-center justify-content-center">
                        <i class="ki-outline ki-arrows-circle fs-2 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="modal-title fw-bold text-gray-900 mb-0">Sinkronisasi Database Aplikasi Lama</h3>
                        <span class="text-muted fs-8">Incremental UPSERT Sync (Bebas Timeout & Duplikasi)</span>
                    </div>
                </div>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal" id="btnModalClose">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>

            <div class="modal-body py-6 px-lg-8">
                <!-- Info Notice -->
                <div class="alert alert-dismissible bg-light-primary border border-primary border-dashed d-flex flex-column flex-sm-row p-4 mb-5 rounded-3">
                    <i class="ki-outline ki-information-5 fs-2hx text-primary me-4 mb-3 mb-sm-0"></i>
                    <div class="d-flex flex-column">
                        <h5 class="fw-bold text-gray-900 mb-1">Aman & Bebas Duplikasi</h5>
                        <span class="fs-7 text-gray-700">Proses ini menarik data secara bertahap (*Users, Projects, Domains, Pixels, Links, Blocks, Clicks*) menggunakan logika UPSERT berdasarkan Primary ID sehingga tidak menghapus data lokal dan tidak membuat duplikat.</span>
                    </div>
                </div>

                <!-- Database Check Box -->
                <div id="syncDbStatus" class="p-3 mb-5 rounded bg-light border d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="spinner-border spinner-border-sm text-primary" id="dbCheckSpinner" role="status"></span>
                        <span class="fs-7 fw-semibold text-gray-800" id="dbStatusText">Mengecek koneksi database lama...</span>
                    </div>
                    <button type="button" class="btn btn-xs btn-light-primary fw-bold" id="btnRecheckDb">Cek Ulang</button>
                </div>

                <!-- Live Progress Section -->
                <div id="syncProgressContainer" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fs-7 fw-bold text-gray-800" id="syncStepLabel">Memulai sinkronisasi...</span>
                        <span class="badge badge-primary fs-8 fw-bold" id="syncPercentLabel">0%</span>
                    </div>
                    <div class="progress h-10px bg-light-primary rounded mb-4">
                        <div id="syncProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Console Output Terminal -->
                <label class="form-label fs-8 fw-bold text-uppercase text-gray-700">Log Sinkronisasi Real-time</label>
                <div id="syncLogConsole" class="bg-dark text-white font-monospace p-3 rounded fs-8 overflow-auto border" style="max-height: 180px; min-height: 130px; line-height: 1.6;">
                    <div class="text-muted">[System ready] Silakan klik tombol "Mulai Sinkronisasi" untuk menjalankan proses sinkronisasi via AJAX bertahap.</div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0 px-lg-8 pb-6 justify-content-between">
                <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal" id="btnCancelSync">Tutup</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-primary fw-bold d-flex align-items-center gap-2" id="btnStartSync">
                        <i class="ki-outline ki-arrows-circle fs-3"></i> Mulai Sinkronisasi
                    </button>
                    <button type="button" class="btn btn-sm btn-success fw-bold d-none d-flex align-items-center gap-2" id="btnFinishReload" onclick="window.location.reload()">
                        <i class="ki-outline ki-check-circle fs-3"></i> Selesai & Refresh Halaman
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkUrl = "{{ route('admin.sync.check') }}";
    const stepUrl = "{{ route('admin.sync.step') }}";
    const csrfToken = "{{ csrf_token() }}";

    const dbCheckSpinner = document.getElementById('dbCheckSpinner');
    const dbStatusText = document.getElementById('dbStatusText');
    const btnRecheckDb = document.getElementById('btnRecheckDb');
    const btnStartSync = document.getElementById('btnStartSync');
    const btnCancelSync = document.getElementById('btnCancelSync');
    const btnModalClose = document.getElementById('btnModalClose');
    const btnFinishReload = document.getElementById('btnFinishReload');
    const syncProgressContainer = document.getElementById('syncProgressContainer');
    const syncProgressBar = document.getElementById('syncProgressBar');
    const syncStepLabel = document.getElementById('syncStepLabel');
    const syncPercentLabel = document.getElementById('syncPercentLabel');
    const syncLogConsole = document.getElementById('syncLogConsole');

    function appendLog(text, type = 'info') {
        const time = new Date().toLocaleTimeString();
        let colorClass = 'text-white';
        if (type === 'success') colorClass = 'text-success';
        else if (type === 'warning') colorClass = 'text-warning';
        else if (type === 'error') colorClass = 'text-danger';
        else if (type === 'muted') colorClass = 'text-muted';

        const line = document.createElement('div');
        line.className = colorClass;
        line.innerHTML = `<span class="text-muted">[${time}]</span> ${text}`;
        syncLogConsole.appendChild(line);
        syncLogConsole.scrollTop = syncLogConsole.scrollHeight;
    }

    // 1. Check DB Connection
    async function checkDbConnection() {
        dbCheckSpinner.classList.remove('d-none');
        dbStatusText.textContent = 'Mengecek koneksi database aplikasi lama...';
        btnStartSync.disabled = true;

        try {
            const res = await fetch(checkUrl);
            const data = await res.json();
            dbCheckSpinner.classList.add('d-none');

            if (data.success) {
                dbStatusText.innerHTML = `<span class="text-success fw-bold">✓ Terhubung</span> (${data.stats.users} Users, ${data.stats.links} Links ditemukan di database lama)`;
                btnStartSync.disabled = false;
                appendLog('Koneksi ke database aplikasi lama terverifikasi.', 'success');
            } else {
                dbStatusText.innerHTML = `<span class="text-danger fw-bold">✗ Gagal Terhubung:</span> ${data.message}`;
                appendLog(data.message, 'error');
            }
        } catch (err) {
            dbCheckSpinner.classList.add('d-none');
            dbStatusText.innerHTML = `<span class="text-danger fw-bold">✗ Gagal Terhubung ke Database Legacy</span>`;
            appendLog('Gagal menghubungkan ke database legacy. Pastikan konfigurasi .env sudah sesuai.', 'error');
        }
    }

    btnRecheckDb.addEventListener('click', checkDbConnection);
    
    // Auto check when modal opens
    const syncModalEl = document.getElementById('syncLegacyModal');
    syncModalEl.addEventListener('show.bs.modal', function () {
        checkDbConnection();
    });

    // 2. Sequential Step-by-Step AJAX Sync
    const stepsSequence = [
        { key: 'users', label: 'Sinkronisasi Users (1/7)...' },
        { key: 'projects', label: 'Sinkronisasi Projects (2/7)...' },
        { key: 'domains', label: 'Sinkronisasi Custom Domains (3/7)...' },
        { key: 'pixels', label: 'Sinkronisasi Tracking Pixels (4/7)...' },
        { key: 'links', label: 'Sinkronisasi Tautan & Shortlinks (5/7)...' },
        { key: 'biolink_blocks', label: 'Sinkronisasi Biolink Blocks (6/7)...' },
        { key: 'track_links', label: 'Sinkronisasi Log Klik (7/7)...' },
        { key: 'finish', label: 'Menyelesaikan & Menyelaraskan Sequence...' }
    ];

    btnStartSync.addEventListener('click', async function() {
        btnStartSync.disabled = true;
        btnCancelSync.disabled = true;
        btnModalClose.style.display = 'none';
        btnStartSync.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span> Sedang Menyinkronkan...`;
        syncProgressContainer.classList.remove('d-none');
        syncLogConsole.innerHTML = '';
        appendLog('Memulai proses sinkronisasi bertahap via AJAX...', 'warning');

        for (let i = 0; i < stepsSequence.length; i++) {
            const stepItem = stepsSequence[i];
            syncStepLabel.textContent = stepItem.label;

            try {
                const response = await fetch(stepUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ step: stepItem.key })
                });

                const result = await response.json();

                if (result.success) {
                    syncProgressBar.style.width = result.progress + '%';
                    syncPercentLabel.textContent = result.progress + '%';
                    appendLog(`✓ [${result.current_step.toUpperCase()}] ${result.message}`, 'success');
                } else {
                    appendLog(`✗ Error pada step ${stepItem.key}: ${result.message}`, 'error');
                    btnStartSync.disabled = false;
                    btnCancelSync.disabled = false;
                    btnModalClose.style.display = 'block';
                    btnStartSync.innerHTML = `<i class="ki-outline ki-arrows-circle fs-3"></i> Coba Lagi`;
                    return;
                }
            } catch (error) {
                appendLog(`✗ Network / Server Error: ${error.message}`, 'error');
                btnStartSync.disabled = false;
                btnCancelSync.disabled = false;
                btnModalClose.style.display = 'block';
                btnStartSync.innerHTML = `<i class="ki-outline ki-arrows-circle fs-3"></i> Coba Lagi`;
                return;
            }
        }

        // Complete
        syncProgressBar.classList.remove('progress-bar-animated', 'progress-bar-striped');
        syncProgressBar.classList.add('bg-success');
        syncProgressBar.style.width = '100%';
        syncPercentLabel.textContent = '100%';
        syncPercentLabel.className = 'badge badge-success fs-8 fw-bold';
        syncStepLabel.innerHTML = '<span class="text-success fw-bold">✓ Sinkronisasi Selesai 100%!</span>';
        appendLog('Seluruh data berhasil disinkronkan dari database lama tanpa duplikasi!', 'success');

        btnStartSync.classList.add('d-none');
        btnFinishReload.classList.remove('d-none');
        btnCancelSync.disabled = false;
        btnModalClose.style.display = 'block';
    });
});
</script>
@endsection
