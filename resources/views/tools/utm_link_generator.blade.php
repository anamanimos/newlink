@extends('layouts.app')

@section('title', 'UTM Link Generator')

@section('content')
<div class="d-flex flex-column gap-6">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tools.index') }}" class="btn btn-sm btn-icon btn-light me-2">
                <i class="ki-outline ki-arrow-left fs-2"></i>
            </a>
            <div>
                <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
                    UTM Link Generator
                </h1>
                <span class="text-muted fs-7">Tambahkan parameter tracking kampanye Google Analytics (UTM Source, Medium, Campaign) ke tautan website Anda.</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tools.index') }}" class="btn btn-sm btn-light fw-bold">
                <i class="ki-outline ki-element-11 fs-4 me-1"></i> Semua Tools
            </a>
        </div>
    </div>

    <!-- Main Row -->
    <div class="row g-6 g-xl-9">
        <!-- Form Column -->
        <div class="col-lg-7">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-header pt-6 pb-2">
                    <h3 class="card-title fw-bold text-gray-900 fs-4">
                        <i class="ki-outline ki-filter-search fs-2 text-primary me-2"></i> Parameter UTM Campaign
                    </h3>
                </div>
                <div class="card-body p-6">
                    <div class="mb-4">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Website URL Utama</label>
                        <input type="url" id="utmBaseUrl" class="form-control form-control-solid" placeholder="https://example.com/promo" value="https://example.com/promo">
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fs-7 fw-semibold text-gray-900 required">Campaign Source (utm_source)</label>
                            <input type="text" id="utmSource" class="form-control form-control-solid" placeholder="contoh: google, facebook, newsletter" value="facebook">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-7 fw-semibold text-gray-900 required">Campaign Medium (utm_medium)</label>
                            <input type="text" id="utmMedium" class="form-control form-control-solid" placeholder="contoh: cpc, banner, bio, email" value="cpc">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Campaign Name (utm_campaign)</label>
                        <input type="text" id="utmCampaign" class="form-control form-control-solid" placeholder="contoh: promo_merdeka, launch_2026" value="promo_september">
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Campaign Term (utm_term - Opsional)</label>
                            <input type="text" id="utmTerm" class="form-control form-control-solid" placeholder="keyword iklan pencarian">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-7 fw-semibold text-gray-900">Campaign Content (utm_content - Opsional)</label>
                            <input type="text" id="utmContent" class="form-control form-control-solid" placeholder="varian materi iklan A/B">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Output Column -->
        <div class="col-lg-5">
            <div class="card card-flush border-0 shadow-sm sticky-top" style="top: 90px;">
                <div class="card-header pt-6 pb-2">
                    <h3 class="card-title fw-bold text-gray-900 fs-4">
                        <i class="ki-outline ki-link fs-2 text-primary me-2"></i> Hasil URL UTM
                    </h3>
                </div>
                <div class="card-body p-6">
                    <div class="mb-4">
                        <label class="form-label fs-7 fw-semibold text-gray-800">Generated Campaign URL</label>
                        <textarea id="utmGeneratedUrl" class="form-control form-control-solid bg-light text-primary fw-semibold fs-7" rows="4" readonly></textarea>
                    </div>

                    <div class="d-flex gap-2 mb-4">
                        <button type="button" id="btnCopyUtm" class="btn btn-primary btn-sm fw-bold flex-grow-1">
                            <i class="ki-outline ki-copy fs-4 me-1"></i> Salin URL
                        </button>
                        <a id="btnTestUtm" href="#" target="_blank" class="btn btn-light-primary btn-sm fw-bold">
                            <i class="ki-outline ki-exit-right-corner fs-4"></i> Buka
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = document.getElementById('utmBaseUrl');
    const source = document.getElementById('utmSource');
    const medium = document.getElementById('utmMedium');
    const campaign = document.getElementById('utmCampaign');
    const term = document.getElementById('utmTerm');
    const content = document.getElementById('utmContent');
    const output = document.getElementById('utmGeneratedUrl');
    const btnTest = document.getElementById('btnTestUtm');

    function generateUtm() {
        let base = baseUrl.value.trim();
        if (!base) {
            output.value = '';
            btnTest.href = '#';
            return;
        }

        const params = new URLSearchParams();
        if (source.value.trim()) params.append('utm_source', source.value.trim());
        if (medium.value.trim()) params.append('utm_medium', medium.value.trim());
        if (campaign.value.trim()) params.append('utm_campaign', campaign.value.trim());
        if (term.value.trim()) params.append('utm_term', term.value.trim());
        if (content.value.trim()) params.append('utm_content', content.value.trim());

        const queryStr = params.toString();
        let finalUrl = base;
        if (queryStr) {
            finalUrl += (base.includes('?') ? '&' : '?') + queryStr;
        }

        output.value = finalUrl;
        btnTest.href = finalUrl;
    }

    [baseUrl, source, medium, campaign, term, content].forEach(el => {
        el.addEventListener('input', generateUtm);
    });

    document.getElementById('btnCopyUtm').addEventListener('click', function() {
        if (!output.value) return;
        navigator.clipboard.writeText(output.value).then(function() {
            Swal.fire({ text: 'UTM URL berhasil disalin!', icon: 'success', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        });
    });

    generateUtm();
});
</script>
@endsection
