@php
    $payment = $settings ?? [];
    $isEnabled = $payment['is_enabled'] ?? true;
    $type = $payment['type'] ?? 'both';
    $defaultPaymentType = $payment['default_payment_type'] ?? 'one_time';
    $defaultPaymentFrequency = $payment['default_payment_frequency'] ?? 'monthly';
    $currencies = $payment['currencies'] ?? [
        'USD' => ['code' => 'USD', 'symbol' => '$', 'default_payment_processor' => 'paypal'],
        'IDR' => ['code' => 'IDR', 'symbol' => 'Rp', 'default_payment_processor' => 'midtrans']
    ];
    $defaultCurrency = $payment['default_currency'] ?? 'USD';
    $codesIsEnabled = $payment['codes_is_enabled'] ?? true;
    $taxesAndBillingIsEnabled = $payment['taxes_and_billing_is_enabled'] ?? true;
    $invoicesIsEnabled = $payment['invoices_is_enabled'] ?? true;
    $userPlanExpiryReminder = $payment['user_plan_expiry_reminder'] ?? 3;
    $userPlanExpiryCheckerIsEnabled = $payment['user_plan_expiry_checker_is_enabled'] ?? true;
    $currencyExchangeApiKey = $payment['currency_exchange_api_key'] ?? '';
@endphp

<!-- Documentation Alert -->
<div class="alert alert-light-info d-flex align-items-center p-4 mb-7 rounded-3 border-0">
    <i class="ki-outline ki-information-5 fs-2hx text-info me-3"></i>
    <div class="fs-7 text-gray-800">
        Read the <a href="https://altumcode.com/docs" target="_blank" class="fw-bold text-info text-hover-primary text-decoration-underline">documentation</a> to get more details.
    </div>
</div>

<!-- 1. Enable Payments System -->
<div class="mb-7">
    <div class="d-flex align-items-center justify-content-between mb-1">
        <label class="form-check-label fs-7 fw-bold text-gray-900 cursor-pointer d-flex align-items-center" for="is_enabled">
            <i class="ki-outline ki-dollar fs-4 text-gray-600 me-2"></i> Enable Payments System
        </label>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="is_enabled" name="is_enabled" {{ $isEnabled ? 'checked' : '' }}>
        </div>
    </div>
    <div class="text-muted fs-8">Disabling the payment system will remove all the options for the users to upgrade their accounts or see any payment related information.</div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- 2. Enabled payment types -->
<div class="mb-6">
    <label for="type" class="form-label fs-7 fw-bold text-gray-900 d-flex align-items-center">
        <i class="ki-outline ki-category fs-4 text-gray-600 me-2"></i> Enabled payment types
    </label>
    <select id="type" name="type" class="form-select form-select-solid form-select-sm">
        <option value="both" {{ $type == 'both' ? 'selected' : '' }}>Both</option>
        <option value="one_time" {{ $type == 'one_time' ? 'selected' : '' }}>One time</option>
        <option value="recurring" {{ $type == 'recurring' ? 'selected' : '' }}>Recurring</option>
    </select>
</div>

<!-- 3. Default payment type -->
<div class="mb-6">
    <label for="default_payment_type" class="form-label fs-7 fw-bold text-gray-900 d-flex align-items-center">
        <i class="ki-outline ki-tag fs-4 text-gray-600 me-2"></i> Default payment type
    </label>
    <select id="default_payment_type" name="default_payment_type" class="form-select form-select-solid form-select-sm">
        <option value="one_time" {{ $defaultPaymentType == 'one_time' ? 'selected' : '' }}>One time</option>
        <option value="recurring" {{ $defaultPaymentType == 'recurring' ? 'selected' : '' }}>Recurring</option>
    </select>
</div>

