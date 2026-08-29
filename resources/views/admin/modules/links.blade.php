@extends('layouts.app')

@section('title', 'Manage Links')

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Links Management</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">All Platform Links</span>
    </div>
</div>

<div class="card card-flush shadow-sm border-0 mb-6">
    <!-- Card Header: Search & Filters -->
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <form method="GET" action="{{ route('admin.links') }}" class="d-flex align-items-center flex-wrap gap-3 w-100">
            <div class="d-flex align-items-center position-relative">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                <input type="text" name="search" class="form-control form-control-sm form-control-solid w-250px ps-11" placeholder="Search slug or target..." value="{{ request('search') }}" />
            </div>

            <div class="w-175px">
                <select name="type" class="form-select form-select-sm form-select-solid">
                    <option value="">All Types</option>
                    <option value="biolink" {{ request('type') == 'biolink' ? 'selected' : '' }}>Biolink</option>
                    <option value="link" {{ request('type') == 'link' ? 'selected' : '' }}>Shortlink</option>
                </select>
            </div>

            <div class="d-flex gap-2 ms-auto">
                <button type="submit" class="btn btn-sm btn-primary fw-bold">Filter</button>
                <a href="{{ route('admin.links') }}" class="btn btn-sm btn-light fw-bold">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-200px">Link</th>
                        <th class="min-w-150px">Owner</th>
                        <th class="min-w-80px">Clicks</th>
                        <th class="min-w-100px">Verified</th>
                        <th class="text-end min-w-100px pe-3">Created</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($links as $link)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px symbol-circle me-3">
                                        @if($link->type == 'biolink')
                                            <span class="symbol-label bg-light-primary">
                                                <i class="ki-outline ki-abstract-26 fs-2 text-primary"></i>
                                            </span>
                                        @else
                                            <span class="symbol-label bg-light-info">
                                                <i class="ki-outline ki-disconnect fs-2 text-info"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="{{ url('/') }}/{{ $link->url }}" target="_blank" class="fw-bold text-gray-800 text-hover-primary fs-6">
                                                {{ $link->url }}
                                            </a>
                                            <span id="verified-badge-{{ $link->id }}" class="badge-verify-container d-inline-flex {{ $link->is_verified ? '' : 'd-none' }}">
                                                <i class="ki-outline ki-verify fs-5 text-primary"></i>
                                            </span>
                                        </div>
                                        <span class="text-muted fs-7 text-truncate" style="max-width: 250px;">{{ $link->location_url ?? 'Biolink Page' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-gray-800 fs-7">{{ $link->user->name ?? 'Deleted User' }}</span>
                                    <span class="text-muted fs-8">{{ $link->user->email ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="badge badge-light fw-bold text-gray-800 fs-7">
                                    {{ number_format($link->clicks) }}
                                </div>
                            </td>
                            <td>
                                <!-- AJAX Toggle Button -->
                                <button class="btn btn-sm btn-verify-toggle {{ $link->is_verified ? 'btn-light-danger' : 'btn-light-primary' }} fw-bold fs-8 py-1.5 px-3" data-id="{{ $link->id }}">
                                    {{ $link->is_verified ? 'Unverify' : 'Verify' }}
                                </button>
                            </td>
                            <td class="text-end pe-3 text-muted fs-7">
                                {{ $link->created_at ? $link->created_at->format('M d, Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-muted">
                                <i class="ki-outline ki-disconnect fs-4x text-muted mb-3"></i>
                                <p class="fs-6 fw-semibold mb-0">No links registered yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        @if($links->hasPages())
            <div class="d-flex justify-content-center mt-6">
                {{ $links->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- AJAX and Notification script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Verification Handler
    $('.btn-verify-toggle').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const id = btn.attr('data-id');
        btn.prop('disabled', true);

        $.ajax({
            url: `/admin/links/${id}/toggle-verify`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                const badge = $(`#verified-badge-${id}`);
                if (response.is_verified) {
                    btn.removeClass('btn-light-primary').addClass('btn-light-danger').text('Unverify');
                    badge.removeClass('d-none');
                } else {
                    btn.removeClass('btn-light-danger').addClass('btn-light-primary').text('Verify');
                    badge.addClass('d-none');
                }
            },
            error: function() {
                alert('Failed to process verification.');
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection
