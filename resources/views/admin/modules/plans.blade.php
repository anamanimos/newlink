@extends('layouts.app')

@section('title', 'Manage Plans')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Plans & Packages</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Subscription Management</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-primary d-flex align-items-center gap-2 fw-bold" data-bs-toggle="modal" data-bs-target="#createPlanModal">
            <i class="ki-outline ki-plus fs-2"></i> Create Plan
        </button>
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

@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3 shadow-sm">
        <i class="ki-outline ki-information fs-2hx text-danger me-4"></i>
        <div class="d-flex flex-column">
            <span class="fs-7 text-gray-900 fw-semibold">{{ session('error') }}</span>
        </div>
    </div>
@endif

<!-- Top Stats Grid (4 Cards) -->
<div class="row g-5 g-xl-8 mb-8">
    <!-- Total Plans -->
    <div class="col-6 col-xl-3">
        <div class="card card-flush shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-outline ki-crown fs-2x text-primary"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $totalPlansCount }}</span>
                    <span class="text-gray-600 fw-semibold fs-7 text-truncate">Total Paket</span>
                    <span class="text-muted fs-8">Tersedia untuk user</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Subscribers -->
    <div class="col-6 col-xl-3">
        <div class="card card-flush shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-info">
                        <i class="ki-outline ki-people fs-2x text-info"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($totalSubscribers) }}</span>
                    <span class="text-gray-600 fw-semibold fs-7 text-truncate">Total Akun Terdaftar</span>
                    <span class="text-muted fs-8">Semua pengguna aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Paid & Custom Users -->
    <div class="col-6 col-xl-3">
        <div class="card card-flush shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-outline ki-credit-cart fs-2x text-success"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($paidSubscribers) }}</span>
                    <span class="text-gray-600 fw-semibold fs-7 text-truncate">Paket Pro / Custom</span>
                    <span class="text-muted fs-8">Pengguna berbayar</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Free Users -->
    <div class="col-6 col-xl-3">
        <div class="card card-flush shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-warning">
                        <i class="ki-outline ki-profile-user fs-2x text-warning"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($freeSubscribers) }}</span>
                    <span class="text-gray-600 fw-semibold fs-7 text-truncate">Paket Free</span>
                    <span class="text-muted fs-8">Paket default</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Plan Cards Section -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h3 class="fw-bold text-gray-900 fs-4 my-0">Kartu Paket Langganan</h3>
    <span class="text-muted fs-7">Rincian fitur dan batas kuota setiap paket</span>
</div>

