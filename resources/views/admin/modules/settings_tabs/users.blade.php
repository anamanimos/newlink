<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-profile-user fs-3 text-primary me-2"></i> User Registration & Authentication
    </h4>

    <!-- Registration Enabled -->
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="register_is_enabled">Enable Registration</label>
            <div class="text-muted fs-8">Allow new visitors to create accounts on the site.</div>
        </div>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="register_is_enabled" name="register_is_enabled" checked>
        </div>
    </div>

    <!-- Only Social Logins -->
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="register_only_social_logins">Social Logins Only</label>
            <div class="text-muted fs-8">Force users to register/login using configured social networks (Google, Facebook, etc.).</div>
        </div>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="register_only_social_logins" name="register_only_social_logins">
        </div>
    </div>

    <!-- Email Confirmation -->
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="email_confirmation">Email Verification</label>
            <div class="text-muted fs-8">Send a verification email to newly registered users to confirm email ownership.</div>
        </div>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="email_confirmation" name="email_confirmation" checked>
        </div>
    </div>

    <!-- Welcome Email -->
    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <label class="form-check-label fs-7 fw-semibold text-gray-900 mb-0 cursor-pointer" for="welcome_email_is_enabled">Welcome Email</label>
            <div class="text-muted fs-8">Send an onboarding email after registration or email verification.</div>
        </div>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="welcome_email_is_enabled" name="welcome_email_is_enabled" checked>
        </div>
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<div class="mb-7">
    <h4 class="fw-bold text-gray-900 mb-5">
        <i class="ki-outline ki-trash fs-3 text-primary me-2"></i> Account Maintenance & Security
    </h4>

    <div class="row g-5">
        <!-- Auto Delete Unconfirmed Users -->
        <div class="col-md-6">
            <label for="auto_delete_unconfirmed_users" class="form-label fs-7 fw-semibold text-gray-900">
                Auto Delete Unconfirmed Users (Days)
            </label>
            <input type="number" class="form-control form-control-solid form-control-sm" id="auto_delete_unconfirmed_users" name="auto_delete_unconfirmed_users" value="30" min="0">
            <div class="form-text fs-8 text-muted">
                Delete unverified accounts automatically after N days. Set 0 to disable.
            </div>
        </div>

        <!-- Auto Delete Inactive Users -->
        <div class="col-md-6">
            <label for="auto_delete_inactive_users" class="form-label fs-7 fw-semibold text-gray-900">
                Auto Delete Inactive Users (Days)
            </label>
            <input type="number" class="form-control form-control-solid form-control-sm" id="auto_delete_inactive_users" name="auto_delete_inactive_users" value="365" min="0">
            <div class="form-text fs-8 text-muted">
                Delete inactive user accounts automatically after N days. Set 0 to disable.
            </div>
        </div>

        <!-- Blacklisted Domains -->
        <div class="col-12">
            <label for="blacklisted_domains" class="form-label fs-7 fw-semibold text-gray-900">
                Blacklisted Email Domains
            </label>
            <textarea class="form-control form-control-solid form-control-sm" id="blacklisted_domains" name="blacklisted_domains" rows="3" placeholder="tempmail.com, discard.com, mailinator.com"></textarea>
            <div class="form-text fs-8 text-muted">
                Prevent registration using email addresses from these domains. Separate multiple domains by comma.
            </div>
        </div>
    </div>
</div>
