@extends('layouts.app')

@section('title', 'Manage Plans')

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Plans & Subscriptions</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Packages</span>
    </div>
    <button class="btn btn-sm btn-primary d-flex align-items-center gap-2">
        <i class="ki-outline ki-plus fs-2"></i> Create Plan
    </button>
</div>

<div class="row g-6 g-xl-9">
    <!-- Free Plan Card -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card card-flush shadow-sm border-0 h-100">
            <div class="card-body p-6">
                <h4 class="fw-bold text-gray-900 mb-1">Free Plan</h4>
                <div class="text-muted fs-7 mb-4">Default Package</div>
                <div class="separator separator-dashed mb-5"></div>
                <ul class="list-unstyled mb-0 fs-7 text-gray-700">
                    <li class="d-flex align-items-center mb-3">
                        <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i> 15 Biolinks limit
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i> Unlimited short links
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i> Basic statistics
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i> System branding
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Custom Plan Card -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card card-flush shadow-sm border border-primary h-100">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <h4 class="fw-bold text-gray-900 mb-0">Pro Plan</h4>
                    <span class="badge badge-light-primary fw-bold fs-8">Popular</span>
                </div>
                <div class="text-muted fs-7 mb-4">Internal / Enterprise Package</div>
                <div class="separator separator-dashed mb-5"></div>
                <ul class="list-unstyled mb-0 fs-7 text-gray-700">
                    <li class="d-flex align-items-center mb-3">
                        <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i> Unlimited Biolinks
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i> Unlimited short links
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i> Advanced statistics
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i> Custom branding
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="ki-outline ki-check-circle fs-4 text-success me-2"></i> Custom domains & Pixels
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
