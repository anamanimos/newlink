<!-- Website Title -->
<div class="mb-4">
    <label for="title" class="form-label small fw-semibold text-secondary d-flex align-items-center">
        <span data-duo-icons="world" class="me-2 text-muted" style="width: 16px; height: 16px;"></span>Website title
    </label>
    <input type="text" class="form-control glass-input" id="title" name="title" value="NamsLink" required>
</div>

<!-- Logo Light -->
<div class="mb-4">
    <label for="logo_light" class="form-label small fw-semibold text-secondary d-flex align-items-center">
        <span data-duo-icons="palette" class="me-2 text-muted" style="width: 16px; height: 16px;"></span>Logo for light theme
    </label>
    <input type="file" class="form-control glass-input" id="logo_light" name="logo_light">
    <div class="form-text text-muted small mt-1.5" style="font-size: 0.725rem;">
        .jpg, .jpeg, .png, .svg, .gif, .webp allowed. 64 MB maximum.
    </div>
</div>

<!-- Logo Dark -->
<div class="mb-4">
    <label for="logo_dark" class="form-label small fw-semibold text-secondary d-flex align-items-center">
        <span data-duo-icons="moon-stars" class="me-2 text-muted" style="width: 16px; height: 16px;"></span>Logo for dark theme
    </label>
    <input type="file" class="form-control glass-input" id="logo_dark" name="logo_dark">
    <div class="form-text text-muted small mt-1.5" style="font-size: 0.725rem;">
        .jpg, .jpeg, .png, .svg, .gif, .webp allowed. 64 MB maximum.
    </div>
</div>

<!-- Logo Emails -->
<div class="mb-4">
    <label for="logo_email" class="form-label small fw-semibold text-secondary d-flex align-items-center">
        <span data-duo-icons="message-3" class="me-2 text-muted" style="width: 16px; height: 16px;"></span>Logo for sent emails
    </label>
    <input type="file" class="form-control glass-input" id="logo_email" name="logo_email">
    <div class="form-text text-muted small mt-1.5" style="font-size: 0.725rem;">
        .jpg, .jpeg, .png, .gif allowed. 64 MB maximum.
    </div>
</div>

<!-- Favicon -->
<div class="mb-4">
    <label for="favicon" class="form-label small fw-semibold text-secondary d-flex align-items-center">
        <span data-duo-icons="app" class="me-2 text-muted" style="width: 16px; height: 16px;"></span>Favicon
    </label>
    <input type="file" class="form-control glass-input" id="favicon" name="favicon">
    <div class="form-text text-muted small mt-1.5" style="font-size: 0.725rem;">
        .jpg, .jpeg, .png, .ico, .svg, .gif, .webp allowed. 64 MB maximum.
    </div>
</div>

<!-- Opengraph Image -->
<div class="mb-4">
    <label for="opengraph" class="form-label small fw-semibold text-secondary d-flex align-items-center">
        <span data-duo-icons="file" class="me-2 text-muted" style="width: 16px; height: 16px;"></span>Opengraph image
    </label>
    <input type="file" class="form-control glass-input" id="opengraph" name="opengraph">
    <div class="form-text text-muted small mt-1.5" style="font-size: 0.725rem;">
        .jpg, .jpeg, .png, .gif, .webp allowed. 64 MB maximum.
    </div>
</div>

<hr class="my-4" style="border-color: var(--glass-border);">

<!-- Default Timezone -->
<div class="mb-4">
    <label for="default_timezone" class="form-label small fw-semibold text-secondary">
        Default timezone
    </label>
    <select id="default_timezone" name="default_timezone" class="form-select glass-input">
        @foreach(DateTimeZone::listIdentifiers() as $timezone)
            <option value="{{ $timezone }}" {{ $timezone == 'Asia/Jakarta' ? 'selected' : '' }}>{{ $timezone }}</option>
        @endforeach
    </select>
    <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
        The default timezone for users when they are registering (they can change it later on).
    </div>
</div>

<!-- Default Theme Style -->
<div class="mb-4">
    <label for="default_theme_style" class="form-label small fw-semibold text-secondary">
        Default theme style
    </label>
    <select id="default_theme_style" name="default_theme_style" class="form-select glass-input">
        <option value="light" selected>light</option>
        <option value="dark">dark</option>
    </select>
</div>

<!-- Default Language -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <label for="default_language" class="form-label small fw-semibold text-secondary mb-0">
            Default language
        </label>
        <a href="#" class="small text-decoration-none">+ Create</a>
    </div>
    <select id="default_language" name="default_language" class="form-select glass-input">
        <option value="english" selected>english - en</option>
        <option value="indonesian">indonesian - id</option>
    </select>
</div>

<!-- Default Results Per Page -->
<div class="mb-4">
    <label for="default_results_per_page" class="form-label small fw-semibold text-secondary">
        Default results per page
    </label>
    <select id="default_results_per_page" name="default_results_per_page" class="form-select glass-input">
        @foreach([10, 25, 50, 100, 250, 500, 1000] as $count)
            <option value="{{ $count }}" {{ $count == 25 ? 'selected' : '' }}>{{ $count }}</option>
        @endforeach
    </select>
