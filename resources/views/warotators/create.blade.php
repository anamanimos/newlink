@extends('layouts.app')

@section('title', 'Buat WhatsApp Rotator')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* ─── Select2 Glassmorphism Styling ─── */
    .select2-container--default .select2-selection--single {
        background-color: rgba(255,255,255,0.03) !important;
        border: 1px solid var(--glass-border, #e2e8f0) !important;
        height: 42px !important;
        border-radius: 10px !important;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }
    [data-bs-theme="dark"] .select2-container--default .select2-selection--single {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--text-secondary) !important;
        font-size: 0.825rem !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: var(--text-secondary) transparent transparent transparent !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent var(--text-secondary) transparent !important;
    }
    .select2-dropdown {
        background-color: var(--header-bg, #fff) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid var(--glass-border, #e2e8f0) !important;
        border-radius: 10px !important;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
        z-index: 9999;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: rgba(255,255,255,0.05) !important;
        border: 1px solid var(--glass-border, #e2e8f0) !important;
        border-radius: 8px !important;
        color: var(--text-primary) !important;
        padding: 6px 10px !important;
        font-size: 0.825rem !important;
        outline: none !important;
    }
    .select2-results__option {
        font-size: 0.825rem !important;
        padding: 8px 12px !important;
        color: var(--text-secondary) !important;
        background-color: transparent !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary-color) !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: var(--primary-light) !important;
        color: var(--primary-color) !important;
    }
    .select2-container .select2-selection--single .select2-selection__placeholder {
        color: var(--text-secondary) !important;
        opacity: 0.6;
    }

    /* ─── Form Input Group Icon Styling ─── */
    .input-icon-group {
        position: relative;
    }
    .input-icon-group .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        z-index: 2;
    }
    .input-icon-group input,
    .input-icon-group textarea {
        padding-left: 38px !important;
    }

    /* ─── Custom Form Inputs ─── */
    .wa-form-control {
        background-color: rgba(255,255,255,0.03) !important;
        border: 1px solid var(--glass-border, #e2e8f0) !important;
        padding: 10px 14px;
        font-size: 0.825rem;
        border-radius: 10px !important;
        color: var(--text-primary) !important;
        transition: all 0.2s ease;
    }
    .wa-form-control:focus {
        border-color: #25d366 !important;
        box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1) !important;
        background-color: rgba(255,255,255,0.06) !important;
    }
    .wa-form-control::placeholder {
        color: #94a3b8 !important;
        font-size: 0.8rem;
    }
    [data-bs-theme="dark"] .wa-form-control {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }

    /* ─── Card Sections ─── */
    .wa-section-card {
        background: var(--card-bg-blur, #fff);
        border: 1px solid var(--glass-border, rgba(0,0,0,0.06));
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 20px;
        transition: box-shadow 0.2s ease;
    }
    .wa-section-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }
    .wa-section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--glass-border, rgba(0,0,0,0.06));
    }
    .wa-section-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .wa-section-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.2px;
    }
    .wa-section-subtitle {
        font-size: 0.75rem;
        color: #94a3b8;
        margin: 2px 0 0 0;
    }

    /* ─── Label Styling ─── */
    .wa-label {
        font-size: 0.775rem;
        font-weight: 600;
        color: var(--text-secondary, #64748b);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .wa-label svg {
        opacity: 0.5;
    }

    /* ─── Alias URL Group ─── */
    .alias-url-group {
        display: flex;
        align-items: center;
        border: 1px solid var(--glass-border, #e2e8f0);
        border-radius: 10px;
        background: rgba(255,255,255,0.03);
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .alias-url-group:focus-within {
        border-color: #25d366;
        box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1);
    }
    .alias-url-prefix {
        padding: 10px 12px;
        font-size: 0.775rem;
        font-weight: 700;
        color: #64748b;
        background: rgba(0,0,0,0.02);
        border-right: 1px solid var(--glass-border, #e2e8f0);
        white-space: nowrap;
        flex-shrink: 0;
    }
    [data-bs-theme="dark"] .alias-url-prefix {
        background: rgba(255,255,255,0.03);
    }
    .alias-url-group input {
        border: none !important;
        background: transparent !important;
        padding: 10px 14px !important;
        font-size: 0.825rem;
        color: var(--text-primary) !important;
        outline: none !important;
        box-shadow: none !important;
        flex: 1;
    }

    /* ─── Helper Text ─── */
    .wa-helper-text {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 6px;
        display: flex;
        align-items: flex-start;
        gap: 4px;
        line-height: 1.4;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">

        <!-- Breadcrumb / Page Header -->
        <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
            <div>
                <a href="{{ route('warotators.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1 mb-2" style="transition: color 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Kembali ke Dashboard
                </a>
                <h4 class="fw-bold mb-0 text-dark-custom d-flex align-items-center gap-2" style="font-size: 1.4rem; letter-spacing: -0.5px;">
                    <div class="p-2 rounded-3 d-flex align-items-center justify-content-center" style="background: rgba(37, 211, 102, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    Buat WhatsApp Rotator Baru
                </h4>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('warotators.store') }}" method="POST" id="createWaRotatorForm">
            @csrf

            <!-- ═══════════════ Section 1: Identitas Halaman ═══════════════ -->
            <div class="wa-section-card">
                <div class="wa-section-header">
                    <div class="wa-section-icon" style="background: rgba(37, 211, 102, 0.08);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </div>
                    <div>
                        <h6 class="wa-section-title">Identitas Halaman</h6>
                        <p class="wa-section-subtitle">Atur domain, alias URL, dan informasi dasar halaman rotator Anda</p>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Domain -->
                    <div class="col-md-6">
                        <label class="wa-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            Domain
                        </label>
                        <select name="domain_id" id="create_wa_domain_id" class="wa-select2">
                            <option value="0" selected>Domain Bawaan ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->host }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Alias URL -->
                    <div class="col-md-6">
                        <label class="wa-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                            Alias URL <span class="text-danger">*</span>
                        </label>
                        <div class="alias-url-group">
                            <span class="alias-url-prefix" id="create_wa_domain_prefix">{{ parse_url(url('/'), PHP_URL_HOST) }}/</span>
                            <input type="text" name="url" id="create_wa_url" placeholder="custom-alias" required value="{{ old('url') }}">
                        </div>
                        <div id="create_wa_alias_feedback" class="mt-1" style="font-size: 0.725rem;"></div>
                        @error('url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Judul -->
                    <div class="col-md-6">
                        <label class="wa-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Judul Halaman <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon-group">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 7 4 4 20 4 20 7"></polyline><line x1="9" y1="20" x2="15" y2="20"></line><line x1="12" y1="4" x2="12" y2="20"></line></svg>
                            </span>
                            <input type="text" name="title" class="form-control wa-form-control" required placeholder="Contoh: CS Fast Response" value="{{ old('title') }}">
                        </div>
                        @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subtitle / Deskripsi -->
                    <div class="col-md-6">
                        <label class="wa-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="17" y1="18" x2="3" y2="18"></line></svg>
                            Subtitle / Deskripsi Form
                        </label>
                        <div class="input-icon-group">
                            <span class="input-icon" style="top: 20px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="17" y1="18" x2="3" y2="18"></line></svg>
                            </span>
                            <textarea name="description" class="form-control wa-form-control" rows="1" placeholder="Contoh: Silakan isi form untuk terhubung ke admin kami.">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════════════ Section 2: Konfigurasi Rotasi WhatsApp ═══════════════ -->
            <div class="wa-section-card">
                <div class="wa-section-header">
                    <div class="wa-section-icon" style="background: rgba(37, 211, 102, 0.08);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <div>
                        <h6 class="wa-section-title">Konfigurasi Rotasi WhatsApp</h6>
                        <p class="wa-section-subtitle">Atur nomor admin CS, template pesan, dan opsi formulir</p>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Nomor WhatsApp -->
                    <div class="col-md-6">
                        <label class="wa-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            Nomor WhatsApp Tujuan (Dirotasi) <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon-group">
                            <span class="input-icon" style="top: 20px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                            </span>
                            <textarea name="numbers" class="form-control wa-form-control" rows="3" required placeholder="Satu nomor per baris atau dipisah koma&#10;628123456789&#10;628987654321">{{ old('numbers') }}</textarea>
                        </div>
                        <div class="wa-helper-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top: 1px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            Format tanpa simbol + (628xxxxxxxx). Pesan didistribusikan round-robin ke setiap nomor.
                        </div>
                        @error('numbers')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Template Pesan -->
                    <div class="col-md-6">
                        <label class="wa-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            Template Pesan WhatsApp <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon-group">
                            <span class="input-icon" style="top: 20px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            </span>
                            <textarea name="template" class="form-control wa-form-control" rows="3" required placeholder="Contoh: Halo admin, nama saya [nama] dari [kota]...">{{ old('template', 'Halo admin, nama saya [nama] dari [kota]. Nomor saya [nomor]. Pesan: [pesan]') }}</textarea>
                        </div>
                        <div class="wa-helper-text">
                            Placeholders: <code style="font-size: 0.7rem;">[nama]</code> <code style="font-size: 0.7rem;">[kota]</code> <code style="font-size: 0.7rem;">[nomor]</code> <code style="font-size: 0.7rem;">[pesan]</code>
                        </div>
                        @error('template')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Teks Tombol -->
                    <div class="col-md-6">
                        <label class="wa-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                            Teks Tombol Form <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon-group">
                            <span class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>
                            </span>
                            <input type="text" name="button_text" class="form-control wa-form-control" required placeholder="Contoh: Hubungi CS Sekarang" value="{{ old('button_text', 'Hubungi CS Sekarang') }}">
                        </div>
                        @error('button_text')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Daftar Kota -->
                    <div class="col-md-6">
                        <label class="wa-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            Pilihan Kota / Kabupaten (Pisah Koma)
                        </label>
                        <div class="input-icon-group">
                            <span class="input-icon" style="top: 20px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </span>
                            <textarea name="cities" class="form-control wa-form-control" rows="1" placeholder="Jakarta, Bandung, Surabaya, Yogyakarta, Semarang, Medan">{{ old('cities') }}</textarea>
                        </div>
                        <div class="wa-helper-text">
                            Pisahkan opsi kota dropdown menggunakan tanda koma.
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════════════ Section 3: Proyek (Opsional) ═══════════════ -->
            @if(isset($projects) && $projects->count() > 0)
            <div class="wa-section-card">
                <div class="wa-section-header">
                    <div class="wa-section-icon" style="background: rgba(99, 102, 241, 0.08);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <div>
                        <h6 class="wa-section-title">Proyek</h6>
                        <p class="wa-section-subtitle">Kelompokkan rotator ini dalam proyek tertentu (opsional)</p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="wa-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                            Pilih Proyek
                        </label>
                        <select name="project_id" class="wa-select2">
                            <option value="">Tanpa Proyek</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @endif

            <!-- ═══════════════ Submit Actions ═══════════════ -->
            <div class="d-flex align-items-center justify-content-between mb-5 pt-2">
                <a href="{{ route('warotators.index') }}" class="btn fw-semibold px-4 py-2 rounded-3 border shadow-sm d-flex align-items-center gap-2" style="font-size: 0.85rem; background: var(--card-bg-blur); color: var(--text-secondary); border-color: var(--glass-border);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Batal
                </a>
                <button type="submit" id="create_wa_submit_btn" class="btn fw-semibold px-5 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="background: linear-gradient(135deg, #25d366, #128c7e); border: none; color: #fff; font-size: 0.85rem; transition: all 0.2s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    Buat WA Rotator
                </button>
            </div>
        </form>
    </div>
</div>

<!-- jQuery + Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 on all .wa-select2 elements
    $('.wa-select2').select2({
        minimumResultsForSearch: 5,
        width: '100%',
        placeholder: 'Pilih opsi...'
    });

    // ─── Alias Availability Check ───
    let checkTimeout;
    const aliasInput = document.getElementById('create_wa_url');
    const feedbackEl = document.getElementById('create_wa_alias_feedback');
    const submitBtn = document.getElementById('create_wa_submit_btn');
    const prefixEl = document.getElementById('create_wa_domain_prefix');

    function checkAlias() {
        clearTimeout(checkTimeout);
        const alias = aliasInput.value;
        const domainId = $('#create_wa_domain_id').val();

        if (!alias) {
            feedbackEl.innerHTML = '';
            submitBtn.disabled = false;
            return;
        }

        feedbackEl.innerHTML = '<span class="text-muted"><i class="spinner-border spinner-border-sm me-1" style="width: 10px; height: 10px;"></i> Memeriksa ketersediaan...</span>';

        checkTimeout = setTimeout(() => {
            fetch(`/link/check-availability?url=${encodeURIComponent(alias)}&domain_id=${domainId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        feedbackEl.innerHTML = '<span class="text-success small fw-semibold">✓ Alias tersedia pada domain ini!</span>';
                        submitBtn.disabled = false;
                    } else {
                        feedbackEl.innerHTML = '<span class="text-danger small fw-semibold">✗ Alias sudah digunakan pada domain ini!</span>';
                        submitBtn.disabled = true;
                    }
                })
                .catch(() => {
                    feedbackEl.innerHTML = '';
                    submitBtn.disabled = false;
                });
        }, 300);
    }

    function updatePrefix() {
        const text = $('#create_wa_domain_id option:selected').text();
        if (text.includes('Domain Bawaan')) {
            prefixEl.textContent = '{{ parse_url(url("/"), PHP_URL_HOST) }}/';
        } else {
            prefixEl.textContent = text + '/';
        }
    }

    if (aliasInput) {
        aliasInput.addEventListener('input', checkAlias);
    }

    // Listen for Select2 domain change
    $('#create_wa_domain_id').on('change', function() {
        updatePrefix();
        checkAlias();
    });
});
</script>
@endsection
