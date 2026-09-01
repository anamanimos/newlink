@extends('layouts.app')

@section('title', 'Create WhatsApp Rotator')

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('warotators.index') }}" class="btn btn-sm btn-icon btn-light me-2">
            <i class="ki-outline ki-arrow-left fs-2"></i>
        </a>
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
            Create WhatsApp Rotator
        </h1>
        <span class="badge badge-light-success fw-semibold fs-8 px-2 py-1 ms-2">WA Rotator</span>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <form action="{{ route('warotators.store') }}" method="POST" id="createWaRotatorForm">
            @csrf

            <!-- Section 1: Page Identity -->
            <div class="card card-flush shadow-sm border-0 mb-6">
                <div class="card-header pt-6">
                    <div class="card-title d-flex align-items-center">
                        <div class="symbol symbol-40px symbol-circle bg-light-success me-3 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-geolocation fs-2 text-success"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="fw-bold text-gray-900 fs-5 mb-0">Page Identity</h3>
                            <span class="text-muted fs-7">Configure domain, alias URL, and page info</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        <!-- Domain -->
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-semibold text-gray-900">Domain</label>
                            <select name="domain_id" id="create_wa_domain_id" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                <option value="0" selected>Default Domain ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}">{{ $domain->host }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Alias URL -->
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-semibold text-gray-900 required">Alias URL</label>
                            <div class="input-group input-group-solid">
                                <span class="input-group-text text-muted fs-7" id="create_wa_domain_prefix">{{ parse_url(url('/'), PHP_URL_HOST) }}/</span>
                                <input type="text" name="url" id="create_wa_url" class="form-control form-control-solid" placeholder="custom-alias" required value="{{ old('url') }}" />
                            </div>
                            <div id="create_wa_alias_feedback" class="mt-1 fs-8"></div>
                            @error('url')
                                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-semibold text-gray-900 required">Page Title</label>
                            <input type="text" name="title" class="form-control form-control-solid" required placeholder="e.g. CS Fast Response" value="{{ old('title') }}" />
                            @error('title')
                                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subtitle / Description -->
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-semibold text-gray-900">Form Subtitle / Description</label>
                            <input type="text" name="description" class="form-control form-control-solid" placeholder="e.g. Fill form to connect with admin" value="{{ old('description') }}" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: WhatsApp Rotation Configuration -->
            <div class="card card-flush shadow-sm border-0 mb-6">
                <div class="card-header pt-6">
                    <div class="card-title d-flex align-items-center">
                        <div class="symbol symbol-40px symbol-circle bg-light-success me-3 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-whatsapp fs-2 text-success"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="fw-bold text-gray-900 fs-5 mb-0">Rotation & Numbers</h3>
                            <span class="text-muted fs-7">Manage CS phone numbers, message templates, and button texts</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        <!-- Numbers -->
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-semibold text-gray-900 required">WhatsApp Numbers (Rotated)</label>
                            <textarea name="numbers" class="form-control form-control-solid" rows="3" required placeholder="One per line or comma-separated&#10;628123456789&#10;628987654321">{{ old('numbers', request('number')) }}</textarea>
                            <div class="form-text text-muted fs-8">Use international format without + (628xxxxxxxx). Distributed round-robin.</div>
                            @error('numbers')
                                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Message Template -->
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-semibold text-gray-900 required">Message Template</label>
                            <textarea name="template" class="form-control form-control-solid" rows="3" required placeholder="Hello admin, my name is [nama]...">{{ old('template', request('template', 'Halo admin, nama saya [nama] dari [kota]. Nomor saya [nomor]. Pesan: [pesan]')) }}</textarea>
                            <div class="form-text text-muted fs-8">Placeholders: <code>[nama]</code> <code>[kota]</code> <code>[nomor]</code> <code>[pesan]</code></div>
                            @error('template')
                                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Button Text -->
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-semibold text-gray-900 required">Form Button Text</label>
                            <input type="text" name="button_text" class="form-control form-control-solid" required placeholder="Contact CS Now" value="{{ old('button_text', 'Hubungi CS Sekarang') }}" />
                            @error('button_text')
                                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- City Options -->
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-semibold text-gray-900">City / Region Options (Comma-separated)</label>
                            <input type="text" name="cities" class="form-control form-control-solid" placeholder="Jakarta, Bandung, Surabaya, Yogyakarta" value="{{ old('cities') }}" />
                            <div class="form-text text-muted fs-8">Separated by comma for dropdown selections.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Project (Optional) -->
            @if(isset($projects) && $projects->count() > 0)
            <div class="card card-flush shadow-sm border-0 mb-6">
                <div class="card-header pt-6">
                    <div class="card-title d-flex align-items-center">
                        <div class="symbol symbol-40px symbol-circle bg-light-primary me-3 d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-folder fs-2 text-primary"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="fw-bold text-gray-900 fs-5 mb-0">Project Assignment</h3>
                            <span class="text-muted fs-7">Group this rotator under a project (optional)</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-semibold text-gray-900">Select Project</label>
                            <select name="project_id" class="form-select form-select-solid" data-control="select2">
                                <option value="">No Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Submit Actions -->
            <div class="d-flex align-items-center justify-content-between mb-10">
                <a href="{{ route('warotators.index') }}" class="btn btn-light fw-bold">Cancel</a>
                <button type="submit" id="create_wa_submit_btn" class="btn btn-primary fw-bold">
                    <i class="ki-outline ki-check fs-2 me-1"></i> Create WhatsApp Rotator
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

        feedbackEl.innerHTML = '<span class="text-muted"><i class="spinner-border spinner-border-sm me-1" style="width: 10px; height: 10px;"></i> Checking availability...</span>';

        checkTimeout = setTimeout(() => {
            fetch(`/link/check-availability?url=${encodeURIComponent(alias)}&domain_id=${domainId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        feedbackEl.innerHTML = '<span class="text-success small fw-semibold">✓ Alias available!</span>';
                        submitBtn.disabled = false;
                    } else {
                        feedbackEl.innerHTML = '<span class="text-danger small fw-semibold">✗ Alias is already taken on this domain!</span>';
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
        if (text.includes('Default Domain')) {
            prefixEl.textContent = '{{ parse_url(url("/"), PHP_URL_HOST) }}/';
        } else {
            prefixEl.textContent = text + '/';
        }
    }

    if (aliasInput) {
        aliasInput.addEventListener('input', checkAlias);
    }

    $('#create_wa_domain_id').on('change', function() {
        updatePrefix();
        checkAlias();
    });
});
</script>
@endsection
