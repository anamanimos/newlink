@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
<form method="POST" action="{{ route('register') }}" class="text-start" id="register-form">
    @csrf

    <!-- Indikator Langkah (Progress Step) -->
    <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-circle d-flex align-items-center justify-content-center" id="badge-step-1" style="width: 24px; height: 24px; background: var(--primary-color); color: white; font-size: 0.75rem; transition: all 0.3s ease;">1</span>
            <span class="small fw-semibold" id="label-step-1" style="color: var(--text-primary); transition: all 0.3s ease;">Profil</span>
        </div>
        <div style="width: 30px; height: 2px; background: var(--glass-border);"></div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-circle d-flex align-items-center justify-content-center" id="badge-step-2" style="width: 24px; height: 24px; background: rgba(255,255,255,0.1); color: var(--text-secondary); font-size: 0.75rem; border: 1px solid var(--glass-border); transition: all 0.3s ease;">2</span>
            <span class="small text-secondary fw-semibold" id="label-step-2" style="transition: all 0.3s ease;">Keamanan</span>
        </div>
    </div>

    <!-- LANGKAH 1: PROFIL -->
    <div id="wrapper-step-1">
        <div class="text-center mb-4">
            <p class="text-secondary small">Halo! Mari buat akun biolink pertamamu. Masukkan nama dan email untuk mulai menjelajah.</p>
        </div>

        <!-- Nama Lengkap -->
        <div class="mb-3">
            <label for="name" class="form-label small fw-semibold text-secondary">Nama lengkap</label>
            <div class="input-group glass-input-group">
                <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px;">
                    <span data-duo-icons="user" style="width: 18px; height: 18px; color: var(--text-secondary);"></span>
                </span>
                <input type="text" class="form-control ps-2" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama Lengkap Anda">
            </div>
            @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-secondary">Email address</label>
            <div class="input-group glass-input-group">
                <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px;">
                    <span data-duo-icons="message" style="width: 18px; height: 18px; color: var(--text-secondary);"></span>
                </span>
                <input type="email" class="form-control ps-2" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
            </div>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="button" class="btn btn-primary rounded-3 py-2 fw-semibold shadow-sm d-flex align-items-center justify-content-center" id="btn-next-step">
                Lanjut ke Keamanan &rarr;
            </button>
        </div>
    </div>

    <!-- LANGKAH 2: KEAMANAN -->
    <div id="wrapper-step-2" class="d-none">
        <div class="text-center mb-4">
            <p class="text-secondary small">Satu langkah lagi! Buat kata sandi yang aman untuk melindungi akun biolink milikmu.</p>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
            <div class="input-group glass-input-group">
                <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px;">
                    <!-- Custom Padlock SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <input type="password" class="form-control ps-2 pe-0" id="password" name="password" required placeholder="••••••••">
                <button class="btn text-secondary d-flex align-items-center justify-content-center" type="button" id="toggle-password" style="width: 46px; padding: 0;">
                    <!-- Custom Eye SVG -->
                    <svg id="toggle-password-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary); transition: all 0.2s ease;">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Ulangi password</label>
            <div class="input-group glass-input-group">
                <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px;">
                    <!-- Custom Padlock SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <input type="password" class="form-control ps-2 pe-0" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                <button class="btn text-secondary d-flex align-items-center justify-content-center" type="button" id="toggle-password-confirm" style="width: 46px; padding: 0;">
                    <!-- Custom Eye SVG -->
                    <svg id="toggle-password-confirm-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary); transition: all 0.2s ease;">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary rounded-3 py-2 fw-semibold shadow-sm d-flex align-items-center justify-content-center">
                Daftar Akun Baru &checkmark;
            </button>
        </div>

        <div class="text-center mt-3">
            <a href="#" id="btn-prev-step" class="small text-secondary text-decoration-none">&larr; Kembali ke Data Diri</a>
        </div>
    </div>

    <div class="text-center mt-4">
        <p class="small text-secondary mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Sign In</a></p>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const step1 = document.getElementById('wrapper-step-1');
        const step2 = document.getElementById('wrapper-step-2');
        const btnNext = document.getElementById('btn-next-step');
        const btnPrev = document.getElementById('btn-prev-step');
        
        const badge1 = document.getElementById('badge-step-1');
        const label1 = document.getElementById('label-step-1');
        const badge2 = document.getElementById('badge-step-2');
        const label2 = document.getElementById('label-step-2');
        
        const inputName = document.getElementById('name');
        const inputEmail = document.getElementById('email');

        // Navigasi Langkah
        if (btnNext && step1 && step2) {
            btnNext.addEventListener('click', function() {
                // Validasi data langkah 1
                if (!inputName.reportValidity() || !inputEmail.reportValidity()) {
                    return;
                }
                
                // Pindah ke langkah 2
                step1.classList.add('d-none');
                step2.classList.remove('d-none');
                
                // Update Badge UI
                badge1.style.background = 'rgba(255,255,255,0.1)';
                badge1.style.color = 'var(--text-secondary)';
                badge1.style.border = '1px solid var(--glass-border)';
                label1.classList.add('text-secondary');
                
                badge2.style.background = 'var(--primary-color)';
                badge2.style.color = '#fff';
                badge2.style.border = 'none';
                label2.classList.remove('text-secondary');
                label2.style.color = 'var(--text-primary)';
            });
        }
        
        if (btnPrev && step1 && step2) {
            btnPrev.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Kembali ke langkah 1
                step2.classList.add('d-none');
                step1.classList.remove('d-none');
                
                // Restore Badge UI
                badge1.style.background = 'var(--primary-color)';
                badge1.style.color = '#fff';
                badge1.style.border = 'none';
                label1.classList.remove('text-secondary');
                label1.style.color = 'var(--text-primary)';
                
                badge2.style.background = 'rgba(255,255,255,0.1)';
                badge2.style.color = 'var(--text-secondary)';
                badge2.style.border = '1px solid var(--glass-border)';
                label2.classList.add('text-secondary');
            });
        }

        // Toggle Password Visibility
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('toggle-password-icon');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                if (passwordIcon) {
                    passwordIcon.style.color = isPassword ? 'var(--primary-color)' : 'var(--text-secondary)';
                }
            });
        }

        const togglePasswordConfirm = document.getElementById('toggle-password-confirm');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const passwordConfirmIcon = document.getElementById('toggle-password-confirm-icon');
        
        if (togglePasswordConfirm && passwordConfirmInput) {
            togglePasswordConfirm.addEventListener('click', function() {
                const isPassword = passwordConfirmInput.getAttribute('type') === 'password';
                passwordConfirmInput.setAttribute('type', isPassword ? 'text' : 'password');
                if (passwordConfirmIcon) {
                    passwordConfirmIcon.style.color = isPassword ? 'var(--primary-color)' : 'var(--text-secondary)';
                }
            });
        }
    });
</script>
@endsection
