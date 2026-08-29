@extends('layouts.app')

@section('title', 'Settings - ' . ucfirst($tab == 'links' ? 'Links system' : ($tab == 'cookie-consent' ? 'Cookie consent' : ($tab == 'custom' ? 'Custom JS / CSS' : $tab))))

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0 text-capitalize">
            Settings: {{ str_replace('-', ' ', $tab == 'links' ? 'links system' : $tab) }}
        </h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">System Config</span>
    </div>
</div>

<div class="row g-6 g-xl-9">
    <!-- Left Navigation Column -->
    <div class="col-12 col-lg-4 col-xl-3">
        <div class="card card-flush shadow-sm border-0 mb-6 position-sticky" style="top: 115px; z-index: 95;">
            <div class="card-body p-4">
                <div class="menu menu-column menu-rounded menu-gray-700 menu-state-bg-light-primary menu-state-title-primary fw-semibold fs-7 gap-1">
                    <div class="menu-item">
                        <a href="{{ route('admin.settings', 'main') }}" class="menu-link {{ $tab == 'main' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-setting-2 fs-4 me-3"></i> Main
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.settings', 'users') }}" class="menu-link {{ $tab == 'users' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-profile-user fs-4 me-3"></i> Users
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.settings', 'links') }}" class="menu-link {{ $tab == 'links' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-disconnect fs-4 me-3"></i> Links System
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.settings', 'payment') }}" class="menu-link {{ $tab == 'payment' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-credit-cart fs-4 me-3"></i> Payment
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.settings', 'smtp') }}" class="menu-link {{ $tab == 'smtp' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-sms fs-4 me-3"></i> SMTP (Email)
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.settings', 'theme') }}" class="menu-link {{ $tab == 'theme' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-color-swatch fs-4 me-3"></i> Theme & Branding
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.settings', 'custom') }}" class="menu-link {{ $tab == 'custom' ? 'active' : '' }} py-2.5 px-3">
                            <i class="ki-outline ki-code fs-4 me-3"></i> Custom JS / CSS
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Form Column -->
    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-flush shadow-sm border-0 mb-6">
            <div class="card-body p-6 p-lg-8">
                <form method="POST" action="#" enctype="multipart/form-data">
                    @csrf
                    
                    @if(view()->exists('admin.modules.settings_tabs.' . $tab))
                        @include('admin.modules.settings_tabs.' . $tab)
                    @else
                        @include('admin.modules.settings_tabs.fallback')
                    @endif

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-8">
                        <button type="submit" class="btn btn-primary fw-bold px-6">
                            Update {{ str_replace('-', ' ', $tab == 'links' ? 'links system' : $tab) }} Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
