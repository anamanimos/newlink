<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'NewLink') }}</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

    <!-- Google Fonts Inter -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

    <!-- Metronic Global Stylesheets Bundle -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    @stack('styles')
    @yield('styles')
</head>

<body id="kt_app_body" 
      data-kt-app-header-fixed="true" 
      data-kt-app-header-fixed-mobile="true" 
      data-kt-app-sidebar-enabled="true" 
      data-kt-app-sidebar-fixed="true" 
      data-kt-app-sidebar-hoverable="true" 
      data-kt-app-sidebar-push-toolbar="true" 
      data-kt-app-sidebar-push-footer="true" 
      class="app-default">

    <!-- Theme mode setup on page load -->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <!-- App Root -->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!-- App Page -->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            
            <!-- App Header -->
            <div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="app-header-logo d-flex align-items-center ps-lg-10" id="kt_app_header_logo">
                        <!-- Sidebar toggle desktop -->
                        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-sm btn-icon bg-body btn-color-gray-500 btn-active-color-primary w-30px h-30px ms-n2 me-4 d-none d-lg-flex" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
                            <i class="ki-outline ki-abstract-14 fs-3 mt-1"></i>
                        </div>
                        <!-- Sidebar toggle mobile -->
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none" id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-outline ki-abstract-14 fs-2"></i>
                        </div>
                        <!-- Logo -->
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                            <span class="fs-3 fw-bolder text-gray-900 text-hover-primary" style="letter-spacing: -0.5px;">
                                <i class="ki-outline ki-fasten text-primary fs-2x me-1"></i>{{ config('app.name', 'NewLink') }}
                            </span>
                        </a>
                    </div>

                    <!-- Header Navbar -->
                    <div class="app-navbar flex-grow-1 justify-content-end pe-lg-10" id="kt_app_header_navbar">
                        
                        <!-- Quick View / External Page Button -->
                        @yield('header_actions')

                        <!-- Theme Switcher -->
                        <div class="app-navbar-item ms-2 ms-lg-4">
                            <a href="#" class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                            </a>
                            <!-- Theme Menu -->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                        <span class="menu-icon" data-kt-element="icon">
                                            <i class="ki-outline ki-night-day fs-2"></i>
                                        </span>
                                        <span class="menu-title">Light</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                        <span class="menu-icon" data-kt-element="icon">
                                            <i class="ki-outline ki-moon fs-2"></i>
                                        </span>
                                        <span class="menu-title">Dark</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3 my-0">
                                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                        <span class="menu-icon" data-kt-element="icon">
                                            <i class="ki-outline ki-screen fs-2"></i>
                                        </span>
                                        <span class="menu-title">System</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Menu -->
                        <div class="app-navbar-item ms-2 ms-lg-4" id="kt_header_user_menu_toggle">
                            <div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                <div class="symbol-label fs-5 fw-bold bg-light-primary text-primary">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                                </div>
                            </div>

                            <!-- User Account Menu Dropdown -->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content d-flex align-items-center px-3">
                                        <div class="symbol symbol-50px me-4">
                                            <div class="symbol-label fs-3 fw-bold bg-light-primary text-primary">
                                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold d-flex align-items-center fs-5 text-gray-900">
                                                {{ Auth::user()->name ?? 'Account' }}
                                                @if(Auth::check() && Auth::user()->type === 1)
                                                    <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Admin</span>
                                                @endif
                                            </div>
                                            <span class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="separator my-2"></div>

                                <div class="menu-item px-5">
                                    <a href="{{ route('profile.edit') }}" class="menu-link px-5">
                                        <i class="ki-outline ki-setting-2 fs-5 me-2 text-muted"></i>Account Settings
                                    </a>
                                </div>

                                @if(Auth::check() && Auth::user()->type === 1)
                                    <div class="menu-item px-5">
                                        @if(request()->is('admin*'))
                                            <a href="{{ route('dashboard') }}" class="menu-link px-5">
                                                <i class="ki-outline ki-tablet fs-5 me-2 text-muted"></i>Go to User Panel
                                            </a>
                                        @else
                                            <a href="{{ route('admin.dashboard') }}" class="menu-link px-5 text-primary">
                                                <i class="ki-outline ki-shield-tick fs-5 me-2 text-primary"></i>Go to Admin Panel
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <div class="separator my-2"></div>

                                <div class="menu-item px-5">
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link px-5 text-danger">
                                            <i class="ki-outline ki-exit-right fs-5 me-2 text-danger"></i>Sign Out
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Header Separator -->
                <div class="app-header-separator"></div>
            </div>

            <!-- App Wrapper -->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                
                <!-- App Sidebar -->
                <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
                    
                    <div class="app-sidebar-wrapper">
                        <div id="kt_app_sidebar_wrapper" class="hover-scroll-y my-5 my-lg-2 mx-4" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper" data-kt-scroll-offset="5px">
                            
                            <!-- Sidebar Menu -->
                            <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false" class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
                                
                                @if(request()->is('admin*'))
                                    <!-- ================= ADMIN NAVIGATION ================= -->
                                    <div class="menu-item pt-2 pb-2">
                                        <div class="menu-content pb-2">
                                            <span class="menu-section text-muted text-uppercase fs-8 ls-1">Admin Management</span>
                                        </div>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-element-11 fs-2"></i></span>
                                            <span class="menu-title">Admin Dashboard</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-people fs-2"></i></span>
                                            <span class="menu-title">Users</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.domains*') ? 'active' : '' }}" href="{{ route('admin.domains') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-geolocation fs-2"></i></span>
                                            <span class="menu-title">Domains</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.links*') ? 'active' : '' }}" href="{{ route('admin.links') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-fasten fs-2"></i></span>
                                            <span class="menu-title">Links</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.plans*') ? 'active' : '' }}" href="{{ route('admin.plans') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-crown fs-2"></i></span>
                                            <span class="menu-title">Plans</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.statistics*') ? 'active' : '' }}" href="{{ route('admin.statistics') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-chart-line-star fs-2"></i></span>
                                            <span class="menu-title">Statistics</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-setting-2 fs-2"></i></span>
                                            <span class="menu-title">Settings</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('tools.*') ? 'active' : '' }}" href="{{ route('tools.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-wrench fs-2"></i></span>
                                            <span class="menu-title">Online Tools</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('api-docs') }}" target="_blank">
                                            <span class="menu-icon"><i class="ki-outline ki-code fs-2"></i></span>
                                            <span class="menu-title">API Docs</span>
                                        </a>
                                    </div>
                                @else
                                    <!-- ================= USER NAVIGATION ================= -->
                                    <div class="menu-item pt-2 pb-2">
                                        <div class="menu-content pb-2">
                                            <span class="menu-section text-muted text-uppercase fs-8 ls-1">Main Menu</span>
                                        </div>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ (request()->routeIs('dashboard') && !request()->has('type')) ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-home-2 fs-2"></i></span>
                                            <span class="menu-title">Dashboard</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ (request()->routeIs('biolinks.index') || (request()->routeIs('dashboard') && request('type') == 'biolink')) ? 'active' : '' }}" href="{{ route('biolinks.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-abstract-26 fs-2"></i></span>
                                            <span class="menu-title">Biolink Pages</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ (request()->routeIs('links.index') || (request()->routeIs('dashboard') && request('type') == 'link')) ? 'active' : '' }}" href="{{ route('links.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-disconnect fs-2"></i></span>
                                            <span class="menu-title">Shortened Links</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ (request()->routeIs('warotators.*') || (request()->routeIs('dashboard') && request('type') == 'warotator')) ? 'active' : '' }}" href="{{ route('warotators.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-whatsapp fs-2"></i></span>
                                            <span class="menu-title">WA Rotators</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ (request()->routeIs('qrcodes.index') || (request()->routeIs('dashboard') && request('type') == 'qr')) ? 'active' : '' }}" href="{{ route('qrcodes.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-scan-barcode fs-2"></i></span>
                                            <span class="menu-title">QR Codes</span>
                                        </a>
                                    </div>

                                    <div class="menu-item pt-5 pb-2">
                                        <div class="menu-content pb-2">
                                            <span class="menu-section text-muted text-uppercase fs-8 ls-1">Tools & Management</span>
                                        </div>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('tools.*') ? 'active' : '' }}" href="{{ route('tools.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-wrench fs-2"></i></span>
                                            <span class="menu-title">Online Tools</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('domains.*') ? 'active' : '' }}" href="{{ route('domains.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-geolocation fs-2"></i></span>
                                            <span class="menu-title">Custom Domains</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-folder fs-2"></i></span>
                                            <span class="menu-title">Projects</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('pixels.*') ? 'active' : '' }}" href="{{ route('pixels.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-code fs-2"></i></span>
                                            <span class="menu-title">Tracking Pixels</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('clicks.*') ? 'active' : '' }}" href="{{ route('clicks.index') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-chart-simple-3 fs-2"></i></span>
                                            <span class="menu-title">Click Activity</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('user.api*') ? 'active' : '' }}" href="{{ route('user.api') }}">
                                            <span class="menu-icon"><i class="ki-outline ki-key fs-2"></i></span>
                                            <span class="menu-title">API & Integrations</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('api-docs') }}" target="_blank">
                                            <span class="menu-icon"><i class="ki-outline ki-document fs-2"></i></span>
                                            <span class="menu-title">API Docs</span>
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        
                        <!-- Content -->
                        <div id="kt_app_content" class="app-content flex-column-fluid py-3 py-lg-6">
                            <div id="kt_app_content_container" class="app-container container-fluid px-lg-10">
                                @yield('content')
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div id="kt_app_footer" class="app-footer">
                        <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3 px-lg-10">
                            <div class="text-gray-900 order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">&copy; {{ date('Y') }}</span>
                                <a href="{{ route('dashboard') }}" class="text-gray-800 text-hover-primary fw-semibold">{{ config('app.name', 'NewLink') }}</a>
                            </div>
                            <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                                <li class="menu-item">
                                    <span class="text-muted fs-7">Version 2.0</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Metronic Global Javascript Bundle -->
    <script>var hostUrl = "{{ asset('assets/') }}/";</script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <!-- Global SweetAlert2 & AJAX Form Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Session Flash Messages via SweetAlert2
            @if(session('success'))
                Swal.fire({
                    text: @json(session('success')),
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "btn btn-primary btn-sm"
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    text: @json(session('error')),
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "btn btn-danger btn-sm"
                    }
                });
            @endif

            // 2. Global AJAX Form Submitter for forms with .ajax-form or [data-ajax="true"]
            document.addEventListener('submit', function (e) {
                var form = e.target;
                if (!form || (!form.classList.contains('ajax-form') && form.dataset.ajax !== 'true')) {
                    return;
                }

                e.preventDefault();

                var submitBtn = form.querySelector('button[type="submit"]');
                var origBtnHtml = submitBtn ? submitBtn.innerHTML : '';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';
                }

                var formData = new FormData(form);
                var url = form.action || window.location.href;
                var method = (form.method || 'POST').toUpperCase();
                var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        return { status: res.status, ok: res.ok, data: data };
                    });
                })
                .then(function(response) {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = origBtnHtml;
                    }

                    if (response.ok && response.data.success !== false) {
                        // Close any modal that contains this form
                        var modalEl = form.closest('.modal');
                        if (modalEl && typeof bootstrap !== 'undefined') {
                            var modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();
                        }

                        Swal.fire({
                            text: response.data.message || "Data berhasil disimpan.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "btn btn-primary btn-sm"
                            }
                        }).then(function() {
                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        var errMsg = response.data.message || "Terjadi kesalahan saat memproses data.";
                        if (response.data.errors) {
                            var errList = [];
                            for (var k in response.data.errors) {
                                errList.push(response.data.errors[k].join(', '));
                            }
                            errMsg = errList.join('\n');
                        }

                        Swal.fire({
                            text: errMsg,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "btn btn-danger btn-sm"
                            }
                        });
                    }
                })
                .catch(function(err) {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = origBtnHtml;
                    }
                    Swal.fire({
                        text: "Koneksi gagal atau terjadi error pada server: " + err.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-danger btn-sm"
                        }
                    });
                });
            });

            // 3. Global AJAX Delete Confirmation for .ajax-delete-form or [data-ajax-delete]
            document.addEventListener('submit', function (e) {
                var form = e.target;
                if (!form || (!form.classList.contains('ajax-delete-form') && form.dataset.ajaxDelete !== 'true')) {
                    return;
                }

                e.preventDefault();

                Swal.fire({
                    text: form.dataset.confirmMessage || "Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn btn-danger btn-sm",
                        cancelButton: "btn btn-light btn-sm"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        var formData = new FormData(form);
                        var url = form.action || window.location.href;
                        var method = (form.method || 'POST').toUpperCase();
                        var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

                        fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            Swal.fire({
                                text: data.message || "Data berhasil dihapus.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "OK",
                                customClass: {
                                    confirmButton: "btn btn-primary btn-sm"
                                }
                            }).then(function () {
                                window.location.reload();
                            });
                        })
                        .catch(function (err) {
                            Swal.fire({
                                text: "Gagal menghapus data: " + err.message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "OK",
                                customClass: {
                                    confirmButton: "btn btn-danger btn-sm"
                                }
                            });
                        });
                    }
                });
            });
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
