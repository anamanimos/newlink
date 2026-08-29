<!-- Biolink Settings Section -->
<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-compass fs-3 text-primary me-2"></i> Biolink Settings
    </h4>
    
    <!-- Biolinks Enabled -->
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="biolinks_is_enabled">Enable Biolinks</label>
            <div class="text-muted fs-8">Allow users to create dynamic biolink profile pages.</div>
        </div>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="biolinks_is_enabled" name="biolinks_is_enabled" checked>
        </div>
    </div>

    <!-- Example Biolink URL -->
    <div class="mb-5">
        <label for="example_url" class="form-label fs-7 fw-semibold text-gray-900">
            Example Biolink URL
        </label>
        <input type="url" class="form-control form-control-solid form-control-sm" id="example_url" name="example_url" value="https://newlink.test/example">
        <div class="form-text fs-8 text-muted">
            A default redirect or demo link for users.
        </div>
    </div>

    <!-- Templates Enabled -->
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="biolinks_templates_is_enabled">Enable Biolink Templates</label>
            <div class="text-muted fs-8">Allow users to select premade design templates.</div>
        </div>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="biolinks_templates_is_enabled" name="biolinks_templates_is_enabled" checked>
        </div>
    </div>

    <!-- Themes Enabled -->
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="biolinks_themes_is_enabled">Enable Biolink Themes</label>
            <div class="text-muted fs-8">Allow users to customize and apply color/gradient themes to their page.</div>
        </div>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="biolinks_themes_is_enabled" name="biolinks_themes_is_enabled" checked>
        </div>
    </div>

    <!-- Custom Branding -->
    <div class="mb-5">
        <label for="branding" class="form-label fs-7 fw-semibold text-gray-900">
            Custom Branding Footer
        </label>
        <textarea class="form-control form-control-solid form-control-sm" id="branding" name="branding" rows="2" placeholder="Powered by NewLink"></textarea>
        <div class="form-text fs-8 text-muted">
            Default branding footer shown on user biolink pages unless white-labeled.
        </div>
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- Shortlinks Settings Section -->
<div class="mb-5">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-disconnect fs-3 text-primary me-2"></i> Shortened Links Settings
    </h4>
    
    <!-- Shortlinks Enabled -->
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="shortlinks_is_enabled">Enable Short Links</label>
            <div class="text-muted fs-8">Allow users to shorten long URLs and track click statistics.</div>
        </div>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="shortlinks_is_enabled" name="shortlinks_is_enabled" checked>
        </div>
    </div>
</div>
