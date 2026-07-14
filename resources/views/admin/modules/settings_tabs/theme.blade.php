<ul class="nav nav-pills d-flex mb-4" id="theme-pills-tab" role="tablist">
    <li class="nav-item flex-fill text-center" role="presentation">
        <button class="nav-link active w-100" id="light-theme-tab" data-bs-toggle="pill" data-bs-target="#light-theme-pane" type="button" role="tab">
            <span data-duo-icons="palette" class="me-2" style="width: 16px; height: 16px;"></span>Light Theme
        </button>
    </li>
    <li class="nav-item flex-fill text-center" role="presentation">
        <button class="nav-link w-100" id="dark-theme-tab" data-bs-toggle="pill" data-bs-target="#dark-theme-pane" type="button" role="tab">
            <span data-duo-icons="moon-stars" class="me-2" style="width: 16px; height: 16px;"></span>Dark Theme
        </button>
    </li>
</ul>

<div class="tab-content" id="theme-pills-tabContent">
    <!-- Light Theme Tab -->
    <div class="tab-pane fade show active" id="light-theme-pane" role="tabpanel">
        <div class="mb-4 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="light_theme_enabled" name="light_theme_enabled" checked>
            <label class="form-check-label small fw-semibold text-secondary" for="light_theme_enabled">
                Enable light theme
            </label>
        </div>

        <div class="mb-4">
            <label for="light_primary" class="form-label small fw-semibold text-secondary">
                Primary Brand Color
            </label>
            <div class="d-flex gap-2">
                <input type="color" class="form-control form-control-color glass-input p-1" style="width: 50px; height: 38px;" id="light_primary" name="light_primary" value="#6366f1">
                <input type="text" class="form-control glass-input" value="#6366f1" placeholder="#6366f1">
            </div>
            <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
                Default accent color for links, buttons, and active elements in Light Mode.
            </div>
        </div>

        <div class="mb-4">
            <label for="light_gray" class="form-label small fw-semibold text-secondary">
                Background / Base Gray Color
            </label>
            <div class="d-flex gap-2">
                <input type="color" class="form-control form-control-color glass-input p-1" style="width: 50px; height: 38px;" id="light_gray" name="light_gray" value="#f8fafc">
                <input type="text" class="form-control glass-input" value="#f8fafc" placeholder="#f8fafc">
            </div>
        </div>
    </div>

    <!-- Dark Theme Tab -->
    <div class="tab-pane fade" id="dark-theme-pane" role="tabpanel">
        <div class="mb-4 form-check form-switch">
            <input class="form-check-input" type="checkbox" id="dark_theme_enabled" name="dark_theme_enabled" checked>
            <label class="form-check-label small fw-semibold text-secondary" for="dark_theme_enabled">
                Enable dark theme
            </label>
        </div>

        <div class="mb-4">
            <label for="dark_primary" class="form-label small fw-semibold text-secondary">
                Primary Brand Color
            </label>
            <div class="d-flex gap-2">
                <input type="color" class="form-control form-control-color glass-input p-1" style="width: 50px; height: 38px;" id="dark_primary" name="dark_primary" value="#818cf8">
                <input type="text" class="form-control glass-input" value="#818cf8" placeholder="#818cf8">
            </div>
            <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
                Default accent color for links, buttons, and active elements in Dark Mode.
            </div>
        </div>

        <div class="mb-4">
            <label for="dark_gray" class="form-label small fw-semibold text-secondary">
                Background / Base Gray Color
            </label>
            <div class="d-flex gap-2">
                <input type="color" class="form-control form-control-color glass-input p-1" style="width: 50px; height: 38px;" id="dark_gray" name="dark_gray" value="#090d16">
                <input type="text" class="form-control glass-input" value="#090d16" placeholder="#090d16">
            </div>
        </div>
    </div>
</div>
