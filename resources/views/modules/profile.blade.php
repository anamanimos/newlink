@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold tracking-tight mb-1">Account Settings</h2>
        <p class="text-secondary small">Update your name, email address, timezone, and regional preferences.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="glass-card p-4">
            <form method="POST" action="{{ route('profile.edit') }}">
                @csrf
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Full Name</label>
                    <input type="text" class="form-control glass-input" id="name" name="name" value="{{ Auth::user()->name }}" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Email address</label>
                    <input type="email" class="form-control glass-input" id="email" name="email" value="{{ Auth::user()->email }}" required>
                </div>

                <div class="row">
                    <!-- Timezone -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="timezone" class="form-label small fw-semibold text-secondary">Timezone</label>
                        <select class="form-select glass-input" id="timezone" name="timezone">
                            <option value="Asia/Jakarta" {{ Auth::user()->timezone == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (GMT+7)</option>
                            <option value="UTC" {{ Auth::user()->timezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                        </select>
                    </div>

                    <!-- Language -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="language" class="form-label small fw-semibold text-secondary">Preferred Language</label>
                        <select class="form-select glass-input" id="language" name="language">
                            <option value="english" {{ Auth::user()->language == 'english' ? 'selected' : '' }}>English</option>
                            <option value="indonesia" {{ Auth::user()->language == 'indonesia' ? 'selected' : '' }}>Bahasa Indonesia</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
