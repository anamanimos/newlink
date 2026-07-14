@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold tracking-tight mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h2>
        <p class="text-secondary small">System statistics and overview of NamsLink.</p>
    </div>
</div>

<!-- Admin Stats Grid (8 Cards) -->
<div class="row g-4 mb-5 mobile-cards-slider">
    <!-- Biolink Pages -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="glass-card p-4 d-flex align-items-center w-100">
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 me-3">
                <span data-duo-icons="compass" style="width: 24px; height: 24px; color: var(--primary-color);"></span>
            </div>
            <div>
                <h6 class="text-secondary small mb-1">Biolink Pages</h6>
                <h4 class="fw-bold mb-0">{{ $biolinksCount }}</h4>
                <span class="text-muted d-block small" style="font-size: 0.725rem; margin-top: 2px;">{{ $biolinksThisMonth }} this month</span>
            </div>
        </div>
    </div>

    <!-- Shortened Links -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="glass-card p-4 d-flex align-items-center w-100">
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 me-3">
                <span data-duo-icons="world" style="width: 24px; height: 24px; color: var(--primary-color);"></span>
            </div>
            <div>
                <h6 class="text-secondary small mb-1">Shortened Links</h6>
                <h4 class="fw-bold mb-0">{{ $shortLinksCount }}</h4>
                <span class="text-muted d-block small" style="font-size: 0.725rem; margin-top: 2px;">{{ $shortLinksThisMonth }} this month</span>
            </div>
        </div>
    </div>

    <!-- Pageviews Tracked -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="glass-card p-4 d-flex align-items-center w-100">
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 me-3">
                <span data-duo-icons="chart-pie" style="width: 24px; height: 24px; color: var(--primary-color);"></span>
            </div>
            <div>
                <h6 class="text-secondary small mb-1">Pageviews Tracked</h6>
                <h4 class="fw-bold mb-0">{{ number_format($totalPageviews) }}</h4>
                <span class="text-muted d-block small" style="font-size: 0.725rem; margin-top: 2px;">{{ $pageviewsThisMonth }} this month</span>
            </div>
        </div>
    </div>

    <!-- QR Codes -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="glass-card p-4 d-flex align-items-center w-100">
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 me-3">
                <span data-duo-icons="qr-code" style="width: 24px; height: 24px; color: var(--primary-color);"></span>
            </div>
            <div>
                <h6 class="text-secondary small mb-1">QR Codes</h6>
                <h4 class="fw-bold mb-0">{{ $qrCodesCount }}</h4>
                <span class="text-muted d-block small" style="font-size: 0.725rem; margin-top: 2px;">{{ $qrCodesThisMonth }} this month</span>
            </div>
        </div>
    </div>

    <!-- Domains -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="glass-card p-4 d-flex align-items-center w-100">
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 me-3">
                <span data-duo-icons="world" style="width: 24px; height: 24px; color: var(--primary-color);"></span>
            </div>
            <div>
                <h6 class="text-secondary small mb-1">Domains</h6>
                <h4 class="fw-bold mb-0">{{ $domainsCount }}</h4>
                <span class="text-muted d-block small" style="font-size: 0.725rem; margin-top: 2px;">{{ $domainsThisMonth }} this month</span>
            </div>
        </div>
    </div>

    <!-- Users -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="glass-card p-4 d-flex align-items-center w-100">
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 me-3">
                <span data-duo-icons="user" style="width: 24px; height: 24px; color: var(--primary-color);"></span>
            </div>
            <div>
                <h6 class="text-secondary small mb-1">Users</h6>
                <h4 class="fw-bold mb-0">{{ $usersCount }}</h4>
                <span class="text-muted d-block small" style="font-size: 0.725rem; margin-top: 2px;">{{ $usersThisMonth }} this month</span>
            </div>
        </div>
    </div>

    <!-- Payments -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="glass-card p-4 d-flex align-items-center w-100">
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 me-3">
                <span data-duo-icons="credit-card" style="width: 24px; height: 24px; color: var(--primary-color);"></span>
            </div>
            <div>
                <h6 class="text-secondary small mb-1">Payments</h6>
                <h4 class="fw-bold mb-0">{{ $paymentsCount }}</h4>
                <span class="text-muted d-block small" style="font-size: 0.725rem; margin-top: 2px;">{{ $paymentsThisMonth }} this month</span>
            </div>
        </div>
    </div>

    <!-- Earned -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="glass-card p-4 d-flex align-items-center w-100">
            <div class="p-3 rounded-circle bg-primary bg-opacity-10 me-3">
                <span data-duo-icons="discount" style="width: 24px; height: 24px; color: var(--primary-color);"></span>
            </div>
            <div>
                <h6 class="text-secondary small mb-1">Earned</h6>
                <h4 class="fw-bold mb-0">${{ number_format($earnedCount, 2) }}</h4>
                <span class="text-muted d-block small" style="font-size: 0.725rem; margin-top: 2px;">${{ number_format($earnedThisMonth, 2) }} this month</span>
            </div>
        </div>
    </div>
</div>

<!-- Latest Users Row -->
<div class="row">
    <div class="col-12">
        <div class="glass-card p-4">
            <div class="d-flex align-items-center mb-4">
                <span data-duo-icons="user" class="me-2 text-primary" style="width: 24px; height: 24px;"></span>
                <h5 class="fw-bold mb-0">Latest Users</h5>
            </div>

            <!-- Desktop Table view -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-secondary small">
                            <th class="border-0 px-2 py-3">User</th>
                            <th class="border-0 px-2 py-3">Status</th>
                            <th class="border-0 px-2 py-3">Plan</th>
                            <th class="border-0 px-2 py-3 text-end">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestUsers as $user)
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td class="px-2 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 rounded-circle bg-primary bg-opacity-10 me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; color: var(--primary-color);">
                                            <span data-duo-icons="user" style="width: 18px; height: 18px;"></span>
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-0">{{ $user->name }}</div>
                                            <span class="text-muted small">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-3">
                                    @if($user->status == 1)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1.5 small">Active</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2.5 py-1.5 small">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1.5 text-uppercase small">
                                        {{ $user->plan_id }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 text-end text-muted small">
                                    {{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile List Card view -->
            <div class="d-md-none">
                @foreach($latestUsers as $user)
                    <div class="py-3 border-bottom d-flex align-items-start gap-3" style="border-bottom-color: var(--glass-border) !important;">
                        <!-- Avatar -->
                        <div class="p-2 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0; color: var(--primary-color);">
                            <span data-duo-icons="user" style="width: 18px; height: 18px;"></span>
                        </div>
                        
                        <!-- Content Block -->
                        <div class="flex-grow-1 min-w-0">
                            <!-- Header (Name & Plan) -->
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold mb-0 text-truncate" style="font-size: 0.9rem;">{{ $user->name }}</h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2.5 py-1 text-uppercase" style="font-size: 0.65rem; flex-shrink: 0;">
                                    {{ $user->plan_id }}
                                </span>
                            </div>
                            
                            <!-- Email -->
                            <div class="text-muted text-truncate mb-2" style="font-size: 0.75rem;">
                                {{ $user->email }}
                            </div>
                            
                            <!-- Footer (Joined & Status) -->
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <span class="text-muted" style="font-size: 0.725rem;">
                                    Joined: {{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
                                </span>
                                @if($user->status == 1)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 small" style="font-size: 0.65rem;">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2.5 py-1 small" style="font-size: 0.65rem;">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
