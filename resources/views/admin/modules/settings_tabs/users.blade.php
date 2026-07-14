<!-- Registration Enabled -->
<div class="mb-4 form-check form-switch">
    <input class="form-check-input" type="checkbox" id="register_is_enabled" name="register_is_enabled" checked>
    <label class="form-check-label small fw-semibold text-secondary" for="register_is_enabled">
        Enable registration
    </label>
    <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
        Allow new users to register on the site.
    </div>
</div>

<!-- Only Social Logins -->
<div class="mb-4 form-check form-switch">
    <input class="form-check-input" type="checkbox" id="register_only_social_logins" name="register_only_social_logins">
    <label class="form-check-label small fw-semibold text-secondary" for="register_only_social_logins">
        Only social logins
    </label>
    <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
        Force users to only register/login using active social networks.
    </div>
</div>

<!-- Email Confirmation -->
<div class="mb-4 form-check form-switch">
    <input class="form-check-input" type="checkbox" id="email_confirmation" name="email_confirmation" checked>
    <label class="form-check-label small fw-semibold text-secondary" for="email_confirmation">
        Email confirmation
    </label>
    <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
        Send a verification email to newly registered users to confirm email ownership.
    </div>
</div>

<!-- Welcome Email -->
<div class="mb-4 form-check form-switch">
    <input class="form-check-input" type="checkbox" id="welcome_email_is_enabled" name="welcome_email_is_enabled" checked>
    <label class="form-check-label small fw-semibold text-secondary" for="welcome_email_is_enabled">
        Enable welcome email
    </label>
    <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
        Send a welcome email to users after registration or email confirmation.
    </div>
</div>

<!-- Auto Delete Unconfirmed Users -->
<div class="mb-4">
    <label for="auto_delete_unconfirmed_users" class="form-label small fw-semibold text-secondary">
        Auto delete unconfirmed users (Days)
    </label>
    <input type="number" class="form-control glass-input" id="auto_delete_unconfirmed_users" name="auto_delete_unconfirmed_users" value="30">
    <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
        Delete unconfirmed user accounts automatically after N days. Set 0 to disable.
    </div>
</div>

<!-- Auto Delete Inactive Users -->
<div class="mb-4">
    <label for="auto_delete_inactive_users" class="form-label small fw-semibold text-secondary">
        Auto delete inactive users (Days)
    </label>
    <input type="number" class="form-control glass-input" id="auto_delete_inactive_users" name="auto_delete_inactive_users" value="365">
    <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
        Delete inactive user accounts automatically after N days. Set 0 to disable.
    </div>
</div>

<!-- Blacklisted Domains -->
<div class="mb-4">
    <label for="blacklisted_domains" class="form-label small fw-semibold text-secondary">
        Blacklisted email domains
    </label>
    <textarea class="form-control glass-input" id="blacklisted_domains" name="blacklisted_domains" rows="3" placeholder="tempmail.com, discard.com"></textarea>
    <div class="form-text text-muted small mt-1" style="font-size: 0.725rem;">
        Prevent registration using email addresses from these domains. Separate by comma.
    </div>
</div>
