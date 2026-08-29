<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') | {{ config('app.name', 'NewLink') }}</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

    <!-- Google Fonts Inter -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

    <!-- Metronic Global Stylesheets Bundle -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    @stack('styles')
    @yield('styles')
</head>

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
    
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

    <!-- Root Container -->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            
            <!-- Body / Form Column -->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="w-lg-500px p-10">
                        @yield('content')
                    </div>
                </div>

                <!-- Footer -->
                <div class="w-lg-500px d-flex flex-stack px-10 mx-auto">
                    <div class="text-gray-500 fw-semibold fs-6">
                        &copy; {{ date('Y') }} {{ config('app.name', 'NewLink') }}
                    </div>
                    <div class="d-flex fw-semibold text-primary fs-base gap-5">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </div>
                </div>
            </div>

            <!-- Aside / Banner Column -->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url('{{ asset('assets/media/misc/auth-bg.png') }}')">
                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                    <!-- Logo -->
                    <a href="{{ url('/') }}" class="mb-12">
                        <span class="fs-2hx fw-bolder text-white">
                            <i class="ki-outline ki-fasten text-primary fs-3x me-2"></i>{{ config('app.name', 'NewLink') }}
                        </span>
                    </a>

                    <!-- Image Illustration -->
                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="{{ asset('assets/media/misc/auth-screens.png') }}" alt="" />

                    <!-- Title -->
                    <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">
                        Fast, Efficient and Productive
                    </h1>
                    <div class="d-none d-lg-block text-white fs-base text-center">
                        All-in-one Biolink builder, Shortened links, and WhatsApp lead rotation platform.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Metronic Global Javascript Bundle -->
    <script>var hostUrl = "{{ asset('assets/') }}/";</script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
