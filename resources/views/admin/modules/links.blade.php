@extends('layouts.app')

@section('title', 'Manage Links')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold tracking-tight mb-1">Links</h2>
        <p class="text-secondary small">Manage user biolinks, shortened links, and toggle their verified status.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="glass-card p-4">
            <!-- Search & Filters -->
            <form method="GET" action="{{ route('admin.links') }}" class="row g-3 mb-4 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-secondary">Cari Tautan</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari slug atau target URL..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Tipe</label>
                    <select name="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="biolink" {{ request('type') == 'biolink' ? 'selected' : '' }}>Biolink</option>
                        <option value="link" {{ request('type') == 'link' ? 'selected' : '' }}>Shortened Link</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 py-2.5 px-4 fw-semibold w-50" style="background-color: var(--primary-color); border-color: var(--primary-color);">Filter</button>
                    <a href="{{ route('admin.links') }}" class="btn btn-light btn-sm rounded-3 py-2.5 px-4 fw-semibold w-50">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-secondary small">
                            <th class="border-0 px-2 py-3">Link</th>
                            <th class="border-0 px-2 py-3">Owner</th>
                            <th class="border-0 px-2 py-3">Clicks</th>
                            <th class="border-0 px-2 py-3">Verified</th>
                            <th class="border-0 px-2 py-3 text-end">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($links as $link)
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td class="px-2 py-3">
                                    <div class="d-flex align-items-center">
                                        <!-- Icon Avatar depending on type -->
                                        @if($link->type == 'biolink')
                                            <div class="p-2 rounded-circle bg-primary bg-opacity-10 me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; color: var(--primary-color);">
                                                <span data-duo-icons="app" style="width: 18px; height: 18px;"></span>
                                            </div>
                                        @else
                                            <div class="p-2 rounded-circle bg-success bg-opacity-10 me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; color: var(--primary-color);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M9 17H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                                                    <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ url('/') }}/{{ $link->url }}" target="_blank" class="fw-bold text-decoration-none text-dark-custom mb-0.5">
                                                    {{ $link->url }}
                                                </a>
                                                <span id="verified-badge-{{ $link->id }}" class="badge-verify-container d-inline-flex {{ $link->is_verified ? '' : 'd-none' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#0095f6" style="color: white; flex-shrink: 0;" title="Verified Profile">
                                                        <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <span class="text-muted small text-truncate d-block" style="max-width: 250px;">{{ $link->location_url ?? 'Halaman Biolink' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-3 text-secondary small">
                                    <div class="fw-bold text-dark-custom">{{ $link->user->name ?? 'Deleted User' }}</div>
                                    <div>{{ $link->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-2 py-3 font-monospace small">
                                    {{ number_format($link->clicks) }}
                                </td>
                                <td class="px-2 py-3">
                                    <!-- AJAX Toggle Button -->
                                    <button class="btn btn-sm btn-verify-toggle rounded-3 px-3 py-1.5 fw-semibold {{ $link->is_verified ? 'btn-danger bg-danger text-white border-0' : 'btn-outline-primary border-primary text-primary' }}" data-id="{{ $link->id }}" style="font-size: 0.725rem;">
                                        {{ $link->is_verified ? 'Unverify' : 'Verify' }}
                                    </button>
                                </td>
                                <td class="px-2 py-3 text-end text-muted small">
                                    {{ $link->created_at ? $link->created_at->format('M d, Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">
                                    Belum ada tautan yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if($links->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $links->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- AJAX and Notification script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show premium toast
    function showToast(message, isSuccess = true) {
        const toast = $('<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">' +
            '<div class="toast show align-items-center text-white border-0" role="alert" style="background-color: ' + (isSuccess ? '#10b981' : '#ef4444') + '; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">' +
            '<div class="d-flex py-2 px-3 align-items-center gap-2">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' +
            '<span class="fw-semibold small">' + message + '</span>' +
            '</div>' +
            '</div>' +
            '</div>');
        $('body').append(toast);
        setTimeout(() => {
            toast.fadeOut(400, function() { $(this).remove(); });
        }, 2500);
    }

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
                    // Turn to verified
                    btn.removeClass('btn-outline-primary border-primary text-primary')
                       .addClass('btn-danger bg-danger text-white border-0')
                       .text('Unverify');
                    badge.removeClass('d-none');
                } else {
                    // Turn to unverified
                    btn.removeClass('btn-danger bg-danger text-white border-0')
                       .addClass('btn-outline-primary border-primary text-primary')
                       .text('Verify');
                    badge.addClass('d-none');
                }

                showToast(response.message);
            },
            error: function() {
                showToast('Gagal memproses verifikasi.', false);
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection
