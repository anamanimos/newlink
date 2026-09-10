@php
    $isEnabled = isset($settings['is_enabled']) ? (bool)$settings['is_enabled'] : config('services.sso.enabled', true);
    $baseUrl = $settings['base_url'] ?? config('services.sso.base_url', 'https://app.damaijaya.my.id');
    $clientId = $settings['client_id'] ?? config('services.sso.client_id', '');
    $clientSecret = $settings['client_secret'] ?? config('services.sso.client_secret', '');
    $redirectUri = $settings['redirect_uri'] ?? (config('services.sso.redirect_uri') ?: route('sso.callback'));
    $buttonText = $settings['button_text'] ?? config('services.sso.button_text', 'Masuk dengan Akun Damai Jaya (SSO)');
@endphp

<!-- Section 1: General SSO Configuration -->
<div class="mb-7">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-gray-900 mb-1">
                <i class="ki-outline ki-shield-tick fs-3 text-primary me-2"></i> OpenID Connect (OIDC) Single Sign-On
            </h4>
            <div class="text-muted fs-7">Izinkan pengguna login secara terpusat menggunakan akun ERP Damai Jaya via protokol OpenID Connect.</div>
        </div>
        <span class="badge badge-light-primary fw-bold fs-8 px-3 py-1">OIDC Standard</span>
    </div>

    <!-- Enable SSO Switch -->
    <div class="d-flex align-items-center justify-content-between p-4 bg-light-primary rounded-3 border border-primary border-dashed mb-6">
        <div>
            <label class="form-check-label fs-6 fw-bold text-gray-900 mb-0 cursor-pointer" for="sso_is_enabled">
                Status Integrasi SSO
            </label>
            <div class="text-muted fs-8">Aktifkan tombol login SSO di halaman Sign In platform NewLink.</div>
        </div>
        <div class="form-check form-switch form-check-custom form-check-solid">
            <input type="hidden" name="is_enabled" value="0">
            <input class="form-check-input h-25px w-45px cursor-pointer" type="checkbox" role="switch" id="sso_is_enabled" name="is_enabled" value="1" {{ $isEnabled ? 'checked' : '' }}>
        </div>
    </div>

    <div class="row g-5">
        <!-- SSO Base URL / Issuer -->
        <div class="col-md-6">
            <label class="form-label fs-7 fw-semibold text-gray-900 required d-flex align-items-center">
                <i class="ki-outline ki-global fs-5 text-gray-500 me-2"></i> Base URL / Issuer OIDC
            </label>
            <input type="url" class="form-control form-control-solid form-control-sm font-monospace" name="base_url" value="{{ $baseUrl }}" placeholder="https://app.damaijaya.my.id" required>
            <div class="form-text fs-8 text-muted">URL induk sistem ERP penyedia SSO (misal: <code>https://app.damaijaya.my.id</code>).</div>
        </div>

        <!-- Button Label -->
        <div class="col-md-6">
            <label class="form-label fs-7 fw-semibold text-gray-900 d-flex align-items-center">
                <i class="ki-outline ki-text fs-5 text-gray-500 me-2"></i> Label Tombol Login
            </label>
            <input type="text" class="form-control form-control-solid form-control-sm" name="button_text" value="{{ $buttonText }}" placeholder="Masuk dengan Akun Damai Jaya (SSO)">
            <div class="form-text fs-8 text-muted">Teks yang akan muncul pada tombol login SSO di halaman login.</div>
        </div>

        <!-- Client ID -->
        <div class="col-md-6">
            <label class="form-label fs-7 fw-semibold text-gray-900 required d-flex align-items-center">
                <i class="ki-outline ki-key fs-5 text-gray-500 me-2"></i> Client ID (UUID)
            </label>
            <input type="text" class="form-control form-control-solid form-control-sm font-monospace" name="client_id" value="{{ $clientId }}" placeholder="e.g. 9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d" required>
            <div class="form-text fs-8 text-muted">Client ID yang diperoleh saat mendaftarkan aplikasi NewLink di ERP Damai Jaya.</div>
        </div>

        <!-- Client Secret -->
        <div class="col-md-6">
            <label class="form-label fs-7 fw-semibold text-gray-900 required d-flex align-items-center">
                <i class="ki-outline ki-lock fs-5 text-gray-500 me-2"></i> Client Secret
            </label>
            <div class="position-relative">
                <input type="password" class="form-control form-control-solid form-control-sm font-monospace pe-10" id="sso_client_secret" name="client_secret" value="{{ $clientSecret }}" placeholder="••••••••••••••••••••••••••••••••" required>
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-1 cursor-pointer" onclick="toggleSecretVisibility()">
                    <i class="ki-outline ki-eye fs-4" id="sso_secret_icon"></i>
                </span>
            </div>
            <div class="form-text fs-8 text-muted">Kunci rahasia untuk otentikasi token OIDC (disimpan secara aman).</div>
        </div>

        <!-- Redirect URI / Callback URL -->
        <div class="col-12">
            <label class="form-label fs-7 fw-semibold text-gray-900 d-flex align-items-center">
                <i class="ki-outline ki-fasten fs-5 text-gray-500 me-2"></i> Redirect URI / Callback URL (Daftarkan ke ERP)
            </label>
            <div class="input-group input-group-solid">
                <input type="text" class="form-control form-control-solid form-control-sm font-monospace" id="sso_redirect_uri_input" name="redirect_uri" value="{{ $redirectUri }}" readonly>
                <button class="btn btn-light-primary btn-sm fw-bold" type="button" onclick="copyCallbackUrl()">
                    <i class="ki-outline ki-copy fs-5 me-1"></i> Salin URL
                </button>
            </div>
            <div class="form-text fs-8 text-muted">Masukkan URL Callback ini ke pengaturan OAuth Client di ERP Damai Jaya.</div>
        </div>
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<!-- Section 2: OIDC Standard Endpoints Guide -->
<div class="card bg-light-info border border-info border-dashed p-5 rounded-3 mb-4">
    <h5 class="fw-bold text-gray-900 mb-2">
        <i class="ki-outline ki-information-4 fs-4 text-info me-2"></i> Endpoint Standar OpenID Connect (OIDC) ERP Damai Jaya
    </h5>
    <p class="text-gray-700 fs-8 mb-4">
        Aplikasi NewLink berkomunikasi secara otomatis dengan endpoint standar OIDC di <strong>{{ $baseUrl }}</strong>:
    </p>

    <div class="table-responsive bg-white rounded-2 border">
        <table class="table table-sm table-row-dashed fs-8 gy-2 px-3 mb-0 align-middle">
            <thead class="bg-light fw-bold text-gray-700">
                <tr>
                    <th class="ps-3 py-2">Endpoint</th>
                    <th class="py-2">Metode</th>
                    <th class="pe-3 py-2">Fungsi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                <tr>
                    <td class="ps-3 font-monospace text-primary fw-semibold">/oauth/authorize</td>
                    <td><span class="badge badge-light-primary fs-9">GET</span></td>
                    <td class="pe-3">Mengarahkan pengguna ke halaman login ERP & meminta izin otorisasi.</td>
                </tr>
                <tr>
                    <td class="ps-3 font-monospace text-primary fw-semibold">/oauth/token</td>
                    <td><span class="badge badge-light-success fs-9">POST</span></td>
                    <td class="pe-3">Menukar authorization code & verifier PKCE menjadi Access Token dan ID Token (JWT).</td>
                </tr>
                <tr>
                    <td class="ps-3 font-monospace text-primary fw-semibold">/oauth/userinfo</td>
                    <td><span class="badge badge-light-info fs-9">GET</span></td>
                    <td class="pe-3">Mengambil profil dasar pengguna (name, email, sub) menggunakan Bearer token.</td>
                </tr>
                <tr>
                    <td class="ps-3 font-monospace text-primary fw-semibold">/oauth/jwks</td>
                    <td><span class="badge badge-light-secondary fs-9">GET</span></td>
                    <td class="pe-3">JSON Web Key Set berisi public key untuk verifikasi signature JWT ID Token.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function copyCallbackUrl() {
    var input = document.getElementById('sso_redirect_uri_input');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function() {
        if (window.Swal) {
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Redirect URI berhasil disalin ke clipboard.',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            alert('Redirect URI berhasil disalin!');
        }
    });
}

function toggleSecretVisibility() {
    var input = document.getElementById('sso_client_secret');
    var icon = document.getElementById('sso_secret_icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ki-outline ki-eye-slash fs-4 text-primary';
    } else {
        input.type = 'password';
        icon.className = 'ki-outline ki-eye fs-4';
    }
}
</script>