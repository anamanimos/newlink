<div class="row">
    <!-- From Name -->
    <div class="col-md-6 mb-4">
        <label for="from_name" class="form-label small fw-semibold text-secondary">
            From Name
        </label>
        <input type="text" class="form-control glass-input" id="from_name" name="from_name" value="NamsLink">
        <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
            Name used to send emails to users.
        </div>
    </div>

    <!-- From Email -->
    <div class="col-md-6 mb-4">
        <label for="from" class="form-label small fw-semibold text-secondary">
            From Email
        </label>
        <input type="email" class="form-control glass-input" id="from" name="from" value="noreply@newlink.test">
        <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
            Email address used to send emails to users.
        </div>
    </div>
</div>

<div class="row">
    <!-- Reply To Name -->
    <div class="col-md-6 mb-4">
        <label for="reply_to_name" class="form-label small fw-semibold text-secondary">
            Reply To Name
        </label>
        <input type="text" class="form-control glass-input" id="reply_to_name" name="reply_to_name" value="NamsLink Support">
    </div>

    <!-- Reply To Email -->
    <div class="col-md-6 mb-4">
        <label for="reply_to" class="form-label small fw-semibold text-secondary">
            Reply To Email
        </label>
        <input type="email" class="form-control glass-input" id="reply_to" name="reply_to" value="support@newlink.test">
    </div>
</div>

<!-- SMTP Host -->
<div class="mb-4">
    <label for="host" class="form-label small fw-semibold text-secondary">
        SMTP Host
    </label>
    <input type="text" class="form-control glass-input" id="host" name="host" value="smtp.mailtrap.io" placeholder="smtp.example.com">
</div>

<div class="row">
    <!-- SMTP Encryption -->
    <div class="col-md-6 mb-4">
        <label for="encryption" class="form-label small fw-semibold text-secondary">
            Encryption
        </label>
        <select id="encryption" name="encryption" class="form-select glass-input">
            <option value="none">None</option>
            <option value="ssl">SSL</option>
            <option value="tls" selected>TLS</option>
        </select>
    </div>

    <!-- SMTP Port -->
    <div class="col-md-6 mb-4">
        <label for="port" class="form-label small fw-semibold text-secondary">
            SMTP Port
        </label>
        <input type="text" class="form-control glass-input" id="port" name="port" value="2525">
    </div>
</div>

<!-- SMTP Authentication -->
<div class="mb-4 form-check form-switch">
    <input class="form-check-input" type="checkbox" id="auth" name="auth" checked>
    <label class="form-check-label small fw-semibold text-secondary" for="auth">
        Use authentication
    </label>
</div>

<!-- SMTP Username -->
<div class="mb-4">
    <label for="username" class="form-label small fw-semibold text-secondary">
        SMTP Username
    </label>
    <input type="text" class="form-control glass-input" id="username" name="username" value="mailtrap-user">
</div>

<!-- SMTP Password -->
<div class="mb-4">
    <label for="password" class="form-label small fw-semibold text-secondary">
        SMTP Password
    </label>
    <input type="password" class="form-control glass-input" id="password" name="password" value="••••••••••••">
</div>
