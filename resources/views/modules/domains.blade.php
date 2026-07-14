@extends('layouts.app')

@section('title', 'Custom Domains')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <div>
            <h2 class="fw-bold tracking-tight mb-1">Custom Domains</h2>
            <p class="text-secondary small">Connect your own custom domains for branded short links.</p>
        </div>
        <button class="btn btn-primary rounded-3 px-3 fw-semibold shadow-sm d-flex align-items-center">
            <span data-duo-icons="add-circle" class="me-1" style="width: 14px; height: 14px;"></span>Connect Domain
        </button>
    </div>
</div>

<div class="row g-4">
    @if($domains->isEmpty())
        <div class="col-12">
            <div class="glass-card p-5 text-center">
                <span data-duo-icons="world" style="width: 48px; height: 48px; color: #6c757d;" class="mb-3"></span>
                <p class="text-secondary mb-0">No domains connected yet.</p>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-secondary small">
                                <th class="border-0 px-2 py-3">Host Domain</th>
                                <th class="border-0 px-2 py-3">Scheme</th>
                                <th class="border-0 px-2 py-3">Type</th>
                                <th class="border-0 px-2 py-3">Status</th>
                                <th class="border-0 px-2 py-3 text-end">Connected</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($domains as $domain)
                                <tr style="border-bottom: 1px solid var(--glass-border);">
                                    <td class="px-2 py-3 fw-bold text-primary">
                                        {{ $domain->host }}
                                    </td>
                                    <td class="px-2 py-3 text-muted small">
                                        {{ $domain->scheme }}
                                    </td>
                                    <td class="px-2 py-3">
                                        <span class="badge {{ $domain->type == 1 ? 'bg-secondary text-secondary' : 'bg-primary text-primary' }} bg-opacity-10 rounded-pill px-3 py-1.5 small">
                                            {{ $domain->type == 1 ? 'System' : 'Custom' }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-3">
                                        @if($domain->is_enabled)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1.5 small">Active</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2.5 py-1.5 small">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-2 py-3 text-end text-muted small">
                                        {{ $domain->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
