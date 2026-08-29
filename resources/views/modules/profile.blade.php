@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="d-flex flex-stack mb-6">
    <div class="d-flex align-items-center gap-2">
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">Account Settings</h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Preferences</span>
    </div>
</div>

<div class="row g-6 g-xl-9">
    <div class="col-12 col-lg-8">
        <div class="card card-flush shadow-sm border-0">
            <div class="card-header pt-6">
                <h3 class="card-title fw-bold text-gray-900 fs-4">Profile Information</h3>
            </div>
            <div class="card-body pt-2 pb-8">
                <form method="POST" action="{{ route('profile.edit') }}">
                    @csrf
                    <!-- Name -->
                    <div class="fv-row mb-6">
                        <label for="name" class="form-label fs-6 fw-semibold text-gray-900 required">Full Name</label>
                        <input type="text" class="form-control form-control-solid" id="name" name="name" value="{{ Auth::user()->name }}" required />
                    </div>

                    <!-- Email -->
                    <div class="fv-row mb-6">
                        <label for="email" class="form-label fs-6 fw-semibold text-gray-900 required">Email Address</label>
                        <input type="email" class="form-control form-control-solid" id="email" name="email" value="{{ Auth::user()->email }}" required />
                    </div>

                    <div class="row g-5">
                        <!-- Timezone -->
                        <div class="col-12 col-md-6 fv-row mb-6">
                            <label for="timezone" class="form-label fs-6 fw-semibold text-gray-900">Timezone</label>
                            <select class="form-select form-select-solid" id="timezone" name="timezone">
                                <option value="Asia/Jakarta" {{ Auth::user()->timezone == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (GMT+7)</option>
                                <option value="UTC" {{ Auth::user()->timezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                            </select>
                        </div>

                        <!-- Language -->
                        <div class="col-12 col-md-6 fv-row mb-6">
                            <label for="language" class="form-label fs-6 fw-semibold text-gray-900">Preferred Language</label>
                            <select class="form-select form-select-solid" id="language" name="language">
                                <option value="english" {{ Auth::user()->language == 'english' ? 'selected' : '' }}>English</option>
                                <option value="indonesia" {{ Auth::user()->language == 'indonesia' ? 'selected' : '' }}>Bahasa Indonesia</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary fw-bold px-6">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
