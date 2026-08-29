@extends('layouts.auth')

@section('title', 'Sign Up')

@section('content')
<form method="POST" action="{{ route('register') }}" class="form w-100" novalidate="novalidate" id="kt_sign_up_form">
    @csrf

    <!-- Heading -->
    <div class="text-center mb-11">
        <h1 class="text-gray-900 fw-bolder mb-3">Sign Up</h1>
        <div class="text-gray-500 fw-semibold fs-6">Create your account to start managing links</div>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3">
            <i class="ki-outline ki-cross-circle fs-2hx text-danger me-4"></i>
            <div class="d-flex flex-column">
                <h5 class="mb-1 text-danger fw-bold">Registration Failed</h5>
                <span class="fs-7 text-gray-800">{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    <!-- Full Name -->
    <div class="fv-row mb-8">
        <label class="form-label fs-6 fw-semibold text-gray-900">Full Name</label>
        <input type="text" placeholder="Your Full Name" name="name" value="{{ old('name') }}" autocomplete="off" class="form-control form-control-solid @error('name') is-invalid @enderror" required autofocus />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="fv-row mb-8">
        <label class="form-label fs-6 fw-semibold text-gray-900">Email Address</label>
        <input type="email" placeholder="name@example.com" name="email" value="{{ old('email') }}" autocomplete="off" class="form-control form-control-solid @error('email') is-invalid @enderror" required />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="fv-row mb-8">
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

    <!-- Confirm Password -->
    <div class="fv-row mb-8">
        <label class="form-label fs-6 fw-semibold text-gray-900">Confirm Password</label>
        <div class="position-relative mb-3">
            <input type="password" placeholder="••••••••" name="password_confirmation" autocomplete="off" class="form-control form-control-solid" id="password_confirmation" required />
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="toggle-password-confirm">
                <i class="ki-outline ki-eye fs-2" id="toggle-password-confirm-icon"></i>
            </span>
        </div>
    </div>

    <!-- Submit button -->
    <div class="d-grid mb-10">
        <button type="submit" class="btn btn-primary">
            <span class="indicator-label">Create Account</span>
        </button>
    </div>

    <!-- Sign in link -->
    <div class="text-gray-500 text-center fw-semibold fs-6">
        Already have an account? 
        <a href="{{ route('login') }}" class="link-primary fw-bold">Sign In</a>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('toggle-password-icon');
        
        if (togglePassword && passwordInput && passwordIcon) {
            togglePassword.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                passwordIcon.className = isPassword ? 'ki-outline ki-eye-slash fs-2' : 'ki-outline ki-eye fs-2';
            });
        }

        const togglePasswordConfirm = document.getElementById('toggle-password-confirm');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const passwordConfirmIcon = document.getElementById('toggle-password-confirm-icon');
        
        if (togglePasswordConfirm && passwordConfirmInput && passwordConfirmIcon) {
            togglePasswordConfirm.addEventListener('click', function() {
                const isPassword = passwordConfirmInput.getAttribute('type') === 'password';
                passwordConfirmInput.setAttribute('type', isPassword ? 'text' : 'password');
                passwordConfirmIcon.className = isPassword ? 'ki-outline ki-eye-slash fs-2' : 'ki-outline ki-eye fs-2';
            });
        }
    });
</script>
@endpush
@endsection
