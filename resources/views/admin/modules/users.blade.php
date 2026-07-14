@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold tracking-tight mb-1">Users</h2>
        <p class="text-secondary small">Manage registered accounts, plan subscriptions, and user status.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="glass-card p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-secondary small">
                            <th class="border-0 px-2 py-3">User</th>
                            <th class="border-0 px-2 py-3">Status</th>
                            <th class="border-0 px-2 py-3">Plan</th>
                            <th class="border-0 px-2 py-3">Role</th>
                            <th class="border-0 px-2 py-3 text-end">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
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
                                <td class="px-2 py-3">
                                    <span class="badge {{ $user->type == 1 ? 'bg-danger text-danger border-danger' : 'bg-secondary text-secondary border-secondary' }} bg-opacity-10 border border-opacity-25 rounded-pill px-2.5 py-1.5 small">
                                        {{ $user->type == 1 ? 'Admin' : 'User' }}
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
        </div>
    </div>
</div>
@endsection
