@extends('layouts.app')

@section('title', 'Manage Domains')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Manajemen Domain</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Platform & Custom Domains</span>
    </div>
    <button class="btn btn-sm btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createDomainModal">
        <i class="ki-outline ki-plus fs-2"></i> Tambah System Domain
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3 shadow-sm">
        <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
        <div class="d-flex flex-column">
            <span class="fs-7 text-gray-900 fw-semibold">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3 shadow-sm">
        <i class="ki-outline ki-cross-circle fs-2hx text-danger me-4"></i>
        <div class="d-flex flex-column">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li class="fs-7 text-gray-900 fw-semibold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@php
    $serverIp = \App\Services\DomainSslService::getServerIp();
@endphp
<div class="card card-flush shadow-sm border-0 mb-6 bg-light-primary border-primary border-dashed">
    <div class="card-body p-5">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
            <div class="d-flex align-items-center gap-3">
                <div class="symbol symbol-45px symbol-circle bg-white">
                    <span class="symbol-label">
                        <i class="ki-outline ki-shield-tick fs-2x text-primary"></i>
                    </span>
                </div>
                <div>
                    <h4 class="text-gray-900 fw-bolder fs-6 mb-1">Server IP untuk DNS A Record</h4>
                    <p class="text-gray-700 fs-7 mb-0">
                        Domain harus mengarahkan <strong>A Record</strong> ke IP server ini sebelum penerbitan SSL Let's Encrypt:
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 bg-white px-4 py-2 rounded-3 border">
                <span class="text-muted fs-8 fw-bold text-uppercase">IP Server:</span>
                <code class="fs-6 text-primary fw-bolder font-monospace" id="serverIpText">{{ $serverIp }}</code>
                <button type="button" class="btn btn-sm btn-icon btn-light" id="copyServerIpBtn" title="Salin IP">
                    <i class="ki-outline ki-copy fs-5"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card card-flush shadow-sm border-0 mb-6">
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-7 gy-4 mb-0">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                        <th class="min-w-180px">Host Domain</th>
                        <th class="min-w-140px">Pemilik</th>
                        <th class="min-w-120px">Tipe</th>
                        <th class="min-w-140px">Status DNS</th>
                        <th class="min-w-140px">Status SSL (HTTPS)</th>
                        <th class="min-w-100px">Status</th>
                        <th class="text-end min-w-140px pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    @forelse($domains as $domain)
                        <tr id="domain-row-{{ $domain->id }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-35px symbol-circle me-3 bg-light-primary">
                                        <span class="symbol-label">
                                            <i class="ki-outline ki-geolocation fs-3 text-primary"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="{{ $domain->scheme . $domain->host }}" target="_blank" class="fw-bold text-gray-900 text-hover-primary fs-6">
                                            {{ $domain->host }}
                                            <i class="ki-outline ki-exit-right-corner fs-8 text-muted ms-1"></i>
                                        </a>
                                        <span class="text-muted fs-8">{{ $domain->scheme }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($domain->user)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-gray-900 fs-7">{{ $domain->user->name }}</span>
                                        <span class="text-muted fs-8">{{ $domain->user->email }}</span>
                                    </div>
                                @else
                                    <span class="badge badge-light-dark fs-8">System</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $domain->type == 1 ? 'badge-light-secondary' : 'badge-light-primary' }} fw-semibold fs-8">
                                    {{ $domain->type == 1 ? 'System' : 'Custom' }}
                                </span>
                            </td>

                            <!-- DNS Status -->
                            <td>
                                <div id="dns-badge-{{ $domain->id }}" class="mb-1">
                                    @if($domain->dns_status === 'verified')
                                        <span class="badge badge-light-success fw-bold fs-8">
                                            <i class="ki-outline ki-check-circle fs-8 text-success me-1"></i> Terverifikasi
                                        </span>
                                    @else
                                        <span class="badge badge-light-warning fw-bold fs-8">
                                            <i class="ki-outline ki-information fs-8 text-warning me-1"></i> Belum Mengarah
                                        </span>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-xs btn-light-info fw-bold py-1 px-2 btn-check-dns" data-id="{{ $domain->id }}" data-url="{{ route('admin.domains.verify-dns', $domain->id) }}">
                                    <i class="ki-outline ki-arrows-circle fs-8 me-1"></i> Cek DNS
                                </button>
                            </td>

                            <!-- SSL Status -->
                            <td>
                                <div id="ssl-badge-{{ $domain->id }}" class="mb-1">
                                    @if($domain->ssl_status === 'active' || $domain->scheme === 'https://')
                                        <span class="badge badge-light-success fw-bold fs-8">
                                            <i class="ki-outline ki-shield-tick fs-8 text-success me-1"></i> HTTPS Aktif 🔒
                                        </span>
                                    @elseif($domain->ssl_status === 'failed')
                                        <span class="badge badge-light-danger fw-bold fs-8">
                                            <i class="ki-outline ki-shield-cross fs-8 text-danger me-1"></i> SSL Gagal
                                        </span>
                                    @else
                                        <span class="badge badge-light-secondary fw-bold fs-8">
                                            <i class="ki-outline ki-shield fs-8 text-muted me-1"></i> Belum Ada SSL
                                        </span>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-xs btn-light-success fw-bold py-1 px-2 btn-provision-ssl" data-id="{{ $domain->id }}" data-url="{{ route('admin.domains.provision-ssl', $domain->id) }}">
                                    <i class="ki-outline ki-key fs-8 me-1"></i> Pasang SSL
                                </button>
                            </td>

                            <td>
                                <div id="domain-status-badge-{{ $domain->id }}">
                                    @if($domain->is_enabled)
                                        <span class="badge badge-light-success fw-bold fs-8">Aktif</span>
                                    @else
                                        <span class="badge badge-light-warning fw-bold fs-8">Pending</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end pe-3">
                                <button class="btn btn-sm btn-icon btn-light-primary me-2" data-bs-toggle="modal" data-bs-target="#editDomainModal{{ $domain->id }}" title="Edit">
                                    <i class="ki-outline ki-pencil fs-5"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-light-danger" data-bs-toggle="modal" data-bs-target="#deleteDomainModal{{ $domain->id }}" title="Hapus">
                                    <i class="ki-outline ki-trash fs-5"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Domain Modal -->
                        <div class="modal fade" id="editDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-3 border-0 shadow-lg">
                                    <div class="modal-header pb-0 border-0 justify-content-between">
                                        <h3 class="modal-title fw-bold text-gray-900">Kelola Domain: {{ $domain->host }}</h3>
                                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.domains.update', $domain->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body py-6 px-lg-8">
                                            <div class="fv-row mb-5">
                                                <label class="form-label fs-6 fw-semibold text-gray-900">Status Domain</label>
                                                <select class="form-select form-select-solid" name="is_enabled">
                                                    <option value="1" {{ $domain->is_enabled ? 'selected' : '' }}>Aktif / Disetujui</option>
                                                    <option value="0" {{ !$domain->is_enabled ? 'selected' : '' }}>Pending / Nonaktif</option>
                                                </select>
                                            </div>

                                            <div class="fv-row mb-5">
                                                <label class="form-label fs-6 fw-semibold text-gray-900">Scheme</label>
                                                <select class="form-select form-select-solid" name="scheme">
                                                    <option value="https://" {{ $domain->scheme == 'https://' ? 'selected' : '' }}>https:// (SSL)</option>
                                                    <option value="http://" {{ $domain->scheme == 'http://' ? 'selected' : '' }}>http://</option>
                                                </select>
                                            </div>

                                            <div class="fv-row mb-5">
                                                <label class="form-label fs-6 fw-semibold text-gray-900">Custom Index URL</label>
                                                <input type="url" class="form-control form-control-solid" name="custom_index_url" value="{{ $domain->custom_index_url }}" placeholder="https://websiteanda.com" />
                                            </div>

                                            <div class="fv-row mb-2">
                                                <label class="form-label fs-6 fw-semibold text-gray-900">Custom 404 URL</label>
                                                <input type="url" class="form-control form-control-solid" name="custom_not_found_url" value="{{ $domain->custom_not_found_url }}" placeholder="https://websiteanda.com/404" />
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0 px-lg-8 pb-6 justify-content-between">
                                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Domain Modal -->
                        <div class="modal fade" id="deleteDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content rounded-3 border-0 shadow-lg">
                                    <div class="modal-body text-center p-6">
                                        <i class="ki-outline ki-information-5 fs-4x text-danger mb-3"></i>
                                        <h4 class="fw-bold text-gray-900 mb-2">Hapus Domain?</h4>
                                        <p class="text-gray-600 fs-7 mb-5">Apakah Anda yakin ingin menghapus domain <strong>{{ $domain->host }}</strong>?</p>
                                        <form action="{{ route('admin.domains.destroy', $domain->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-light flex-grow-1 fw-bold" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sm btn-danger flex-grow-1 fw-bold">Ya, Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-muted">
                                <i class="ki-outline ki-geolocation fs-4x text-muted mb-3"></i>
                                <p class="fs-6 fw-semibold mb-0">Belum ada domain yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Domain Modal -->
