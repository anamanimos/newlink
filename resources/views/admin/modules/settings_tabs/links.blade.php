<!-- Biolink Settings Section -->
<div class="card bg-transparent border-0 mb-4">
    <div class="card-header bg-transparent border-0 p-0 mb-3">
        <h5 class="fw-bold mb-0 text-primary d-flex align-items-center">
            <span data-duo-icons="compass" class="me-2" style="width: 20px; height: 20px;"></span>Biolink Settings
        </h5>
    </div>
    
    <div class="card-body p-0">
        <!-- Biolinks Enabled -->
        <div class="mb-4 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="biolinks_is_enabled" name="biolinks_is_enabled" checked>
            <label class="form-check-label small fw-semibold text-secondary" for="biolinks_is_enabled">
                Enable biolinks
            </label>
            <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
                Allow users to create dynamic biolink profile pages.
            </div>
        </div>

        <!-- Example Biolink URL -->
        <div class="mb-4">
            <label for="example_url" class="form-label small fw-semibold text-secondary">
                Example Biolink URL
            </label>
            <input type="url" class="form-control glass-input" id="example_url" name="example_url" value="https://newlink.test/example">
            <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
                A default redirect or demo link for users.
            </div>
        </div>

        <!-- Templates Enabled -->
        <div class="mb-4 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="biolinks_templates_is_enabled" name="biolinks_templates_is_enabled" checked>
            <label class="form-check-label small fw-semibold text-secondary" for="biolinks_templates_is_enabled">
                Enable biolink templates
            </label>
            <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
                Allow users to select premade design templates.
            </div>
        </div>

        <!-- Themes Enabled -->
        <div class="mb-4 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="biolinks_themes_is_enabled" name="biolinks_themes_is_enabled" checked>
            <label class="form-check-label small fw-semibold text-secondary" for="biolinks_themes_is_enabled">
                Enable biolink themes
            </label>
            <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
                Allow users to customize and apply color/gradient themes to their page.
            </div>
        </div>

        <!-- Custom Branding -->
        <div class="mb-4">
            <label for="branding" class="form-label small fw-semibold text-secondary">
                Custom Branding Footer
            </label>
            <textarea class="form-control glass-input" id="branding" name="branding" rows="2" placeholder="Powered by NamsLink"></textarea>
            <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
                Default branding footer shown on user biolink pages unless white-labeled.
            </div>
        </div>
    </div>
</div>

<hr class="my-4" style="border-color: var(--glass-border);">

<!-- Shortlinks Settings Section -->
<div class="card bg-transparent border-0 mb-4">
    <div class="card-header bg-transparent border-0 p-0 mb-3">
        <h5 class="fw-bold mb-0 text-primary d-flex align-items-center">
            <span data-duo-icons="world" class="me-2" style="width: 20px; height: 20px;"></span>Shortened Links Settings
        </h5>
    </div>
    
    <div class="card-body p-0">
        <!-- Shortlinks Enabled -->
        <div class="mb-4 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="shortlinks_is_enabled" name="shortlinks_is_enabled" checked>
            <label class="form-check-label small fw-semibold text-secondary" for="shortlinks_is_enabled">
                Enable short links
            </label>
            <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
                Allow users to shorten long URLs.
            </div>
        </div>
    </div>
</div>