<!-- 4. Default payment frequency -->
<div class="mb-7">
    <label for="default_payment_frequency" class="form-label fs-7 fw-bold text-gray-900 d-flex align-items-center">
        <i class="ki-outline ki-calendar fs-4 text-gray-600 me-2"></i> Default payment frequency
    </label>
    <select id="default_payment_frequency" name="default_payment_frequency" class="form-select form-select-solid form-select-sm">
        <option value="monthly" {{ $defaultPaymentFrequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
        <option value="annual" {{ $defaultPaymentFrequency == 'annual' ? 'selected' : '' }}>Annual</option>
        <option value="lifetime" {{ $defaultPaymentFrequency == 'lifetime' ? 'selected' : '' }}>Lifetime</option>
    </select>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- 5. Currencies Repeater -->
<div class="mb-7">
    <label class="form-label fs-6 fw-bolder text-gray-900 d-flex align-items-center mb-4">
        <i class="ki-outline ki-finance-calculator fs-3 text-primary me-2"></i> Currencies
    </label>

    <div id="currencies_container" class="d-flex flex-column gap-4 mb-4">
        @foreach($currencies as $curr)
            <div class="card card-bordered border-gray-300 shadow-none p-5 rounded-3 currency-item">
                <div class="mb-4">
                    <label class="form-label fs-7 fw-bold text-gray-800 d-flex align-items-center">
                        <i class="ki-outline ki-bank fs-5 text-gray-500 me-2"></i> Currency code
                    </label>
                    <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-bold" name="currencies_code[]" value="{{ $curr['code'] ?? '' }}" placeholder="USD" required>
                    <div class="text-muted fs-8 mt-1">Currency code for the payments (ex: USD, EUR, IDR, CAD). ISO-4217 standard.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fs-7 fw-bold text-gray-800 d-flex align-items-center">
                        <i class="ki-outline ki-text fs-5 text-gray-500 me-2"></i> Currency symbol
                    </label>
                    <input type="text" class="form-control form-control-solid form-control-sm" name="currencies_symbol[]" value="{{ $curr['symbol'] ?? '$' }}" placeholder="$" required>
                </div>

                <div class="mb-5">
                    <label class="form-label fs-7 fw-bold text-gray-800 d-flex align-items-center">
                        <i class="ki-outline ki-wallet fs-5 text-gray-500 me-2"></i> Default processor
                    </label>
                    <select class="form-select form-select-solid form-select-sm" name="currencies_default_processor[]">
                        @php
                            $proc = strtolower($curr['default_payment_processor'] ?? 'paypal');
                        @endphp
                        <option value="paypal" {{ $proc == 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="stripe" {{ $proc == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        <option value="midtrans" {{ $proc == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                        <option value="tripay" {{ $proc == 'tripay' ? 'selected' : '' }}>Tripay</option>
                        <option value="xendit" {{ $proc == 'xendit' ? 'selected' : '' }}>Xendit</option>
                        <option value="offline_payment" {{ $proc == 'offline_payment' ? 'selected' : '' }}>Offline / Bank Transfer</option>
                        <option value="crypto" {{ $proc == 'crypto' ? 'selected' : '' }}>Crypto / Coinbase</option>
                    </select>
                    <div class="text-muted fs-8 mt-1">Make sure the payment processor has this particular currency code enabled.</div>
                </div>

                <button type="button" class="btn btn-sm btn-outline btn-outline-danger btn-active-light-danger fw-bold delete-currency-btn">
                    <i class="ki-outline ki-cross fs-5 me-1"></i> Delete
                </button>
            </div>
        @endforeach
    </div>

    <!-- Create Currency Button -->
    <button type="button" id="add_currency_btn" class="btn btn-sm btn-outline btn-outline-success btn-active-light-success w-100 fw-bold py-3">
        <i class="ki-outline ki-plus-circle fs-4 me-1"></i> Create
    </button>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- 6. Default currency -->
<div class="mb-7">
    <label for="default_currency" class="form-label fs-7 fw-bold text-gray-900 d-flex align-items-center">
        <i class="ki-outline ki-dollar fs-4 text-gray-600 me-2"></i> Default currency
    </label>
    <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-bold" id="default_currency" name="default_currency" value="{{ $defaultCurrency }}" placeholder="USD" required>
    <div class="text-muted fs-8 mt-1">Default currency code for the payments. Once you start receiving payments, do not change this value.</div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- 7. Enable Discount/Redeemable Codes -->
<div class="mb-7">
    <div class="d-flex align-items-center justify-content-between mb-1">
        <label class="form-check-label fs-7 fw-bold text-gray-900 cursor-pointer d-flex align-items-center" for="codes_is_enabled">
            <i class="ki-outline ki-barcode fs-4 text-gray-600 me-2"></i> Enable Discount/Redeemable Codes
        </label>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="codes_is_enabled" name="codes_is_enabled" {{ $codesIsEnabled ? 'checked' : '' }}>
        </div>
    </div>
    <div class="text-muted fs-8">Enabling the discount codes system will enable users to add a discount code created from the admin panel, before they checkout.</div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- 8. Enable Taxes & Billing system -->
<div class="mb-7">
    <div class="d-flex align-items-center justify-content-between mb-1">
        <label class="form-check-label fs-7 fw-bold text-gray-900 cursor-pointer d-flex align-items-center" for="taxes_and_billing_is_enabled">
            <i class="ki-outline ki-receipt fs-4 text-gray-600 me-2"></i> Enable Taxes & Billing system
        </label>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="taxes_and_billing_is_enabled" name="taxes_and_billing_is_enabled" {{ $taxesAndBillingIsEnabled ? 'checked' : '' }}>
        </div>
    </div>
    <div class="text-muted fs-8">Enabling this feature will require users to fill in their billing info before checking out and you will also be able to create taxes for each created plan.</div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- 9. Enable Invoices System -->
<div class="mb-7">
    <div class="d-flex align-items-center justify-content-between mb-1">
        <label class="form-check-label fs-7 fw-bold text-gray-900 cursor-pointer d-flex align-items-center" for="invoices_is_enabled">
            <i class="ki-outline ki-document fs-4 text-gray-600 me-2"></i> Enable Invoices System
        </label>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="invoices_is_enabled" name="invoices_is_enabled" {{ $invoicesIsEnabled ? 'checked' : '' }}>
        </div>
    </div>
    <div class="text-muted fs-8">This option will determine if users will be able to see invoices for their payments or not.</div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- 10. User plan expiry reminder -->
<div class="mb-7">
    <label for="user_plan_expiry_reminder" class="form-label fs-7 fw-bold text-gray-900 d-flex align-items-center">
        <i class="ki-outline ki-alarm fs-4 text-gray-600 me-2"></i> User plan expiry reminder
    </label>
    <div class="input-group input-group-sm">
        <input type="number" min="0" class="form-control form-control-solid" id="user_plan_expiry_reminder" name="user_plan_expiry_reminder" value="{{ $userPlanExpiryReminder }}">
        <span class="input-group-text bg-light text-muted fw-bold">days</span>
    </div>
    <div class="text-muted fs-8 mt-1">How many days prior should the user get reminded that his plan is going to expire. Set 0 to disable the reminder.</div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- 11. User plan expiry checker -->
<div class="mb-7">
    <div class="d-flex align-items-center justify-content-between mb-1">
        <label class="form-check-label fs-7 fw-bold text-gray-900 cursor-pointer d-flex align-items-center" for="user_plan_expiry_checker_is_enabled">
            <i class="ki-outline ki-calendar-tick fs-4 text-gray-600 me-2"></i> User plan expiry checker
        </label>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="user_plan_expiry_checker_is_enabled" name="user_plan_expiry_checker_is_enabled" {{ $userPlanExpiryCheckerIsEnabled ? 'checked' : '' }}>
        </div>
    </div>
    <div class="text-muted fs-8">Automatically check and expire plans via the Cron job for users who stop paying their plans. If disabled, an expired plan will be reset when the user will log into his account.</div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- 12. Currency exchange API key -->
<div class="mb-5">
    <label for="currency_exchange_api_key" class="form-label fs-7 fw-bold text-gray-900 d-flex align-items-center">
        <i class="ki-outline ki-code fs-4 text-gray-600 me-2"></i> Currency exchange API key
    </label>
    <input type="text" class="form-control form-control-solid form-control-sm font-monospace" id="currency_exchange_api_key" name="currency_exchange_api_key" value="{{ $currencyExchangeApiKey }}" placeholder="API Key">
    <div class="text-muted fs-8 mt-1">This API key is needed only in case you use multiple currencies across your website.</div>
</div>

<!-- Dynamic Currency JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('currencies_container');
    var addBtn = document.getElementById('add_currency_btn');

    // Handle Delete
    container.addEventListener('click', function (e) {
        if (e.target.closest('.delete-currency-btn')) {
            var items = container.querySelectorAll('.currency-item');
            if (items.length <= 1) {
                alert('At least one currency must remain.');
                return;
            }
            e.target.closest('.currency-item').remove();
        }
    });

    // Handle Add
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            var template = `
            <div class="card card-bordered border-gray-300 shadow-none p-5 rounded-3 currency-item">
                <div class="mb-4">
                    <label class="form-label fs-7 fw-bold text-gray-800 d-flex align-items-center">
                        <i class="ki-outline ki-bank fs-5 text-gray-500 me-2"></i> Currency code
                    </label>
                    <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-bold" name="currencies_code[]" value="" placeholder="EUR" required>
                    <div class="text-muted fs-8 mt-1">Currency code for the payments (ex: USD, EUR, IDR, CAD). ISO-4217 standard.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fs-7 fw-bold text-gray-800 d-flex align-items-center">
                        <i class="ki-outline ki-text fs-5 text-gray-500 me-2"></i> Currency symbol
                    </label>
                    <input type="text" class="form-control form-control-solid form-control-sm" name="currencies_symbol[]" value="€" placeholder="€" required>
                </div>

                <div class="mb-5">
                    <label class="form-label fs-7 fw-bold text-gray-800 d-flex align-items-center">
                        <i class="ki-outline ki-wallet fs-5 text-gray-500 me-2"></i> Default processor
                    </label>
                    <select class="form-select form-select-solid form-select-sm" name="currencies_default_processor[]">
                        <option value="paypal">PayPal</option>
                        <option value="stripe">Stripe</option>
                        <option value="midtrans">Midtrans</option>
                        <option value="tripay">Tripay</option>
                        <option value="xendit">Xendit</option>
                        <option value="offline_payment">Offline / Bank Transfer</option>
                        <option value="crypto">Crypto / Coinbase</option>
                    </select>
                    <div class="text-muted fs-8 mt-1">Make sure the payment processor has this particular currency code enabled.</div>
                </div>

                <button type="button" class="btn btn-sm btn-outline btn-outline-danger btn-active-light-danger fw-bold delete-currency-btn">
                    <i class="ki-outline ki-cross fs-5 me-1"></i> Delete
                </button>
            </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
        });
    }
});
</script>
