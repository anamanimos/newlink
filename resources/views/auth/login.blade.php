@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<form method="POST" action="{{ route('login') }}" class="text-start">
    @csrf

    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label small fw-semibold text-secondary">Email address</label>
        <div class="input-group glass-input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px;">
                <span data-duo-icons="user" style="width: 18px; height: 18px; color: var(--text-secondary);"></span>
            </span>
            <input type="email" class="form-control ps-2" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
        <div class="input-group glass-input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px;">
                <!-- Custom Padlock SVG (Duotone) -->
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" opacity="0.25" fill="currentColor" style="stroke: none;"></rect>
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </span>
            <input type="password" class="form-control ps-2 pe-0" id="password" name="password" required placeholder="••••••••">
            <button class="btn text-secondary d-flex align-items-center justify-content-center" type="button" id="toggle-password" style="width: 46px; padding: 0;">
                <!-- Custom Eye SVG (Duotone) -->
                <svg id="toggle-password-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary); transition: all 0.2s ease;">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3" fill="currentColor"></circle>
                </svg>
            </button>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="mb-3 form-check d-flex align-items-center gap-2 ps-0">
        <input type="checkbox" class="form-check-input m-0" id="remember" name="remember" style="width: 18px; height: 18px; cursor: pointer;">
        <label class="form-check-label small text-secondary" for="remember" style="cursor: pointer; user-select: none; line-height: 1;">Remember me</label>
    </div>

    <!-- Submit Button -->
    <div class="d-grid gap-2 mt-4">
        <button type="submit" class="btn btn-primary rounded-3 py-2 fw-semibold shadow-sm d-flex align-items-center justify-content-center">
            <span data-duo-icons="power" class="me-2" style="width: 18px; height: 18px; color: #fff;"></span>Sign In
        </button>
    </div>

    <div class="text-center mt-4">
        <p class="small text-secondary mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Sign Up</a></p>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswordBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const iconSpan = document.getElementById('toggle-password-icon');
        
        if (togglePasswordBtn && passwordInput) {
            togglePasswordBtn.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                // Toggle eye icon styling
                if (iconSpan) {
                    if (isPassword) {
                        iconSpan.style.color = 'var(--primary-color)';
                    } else {
                        iconSpan.style.color = 'var(--text-secondary)';
                    }
                }
            });
        }
    });
</script>
@endsection
