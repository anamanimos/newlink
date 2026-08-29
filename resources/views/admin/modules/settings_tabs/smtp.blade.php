<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-sms fs-3 text-primary me-2"></i> Sender Details & Routing
    </h4>

    <div class="row g-5">
        <!-- From Name -->
        <div class="col-md-6">
            <label for="from_name" class="form-label fs-7 fw-semibold text-gray-900">
                From Name
            </label>
            <input type="text" class="form-control form-control-solid form-control-sm" id="from_name" name="from_name" value="NewLink">
            <div class="form-text fs-8 text-muted">
                Name used as sender in outgoing platform emails.
            </div>
        </div>

        <!-- From Email -->
        <div class="col-md-6">
            <label for="from" class="form-label fs-7 fw-semibold text-gray-900">
                From Email Address
            </label>
            <input type="email" class="form-control form-control-solid form-control-sm" id="from" name="from" value="noreply@newlink.test">
            <div class="form-text fs-8 text-muted">
                Sender email address used in outgoing headers.
            </div>
        </div>

        <!-- Reply To Name -->
        <div class="col-md-6">
            <label for="reply_to_name" class="form-label fs-7 fw-semibold text-gray-900">
                Reply-To Name
            </label>
            <input type="text" class="form-control form-control-solid form-control-sm" id="reply_to_name" name="reply_to_name" value="NewLink Support">
        </div>

        <!-- Reply To Email -->
        <div class="col-md-6">
            <label for="reply_to" class="form-label fs-7 fw-semibold text-gray-900">
                Reply-To Email Address
            </label>
            <input type="email" class="form-control form-control-solid form-control-sm" id="reply_to" name="reply_to" value="support@newlink.test">
        </div>
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-shield-search fs-3 text-primary me-2"></i> SMTP Server Configuration
    </h4>

    <div class="row g-5">
        <!-- SMTP Host -->
        <div class="col-12">
            <label for="host" class="form-label fs-7 fw-semibold text-gray-900">
                SMTP Host
            </label>
            <input type="text" class="form-control form-control-solid form-control-sm" id="host" name="host" value="smtp.mailtrap.io" placeholder="smtp.example.com">
        </div>

        <!-- SMTP Encryption -->
        <div class="col-md-6">
            <label for="encryption" class="form-label fs-7 fw-semibold text-gray-900">
                Encryption Protocol
            </label>
            <select id="encryption" name="encryption" class="form-select form-select-solid form-select-sm">
                <option value="none">None</option>
                <option value="ssl">SSL</option>
                <option value="tls" selected>TLS</option>
            </select>
        </div>

        <!-- SMTP Port -->
        <div class="col-md-6">
            <label for="port" class="form-label fs-7 fw-semibold text-gray-900">
                SMTP Port
            </label>
            <input type="text" class="form-control form-control-solid form-control-sm" id="port" name="port" value="2525">
        </div>

        <!-- SMTP Authentication -->
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="auth">Require SMTP Authentication</label>
                    <div class="text-muted fs-8">Enable username and password verification for SMTP server.</div>
                </div>
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="auth" name="auth" checked>
                </div>
            </div>
        </div>

        <!-- SMTP Username -->
        <div class="col-md-6">
            <label for="username" class="form-label fs-7 fw-semibold text-gray-900">
                SMTP Username
            </label>
            <input type="text" class="form-control form-control-solid form-control-sm" id="username" name="username" value="mailtrap-user">
        </div>

        <!-- SMTP Password -->
        <div class="col-md-6">
            <label for="password" class="form-label fs-7 fw-semibold text-gray-900">
                SMTP Password
            </label>
            <input type="password" class="form-control form-control-solid form-control-sm" id="password" name="password" value="••••••••••••">
        </div>
    </div>
</div>
