<div class="py-4 text-center">
    <div class="p-3 rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--primary-color);">
        <span data-duo-icons="settings" style="width: 32px; height: 32px;"></span>
    </div>
    <h5 class="fw-bold mb-2">Settings - {{ ucfirst($tab == 'cookie-consent' ? 'Cookie consent' : $tab) }}</h5>
    <p class="text-secondary small mb-4">Replicating configurations for system sub-module.</p>
</div>

<!-- Fallback Settings Inputs -->
<div class="mb-4 form-check form-switch">
    <input class="form-check-input" type="checkbox" id="{{ $tab }}_is_enabled" name="{{ $tab }}_is_enabled" checked>
    <label class="form-check-label small fw-semibold text-secondary" for="{{ $tab }}_is_enabled">
        Enable {{ str_replace('-', ' ', $tab) }} module
    </label>
</div>

<div class="mb-4">
    <label for="{{ $tab }}_api" class="form-label small fw-semibold text-secondary">
        API Credentials / Client ID
    </label>
    <input type="text" class="form-control glass-input" id="{{ $tab }}_api" name="{{ $tab }}_api" placeholder="Enter key or ID for {{ $tab }}">
</div>

<div class="mb-4">
    <label for="{{ $tab }}_secret" class="form-label small fw-semibold text-secondary">
        Secret Token
    </label>
    <input type="password" class="form-control glass-input" id="{{ $tab }}_secret" name="{{ $tab }}_secret" value="••••••••••••">
</div>
