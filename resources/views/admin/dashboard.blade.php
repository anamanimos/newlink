@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
            Welcome back, {{ Auth::user()->name }}! 👋
        </h1>
        <span class="badge badge-light-danger fw-semibold fs-8 px-2 py-1 ms-2">Admin Panel</span>
    </div>
</div>

<!-- Admin Stats Grid (8 Cards) -->
<div class="row g-5 g-xl-8 mb-6">
    <!-- Biolink Pages -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-outline ki-profile-user fs-2x text-primary"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $biolinksCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Biolink Pages</span>
                    <span class="text-muted fs-8">{{ $biolinksThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Shortened Links -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-info">
                        <i class="ki-outline ki-disconnect fs-2x text-info"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $shortLinksCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Shortened Links</span>
                    <span class="text-muted fs-8">{{ $shortLinksThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pageviews Tracked -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-outline ki-chart-simple fs-2x text-success"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ number_format($totalPageviews) }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Pageviews</span>
                    <span class="text-muted fs-8">{{ $pageviewsThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Codes -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-warning">
                        <i class="ki-outline ki-scan-barcode fs-2x text-warning"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $qrCodesCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">QR Codes</span>
                    <span class="text-muted fs-8">{{ $qrCodesThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Domains -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-danger">
                        <i class="ki-outline ki-geolocation fs-2x text-danger"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $domainsCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Domains</span>
                    <span class="text-muted fs-8">{{ $domainsThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Users -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-outline ki-people fs-2x text-primary"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $usersCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Users</span>
                    <span class="text-muted fs-8">{{ $usersThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-outline ki-credit-cart fs-2x text-success"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">{{ $paymentsCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Payments</span>
                    <span class="text-muted fs-8">{{ $paymentsThisMonth }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Earned -->
    <div class="col-6 col-md-3">
        <div class="card card-flush h-md-100 shadow-sm border-0">
            <div class="card-body d-flex align-items-center p-5">
                <div class="symbol symbol-45px symbol-circle me-4">
                    <span class="symbol-label bg-light-info">
                        <i class="ki-outline ki-dollar fs-2x text-info"></i>
                    </span>
                </div>
                <div class="d-flex flex-column min-w-0">
                    <span class="fs-2hx fw-bold text-gray-900 lh-1 mb-1">${{ number_format($earnedCount, 2) }}</span>
                    <span class="text-gray-500 fw-semibold fs-7 text-truncate">Earned</span>
                    <span class="text-muted fs-8">${{ number_format($earnedThisMonth, 2) }} this month</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Users Card -->
<div class="card card-flush shadow-sm border-0 mb-6">
    <div class="card-header pt-6">
        <h3 class="card-title fw-bold text-gray-900 fs-4">Latest Users</h3>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-200px">User</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-100px">Plan</th>
                        <th class="text-end min-w-100px pe-3">Joined</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach($latestUsers as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px symbol-circle me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-outline ki-user fs-2 text-primary"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-gray-800 fs-6">{{ $user->name }}</span>
                                        <span class="text-muted fs-7">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->status == 1)
                                    <span class="badge badge-light-success fw-bold fs-8">Active</span>
                                @else
                                    <span class="badge badge-light-secondary fw-bold fs-8">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light-primary fw-semibold fs-8 text-uppercase">
                                    {{ $user->plan_id }}
                                </span>
                            </td>
                            <td class="text-end pe-3 text-muted fs-7">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