<div class="row g-6 g-xl-9 mb-10">
    @forelse($plans as $plan)
        @php
            $settings = is_array($plan->settings) ? $plan->settings : json_decode($plan->settings, true) ?? [];
            $biolinks = $settings['biolinks_limit'] ?? 15;
            $links = $settings['links_limit'] ?? 50;
            $projects = $settings['projects_limit'] ?? 5;
            $domains = $settings['domains_limit'] ?? 0;
            $pixels = $settings['pixels_limit'] ?? 0;
            $customBranding = !empty($settings['custom_branding']);
            $verifiedBadge = !empty($settings['verified_badge']);
            $statistics = $settings['statistics'] ?? 'basic';
        @endphp
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-flush shadow-sm border h-100 position-relative {{ $plan->slug === 'pro' ? 'border-primary' : 'border-gray-200' }}" style="border-top: 4px solid {{ $plan->color ?: '#3e97ff' }} !important;">
                <div class="card-body p-6 d-flex flex-column justify-content-between">
                    
                    <div>
                        <!-- Header -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge fs-8 fw-bold" style="background-color: {{ $plan->color }}20; color: {{ $plan->color }};">
                                    {{ $plan->badge ?: ucfirst($plan->slug) }}
                                </span>
                                @if(!$plan->is_enabled)
                                    <span class="badge badge-light-danger fw-bold fs-9">Nonaktif</span>
                                @endif
                            </div>
                            
                            <!-- 3-Dots Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ki-outline ki-dots-horizontal fs-3"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-7 py-2">
                                    <li>
                                        <a href="#" class="dropdown-item py-2 px-4" data-bs-toggle="modal" data-bs-target="#editPlanModal_{{ $plan->id }}">
                                            <i class="ki-outline ki-pencil fs-6 text-primary me-2"></i> Edit Paket
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.users', ['plan_id' => $plan->slug]) }}" class="dropdown-item py-2 px-4">
                                            <i class="ki-outline ki-people fs-6 text-info me-2"></i> Lihat Pengguna ({{ $plan->users_count }})
                                        </a>
                                    </li>
                                    @if($plan->slug !== 'free')
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ $plan->name }}? Seluruh pengguna pada paket ini akan otomatis dialihkan ke Free Plan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 px-4 text-danger">
                                                    <i class="ki-outline ki-trash fs-6 text-danger me-2"></i> Hapus Paket
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <!-- Title & Price -->
                        <h3 class="fw-bolder text-gray-900 mb-1">{{ $plan->name }}</h3>
                        <p class="text-muted fs-7 mb-4">{{ $plan->description ?: 'Paket langganan platform' }}</p>

                        <div class="d-flex align-items-baseline mb-5">
                            @if($plan->monthly_price == 0)
                                <span class="fs-2hx fw-bolder text-gray-900">Gratis</span>
                            @else
                                <span class="fs-2hx fw-bolder text-gray-900">${{ number_format($plan->monthly_price, 2) }}</span>
                                <span class="text-muted fs-7 ms-2">/ bulan</span>
                            @endif
                        </div>

                        <div class="separator separator-dashed mb-5"></div>

                        <!-- Limits Checklist -->
                        <ul class="list-unstyled mb-0 fs-7 text-gray-700">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i>
                                <span><strong>{{ $biolinks == -1 ? 'Unlimited' : $biolinks }}</strong> Halaman Biolink</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i>
                                <span><strong>{{ $links == -1 ? 'Unlimited' : $links }}</strong> Tautan Pendek (Shortlinks)</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i>
                                <span><strong>{{ $projects == -1 ? 'Unlimited' : $projects }}</strong> Folder Proyek</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                @if($domains == -1 || $domains > 0)
                                    <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i>
                                    <span><strong>{{ $domains == -1 ? 'Unlimited' : $domains }}</strong> Custom Domains</span>
                                @else
                                    <i class="ki-outline ki-cross-circle fs-4 text-muted me-2"></i>
                                    <span class="text-muted">Tanpa Custom Domain</span>
                                @endif
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                @if($pixels == -1 || $pixels > 0)
                                    <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i>
                                    <span><strong>{{ $pixels == -1 ? 'Unlimited' : $pixels }}</strong> Tracking Pixels</span>
                                @else
                                    <i class="ki-outline ki-cross-circle fs-4 text-muted me-2"></i>
                                    <span class="text-muted">Tanpa Tracking Pixel</span>
                                @endif
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                @if($customBranding)
                                    <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i>
                                    <span>Kustom / Hapus Branding</span>
                                @else
                                    <i class="ki-outline ki-cross-circle fs-4 text-muted me-2"></i>
                                    <span class="text-muted">Branding Default Sistem</span>
                                @endif
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i>
                                <span>Statistik: <strong class="text-capitalize">{{ $statistics }}</strong></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Card Footer -->
                    <div class="mt-6 pt-4 border-top d-flex align-items-center justify-content-between">
                        <a href="{{ route('admin.users', ['plan_id' => $plan->slug]) }}" class="badge badge-light-primary fw-bold fs-8 py-2 px-3 text-decoration-none">
                            <i class="ki-outline ki-people fs-6 text-primary me-1"></i> {{ $plan->users_count }} Pengguna Aktif
                        </a>
                        <button type="button" class="btn btn-xs btn-light-primary fw-bold" data-bs-toggle="modal" data-bs-target="#editPlanModal_{{ $plan->id }}">
                            Edit Fitur
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">Belum ada paket yang dibuat.</div>
        </div>
    @endforelse
</div>

