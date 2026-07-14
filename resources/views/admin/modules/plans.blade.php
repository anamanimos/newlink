@extends('layouts.app')

@section('title', 'Manage Plans')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <div>
            <h2 class="fw-bold tracking-tight mb-1">Plans</h2>
            <p class="text-secondary small">Define and configure plan limits, pricing, and subscription packages.</p>
        </div>
        <button class="btn btn-primary rounded-3 px-3 fw-semibold shadow-sm d-flex align-items-center">
            <span data-duo-icons="add-circle" class="me-1" style="width: 14px; height: 14px;"></span>Create Plan
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Free Plan Card -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-1">Free Plan</h5>
            <div class="text-secondary small mb-3">Default Package</div>
            <hr class="border-secondary opacity-25 mb-3">
            <ul class="list-unstyled mb-0 small text-secondary">
                <li class="mb-2"><span data-duo-icons="check-circle" class="text-success me-2" style="width:16px;height:16px;"></span>15 Biolinks limit</li>
                <li class="mb-2"><span data-duo-icons="check-circle" class="text-success me-2" style="width:16px;height:16px;"></span>Unlimited short links</li>
                <li class="mb-2"><span data-duo-icons="check-circle" class="text-success me-2" style="width:16px;height:16px;"></span>Basic statistics</li>
                <li class="mb-2"><span data-duo-icons="check-circle" class="text-success me-2" style="width:16px;height:16px;"></span>System branding</li>
            </ul>
        </div>
    </div>

    <!-- Custom Plan Card -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="glass-card p-4 border border-primary border-opacity-25">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <h5 class="fw-bold mb-0">Custom Plan</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small">Popular</span>
            </div>
            <div class="text-secondary small mb-3">Billed package</div>
            <hr class="border-secondary opacity-25 mb-3">
            <ul class="list-unstyled mb-0 small text-secondary">
                <li class="mb-2"><span data-duo-icons="check-circle" class="text-success me-2" style="width:16px;height:16px;"></span>Unlimited Biolinks</li>
                <li class="mb-2"><span data-duo-icons="check-circle" class="text-success me-2" style="width:16px;height:16px;"></span>Unlimited short links</li>
                <li class="mb-2"><span data-duo-icons="check-circle" class="text-success me-2" style="width:16px;height:16px;"></span>Advanced statistics</li>
                <li class="mb-2"><span data-duo-icons="check-circle" class="text-success me-2" style="width:16px;height:16px;"></span>No branding / Custom branding</li>
                <li class="mb-2"><span data-duo-icons="check-circle" class="text-success me-2" style="width:16px;height:16px;"></span>Custom domains & Pixels</li>
            </ul>
        </div>
    </div>
</div>
@endsection
