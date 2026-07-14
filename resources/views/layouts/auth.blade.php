<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Welcome') | {{ config('app.name', 'NewLink') }}</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            padding: 40px 12px;
        }
    </style>
</head>
<body>
    <!-- Theme Switcher Floating -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <button type="button" id="theme-switcher" class="theme-toggle-btn shadow-sm" aria-label="Toggle Theme">
            <!-- Icon is dynamically inserted by JS -->
        </button>
    </div>

    <div class="auth-container">
        <!-- Radial background accents matching mint green theme -->
        <div class="position-absolute top-25 start-25 translate-middle w-50 h-50 rounded-circle opacity-15 filter-blur" style="background-color: var(--primary-color); filter: blur(100px); pointer-events: none; z-index: -1;"></div>
        <div class="position-absolute bottom-25 end-25 translate-middle w-50 h-50 rounded-circle opacity-10 filter-blur" style="background-color: var(--primary-color); filter: blur(100px); pointer-events: none; z-index: -1;"></div>

        <div class="auth-card">
            <div class="mb-4 text-center">
                <h2 class="fw-bold tracking-tight mb-1" style="color: var(--text-primary); letter-spacing: -0.5px;">{{ config('app.name', 'NamsLink') }}</h2>
                <p class="text-muted small">Modern Link-in-Bio & Shortener</p>
            </div>

            @yield('content')

            <div class="mt-4 pt-3 border-top d-flex flex-column align-items-center gap-1" style="border-top-color: var(--glass-border) !important;">
                <span class="text-muted" style="font-size: 0.675rem;">Crafted with love by</span>
                <a href="https://artspaceproduction.my.id" target="_blank" class="d-block mt-1">
                    <img src="/logo_1.png" alt="Artspace Production" class="theme-logo-light" style="height: 32px; width: auto;">
                    <img src="/logo_2.png" alt="Artspace Production" class="theme-logo-dark" style="height: 32px; width: auto;">
                </a>
            </div>
        </div>
    </div>
</body>
</html>
