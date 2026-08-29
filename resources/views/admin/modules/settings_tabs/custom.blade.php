<!-- Global Custom Code Section -->
<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-code fs-3 text-primary me-2"></i> Global Custom Code Injection
    </h4>

    <div class="mb-5">
        <label for="head_js" class="form-label fs-7 fw-semibold text-gray-900">
            Global Head JavaScript
        </label>
        <textarea class="form-control form-control-solid font-monospace fs-7" id="head_js" name="head_js" rows="4" placeholder="// Enter custom JS to inject in head"></textarea>
        <div class="form-text fs-8 text-muted">
            Injected before the closing <code>&lt;/head&gt;</code> tag of all public and admin pages (e.g. Google Analytics, Tag Manager).
        </div>
    </div>

    <div class="mb-5">
        <label for="head_css" class="form-label fs-7 fw-semibold text-gray-900">
            Global Head CSS
        </label>
        <textarea class="form-control form-control-solid font-monospace fs-7" id="head_css" name="head_css" rows="4" placeholder="/* Enter custom CSS rules */"></textarea>
        <div class="form-text fs-8 text-muted">
            Injected before the closing <code>&lt;/head&gt;</code> tag of all pages.
        </div>
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- Biolink Custom Code Section -->
<div class="mb-5">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-compass fs-3 text-primary me-2"></i> Biolink Profile Custom Code
    </h4>

    <div class="mb-5">
        <label for="head_js_biolink" class="form-label fs-7 fw-semibold text-gray-900">
            Biolinks Head JavaScript
        </label>
        <textarea class="form-control form-control-solid font-monospace fs-7" id="head_js_biolink" name="head_js_biolink" rows="4" placeholder="// Enter custom JS to inject in biolinks head"></textarea>
        <div class="form-text fs-8 text-muted">
            Injected only on user-generated biolink public pages.
        </div>
    </div>

    <div class="mb-5">
        <label for="head_css_biolink" class="form-label fs-7 fw-semibold text-gray-900">
            Biolinks Head CSS
        </label>
        <textarea class="form-control form-control-solid font-monospace fs-7" id="head_css_biolink" name="head_css_biolink" rows="4" placeholder="/* Enter custom CSS for biolinks */"></textarea>
        <div class="form-text fs-8 text-muted">
            Injected only on user-generated biolink public pages.
        </div>
    </div>
</div>
