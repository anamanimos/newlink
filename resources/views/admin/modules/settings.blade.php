@extends('layouts.app')

@section('title', 'Settings - ' . ucfirst($tab == 'links' ? 'Links system' : ($tab == 'cookie-consent' ? 'Cookie consent' : ($tab == 'custom' ? 'Custom JS / CSS' : $tab))))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold tracking-tight mb-1 d-flex align-items-center text-capitalize">
            <span data-duo-icons="settings" class="me-2 text-primary" style="width: 28px; height: 28px;"></span>
            Settings - {{ str_replace('-', ' ', $tab == 'links' ? 'links system' : $tab) }}
        </h2>
        <p class="text-secondary small">System configurations, branding, logos, and regional settings.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Navigation Column -->
    <div class="col-12 col-lg-3">
        <div class="glass-card p-3 flex-column d-flex gap-1">
            <a href="{{ route('admin.settings', 'main') }}" class="sidebar-link {{ $tab == 'main' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="settings" class="me-2.5" style="width: 18px; height: 18px;"></span>Main
            </a>
            <a href="{{ route('admin.settings', 'users') }}" class="sidebar-link {{ $tab == 'users' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="user" class="me-2.5" style="width: 18px; height: 18px;"></span>Users
            </a>
            <a href="{{ route('admin.settings', 'content') }}" class="sidebar-link {{ $tab == 'content' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="file" class="me-2.5" style="width: 18px; height: 18px;"></span>Content
            </a>
            <a href="{{ route('admin.settings', 'links') }}" class="sidebar-link {{ $tab == 'links' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="world" class="me-2.5" style="width: 18px; height: 18px;"></span>Links system
            </a>
            <a href="{{ route('admin.settings', 'tools') }}" class="sidebar-link {{ $tab == 'tools' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="target" class="me-2.5" style="width: 18px; height: 18px;"></span>Tools
            </a>
            <a href="{{ route('admin.settings', 'codes') }}" class="sidebar-link {{ $tab == 'codes' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="toggle" class="me-2.5" style="width: 18px; height: 18px;"></span>Codes
            </a>
            <a href="{{ route('admin.settings', 'payment') }}" class="sidebar-link {{ $tab == 'payment' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="credit-card" class="me-2.5" style="width: 18px; height: 18px;"></span>Payment
            </a>
            <a href="{{ route('admin.settings', 'business') }}" class="sidebar-link {{ $tab == 'business' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="id-card" class="me-2.5" style="width: 18px; height: 18px;"></span>Business details
            </a>
            <a href="{{ route('admin.settings', 'affiliate') }}" class="sidebar-link {{ $tab == 'affiliate' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="user-card" class="me-2.5" style="width: 18px; height: 18px;"></span>Affiliate
            </a>
            <a href="{{ route('admin.settings', 'captcha') }}" class="sidebar-link {{ $tab == 'captcha' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="alert-triangle" class="me-2.5" style="width: 18px; height: 18px;"></span>Captcha
            </a>
            <a href="{{ route('admin.settings', 'ads') }}" class="sidebar-link {{ $tab == 'ads' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="discount" class="me-2.5" style="width: 18px; height: 18px;"></span>Ads
            </a>
            <a href="{{ route('admin.settings', 'cookie-consent') }}" class="sidebar-link {{ $tab == 'cookie-consent' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="info" class="me-2.5" style="width: 18px; height: 18px;"></span>Cookie consent
            </a>
            <a href="{{ route('admin.settings', 'socials') }}" class="sidebar-link {{ $tab == 'socials' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="message" class="me-2.5" style="width: 18px; height: 18px;"></span>Socials
            </a>
            <a href="{{ route('admin.settings', 'smtp') }}" class="sidebar-link {{ $tab == 'smtp' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="message-3" class="me-2.5" style="width: 18px; height: 18px;"></span>SMTP (Email)
            </a>
            <a href="{{ route('admin.settings', 'theme') }}" class="sidebar-link {{ $tab == 'theme' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="palette" class="me-2.5" style="width: 18px; height: 18px;"></span>Theme
            </a>
            <a href="{{ route('admin.settings', 'custom') }}" class="sidebar-link {{ $tab == 'custom' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="app" class="me-2.5" style="width: 18px; height: 18px;"></span>Custom JS / CSS
            </a>
            <a href="{{ route('admin.settings', 'email-notifications') }}" class="sidebar-link {{ $tab == 'email-notifications' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="message-3" class="me-2.5" style="width: 18px; height: 18px;"></span>Email Notifications
            </a>
            <a href="{{ route('admin.settings', 'push-notifications') }}" class="sidebar-link {{ $tab == 'push-notifications' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="message" class="me-2.5" style="width: 18px; height: 18px;"></span>Push Notifications
            </a>
            <a href="{{ route('admin.settings', 'webhooks') }}" class="sidebar-link {{ $tab == 'webhooks' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="power" class="me-2.5" style="width: 18px; height: 18px;"></span>Webhooks
            </a>
            <a href="{{ route('admin.settings', 'offload') }}" class="sidebar-link {{ $tab == 'offload' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="world" class="me-2.5" style="width: 18px; height: 18px;"></span>Offload & CDN
            </a>
            <a href="{{ route('admin.settings', 'pwa') }}" class="sidebar-link {{ $tab == 'pwa' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="compass" class="me-2.5" style="width: 18px; height: 18px;"></span>PWA
            </a>
            <a href="{{ route('admin.settings', 'sso') }}" class="sidebar-link {{ $tab == 'sso' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="user" class="me-2.5" style="width: 18px; height: 18px;"></span>SSO
            </a>
            <a href="{{ route('admin.settings', 'cron') }}" class="sidebar-link {{ $tab == 'cron' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="toggle" class="me-2.5" style="width: 18px; height: 18px;"></span>Cron
            </a>
            <a href="{{ route('admin.settings', 'health') }}" class="sidebar-link {{ $tab == 'health' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="alert-triangle" class="me-2.5" style="width: 18px; height: 18px;"></span>Health
            </a>
            <a href="{{ route('admin.settings', 'cache') }}" class="sidebar-link {{ $tab == 'cache' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="target" class="me-2.5" style="width: 18px; height: 18px;"></span>Cache
            </a>
            <a href="{{ route('admin.settings', 'license') }}" class="sidebar-link {{ $tab == 'license' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="id-card" class="me-2.5" style="width: 18px; height: 18px;"></span>License
            </a>
            <a href="{{ route('admin.settings', 'support') }}" class="sidebar-link {{ $tab == 'support' ? 'active' : '' }} m-0 py-2.5 px-3 d-flex align-items-center rounded-3">
                <span data-duo-icons="info" class="me-2.5" style="width: 18px; height: 18px;"></span>Support
            </a>
        </div>
    </div>

    <!-- Right Form Column -->
    <div class="col-12 col-lg-9">
        <div class="glass-card p-4">
            <form method="POST" action="#" enctype="multipart/form-data">
                @csrf
                
                @if(view()->exists('admin.modules.settings_tabs.' . $tab))
                    @include('admin.modules.settings_tabs.' . $tab)
                @else
                    @include('admin.modules.settings_tabs.fallback')
                @endif

                <!-- Submit Button -->
                <div class="d-grid gap-2 mt-5">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold">
                        Update {{ str_replace('-', ' ', $tab == 'links' ? 'links system' : $tab) }} Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
