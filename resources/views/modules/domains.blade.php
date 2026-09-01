@extends('layouts.app')

@section('title', 'Custom Domains')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Custom Domains</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Branding</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        @if($domainLimit !== -1)
            <span class="badge badge-light fw-bold fs-7">
                {{ $domains->count() }} / {{ $domainLimit }} Used
            </span>
        @endif
        <button class="btn btn-sm btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createDomainModal" {{ ($domainLimit !== -1 && $domains->count() >= $domainLimit) ? 'disabled' : '' }}>
            <i class="ki-outline ki-plus fs-2"></i> Hubungkan Domain
        </button>
    </div>
</div>

@php
    $serverIp = \App\Services\DomainSslService::getServerIp();
@endphp
<div class="card card-flush shadow-sm border-0 mb-6 bg-light-primary border-primary border-dashed">
    <div class="card-body p-5">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
            <div class="d-flex align-items-center gap-3">
                <div class="symbol symbol-45px symbol-circle bg-white">
                    <span class="symbol-label">
                        <i class="ki-outline ki-geolocation fs-2x text-primary"></i>
                    </span>
                </div>
                <div>
                    <h4 class="text-gray-900 fw-bolder fs-6 mb-1">Panduan Arahkan DNS Domain Anda</h4>
                    <p class="text-gray-700 fs-7 mb-0">
                        Buat <strong>A Record</strong> di penyedia DNS / Cloudflare Anda dan arahkan ke IP server:
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

