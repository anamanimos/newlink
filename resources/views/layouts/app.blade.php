<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'NewLink') }}</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar glass-nav fixed-top navbar-expand-lg px-4">
        <div class="container-fluid p-0 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <!-- Mobile Sidebar Toggle -->
                <button class="btn theme-toggle-btn sidebar-toggle d-lg-none me-3" type="button" aria-label="Toggle sidebar">
                    <span data-duo-icons="menu" style="width: 20px; height: 20px;"></span>
                </button>
                
                <a class="navbar-brand fw-bold fs-4" href="{{ route('dashboard') }}" style="color: var(--text-primary); letter-spacing: -0.5px;">
                    {{ config('app.name', 'NamsLink') }}
                </a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Theme switch toggle -->
                <button type="button" id="theme-switcher" class="theme-toggle-btn me-2" aria-label="Toggle Theme">
                    <!-- Icon loaded by JS -->
                </button>

                <!-- Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn theme-toggle-btn dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span data-duo-icons="user" style="width: 20px; height: 20px;" class="me-1"></span>
                        <span class="d-none d-md-inline small">{{ Auth::user()->name ?? 'Account' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end glass-card border-0 shadow-lg mt-2 p-2">
                        <li>
                            <a class="dropdown-item rounded-2 py-2 px-3 d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                                <span data-duo-icons="settings" style="width: 16px; height: 16px;"></span>Settings
                            </a>
                        </li>
                        @if(Auth::check() && Auth::user()->type === 1)
                            <li>
                                @if(request()->is('admin*'))
                                    <a class="dropdown-item rounded-2 py-2 px-3 d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                                        <span data-duo-icons="world" style="width: 16px; height: 16px;"></span>Go to User Panel
                                    </a>
                                @else
                                    <a class="dropdown-item rounded-2 py-2 px-3 d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                                        <span data-duo-icons="dashboard" style="width: 16px; height: 16px;"></span>Go to Admin Panel
                                    </a>
                                @endif
                            </li>
                        @endif
                        <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-2 text-danger py-2 px-3 border-0 w-100 text-start bg-transparent m-0 d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="text-danger">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <nav class="flex-column">
                @if(request()->is('admin*'))
                    <!-- Admin Navigation -->
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span data-duo-icons="dashboard" class="me-3" style="width:20px;height:20px;"></span>Admin Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <span data-duo-icons="user" class="me-3" style="width:20px;height:20px;"></span>Users
                    </a>
                    <a href="{{ route('admin.domains') }}" class="sidebar-link {{ request()->routeIs('admin.domains') ? 'active' : '' }}">
                        <span data-duo-icons="world" class="me-3" style="width:20px;height:20px;"></span>Domains
                    </a>
                    <a href="{{ route('admin.links') }}" class="sidebar-link {{ request()->routeIs('admin.links') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="me-3" style="color: var(--text-secondary); flex-shrink: 0;">
                            <path d="M9 17H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                            <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>Links
                    </a>
                    <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <span data-duo-icons="settings" class="me-3" style="width:20px;height:20px;"></span>Settings
                    </a>
                    <a href="{{ route('admin.plans') }}" class="sidebar-link {{ request()->routeIs('admin.plans') ? 'active' : '' }}">
                        <span data-duo-icons="discount" class="me-3" style="width:20px;height:20px;"></span>Plans
                    </a>
                @else
                    <!-- User Navigation -->
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span data-duo-icons="dashboard" class="me-3" style="width:20px;height:20px;"></span>Dashboard
                    </a>
                    <a href="{{ route('biolinks.index') }}" class="sidebar-link {{ request()->routeIs('biolinks.index') ? 'active' : '' }}">
                        <span data-duo-icons="app" class="me-3" style="width:20px;height:20px;"></span>Biolink pages
                    </a>
                    <a href="{{ route('links.index') }}" class="sidebar-link {{ request()->routeIs('links.index') ? 'active' : '' }}">
                        <span data-duo-icons="world" class="me-3" style="width:20px;height:20px;"></span>Shortened links
                    </a>
                    <a href="{{ route('qrcodes.index') }}" class="sidebar-link {{ request()->routeIs('qrcodes.index') ? 'active' : '' }}">
                        <span data-duo-icons="file" class="me-3" style="width:20px;height:20px;"></span>QR Codes
                    </a>
                    <a href="#" class="sidebar-link">
                        <span data-duo-icons="settings" class="me-3" style="width:20px;height:20px;"></span>Tools
                    </a>
                    <a href="{{ route('domains.index') }}" class="sidebar-link {{ request()->routeIs('domains.*') ? 'active' : '' }}">
                        <span data-duo-icons="world" class="me-3" style="width:20px;height:20px;"></span>Custom domains
                    </a>
                    <a href="{{ route('projects.index') }}" class="sidebar-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                        <span data-duo-icons="folder-open" class="me-3" style="width:20px;height:20px;"></span>Projects
                    </a>
                    <a href="{{ route('pixels.index') }}" class="sidebar-link {{ request()->routeIs('pixels.*') ? 'active' : '' }}">
                        <span data-duo-icons="target" class="me-3" style="width:20px;height:20px;"></span>Data
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-content">
            <div class="container-fluid p-0">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
