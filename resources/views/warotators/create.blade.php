@extends('layouts.app')

@section('title', 'Buat WhatsApp Rotator')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">

        <!-- Breadcrumb / Page Header -->
        <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
            <div>
                <a href="{{ route('warotators.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Kembali ke Dashboard
                </a>
                <h4 class="fw-bold mb-0 text-dark-custom" style="font-size: 1.5rem; letter-spacing: -0.5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="vertical-align: -4px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    Buat WhatsApp Rotator Baru
                </h4>
            </div>
        </div>

        <!-- Main Form Card -->
        <form action="{{ route('warotators.store') }}" method="POST" id="createWaRotatorForm">
            @csrf

            <!-- Section 1: Identitas Halaman -->
            <div class="glass-card p-4 mb-4 border border-secondary border-opacity-10 rounded-4" style="background: var(--card-bg-blur);">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="p-2 rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(37, 211, 102, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </div>
                    <h6 class="fw-bold text-dark-custom mb-0">Identitas Halaman</h6>
                </div>

                <div class="row g-3">
                    <!-- Domain -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Domain</label>
                        <select name="domain_id" id="create_wa_domain_id" class="form-select bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 text-secondary small">
                            <option value="0" selected>Domain Bawaan ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}">{{ $domain->host }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Alias URL -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Alias URL <span class="text-danger">*</span></label>
                        <div class="glass-input-group d-flex align-items-center border rounded-3 p-1" style="border-color: var(--glass-border) !important;">
                            <span class="px-2 text-muted small fw-bold" id="create_wa_domain_prefix">
                                {{ parse_url(url('/'), PHP_URL_HOST) }}/
                            </span>
                            <input type="text" name="url" id="create_wa_url" class="form-control bg-transparent border-0 py-1 small" placeholder="custom-alias" required value="{{ old('url') }}" style="outline: none; box-shadow: none;">
                        </div>
                        <div id="create_wa_alias_feedback" class="mt-1" style="font-size: 0.725rem;"></div>
                        @error('url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Judul -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Judul Halaman <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" required placeholder="Contoh: CS Fast Response" value="{{ old('title') }}">
                        @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subtitle / Deskripsi -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Subtitle / Deskripsi Form</label>
                        <textarea name="description" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" rows="1" placeholder="Contoh: Silakan isi form untuk terhubung ke admin kami.">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Konfigurasi Rotasi WhatsApp -->
            <div class="glass-card p-4 mb-4 border border-secondary border-opacity-10 rounded-4" style="background: var(--card-bg-blur);">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="p-2 rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(37, 211, 102, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <h6 class="fw-bold text-dark-custom mb-0">Konfigurasi Rotasi WhatsApp</h6>
                </div>

                <div class="row g-3">
                    <!-- Nomor WhatsApp -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Nomor WhatsApp Tujuan (Dirotasi) <span class="text-danger">*</span></label>
                        <textarea name="numbers" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" rows="3" required placeholder="Masukkan nomor WhatsApp, satu per baris atau dipisah koma&#10;Contoh:&#10;628123456789&#10;628987654321">{{ old('numbers') }}</textarea>
                        <div class="form-text text-muted" style="font-size: 0.725rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            Gunakan format kode negara tanpa simbol + (Contoh: 628xxxxxxxx). Pesan didistribusikan secara round-robin adil ke setiap nomor.
                        </div>
                        @error('numbers')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Template Pesan -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Template Pesan WhatsApp <span class="text-danger">*</span></label>
                        <textarea name="template" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" rows="3" required placeholder="Contoh: Halo admin, nama saya [nama] dari [kota]. Saya ingin mengklaim promo.">{{ old('template', 'Halo admin, nama saya [nama] dari [kota]. Nomor saya [nomor]. Pesan: [pesan]') }}</textarea>
                        <div class="form-text text-muted" style="font-size: 0.725rem;">
                            Placeholders dinamis: <code>[nama]</code>, <code>[kota]</code>, <code>[nomor]</code>, <code>[pesan]</code>
                        </div>
                        @error('template')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Teks Tombol -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Teks Tombol Form <span class="text-danger">*</span></label>
                        <input type="text" name="button_text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" required placeholder="Contoh: Hubungi CS Sekarang" value="{{ old('button_text', 'Hubungi CS Sekarang') }}">
                        @error('button_text')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Daftar Kota -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Pilihan Kota / Kabupaten (Pisah Koma)</label>
                        <textarea name="cities" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" rows="1" placeholder="Contoh: Jakarta, Bandung, Surabaya, Yogyakarta, Semarang, Medan">{{ old('cities') }}</textarea>
                        <div class="form-text text-muted" style="font-size: 0.725rem;">
                            Pisahkan opsi kota dropdown menggunakan tanda koma.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Proyek -->
            @if(isset($projects) && $projects->count() > 0)
            <div class="glass-card p-4 mb-4 border border-secondary border-opacity-10 rounded-4" style="background: var(--card-bg-blur);">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="p-2 rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(99, 102, 241, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <h6 class="fw-bold text-dark-custom mb-0">Proyek (Opsional)</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Pilih Proyek</label>
                        <select name="project_id" class="form-select bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 text-secondary small">
                            <option value="">Tanpa Proyek</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @endif

            <!-- Submit Actions -->
            <div class="d-flex align-items-center justify-content-between mb-5">
                <a href="{{ route('warotators.index') }}" class="btn btn-light fw-semibold px-4 py-2 rounded-3 border shadow-sm" style="font-size: 0.875rem;">
                    Batal
                </a>
                <button type="submit" id="create_wa_submit_btn" class="btn btn-primary fw-semibold px-5 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="background-color: var(--primary-color); border-color: var(--primary-color); font-size: 0.875rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    Buat WA Rotator
                </button>
            </div>
        </form>
    </div>
</div>

<!-- AJAX Alias Availability Check -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let checkTimeout;
    const aliasInput = document.getElementById('create_wa_url');
    const domainSelect = document.getElementById('create_wa_domain_id');
    const feedbackEl = document.getElementById('create_wa_alias_feedback');
    const submitBtn = document.getElementById('create_wa_submit_btn');
    const prefixEl = document.getElementById('create_wa_domain_prefix');

    function checkAlias() {
        clearTimeout(checkTimeout);
        const alias = aliasInput.value;
        const domainId = domainSelect.value;

        if (!alias) {
            feedbackEl.innerHTML = '';
            submitBtn.disabled = false;
            return;
        }

        feedbackEl.innerHTML = '<span class="text-muted"><i class="spinner-border spinner-border-sm me-1" style="width: 10px; height: 10px;"></i> Memeriksa ketersediaan...</span>';

        checkTimeout = setTimeout(() => {
            const url = `/link/check-availability?url=${encodeURIComponent(alias)}&domain_id=${domainId}`;

            fetch(url)
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
                .catch(err => {
                    feedbackEl.innerHTML = '';
                    submitBtn.disabled = false;
                });
        }, 300);
    }

    function updatePrefix() {
        const selectedOption = domainSelect.options[domainSelect.selectedIndex];
        const text = selectedOption.text;
        if (text.includes('Domain Bawaan')) {
            prefixEl.textContent = '{{ parse_url(url("/"), PHP_URL_HOST) }}/';
        } else {
            prefixEl.textContent = text + '/';
        }
    }

    if (aliasInput) {
        aliasInput.addEventListener('input', checkAlias);
    }

    if (domainSelect) {
        domainSelect.addEventListener('change', function() {
            updatePrefix();
            checkAlias();
        });
    }
});
</script>
@endsection
