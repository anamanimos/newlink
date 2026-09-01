@extends('layouts.app')

@section('title', 'Settings - ' . ucfirst(str_replace('-', ' ', $tab == 'links' ? 'Links system' : ($tab == 'cookie-consent' ? 'Cookie consent' : ($tab == 'custom' ? 'Custom JS / CSS' : $tab)))))

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0 text-capitalize">
            <i class="ki-outline ki-setting-2 fs-2 text-primary me-2"></i>
            Settings: {{ str_replace('-', ' ', $tab == 'links' ? 'links system' : $tab) }}
        </h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">System Config</span>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3 shadow-sm">
        <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
        <div class="d-flex flex-column">
            <span class="fs-7 text-gray-900 fw-semibold">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3 shadow-sm">
        <i class="ki-outline ki-cross-circle fs-2hx text-danger me-4"></i>
        <div class="d-flex flex-column">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li class="fs-7 text-gray-900 fw-semibold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="row g-6 g-xl-9">
    <!-- Left Navigation Column -->
    <div class="col-12 col-lg-4 col-xl-3">
        <div class="card card-flush shadow-sm border-0 mb-6 position-sticky" style="top: 100px; z-index: 95; max-height: calc(100vh - 120px); overflow-y: auto;">
            <div class="card-body p-4">
                <div class="menu menu-column menu-rounded menu-gray-700 menu-state-bg-light-primary menu-state-title-primary fw-semibold fs-7 gap-1">
                    @php
                        $settingNavs = [
                            ['tab' => 'main', 'label' => 'Main', 'icon' => 'ki-home'],
                            ['tab' => 'users', 'label' => 'Users', 'icon' => 'ki-profile-user'],
                            ['tab' => 'content', 'label' => 'Content', 'icon' => 'ki-element-11'],
                            ['tab' => 'links', 'label' => 'Links system', 'icon' => 'ki-disconnect'],
                            ['tab' => 'tools', 'label' => 'Tools', 'icon' => 'ki-wrench'],
                            ['tab' => 'codes', 'label' => 'Codes', 'icon' => 'ki-barcode'],
                            ['tab' => 'payment', 'label' => 'Payment', 'icon' => 'ki-credit-cart'],
                            ['tab' => 'business', 'label' => 'Business details', 'icon' => 'ki-briefcase'],
                            ['tab' => 'processors', 'label' => 'Payment processors', 'icon' => 'ki-wallet'],
                            ['tab' => 'affiliate', 'label' => 'Affiliate', 'icon' => 'ki-people'],
                            ['tab' => 'captcha', 'label' => 'Captcha', 'icon' => 'ki-security-user'],
                            ['tab' => 'social-logins', 'label' => 'Social logins', 'icon' => 'ki-user-tick'],
                            ['tab' => 'ads', 'label' => 'Ads', 'icon' => 'ki-bill'],
                            ['tab' => 'cookie-consent', 'label' => 'Cookie consent', 'icon' => 'ki-shield-tick'],
                            ['tab' => 'socials', 'label' => 'Socials', 'icon' => 'ki-share'],
                            ['tab' => 'smtp', 'label' => 'SMTP (Email)', 'icon' => 'ki-sms'],
                            ['tab' => 'theme', 'label' => 'Theme', 'icon' => 'ki-color-swatch'],
                            ['tab' => 'custom', 'label' => 'Custom JS / CSS', 'icon' => 'ki-code'],
                            ['tab' => 'announcements', 'label' => 'Announcements', 'icon' => 'ki-notification-bing'],
                            ['tab' => 'webhooks', 'label' => 'Webhooks', 'icon' => 'ki-directbox-default'],
                            ['tab' => 'offload', 'label' => 'Offload & CDN', 'icon' => 'ki-cloud'],
                            ['tab' => 'pwa', 'label' => 'PWA', 'icon' => 'ki-phone'],
                            ['tab' => 'sso', 'label' => 'SSO', 'icon' => 'ki-key'],
                            ['tab' => 'cron', 'label' => 'Cron', 'icon' => 'ki-arrows-circle'],
                            ['tab' => 'health', 'label' => 'Health', 'icon' => 'ki-heart'],
                            ['tab' => 'cache', 'label' => 'Cache', 'icon' => 'ki-data'],
                            ['tab' => 'license', 'label' => 'License', 'icon' => 'ki-award'],
                            ['tab' => 'support', 'label' => 'Support', 'icon' => 'ki-support']
                        ];
                    @endphp

                    @foreach($settingNavs as $nav)
                        <div class="menu-item">
                            <a href="{{ route('admin.settings', $nav['tab']) }}" class="menu-link {{ $tab == $nav['tab'] ? 'active' : '' }} py-2 px-3">
                                <i class="ki-outline {{ $nav['icon'] }} fs-4 me-3"></i> {{ $nav['label'] }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right Form Column -->
    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-flush shadow-sm border-0 mb-6">
            <div class="card-body p-6 p-lg-8">
                <form method="POST" action="{{ route('admin.settings.update', $tab) }}" enctype="multipart/form-data">
                    @csrf
                    
                    @if(view()->exists('admin.modules.settings_tabs.' . $tab))
                        @include('admin.modules.settings_tabs.' . $tab)
                    @else
                        @include('admin.modules.settings_tabs.fallback')
                    @endif

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-8">
                        <button type="submit" class="btn btn-primary fw-bold px-8">
                            <i class="ki-outline ki-check fs-4 me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
