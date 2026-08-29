<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold mb-6" id="theme-pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary active py-3 px-4" id="light-theme-tab" data-bs-toggle="tab" href="#light-theme-pane" role="tab">
            <i class="ki-outline ki-sun fs-4 me-2"></i> Light Theme
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary py-3 px-4" id="dark-theme-tab" data-bs-toggle="tab" href="#dark-theme-pane" role="tab">
            <i class="ki-outline ki-moon fs-4 me-2"></i> Dark Theme
        </a>
    </li>
</ul>

<div class="tab-content" id="theme-pills-tabContent">
    <!-- Light Theme Tab -->
    <div class="tab-pane fade show active" id="light-theme-pane" role="tabpanel">
        <div class="d-flex align-items-center justify-content-between mb-6">
            <div>
                <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="light_theme_enabled">Enable Light Theme</label>
                <div class="text-muted fs-8">Allow users to view the platform in light mode.</div>
            </div>
            <div class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="light_theme_enabled" name="light_theme_enabled" checked>
            </div>
        </div>

        <div class="mb-5">
            <label for="light_primary" class="form-label fs-7 fw-semibold text-gray-900">
                Primary Brand Color
            </label>
            <div class="d-flex align-items-center gap-3">
                <input type="color" class="form-control form-control-color border-0 p-1 rounded-3" style="width: 44px; height: 38px;" id="light_primary" name="light_primary" value="#6366f1">
                <input type="text" class="form-control form-control-solid form-control-sm" value="#6366f1" placeholder="#6366f1">
            </div>
            <div class="form-text fs-8 text-muted mt-1">
                Default accent color for links, buttons, and active elements in Light Mode.
            </div>
        </div>

        <div class="mb-5">
            <label for="light_gray" class="form-label fs-7 fw-semibold text-gray-900">
                Background / Base Gray Color
            </label>
            <div class="d-flex align-items-center gap-3">
                <input type="color" class="form-control form-control-color border-0 p-1 rounded-3" style="width: 44px; height: 38px;" id="light_gray" name="light_gray" value="#f8fafc">
                <input type="text" class="form-control form-control-solid form-control-sm" value="#f8fafc" placeholder="#f8fafc">
            </div>
        </div>
    </div>

    <!-- Dark Theme Tab -->
    <div class="tab-pane fade" id="dark-theme-pane" role="tabpanel">
        <div class="d-flex align-items-center justify-content-between mb-6">
            <div>
                <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="dark_theme_enabled">Enable Dark Theme</label>
                <div class="text-muted fs-8">Allow users to switch to dark mode.</div>
            </div>
            <div class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="dark_theme_enabled" name="dark_theme_enabled" checked>
            </div>
        </div>

        <div class="mb-5">
            <label for="dark_primary" class="form-label fs-7 fw-semibold text-gray-900">
                Primary Brand Color
            </label>
            <div class="d-flex align-items-center gap-3">
                <input type="color" class="form-control form-control-color border-0 p-1 rounded-3" style="width: 44px; height: 38px;" id="dark_primary" name="dark_primary" value="#818cf8">
                <input type="text" class="form-control form-control-solid form-control-sm" value="#818cf8" placeholder="#818cf8">
            </div>
            <div class="form-text fs-8 text-muted mt-1">
                Default accent color for links, buttons, and active elements in Dark Mode.
            </div>
        </div>

        <div class="mb-5">
            <label for="dark_gray" class="form-label fs-7 fw-semibold text-gray-900">
                Background / Base Gray Color
            </label>
            <div class="d-flex align-items-center gap-3">
                <input type="color" class="form-control form-control-color border-0 p-1 rounded-3" style="width: 44px; height: 38px;" id="dark_gray" name="dark_gray" value="#090d16">
                <input type="text" class="form-control form-control-solid form-control-sm" value="#090d16" placeholder="#090d16">
            </div>
        </div>
    </div>
</div>
