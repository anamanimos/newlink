<!-- Section 1: General Info -->
<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-information-4 fs-3 text-primary me-2"></i> General Information
    </h4>

    <!-- Website Title -->
    <div class="mb-5">
        <label for="title" class="form-label fs-7 fw-semibold text-gray-900 required d-flex align-items-center">
            <i class="ki-outline ki-global fs-5 text-gray-500 me-2"></i> Website Title
        </label>
        <input type="text" class="form-control form-control-solid form-control-sm" id="title" name="title" value="NewLink" required placeholder="Enter website title">
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- Section 2: Branding & Assets -->
<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-picture fs-3 text-primary me-2"></i> Branding & Logos
    </h4>

    <div class="row g-5">
        <!-- Logo Light -->
        <div class="col-md-6">
            <label for="logo_light" class="form-label fs-7 fw-semibold text-gray-900 d-flex align-items-center">
                <i class="ki-outline ki-sun fs-5 text-gray-500 me-2"></i> Logo for Light Theme
            </label>
            <input type="file" class="form-control form-control-solid form-control-sm" id="logo_light" name="logo_light" accept="image/*">
            <div class="form-text fs-8 text-muted">
                .jpg, .png, .svg, .webp allowed. Max 64MB.
            </div>
        </div>

        <!-- Logo Dark -->
        <div class="col-md-6">
            <label for="logo_dark" class="form-label fs-7 fw-semibold text-gray-900 d-flex align-items-center">
                <i class="ki-outline ki-moon fs-5 text-gray-500 me-2"></i> Logo for Dark Theme
            </label>
            <input type="file" class="form-control form-control-solid form-control-sm" id="logo_dark" name="logo_dark" accept="image/*">
            <div class="form-text fs-8 text-muted">
                .jpg, .png, .svg, .webp allowed. Max 64MB.
            </div>
        </div>

        <!-- Logo Emails -->
        <div class="col-md-6">
            <label for="logo_email" class="form-label fs-7 fw-semibold text-gray-900 d-flex align-items-center">
                <i class="ki-outline ki-sms fs-5 text-gray-500 me-2"></i> Logo for Sent Emails
            </label>
            <input type="file" class="form-control form-control-solid form-control-sm" id="logo_email" name="logo_email" accept="image/*">
            <div class="form-text fs-8 text-muted">
                .jpg, .png, .gif allowed. Max 64MB.
            </div>
        </div>

        <!-- Favicon -->
        <div class="col-md-6">
            <label for="favicon" class="form-label fs-7 fw-semibold text-gray-900 d-flex align-items-center">
                <i class="ki-outline ki-element-11 fs-5 text-gray-500 me-2"></i> Favicon
            </label>
            <input type="file" class="form-control form-control-solid form-control-sm" id="favicon" name="favicon" accept="image/*,.ico">
            <div class="form-text fs-8 text-muted">
                .ico, .png, .svg, .webp allowed. Max 64MB.
            </div>
        </div>

        <!-- Opengraph Image -->
        <div class="col-12">
            <label for="opengraph" class="form-label fs-7 fw-semibold text-gray-900 d-flex align-items-center">
                <i class="ki-outline ki-file-sheet fs-5 text-gray-500 me-2"></i> Opengraph (Social Share) Image
            </label>
            <input type="file" class="form-control form-control-solid form-control-sm" id="opengraph" name="opengraph" accept="image/*">
            <div class="form-text fs-8 text-muted">
                .jpg, .png, .webp allowed. Max 64MB. (Recommended: 1200x630px).
            </div>
        </div>
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- Section 3: Regional & Defaults -->
<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-geolocation fs-3 text-primary me-2"></i> Defaults & Localization
    </h4>

    <div class="row g-5">
        <!-- Default Timezone -->
        <div class="col-md-6">
            <label for="default_timezone" class="form-label fs-7 fw-semibold text-gray-900">
                Default Timezone
            </label>
            <select id="default_timezone" name="default_timezone" class="form-select form-select-solid form-select-sm" data-control="select2">
                @foreach(DateTimeZone::listIdentifiers() as $timezone)
                    <option value="{{ $timezone }}" {{ $timezone == 'Asia/Jakarta' ? 'selected' : '' }}>{{ $timezone }}</option>
                @endforeach
            </select>
            <div class="form-text fs-8 text-muted">
                Default timezone for newly registered users.
            </div>
        </div>

        <!-- Default Theme Style -->
        <div class="col-md-6">
            <label for="default_theme_style" class="form-label fs-7 fw-semibold text-gray-900">
                Default Theme Style
            </label>
            <select id="default_theme_style" name="default_theme_style" class="form-select form-select-solid form-select-sm">
                <option value="light" selected>Light Theme</option>
                <option value="dark">Dark Theme</option>
            </select>
        </div>

        <!-- Default Language -->
        <div class="col-md-6">
            <label for="default_language" class="form-label fs-7 fw-semibold text-gray-900">
                Default Language
            </label>
            <select id="default_language" name="default_language" class="form-select form-select-solid form-select-sm">
                <option value="english" selected>English (en)</option>
                <option value="indonesian">Indonesian (id)</option>
            </select>
        </div>

        <!-- Default Results Per Page -->
        <div class="col-md-3">
            <label for="default_results_per_page" class="form-label fs-7 fw-semibold text-gray-900">
                Results Per Page
            </label>
            <select id="default_results_per_page" name="default_results_per_page" class="form-select form-select-solid form-select-sm">
                @foreach([10, 25, 50, 100, 250, 500] as $count)
                    <option value="{{ $count }}" {{ $count == 25 ? 'selected' : '' }}>{{ $count }}</option>
                @endforeach
            </select>
        </div>

        <!-- Default Results Order -->
        <div class="col-md-3">
            <label for="default_order_type" class="form-label fs-7 fw-semibold text-gray-900">
                Results Order
            </label>
            <select id="default_order_type" name="default_order_type" class="form-select form-select-solid form-select-sm">
                <option value="ASC">Ascending (ASC)</option>
                <option value="DESC" selected>Descending (DESC)</option>
            </select>
        </div>
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- Section 4: Advanced & Module Settings (Collapsible Metronic Accordion) -->
<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-element-plus fs-3 text-primary me-2"></i> Advanced Features
    </h4>

    <div class="accordion accordion-icon-toggle" id="main_settings_accordion">
        
        <!-- Item 1: App-wide Settings -->
        <div class="accordion-item mb-4 border rounded-3 overflow-hidden">
            <div class="accordion-header" id="heading_app_settings">
                <button class="accordion-button fs-6 fw-bold collapsed bg-light py-4 px-5 text-gray-800" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_app_settings" aria-expanded="false" aria-controls="collapse_app_settings">
                    <i class="ki-outline ki-setting-2 fs-3 text-primary me-3"></i> App-wide Settings & Toggles
                </button>
            </div>
            <div id="collapse_app_settings" class="accordion-collapse collapse" aria-labelledby="heading_app_settings" data-bs-parent="#main_settings_accordion">
                <div class="accordion-body p-6">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="admin_spotlight_is_enabled">Admin Spotlight</label>
                                <div class="text-muted fs-8">Enable quick command palette (Ctrl+K) for admin users.</div>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="admin_spotlight_is_enabled" name="admin_spotlight_is_enabled" checked>
                            </div>
                        </div>

                        <div class="separator separator-dashed"></div>

                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="user_spotlight_is_enabled">User Spotlight</label>
                                <div class="text-muted fs-8">Enable quick search palette for regular users.</div>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="user_spotlight_is_enabled" name="user_spotlight_is_enabled" checked>
                            </div>
                        </div>

                        <div class="separator separator-dashed"></div>

                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="white_labeling_is_enabled">White Labeling</label>
                                <div class="text-muted fs-8">Remove NewLink brand references from public views.</div>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="white_labeling_is_enabled" name="white_labeling_is_enabled">
                            </div>
                        </div>

                        <div class="separator separator-dashed"></div>

                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="api_is_enabled">API Interface</label>
                                <div class="text-muted fs-8">Enable REST API access for developer integrations.</div>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="api_is_enabled" name="api_is_enabled" checked>
                            </div>
                        </div>

                        <div class="separator separator-dashed"></div>

                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="force_https_is_enabled">Force HTTPS Redirection</label>
                                <div class="text-muted fs-8">Automatically redirect all HTTP traffic to HTTPS.</div>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="force_https_is_enabled" name="force_https_is_enabled">
                            </div>
                        </div>

                        <div class="separator separator-dashed"></div>

                        <div>
                            <label for="iframe_embedding" class="form-label fs-7 fw-semibold text-gray-900">Iframe Embedding Allowed Domains</label>
                            <input type="text" class="form-control form-control-solid form-control-sm" id="iframe_embedding" name="iframe_embedding" value="all" placeholder="e.g. all, *.example.com">
                            <div class="form-text fs-8 text-muted">Use <code>all</code> to allow embedding on any domain, or comma-separated domains.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item 2: Landing Page & Index Settings -->
        <div class="accordion-item mb-4 border rounded-3 overflow-hidden">
            <div class="accordion-header" id="heading_index_settings">
                <button class="accordion-button fs-6 fw-bold collapsed bg-light py-4 px-5 text-gray-800" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_index_settings" aria-expanded="false" aria-controls="collapse_index_settings">
                    <i class="ki-outline ki-home fs-3 text-primary me-3"></i> Home & Index Page Settings
                </button>
            </div>
            <div id="collapse_index_settings" class="accordion-collapse collapse" aria-labelledby="heading_index_settings" data-bs-parent="#main_settings_accordion">
                <div class="accordion-body p-6">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="display_index_plans">Display Plans on Home Page</label>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="display_index_plans" name="display_index_plans" checked>
                            </div>
                        </div>

                        <div class="separator separator-dashed"></div>

                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="display_index_testimonials">Display Testimonials on Home Page</label>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="display_index_testimonials" name="display_index_testimonials" checked>
                            </div>
                        </div>

                        <div class="separator separator-dashed"></div>

                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="display_index_faq">Display FAQ on Home Page</label>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="display_index_faq" name="display_index_faq" checked>
                            </div>
                        </div>

                        <div class="separator separator-dashed"></div>

                        <div>
                            <label for="index_url" class="form-label fs-7 fw-semibold text-gray-900">Custom Home Page Redirect URL</label>
                            <input type="url" class="form-control form-control-solid form-control-sm" id="index_url" name="index_url" placeholder="https://example.com/custom-home">
                            <div class="form-text fs-8 text-muted">Leave blank to use the built-in default landing page.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item 3: System & API Settings -->
        <div class="accordion-item mb-4 border rounded-3 overflow-hidden">
            <div class="accordion-header" id="heading_other_settings">
                <button class="accordion-button fs-6 fw-bold collapsed bg-light py-4 px-5 text-gray-800" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_other_settings" aria-expanded="false" aria-controls="collapse_other_settings">
                    <i class="ki-outline ki-code fs-3 text-primary me-3"></i> System Cache & External APIs
                </button>
            </div>
            <div id="collapse_other_settings" class="accordion-collapse collapse" aria-labelledby="heading_other_settings" data-bs-parent="#main_settings_accordion">
                <div class="accordion-body p-6">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="not_found_url" class="form-label fs-7 fw-semibold text-gray-900">Custom 404 Redirect URL</label>
                            <input type="url" class="form-control form-control-solid form-control-sm" id="not_found_url" name="not_found_url" placeholder="https://example.com/404">
                        </div>

                        <div class="col-md-6">
                            <label for="chart_cache" class="form-label fs-7 fw-semibold text-gray-900">Chart Cache Lifetime (Hours)</label>
                            <input type="number" class="form-control form-control-solid form-control-sm" id="chart_cache" name="chart_cache" value="12" min="0">
                        </div>

                        <div class="col-12">
                            <label for="openai_api_key" class="form-label fs-7 fw-semibold text-gray-900">OpenAI API Key (AI Integration)</label>
                            <input type="password" class="form-control form-control-solid form-control-sm" id="openai_api_key" name="openai_api_key" value="" placeholder="sk-...">
                            <div class="form-text fs-8 text-muted">Used for AI features such as generating bio descriptions.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- Section 5: SEO & Sitemap -->
<div class="mb-2">
    <label for="sitemap" class="form-label fs-7 fw-semibold text-gray-900">
        <i class="ki-outline ki-document fs-5 text-gray-500 me-2"></i> Dynamic Sitemap URL
    </label>
    <div class="input-group input-group-sm">
        <input type="text" class="form-control form-control-solid" id="sitemap" name="sitemap" value="{{ url('/sitemap.xml') }}" readonly>
        <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-light-primary fw-bold">
            <i class="ki-outline ki-exit-right-corner fs-5 me-1"></i> Open Sitemap
        </a>
    </div>
</div>