<div class="modal fade" id="createDomainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">Tambah System Domain</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form action="{{ route('admin.domains.store') }}" method="POST">
                @csrf
                <div class="modal-body py-6 px-lg-8">
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900 required">Host Domain</label>
                        <input type="text" class="form-control form-control-solid" name="host" placeholder="domain.com" required />
                    </div>

                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Custom Index URL (Opsional)</label>
                        <input type="url" class="form-control form-control-solid" name="custom_index_url" placeholder="https://websiteanda.com" />
                    </div>

                    <div class="fv-row mb-2">
                        <label class="form-label fs-6 fw-semibold text-gray-900">Custom 404 URL (Opsional)</label>
                        <input type="url" class="form-control form-control-solid" name="custom_not_found_url" placeholder="https://websiteanda.com/404" />
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6 justify-content-between">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Tambah Domain</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Copy Server IP
    var copyBtn = document.getElementById('copyServerIpBtn');
    var ipText = document.getElementById('serverIpText');
    if (copyBtn && ipText) {
        copyBtn.addEventListener('click', function () {
            navigator.clipboard.writeText(ipText.innerText.trim()).then(function () {
                copyBtn.innerHTML = '<i class="ki-outline ki-check fs-5 text-success"></i>';
                setTimeout(function () {
                    copyBtn.innerHTML = '<i class="ki-outline ki-copy fs-5"></i>';
                }, 2000);
            });
        });
    }

    var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

    // AJAX Check DNS
    document.querySelectorAll('.btn-check-dns').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var domainId = this.dataset.id;
            var url = this.dataset.url;
            var origHtml = this.innerHTML;
            var button = this;

            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memeriksa...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                button.disabled = false;
                button.innerHTML = origHtml;

                var badgeEl = document.getElementById('dns-badge-' + domainId);
                if (data.success) {
                    badgeEl.innerHTML = '<span class="badge badge-light-success fw-bold fs-8"><i class="ki-outline ki-check-circle fs-8 text-success me-1"></i> Terverifikasi</span>';
                    alert('✅ Sukses: ' + data.message);
                } else {
                    badgeEl.innerHTML = '<span class="badge badge-light-warning fw-bold fs-8"><i class="ki-outline ki-information fs-8 text-warning me-1"></i> Belum Mengarah</span>';
                    alert('⚠️ ' + data.message);
                }
            })
            .catch(function(err) {
                button.disabled = false;
                button.innerHTML = origHtml;
                alert('Gagal memeriksa DNS: ' + err.message);
            });
        });
    });

    // AJAX Provision SSL
    document.querySelectorAll('.btn-provision-ssl').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var domainId = this.dataset.id;
            var url = this.dataset.url;
            var origHtml = this.innerHTML;
            var button = this;

            if (!confirm('Jalankan proses penerbitan sertifikat SSL (HTTPS) otomatis untuk domain ini?')) {
                return;
            }

            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memasang SSL...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                button.disabled = false;
                button.innerHTML = origHtml;

                var badgeEl = document.getElementById('ssl-badge-' + domainId);
                var statusEl = document.getElementById('domain-status-badge-' + domainId);

                if (data.success) {
                    badgeEl.innerHTML = '<span class="badge badge-light-success fw-bold fs-8"><i class="ki-outline ki-shield-tick fs-8 text-success me-1"></i> HTTPS Aktif 🔒</span>';
                    if (statusEl) statusEl.innerHTML = '<span class="badge badge-light-success fw-bold fs-8">Aktif</span>';
                    alert('🔒 ' + data.message);
                } else {
                    badgeEl.innerHTML = '<span class="badge badge-light-danger fw-bold fs-8"><i class="ki-outline ki-shield-cross fs-8 text-danger me-1"></i> SSL Gagal</span>';
                    alert('❌ ' + data.message);
                }
            })
            .catch(function(err) {
                button.disabled = false;
                button.innerHTML = origHtml;
                alert('Gagal memasang SSL: ' + err.message);
            });
        });
    });
});
</script>
@endpush