</div>

<!-- Default Results Order -->
<div class="mb-4">
    <label for="default_order_type" class="form-label small fw-semibold text-secondary">
        Default results order
    </label>
    <select id="default_order_type" name="default_order_type" class="form-select glass-input">
        <option value="ASC">Ascending</option>
        <option value="DESC" selected>Descending</option>
    </select>
</div>

<!-- Collapsible Sections -->
<div class="d-grid gap-2 mb-4">
    <!-- App-wide settings -->
    <button class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center py-2.5 px-3 border border-secondary border-opacity-10 rounded-3 text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#app_settings_container">
        <span class="d-flex align-items-center">
            <span data-duo-icons="settings" class="me-2" style="width: 16px; height: 16px;"></span>App-wide settings
        </span>
        <span class="small opacity-50">+</span>
    </button>
    <div class="collapse p-3 border border-secondary border-opacity-10 rounded-3 mb-2" id="app_settings_container">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="admin_spotlight_is_enabled" name="admin_spotlight_is_enabled" checked>
            <label class="form-check-label small" for="admin_spotlight_is_enabled">Admin spotlight enabled</label>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="user_spotlight_is_enabled" name="user_spotlight_is_enabled" checked>
            <label class="form-check-label small" for="user_spotlight_is_enabled">User spotlight enabled</label>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="white_labeling_is_enabled" name="white_labeling_is_enabled">
            <label class="form-check-label small" for="white_labeling_is_enabled">White labeling enabled</label>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="api_is_enabled" name="api_is_enabled" checked>
            <label class="form-check-label small" for="api_is_enabled">API interface enabled</label>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="force_https_is_enabled" name="force_https_is_enabled">
            <label class="form-check-label small" for="force_https_is_enabled">Force HTTPS redirection</label>
        </div>
        <div class="mb-2">
            <label for="iframe_embedding" class="form-label small">Iframe embedding allowed domains</label>
            <input type="text" class="form-control glass-input py-1.5" id="iframe_embedding" name="iframe_embedding" value="all">
        </div>
    </div>

    <!-- Index settings -->
    <button class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center py-2.5 px-3 border border-secondary border-opacity-10 rounded-3 text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#index_settings_container">
        <span class="d-flex align-items-center">
            <span data-duo-icons="compass" class="me-2" style="width: 16px; height: 16px;"></span>Index settings
        </span>
        <span class="small opacity-50">+</span>
    </button>
    <div class="collapse p-3 border border-secondary border-opacity-10 rounded-3 mb-2" id="index_settings_container">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="display_index_plans" name="display_index_plans" checked>
            <label class="form-check-label small" for="display_index_plans">Display plans on home page</label>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="display_index_testimonials" name="display_index_testimonials" checked>
            <label class="form-check-label small" for="display_index_testimonials">Display testimonials on home page</label>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="display_index_faq" name="display_index_faq" checked>
            <label class="form-check-label small" for="display_index_faq">Display FAQ on home page</label>
        </div>
        <div class="mb-2">
            <label for="index_url" class="form-label small">Custom Home Page Redirect URL</label>
            <input type="url" class="form-control glass-input py-1.5" id="index_url" name="index_url" placeholder="https://example.com">
        </div>
    </div>

    <!-- Other settings -->
    <button class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center py-2.5 px-3 border border-secondary border-opacity-10 rounded-3 text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#other_settings_container">
        <span class="d-flex align-items-center">
            <span data-duo-icons="target" class="me-2" style="width: 16px; height: 16px;"></span>Other settings
        </span>
        <span class="small opacity-50">+</span>
    </button>
    <div class="collapse p-3 border border-secondary border-opacity-10 rounded-3 mb-2" id="other_settings_container">
        <div class="mb-3">
            <label for="not_found_url" class="form-label small">Custom 404 Redirect URL</label>
            <input type="url" class="form-control glass-input py-1.5" id="not_found_url" name="not_found_url">
        </div>
        <div class="mb-3">
            <label for="chart_cache" class="form-label small">Chart cache lifetime (hours)</label>
            <input type="number" class="form-control glass-input py-1.5" id="chart_cache" name="chart_cache" value="12">
        </div>
        <div class="mb-2">
            <label for="openai_api_key" class="form-label small">OpenAI API Key</label>
            <input type="password" class="form-control glass-input py-1.5" id="openai_api_key" name="openai_api_key" value="">
        </div>
    </div>
</div>

<!-- Sitemap URL -->
<div class="mb-4">
    <label for="sitemap" class="form-label small fw-semibold text-secondary">
        Sitemap URL
    </label>
    <input type="text" class="form-control glass-input bg-opacity-25" id="sitemap" name="sitemap" value="https://newlink.test/sitemap" readonly>
</div>