<!-- Plans Data Table Card -->
<div class="card card-flush shadow-sm border-0 mb-6">
    <div class="card-header pt-6 pb-2">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900 fs-4">Daftar & Konfigurasi Paket</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-200px">Nama Paket</th>
                        <th class="min-w-140px">Harga (Bulanan / Tahunan)</th>
                        <th class="min-w-180px">Batas Kuota</th>
                        <th class="min-w-100px">Pengguna</th>
                        <th class="min-w-100px">Status</th>
                        <th class="text-end min-w-100px pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach($plans as $plan)
                        @php
                            $settings = is_array($plan->settings) ? $plan->settings : json_decode($plan->settings, true) ?? [];
                            $biolinks = $settings['biolinks_limit'] ?? 15;
                            $links = $settings['links_limit'] ?? 50;
                            $domains = $settings['domains_limit'] ?? 0;
                            $pixels = $settings['pixels_limit'] ?? 0;
                        @endphp
                        <tr>
                            <!-- Name & Badge -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px symbol-circle me-3 flex-shrink-0" style="background-color: {{ $plan->color }}20;">
                                        <span class="symbol-label" style="color: {{ $plan->color }}; font-weight: bold;">
                                            <i class="ki-outline ki-crown fs-3" style="color: {{ $plan->color }};"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-bold text-gray-900 fs-6">{{ $plan->name }}</span>
                                            <span class="badge fs-9 fw-bold" style="background-color: {{ $plan->color }}20; color: {{ $plan->color }};">
                                                {{ $plan->badge ?: $plan->slug }}
                                            </span>
                                        </div>
                                        <span class="text-muted fs-8">{{ $plan->description ?: 'Slug: ' . $plan->slug }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Price -->
                            <td>
                                @if($plan->monthly_price == 0)
                                    <span class="badge badge-light-success fw-bold fs-7">Gratis</span>
                                @else
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-gray-900 fs-7">${{ number_format($plan->monthly_price, 2) }} / bln</span>
                                        <span class="text-muted fs-8">${{ number_format($plan->annual_price, 2) }} / thn</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Limits -->
                            <td>
                                <div class="d-flex flex-column fs-8 text-gray-700">
                                    <span>• Biolinks: <strong>{{ $biolinks == -1 ? 'Unlimited' : $biolinks }}</strong></span>
                                    <span>• Shortlinks: <strong>{{ $links == -1 ? 'Unlimited' : $links }}</strong></span>
                                    <span>• Domains / Pixels: <strong>{{ $domains == -1 ? 'Unlimited' : $domains }}</strong> / <strong>{{ $pixels == -1 ? 'Unlimited' : $pixels }}</strong></span>
                                </div>
                            </td>

                            <!-- Users count -->
                            <td>
                                <a href="{{ route('admin.users', ['plan_id' => $plan->slug]) }}" class="badge badge-light-primary fw-bold fs-7">
                                    {{ $plan->users_count }} users
                                </a>
                            </td>

                            <!-- Status -->
                            <td>
                                @if($plan->is_enabled)
                                    <span class="badge badge-light-success fw-bold fs-8">Aktif</span>
                                @else
                                    <span class="badge badge-light-secondary fw-bold fs-8">Nonaktif</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="text-end pe-3">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ki-outline ki-dots-horizontal fs-3"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-7 py-2">
                                        <li>
                                            <a href="#" class="dropdown-item py-2 px-4" data-bs-toggle="modal" data-bs-target="#editPlanModal_{{ $plan->id }}">
                                                <i class="ki-outline ki-pencil fs-6 text-primary me-2"></i> Edit Paket
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.users', ['plan_id' => $plan->slug]) }}" class="dropdown-item py-2 px-4">
                                                <i class="ki-outline ki-people fs-6 text-info me-2"></i> Lihat Pengguna
                                            </a>
                                        </li>
                                        @if($plan->slug !== 'free')
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Hapus paket {{ $plan->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 px-4 text-danger">
                                                        <i class="ki-outline ki-trash fs-6 text-danger me-2"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= CREATE PLAN MODAL ================= -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900">
                    <i class="ki-outline ki-plus-circle fs-2 text-primary me-2"></i> Buat Paket Baru
                </h3>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.plans.store') }}">
                @csrf
                <div class="modal-body py-6 px-lg-8">
                    <div class="row g-5">
                        
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h5 class="fw-bold text-gray-900 mb-0">1. Informasi Dasar</h5>
                            <span class="text-muted fs-8">Nama, slug sistem, dan deskripsi paket</span>
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900 required">Nama Paket</label>
                            <input type="text" name="name" class="form-control form-control-solid form-control-sm" placeholder="Contoh: Enterprise Plan" required />
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Slug / ID Paket (Opsional)</label>
                            <input type="text" name="slug" class="form-control form-control-solid form-control-sm" placeholder="enterprise (otomatis dibuat jika kosong)" />
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Label Badge</label>
                            <input type="text" name="badge" class="form-control form-control-solid form-control-sm" placeholder="Contoh: Best Value, Promo" />
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Warna Aksen Paket</label>
                            <input type="color" name="color" class="form-control form-control-solid form-control-sm h-40px" value="#3e97ff" />
                        </div>

                        <div class="col-12 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Deskripsi Singkat</label>
                            <input type="text" name="description" class="form-control form-control-solid form-control-sm" placeholder="Paket lengkap untuk instansi dan tim bisnis..." />
                        </div>

                        <div class="col-12"><div class="separator separator-dashed my-2"></div></div>

                        <!-- Pricing -->
                        <div class="col-12">
                            <h5 class="fw-bold text-gray-900 mb-0">2. Harga Langganan</h5>
                            <span class="text-muted fs-8">Atur harga per bulan, tahunan, atau lifetime</span>
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Harga Bulanan ($)</label>
                            <input type="number" step="0.01" name="monthly_price" class="form-control form-control-solid form-control-sm" value="0.00" />
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Harga Tahunan ($)</label>
                            <input type="number" step="0.01" name="annual_price" class="form-control form-control-solid form-control-sm" value="0.00" />
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Harga Lifetime ($)</label>
                            <input type="number" step="0.01" name="lifetime_price" class="form-control form-control-solid form-control-sm" value="0.00" />
                        </div>

                        <div class="col-12"><div class="separator separator-dashed my-2"></div></div>

                        <!-- Limits & Quotas -->
                        <div class="col-12">
                            <h5 class="fw-bold text-gray-900 mb-0">3. Batas Kuota & Limitasi</h5>
                            <span class="text-muted fs-8">Atur batasan jumlah entitas yang boleh dibuat</span>
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Biolink Pages Limit</label>
                            <input type="number" name="biolinks_limit" class="form-control form-control-solid form-control-sm" value="15" />
                            <div class="form-check form-check-custom form-check-solid form-check-sm mt-1">
                                <input class="form-check-input" type="checkbox" name="unlimited_biolinks" id="unlimited_biolinks_new" />
                                <label class="form-check-label fs-8 text-muted" for="unlimited_biolinks_new">Unlimited</label>
                            </div>
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Shortened Links Limit</label>
                            <input type="number" name="links_limit" class="form-control form-control-solid form-control-sm" value="50" />
                            <div class="form-check form-check-custom form-check-solid form-check-sm mt-1">
                                <input class="form-check-input" type="checkbox" name="unlimited_links" id="unlimited_links_new" />
                                <label class="form-check-label fs-8 text-muted" for="unlimited_links_new">Unlimited</label>
                            </div>
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Projects Folder Limit</label>
                            <input type="number" name="projects_limit" class="form-control form-control-solid form-control-sm" value="5" />
                            <div class="form-check form-check-custom form-check-solid form-check-sm mt-1">
                                <input class="form-check-input" type="checkbox" name="unlimited_projects" id="unlimited_projects_new" />
                                <label class="form-check-label fs-8 text-muted" for="unlimited_projects_new">Unlimited</label>
                            </div>
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Custom Domains Limit</label>
                            <input type="number" name="domains_limit" class="form-control form-control-solid form-control-sm" value="0" />
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Tracking Pixels Limit</label>
                            <input type="number" name="pixels_limit" class="form-control form-control-solid form-control-sm" value="0" />
                        </div>

                        <div class="col-12"><div class="separator separator-dashed my-2"></div></div>

                        <!-- Feature Toggles -->
                        <div class="col-12">
                            <h5 class="fw-bold text-gray-900 mb-2">4. Fitur Tambahan</h5>
                            
                            <div class="d-flex flex-column gap-3">
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-35px" type="checkbox" name="custom_branding" id="custom_branding_new" />
                                    <label class="form-check-label fs-7 fw-semibold text-gray-800" for="custom_branding_new">
                                        Izinkan Hapus / Kustom Branding Sistem pada Halaman Biolink
                                    </label>
                                </div>

                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-35px" type="checkbox" name="verified_badge" id="verified_badge_new" />
                                    <label class="form-check-label fs-7 fw-semibold text-gray-800" for="verified_badge_new">
                                        Izinkan Centang Biru (Verified Checkmark Badge)
                                    </label>
                                </div>

                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-35px" type="checkbox" name="dofollow_links" id="dofollow_links_new" />
                                    <label class="form-check-label fs-7 fw-semibold text-gray-800" for="dofollow_links_new">
                                        Izinkan SEO Dofollow Links
                                    </label>
                                </div>

                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-35px" type="checkbox" name="is_enabled" id="is_enabled_new" checked />
                                    <label class="form-check-label fs-7 fw-semibold text-gray-800" for="is_enabled_new">
                                        Aktifkan Paket Ini (Tersedia untuk Dipilih)
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-lg-8 pb-6 justify-content-between">
                    <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Simpan Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= EDIT PLAN MODALS ================= -->
@foreach($plans as $plan)
    @php
        $settings = is_array($plan->settings) ? $plan->settings : json_decode($plan->settings, true) ?? [];
        $biolinks = $settings['biolinks_limit'] ?? 15;
        $links = $settings['links_limit'] ?? 50;
        $projects = $settings['projects_limit'] ?? 5;
        $domains = $settings['domains_limit'] ?? 0;
        $pixels = $settings['pixels_limit'] ?? 0;
        $customBranding = !empty($settings['custom_branding']);
        $verifiedBadge = !empty($settings['verified_badge']);
        $dofollowLinks = !empty($settings['dofollow_links']);
    @endphp
    <div class="modal fade" id="editPlanModal_{{ $plan->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-3 border-0 shadow-lg">
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h3 class="modal-title fw-bold text-gray-900">
                        <i class="ki-outline ki-pencil fs-2 text-primary me-2"></i> Edit Paket: {{ $plan->name }}
                    </h3>
                    <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.plans.update', $plan->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body py-6 px-lg-8">
                        <div class="row g-5">
                            
                            <!-- Basic Information -->
                            <div class="col-12">
                                <h5 class="fw-bold text-gray-900 mb-0">1. Informasi Dasar</h5>
                            </div>

                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900 required">Nama Paket</label>
                                <input type="text" name="name" class="form-control form-control-solid form-control-sm" value="{{ $plan->name }}" required />
                            </div>

                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Slug / ID Paket</label>
                                <input type="text" class="form-control form-control-solid form-control-sm bg-light-secondary" value="{{ $plan->slug }}" disabled />
                            </div>

                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Label Badge</label>
                                <input type="text" name="badge" class="form-control form-control-solid form-control-sm" value="{{ $plan->badge }}" placeholder="Popular / Custom" />
                            </div>

                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Warna Aksen Paket</label>
                                <input type="color" name="color" class="form-control form-control-solid form-control-sm h-40px" value="{{ $plan->color ?: '#3e97ff' }}" />
                            </div>

                            <div class="col-12 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Deskripsi Singkat</label>
                                <input type="text" name="description" class="form-control form-control-solid form-control-sm" value="{{ $plan->description }}" />
                            </div>

                            <div class="col-12"><div class="separator separator-dashed my-2"></div></div>

                            <!-- Pricing -->
                            <div class="col-12">
                                <h5 class="fw-bold text-gray-900 mb-0">2. Harga Langganan</h5>
                            </div>

                            <div class="col-md-4 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Harga Bulanan ($)</label>
                                <input type="number" step="0.01" name="monthly_price" class="form-control form-control-solid form-control-sm" value="{{ $plan->monthly_price }}" />
                            </div>

                            <div class="col-md-4 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Harga Tahunan ($)</label>
                                <input type="number" step="0.01" name="annual_price" class="form-control form-control-solid form-control-sm" value="{{ $plan->annual_price }}" />
                            </div>

                            <div class="col-md-4 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Harga Lifetime ($)</label>
                                <input type="number" step="0.01" name="lifetime_price" class="form-control form-control-solid form-control-sm" value="{{ $plan->lifetime_price }}" />
                            </div>

                            <div class="col-12"><div class="separator separator-dashed my-2"></div></div>

                            <!-- Limits & Quotas -->
                            <div class="col-12">
                                <h5 class="fw-bold text-gray-900 mb-0">3. Batas Kuota & Limitasi</h5>
                            </div>

                            <div class="col-md-4 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Biolink Pages Limit</label>
                                <input type="number" name="biolinks_limit" class="form-control form-control-solid form-control-sm" value="{{ $biolinks == -1 ? '' : $biolinks }}" />
                                <div class="form-check form-check-custom form-check-solid form-check-sm mt-1">
                                    <input class="form-check-input" type="checkbox" name="unlimited_biolinks" id="unlimited_biolinks_{{ $plan->id }}" {{ $biolinks == -1 ? 'checked' : '' }} />
                                    <label class="form-check-label fs-8 text-muted" for="unlimited_biolinks_{{ $plan->id }}">Unlimited</label>
                                </div>
                            </div>

                            <div class="col-md-4 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Shortened Links Limit</label>
                                <input type="number" name="links_limit" class="form-control form-control-solid form-control-sm" value="{{ $links == -1 ? '' : $links }}" />
                                <div class="form-check form-check-custom form-check-solid form-check-sm mt-1">
                                    <input class="form-check-input" type="checkbox" name="unlimited_links" id="unlimited_links_{{ $plan->id }}" {{ $links == -1 ? 'checked' : '' }} />
                                    <label class="form-check-label fs-8 text-muted" for="unlimited_links_{{ $plan->id }}">Unlimited</label>
                                </div>
                            </div>

                            <div class="col-md-4 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Projects Folder Limit</label>
                                <input type="number" name="projects_limit" class="form-control form-control-solid form-control-sm" value="{{ $projects == -1 ? '' : $projects }}" />
                                <div class="form-check form-check-custom form-check-solid form-check-sm mt-1">
                                    <input class="form-check-input" type="checkbox" name="unlimited_projects" id="unlimited_projects_{{ $plan->id }}" {{ $projects == -1 ? 'checked' : '' }} />
                                    <label class="form-check-label fs-8 text-muted" for="unlimited_projects_{{ $plan->id }}">Unlimited</label>
                                </div>
                            </div>

                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Custom Domains Limit</label>
                                <input type="number" name="domains_limit" class="form-control form-control-solid form-control-sm" value="{{ $domains }}" />
                            </div>

                            <div class="col-md-6 fv-row">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Tracking Pixels Limit</label>
                                <input type="number" name="pixels_limit" class="form-control form-control-solid form-control-sm" value="{{ $pixels }}" />
                            </div>

                            <div class="col-12"><div class="separator separator-dashed my-2"></div></div>

                            <!-- Feature Toggles -->
                            <div class="col-12">
                                <h5 class="fw-bold text-gray-900 mb-2">4. Fitur Tambahan</h5>
                                
                                <div class="d-flex flex-column gap-3">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-35px" type="checkbox" name="custom_branding" id="custom_branding_{{ $plan->id }}" {{ $customBranding ? 'checked' : '' }} />
                                        <label class="form-check-label fs-7 fw-semibold text-gray-800" for="custom_branding_{{ $plan->id }}">
                                            Izinkan Hapus / Kustom Branding Sistem pada Halaman Biolink
                                        </label>
                                    </div>

                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-35px" type="checkbox" name="verified_badge" id="verified_badge_{{ $plan->id }}" {{ $verifiedBadge ? 'checked' : '' }} />
                                        <label class="form-check-label fs-7 fw-semibold text-gray-800" for="verified_badge_{{ $plan->id }}">
                                            Izinkan Centang Biru (Verified Checkmark Badge)
                                        </label>
                                    </div>

                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-35px" type="checkbox" name="dofollow_links" id="dofollow_links_{{ $plan->id }}" {{ $dofollowLinks ? 'checked' : '' }} />
                                        <label class="form-check-label fs-7 fw-semibold text-gray-800" for="dofollow_links_{{ $plan->id }}">
                                            Izinkan SEO Dofollow Links
                                        </label>
                                    </div>

                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-35px" type="checkbox" name="is_enabled" id="is_enabled_{{ $plan->id }}" {{ $plan->is_enabled ? 'checked' : '' }} />
                                        <label class="form-check-label fs-7 fw-semibold text-gray-800" for="is_enabled_{{ $plan->id }}">
                                            Aktifkan Paket Ini (Tersedia untuk Dipilih)
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-lg-8 pb-6 justify-content-between">
                        <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary fw-bold">Perbarui Paket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