<div class="row g-6 g-xl-9">
    @if($domains->isEmpty())
        <div class="col-12">
            <div class="card card-flush shadow-sm border-0 p-10 text-center">
                <i class="ki-outline ki-geolocation fs-4x text-muted mb-3"></i>
                <p class="text-gray-600 fw-semibold fs-6 mb-0">Belum ada domain kustom yang terhubung.</p>
                <div class="mt-4">
                    <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createDomainModal">
                        Hubungkan Domain Pertama
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="card card-flush shadow-sm border-0">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-7 gy-4 mb-0">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                                    <th class="min-w-180px">Host Domain</th>
                                    <th class="min-w-160px">Tautan Terkoneksi</th>
                                    <th class="min-w-140px">Status DNS</th>
                                    <th class="min-w-140px">Status SSL (HTTPS)</th>
                                    <th class="min-w-100px">Status Domain</th>
                                    <th class="text-end min-w-160px pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 fw-semibold">
                                @foreach($domains as $domain)
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
                                                    <span class="text-muted fs-8">
                                                        {{ $domain->type == 1 ? 'System Domain' : 'Custom Domain' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Connected Links Count -->
                                        <td>
                                            @if($domain->links_count > 0)
                                                <div class="d-flex flex-column gap-1">
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="badge badge-light-primary fw-bolder fs-8">
                                                            <i class="ki-outline ki-abstract-26 fs-8 text-primary me-1"></i>{{ $domain->links_count }} Total
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-wrap align-items-center gap-1">
                                                        @if($domain->short_links_count > 0)
                                                            <span class="badge badge-light-info fs-9 py-1 px-2" title="Tautan Pendek (Short Links)">
                                                                <i class="ki-outline ki-link fs-9 me-1 text-info"></i>{{ $domain->short_links_count }} Link
                                                            </span>
                                                        @endif
                                                        @if($domain->biolinks_count > 0)
                                                            <span class="badge badge-light-success fs-9 py-1 px-2" title="Halaman Bio (Biolinks)">
                                                                <i class="ki-outline ki-profile-user fs-9 me-1 text-success"></i>{{ $domain->biolinks_count }} Bio
                                                            </span>
                                                        @endif
                                                        @if($domain->wa_rotators_count > 0)
                                                            <span class="badge badge-light-warning fs-9 py-1 px-2" title="WhatsApp Rotator">
                                                                <i class="ki-outline ki-whatsapp fs-9 me-1 text-warning"></i>{{ $domain->wa_rotators_count }} Rotator
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge badge-light text-muted fs-8">
                                                    <i class="ki-outline ki-cross fs-8 text-muted me-1"></i>0 Tautan
                                                </span>
                                            @endif
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
                                            <button type="button" class="btn btn-xs btn-light-info fw-bold py-1 px-2 btn-check-dns" data-id="{{ $domain->id }}" data-url="{{ route('domains.verify-dns', $domain->id) }}">
                                                <i class="ki-outline ki-arrows-circle fs-8 me-1"></i> Cek DNS
                                            </button>
                                        </td>

                                        <!-- SSL Status -->
                                        <td>
                                            <div id="ssl-badge-{{ $domain->id }}" class="mb-1">
                                                @if(\App\Services\DomainSslService::isSslActive($domain))
                                                    <span class="badge badge-light-success fw-bold fs-8">
                                                        <i class="ki-outline ki-shield-tick fs-8 text-success me-1"></i> HTTPS Aktif 🔒
                                                    </span>
                                                @elseif($domain->ssl_status === 'failed')
                                                    <span class="badge badge-light-danger fw-bold fs-8">
                                                        <i class="ki-outline ki-shield-cross fs-8 text-danger me-1"></i> SSL Gagal
                                                    </span>
                                                @else
                                                    <span class="badge badge-light-secondary fw-bold fs-8">
                                                        <i class="ki-outline ki-shield fs-8 text-muted me-1"></i> Belum Terpasang
                                                    </span>
                                                @endif
                                            </div>
                                            @if($domain->type == 0)
                                                <button type="button" class="btn btn-xs btn-light-success fw-bold py-1 px-2 btn-provision-ssl" data-id="{{ $domain->id }}" data-url="{{ route('domains.provision-ssl', $domain->id) }}">
                                                    <i class="ki-outline ki-key fs-8 me-1"></i> Pasang SSL
                                                </button>
                                            @endif
                                        </td>

                                        <!-- Domain Status -->
                                        <td>
                                            <div id="domain-status-badge-{{ $domain->id }}">
                                                @if($domain->is_enabled)
                                                    <span class="badge badge-light-success fw-bold fs-8">Aktif</span>
                                                @else
                                                    <span class="badge badge-light-warning fw-bold fs-8">Pending</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-end pe-3">
                                            @if($domain->type == 0)
                                                <button class="btn btn-sm btn-icon btn-light-primary me-2" data-bs-toggle="modal" data-bs-target="#editDomainModal{{ $domain->id }}" title="Edit Pengaturan">
                                                    <i class="ki-outline ki-pencil fs-5"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-light-danger" data-bs-toggle="modal" data-bs-target="#deleteDomainModal{{ $domain->id }}" title="Hapus Domain">
                                                    <i class="ki-outline ki-trash fs-5"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Domain Modal -->
                                    <div class="modal fade" id="editDomainModal{{ $domain->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-3 border-0 shadow-lg">
                                                <div class="modal-header pb-0 border-0 justify-content-between">
                                                    <h3 class="modal-title fw-bold text-gray-900">Pengaturan Domain</h3>
                                                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                        <i class="ki-outline ki-cross fs-1"></i>
                                                    </div>
                                                </div>
                                                <form class="ajax-form" action="{{ route('domains.update', $domain->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body py-6 px-lg-8">
                                                        <div class="fv-row mb-5">
                                                            <label class="form-label fs-6 fw-semibold text-gray-900">Host Domain</label>
                                                            <input type="text" class="form-control form-control-solid fw-bold" value="{{ $domain->host }}" disabled />
                                                        </div>

                                                        <div class="fv-row mb-5">
                                                            <label class="form-label fs-6 fw-semibold text-gray-900">Custom Index URL (Opsional)</label>
                                                            <input type="url" class="form-control form-control-solid" name="custom_index_url" value="{{ $domain->custom_index_url }}" placeholder="https://websiteanda.com" />
                                                            <div class="form-text text-muted fs-8">Alihkan pengunjung halaman utama ({{ $domain->host }}) ke URL ini.</div>
                                                        </div>

                                                        <div class="fv-row mb-2">
                                                            <label class="form-label fs-6 fw-semibold text-gray-900">Custom 404 URL (Opsional)</label>
                                                            <input type="url" class="form-control form-control-solid" name="custom_not_found_url" value="{{ $domain->custom_not_found_url }}" placeholder="https://websiteanda.com/404" />
                                                            <div class="form-text text-muted fs-8">Alihkan tautan yang tidak ditemukan ke URL ini.</div>
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
                                                    <form class="ajax-delete-form" action="{{ route('domains.destroy', $domain->id) }}" method="POST">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Create Domain Modal -->
<div class="modal fade" id="createDomainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">Hubungkan Custom Domain</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form class="ajax-form" action="{{ route('domains.store') }}" method="POST">
                @csrf
                <div class="modal-body py-6 px-lg-8">
                    <div class="fv-row mb-5">
                        <label class="form-label fs-6 fw-semibold text-gray-900 required">Host Domain</label>
                        <input type="text" class="form-control form-control-solid" name="host" placeholder="link.domainanda.com" required />
                        <div class="form-text text-muted fs-8">Pastikan Anda telah mengarahkan A Record domain ke IP: <strong>{{ $serverIp }}</strong></div>
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
                    <button type="submit" class="btn btn-primary fw-bold">Hubungkan Domain</button>
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

    // AJAX Check DNS with SweetAlert2
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
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                button.disabled = false;
                button.innerHTML = origHtml;

                var badgeEl = document.getElementById('dns-badge-' + domainId);
                var sslBadgeEl = document.getElementById('ssl-badge-' + domainId);
                var statusEl = document.getElementById('domain-status-badge-' + domainId);

                if (data.success) {
                    badgeEl.innerHTML = '<span class="badge badge-light-success fw-bold fs-8"><i class="ki-outline ki-check-circle fs-8 text-success me-1"></i> Terverifikasi</span>';
                    if (data.ssl_status === 'active' && sslBadgeEl) {
                        sslBadgeEl.innerHTML = '<span class="badge badge-light-success fw-bold fs-8"><i class="ki-outline ki-shield-tick fs-8 text-success me-1"></i> HTTPS Aktif 🔒</span>';
                    }
                    if (statusEl) {
                        statusEl.innerHTML = '<span class="badge badge-light-success fw-bold fs-8">Aktif</span>';
                    }
                    Swal.fire({
                        text: data.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: { confirmButton: "btn btn-primary btn-sm" }
                    });
                } else {
                    badgeEl.innerHTML = '<span class="badge badge-light-warning fw-bold fs-8"><i class="ki-outline ki-information fs-8 text-warning me-1"></i> Belum Mengarah</span>';
                    Swal.fire({
                        text: data.message,
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Mengerti",
                        customClass: { confirmButton: "btn btn-warning btn-sm" }
                    });
                }
            })
            .catch(function(err) {
                button.disabled = false;
                button.innerHTML = origHtml;
                Swal.fire({
                    text: 'Gagal memeriksa DNS: ' + err.message,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Tutup",
                    customClass: { confirmButton: "btn btn-danger btn-sm" }
                });
            });
        });
    });

    // AJAX Provision SSL with SweetAlert2
    document.querySelectorAll('.btn-provision-ssl').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var domainId = this.dataset.id;
            var url = this.dataset.url;
            var origHtml = this.innerHTML;
            var button = this;

            Swal.fire({
                text: 'Jalankan proses penerbitan sertifikat SSL (HTTPS Let\'s Encrypt) otomatis untuk domain ini?',
                icon: 'question',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: 'Ya, Pasang SSL',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-primary btn-sm',
                    cancelButton: 'btn btn-light btn-sm'
                }
            }).then(function (result) {
                if (!result.isConfirmed) return;

                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memasang SSL...';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
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
                        Swal.fire({
                            text: data.message,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "OK",
                            customClass: { confirmButton: "btn btn-primary btn-sm" }
                        });
                    } else {
                        badgeEl.innerHTML = '<span class="badge badge-light-danger fw-bold fs-8"><i class="ki-outline ki-shield-cross fs-8 text-danger me-1"></i> SSL Gagal</span>';
                        Swal.fire({
                            text: data.message,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Tutup",
                            customClass: { confirmButton: "btn btn-danger btn-sm" }
                        });
                    }
                })
                .catch(function(err) {
                    button.disabled = false;
                    button.innerHTML = origHtml;
                    Swal.fire({
                        text: 'Gagal memasang SSL: ' + err.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Tutup",
                        customClass: { confirmButton: "btn btn-danger btn-sm" }
                    });
                });
            });
        });
    });
});
</script>
@endpush
