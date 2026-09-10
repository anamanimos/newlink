@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<form method="POST" action="{{ route('login') }}" class="form w-100" novalidate="novalidate">
    @csrf

    <!-- Heading -->
    <div class="text-center mb-11">
        <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
        <div class="text-gray-500 fw-semibold fs-6">Enter your credentials to access your account</div>
    </div>

    <!-- Flash Alerts -->
    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3">
            <i class="ki-outline ki-cross-circle fs-2hx text-danger me-4"></i>
            <div class="d-flex flex-column">
                <h5 class="mb-1 text-danger fw-bold">Login Gagal</h5>
                <span class="fs-7 text-gray-800">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3">
            <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
            <div class="d-flex flex-column">
                <h5 class="mb-1 text-success fw-bold">Berhasil</h5>
                <span class="fs-7 text-gray-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3">
            <i class="ki-outline ki-cross-circle fs-2hx text-danger me-4"></i>
            <div class="d-flex flex-column">
                <h5 class="mb-1 text-danger fw-bold">Login Failed</h5>
                <span class="fs-7 text-gray-800">{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    @php
        $ssoConfig = \App\Http\Controllers\Auth\SsoController::getConfig();
    @endphp

    <!-- SSO Login Button -->
    @if($ssoConfig['enabled'])
        <div class="mb-8">
            <a href="{{ route('sso.redirect') }}" class="btn btn-flex btn-light-primary border border-primary border-opacity-25 w-100 py-3 d-flex align-items-center justify-content-center shadow-xs text-hover-primary text-decoration-none">
                <i class="ki-outline ki-shield-tick fs-2 text-primary me-2"></i>
                <span class="fw-bold fs-6 text-gray-900">{{ $ssoConfig['button_text'] }}</span>
            </a>
        </div>

        <div class="separator separator-content my-8">
            <span class="w-125px text-gray-500 fw-semibold fs-7">Atau dengan Email</span>
        </div>
    @endif

    <!-- Email -->
    <div class="fv-row mb-8">
        <label class="form-label fs-6 fw-semibold text-gray-900">Email Address</label>
        <input type="email" placeholder="name@example.com" name="email" value="{{ old('email') }}" autocomplete="off" class="form-control form-control-solid @error('email') is-invalid @enderror" required autofocus />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="fv-row mb-3">
        <label class="form-label fs-6 fw-semibold text-gray-900">Password</label>
        <div class="position-relative mb-3">
            <input type="password" placeholder="••••••••" name="password" autocomplete="off" class="form-control form-control-solid @error('password') is-invalid @enderror" id="password" required />
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="toggle-password">
                <i class="ki-outline ki-eye fs-2" id="toggle-password-icon"></i>
            </span>
        </div>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Wrapper -->
    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
        <div>
            <label class="form-check form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                <span class="form-check-label text-gray-700 fs-7">Remember me</span>
            </label>
        </div>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="link-primary fs-7">Forgot Password?</a>
        @endif
    </div>

    <!-- Submit button -->
    <div class="d-grid mb-10">
        <button type="submit" class="btn btn-primary">
            <span class="indicator-label">Sign In</span>
        </button>
    </div>

    <!-- Sign up link -->
    @if (Route::has('register'))
        <div class="text-gray-500 text-center fw-semibold fs-6">
            Not a member yet? 
            <a href="{{ route('register') }}" class="link-primary fw-bold">Sign Up</a>
        </div>
    @endif
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswordBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggle-password-icon');
        
        if (togglePasswordBtn && passwordInput && icon) {
            togglePasswordBtn.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                icon.className = isPassword ? 'ki-outline ki-eye-slash fs-2' : 'ki-outline ki-eye fs-2';
            });
        }
    });
</script>
@endpush
@endsection
