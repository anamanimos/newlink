<div class="py-6 text-center">
    <div class="symbol symbol-60px symbol-circle mb-4 bg-light-primary">
        <span class="symbol-label">
            <i class="ki-outline ki-setting-2 fs-2x text-primary"></i>
        </span>
    </div>
    <h4 class="fw-bold text-gray-900 mb-1 text-capitalize">Settings: {{ str_replace('-', ' ', $tab == 'cookie-consent' ? 'Cookie Consent' : $tab) }}</h4>
    <p class="text-muted fs-7 mb-6">Manage configurations for this system sub-module.</p>
</div>

<!-- Fallback Settings Inputs -->
<div class="d-flex align-items-center justify-content-between mb-6">
    <div>
        <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer text-capitalize" for="{{ $tab }}_is_enabled">
            Enable {{ str_replace('-', ' ', $tab) }} Module
        </label>
        <div class="text-muted fs-8">Toggle this module active or inactive across the site.</div>
    </div>
    <div class="form-check form-switch form-check-custom form-check-solid">
        <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="{{ $tab }}_is_enabled" name="{{ $tab }}_is_enabled" checked>
    </div>
</div>

<div class="mb-5">
    <label for="{{ $tab }}_api" class="form-label fs-7 fw-semibold text-gray-900">
        API Credentials / Client ID
    </label>
    <input type="text" class="form-control form-control-solid form-control-sm" id="{{ $tab }}_api" name="{{ $tab }}_api" placeholder="Enter key or ID for {{ $tab }}">
</div>

<div class="mb-5">
    <label for="{{ $tab }}_secret" class="form-label fs-7 fw-semibold text-gray-900">
        Secret Token / API Secret
    </label>
    <input type="password" class="form-control form-control-solid form-control-sm" id="{{ $tab }}_secret" name="{{ $tab }}_secret" value="••••••••••••">
</div>
