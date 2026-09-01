@extends('layouts.app')

@section('title', 'Biolink Builder')

@section('content')
@php
    $previewUrl = route('biolinks.preview', $link->id);
    $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
    if (request()->isSecure() && str_starts_with($fullUrl, 'http://')) {
        $fullUrl = preg_replace('#^http://#', 'https://', $fullUrl);
    }
@endphp

<!-- CSS for Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
    /* Preset theme card highlights */
    .preset-card {
        cursor: pointer;
        border: 1px solid var(--bs-border-color);
        background: var(--bs-body-bg);
        transition: all 0.2s ease;
        padding: 6px 14px;
        border-radius: 50px !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        user-select: none;
    }
    .preset-card:hover {
        border-color: var(--bs-primary) !important;
        background-color: var(--bs-light-primary);
    }
    .preset-card.selected {
        border-color: var(--bs-primary) !important;
        background-color: var(--bs-light-primary) !important;
        box-shadow: 0 0 0 1px var(--bs-primary);
    }

    /* Drag & Drop overlays */
    #coverDropzone:hover .dropzone-overlay,
    #avatarDropzone:hover .dropzone-overlay {
        opacity: 1 !important;
    }

    .dropzone-overlay {
        pointer-events: none;
    }

    /* Image Cropper sizing */
    .cropper-container-wrapper {
        max-height: 400px;
        overflow: hidden;
    }
    .cropper-container-wrapper img {
        max-width: 100%;
        display: block;
    }

    /* Clean block row hover */
    .block-row {
        transition: background-color 0.2s ease;
    }
    .block-row:hover {
        background-color: var(--bs-light);
    }
</style>

<!-- Page Header (Full Width) -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('biolinks.index') }}" class="btn btn-sm btn-icon btn-light me-2">
            <i class="ki-outline ki-arrow-left fs-2"></i>
        </a>
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
            Biolink Builder: {{ $link->url }}
        </h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">Biolink Page</span>
    </div>
    <div>
        <a href="{{ $fullUrl }}" target="_blank" class="btn btn-sm btn-light-primary fw-bold d-inline-flex align-items-center gap-2">
            <i class="ki-outline ki-exit-right-corner fs-4"></i> Lihat Halaman
        </a>
    </div>
</div>

<div class="row g-6 g-xl-9 align-items-start mb-12" style="min-height: 720px;">
    <!-- Left Column (Tab Content Area) -->
    <div class="col-lg-7 col-xl-8 pe-lg-5 pb-10">
        
        <!-- Metronic Standard Tabs Navigation -->
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold mb-5" id="builderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-3 active d-flex align-items-center gap-2" id="blocks-tab" data-bs-toggle="tab" href="#blocks-pane" role="tab">
                    <i class="ki-outline ki-element-11 fs-4"></i> Blok Konten
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-3 d-flex align-items-center gap-2" id="profile-tab" data-bs-toggle="tab" href="#profile-pane" role="tab">
                    <i class="ki-outline ki-user fs-4"></i> Profil
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-3 d-flex align-items-center gap-2" id="styling-tab" data-bs-toggle="tab" href="#styling-pane" role="tab">
                    <i class="ki-outline ki-color-swatch fs-4"></i> Styling
                </a>
            </li>
            <li class="nav-item d-md-none" role="presentation">
                <a class="nav-link text-active-primary py-3 d-flex align-items-center gap-2" id="preview-tab" data-bs-toggle="tab" href="#preview-pane" role="tab">
                    <i class="ki-outline ki-screen fs-4"></i> Pratinjau
                </a>
            </li>
        </ul>

        <div class="tab-content" id="builderTabContent">
            
            <!-- TAB 1: Blok Konten -->
            <div class="tab-pane fade show active" id="blocks-pane" role="tabpanel" aria-labelledby="blocks-tab">
                <div id="blocks-container-wrapper" class="card card-flush shadow-sm border-0 mb-6">
                    <div class="card-header pt-5 pb-3">
                        <h3 class="card-title fw-bold text-gray-900 fs-5 d-flex align-items-center gap-2">
                            <i class="ki-outline ki-folder fs-3 text-primary"></i> Daftar Blok Konten
                        </h3>
                        <div class="card-toolbar gap-2">
                            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addLinkBlockModal">
                                <i class="ki-outline ki-plus fs-4 me-1"></i> Tambah Tautan
                            </button>
                            <button type="button" class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addTextBlockModal">
                                <i class="ki-outline ki-text fs-4 me-1"></i> Tambah Teks
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        @if($blocks->isEmpty())
                            <div class="text-center py-12 px-4">
                                <div class="symbol symbol-70px symbol-circle mb-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-abstract-26 fs-2x text-primary"></i>
                                    </span>
                                </div>
                                <h5 class="fw-bold text-gray-800 mb-1">Belum ada blok konten</h5>
                                <p class="text-muted fs-7 mb-4">Mulai tambahkan tautan atau teks menggunakan tombol di atas.</p>
                                <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addLinkBlockModal">
                                    <i class="ki-outline ki-plus fs-4 me-1"></i> Tambah Tautan Sekarang
                                </button>
                            </div>
                        @else
                            <div class="d-flex flex-column" id="blocks-container">
                                @foreach($blocks as $block)
                                    <div class="block-row d-flex align-items-center justify-content-between p-4 border-bottom" data-id="{{ $block->id }}">
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Drag Handle -->
                                            <div class="drag-handle text-gray-400 text-hover-primary cursor-grab py-2 pe-1" title="Geser untuk mengatur urutan">
                                                <i class="ki-outline ki-dots-square-vertical fs-3"></i>
                                            </div>

                                            <!-- Symbol Icon -->
                                            <div class="symbol symbol-40px symbol-circle flex-shrink-0">
                                                <span class="symbol-label {{ $block->type == 'link' ? 'bg-light-primary' : 'bg-light-info' }}">
                                                    @if($block->type == 'link')
                                                        @if(!empty($block->settings['icon']))
                                                            <span data-duo-icons="{{ $block->settings['icon'] }}" style="width: 20px; height: 20px; color: var(--bs-primary);"></span>
                                                        @else
                                                            <i class="ki-outline ki-paper-clip fs-3 text-primary"></i>
                                                        @endif
                                                    @elseif($block->type == 'text')
                                                        <i class="ki-outline ki-text fs-3 text-info"></i>
                                                    @endif
                                                </span>
                                            </div>

                                            <!-- Details -->
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-900 fw-bold fs-6 mb-0">
                                                    @if($block->type == 'link')
                                                        {{ $block->settings['title'] ?? 'Tanpa Judul' }}
                                                    @elseif($block->type == 'text')
                                                        {{ Str::limit(strip_tags($block->settings['content'] ?? ''), 35) }}
                                                    @endif
                                                </span>
                                                @if($block->type == 'link')
                                                    <a href="{{ $block->location_url }}" target="_blank" class="text-muted text-hover-primary fs-7 text-truncate" style="max-width: 280px;">
                                                        {{ $block->location_url }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Right Section: Badge, Switch, Edit, Delete -->
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge {{ $block->type == 'link' ? 'badge-light-primary' : 'badge-light-info' }} fw-bold fs-8 px-2.5 py-1">
                                                {{ ucfirst($block->type) }}
                                            </span>

                                            <!-- Toggle Switch -->
                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-35px block-toggle-switch cursor-pointer" type="checkbox" role="switch" data-id="{{ $block->id }}" {{ $block->is_enabled ? 'checked' : '' }} title="{{ $block->is_enabled ? 'Nonaktifkan Blok' : 'Aktifkan Blok' }}">
                                            </div>

                                            <!-- Edit Button -->
                                            @if($block->type == 'link')
                                                <button type="button" class="btn btn-icon btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#editLinkBlockModal-{{ $block->id }}" title="Edit Tautan">
                                                    <i class="ki-outline ki-pencil fs-4"></i>
                                                </button>
                                            @elseif($block->type == 'text')
                                                <button type="button" class="btn btn-icon btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#editTextBlockModal-{{ $block->id }}" title="Edit Teks">
                                                    <i class="ki-outline ki-pencil fs-4"></i>
                                                </button>
                                            @endif

                                            <!-- Delete Button -->
                                            <form action="{{ route('biolinks.blocks.destroy', [$link->id, $block->id]) }}" method="POST" onsubmit="return confirm('Hapus blok ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-light-danger" title="Hapus Blok">
                                                    <i class="ki-outline ki-trash fs-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Edit Link Modal -->
                                    @if($block->type == 'link')
                                        <div class="modal fade" id="editLinkBlockModal-{{ $block->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-3 shadow-lg">
                                                    <form action="{{ route('biolinks.blocks.update', [$link->id, $block->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">Edit Tautan</h5>
                                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                                                                <i class="ki-outline ki-cross fs-2"></i>
                                                            </div>
                                                        </div>
                                                        <div class="modal-body py-5">
                                                            <div class="mb-4">
                                                                <label class="form-label fs-7 fw-semibold text-gray-900 required">Judul Tautan</label>
                                                                <input type="text" name="settings[title]" class="form-control form-control-solid form-control-sm" required placeholder="Cek Promo Terbaru!" value="{{ $block->settings['title'] ?? '' }}">
                                                            </div>
                                                            <div class="mb-4">
                                                                <label class="form-label fs-7 fw-semibold text-gray-900 required">URL Tujuan</label>
                                                                <input type="url" name="location_url" class="form-control form-control-solid form-control-sm" required placeholder="https://example.com/promo" value="{{ $block->location_url ?? '' }}">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="form-label fs-7 fw-semibold text-gray-900">Icon Tautan</label>
                                                                <input type="hidden" name="settings[icon]" class="selected-icon-input" value="{{ $block->settings['icon'] ?? '' }}">
                                                                <div class="d-flex flex-wrap gap-2 p-3 border rounded-3 bg-light" style="max-height: 150px; overflow-y: auto;">
                                                                    <div class="icon-option p-2 rounded-2 border d-flex align-items-center justify-content-center cursor-pointer bg-white {{ empty($block->settings['icon']) ? 'border-primary border-2' : '' }}" data-icon="" title="Tanpa Icon" style="width: 38px; height: 38px;">
                                                                        <i class="ki-outline ki-cross fs-3 text-muted"></i>
                                                                    </div>
                                                                    @foreach(['add_circle', 'airplay', 'alert_octagon', 'alert_triangle', 'align_bottom', 'align_center', 'android', 'app_dots', 'app', 'apple', 'approved', 'appstore', 'award', 'baby_carriage', 'bank', 'battery', 'bell_badge', 'bell', 'book_2', 'book_3', 'book', 'bookmark', 'box_2', 'box', 'bread', 'bridge', 'briefcase', 'brush_2', 'brush', 'bug', 'building', 'bus', 'cake', 'calendar', 'camera_square', 'camera', 'campground', 'candle', 'car', 'certificate', 'chart_pie', 'check_circle', 'chip', 'clapperboard', 'clipboard', 'clock', 'cloud_lightning', 'cloud_snow', 'coin_stack', 'compass', 'computer_camera', 'confetti', 'credit_card', 'currency_euro', 'dashboard', 'discount', 'disk', 'file', 'fire', 'folder_open', 'folder_upload', 'g_translate', 'id_card', 'info', 'lamp_2', 'lamp', 'location', 'marker', 'menu', 'message_2', 'message_3', 'message', 'moon_2', 'moon_stars', 'palette', 'rocket', 'settings', 'shopping_bag', 'slideshow', 'smartphone_vibration', 'smartphone', 'smartwatch', 'sun', 'target', 'toggle', 'translation', 'upload_file', 'user_card', 'user', 'world'] as $iconName)
                                                                        <div class="icon-option p-2 rounded-2 border d-flex align-items-center justify-content-center cursor-pointer bg-white {{ ($block->settings['icon'] ?? '') == $iconName ? 'border-primary border-2' : '' }}" data-icon="{{ $iconName }}" title="{{ $iconName }}" style="width: 38px; height: 38px;">
                                                                            <span data-duo-icons="{{ $iconName }}" style="width: 20px; height: 20px; color: var(--bs-primary);"></span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($block->type == 'text')
                                        <!-- Edit Text Modal -->
                                        <div class="modal fade" id="editTextBlockModal-{{ $block->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-3 shadow-lg">
                                                    <form action="{{ route('biolinks.blocks.update', [$link->id, $block->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">Edit Teks</h5>
                                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                                                                <i class="ki-outline ki-cross fs-2"></i>
                                                            </div>
                                                        </div>
                                                        <div class="modal-body py-5">
                                                            <div class="mb-3">
                                                                <label class="form-label fs-7 fw-semibold text-gray-900 required">Konten Teks</label>
                                                                <textarea name="settings[content]" class="form-control form-control-solid form-control-sm" rows="4" required placeholder="Tulis sesuatu...">{{ $block->settings['content'] ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="card-footer py-3 px-6 d-flex justify-content-between align-items-center">
                        <span class="text-muted fs-7">Total: <strong class="text-gray-900">{{ $blocks->count() }}</strong> blok konten</span>
                        <span class="badge badge-light-success fs-8 fw-semibold d-inline-flex align-items-center gap-1.5 py-1 px-2.5">
                            <span class="bullet bullet-dot bg-success h-6px w-6px"></span>
                            Tersinkronisasi otomatis
                        </span>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Profil -->
            <div class="tab-pane fade" id="profile-pane" role="tabpanel" aria-labelledby="profile-tab">
                <div class="card card-flush shadow-sm border-0 mb-6">
                    <div class="card-header pt-5">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">
                            <i class="ki-outline ki-user fs-3 text-primary me-2"></i> Informasi Profil Biolink
                        </h3>
                    </div>
                    <div class="card-body p-6">
                        <!-- Visual Cover & Avatar Editor Zone -->
                        <div class="card border border-dashed rounded-3 overflow-hidden position-relative mb-6">
                            <div id="coverDropzone" class="position-relative" style="height: 150px; background: {{ isset($link->settings['cover_url']) ? 'url(' . $link->settings['cover_url'] . ') center/cover no-repeat' : 'linear-gradient(135deg, #a4e5bd 0%, #7dd3a1 100%)' }}; cursor: pointer;">
                                <div class="dropzone-overlay d-flex flex-column align-items-center justify-content-center text-white" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.45); opacity: 0; transition: opacity 0.2s ease;">
                                    <i class="ki-outline ki-picture fs-2x mb-1 text-white"></i>
                                    <span class="fs-8 fw-semibold">Ubah Cover (Klik / Seret Foto)</span>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-column align-items-center" style="margin-top: -55px; padding-bottom: 20px;">
                                <div id="avatarDropzone" class="position-relative rounded-circle shadow-sm" style="width: 100px; height: 100px; border: 3px solid #fff; cursor: pointer; overflow: hidden; background: #fff;">
                                    <img id="avatarPreview" src="{{ $link->settings['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($link->settings['title'] ?? 'BL') . '&background=2563eb&color=ffffff&size=128' }}" style="width:100%; height:100%; object-fit:cover;">
                                    <div class="dropzone-overlay d-flex flex-column align-items-center justify-content-center text-white" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.55); opacity: 0; transition: opacity 0.2s ease; border-radius: 50%;">
                                        <i class="ki-outline ki-camera fs-2x mb-1 text-white"></i>
                                        <span style="font-size: 0.65rem;" class="fw-semibold">Ubah Foto</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-1.5 mt-3 mb-1">
                                    <h5 class="fw-bold mb-0 text-gray-900">{{ $link->settings['title'] ?? 'My Biolink' }}</h5>
                                    @if($link->is_verified)
                                        <i class="ki-outline ki-verify fs-4 text-primary" title="Verified Profile"></i>
                                    @endif
                                </div>
                                <p class="text-muted fs-7 mb-0 px-4 text-center text-truncate" style="max-width: 100%;">{{ $link->settings['description'] ?? 'Belum ada deskripsi bio.' }}</p>
                            </div>
                        </div>

                        <input type="file" id="coverInput" class="d-none" accept="image/*">
                        <input type="file" id="avatarInput" class="d-none" accept="image/*">

                        <!-- Profile Edit Form -->
                        <form action="{{ route('biolinks.settings.update', $link->id) }}" method="POST" id="profileTabForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900 required">Nama / Judul Halaman</label>
                                <input type="text" name="title" class="form-control form-control-solid form-control-sm" placeholder="Nama Anda" value="{{ $link->settings['title'] ?? 'My Biolink' }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Deskripsi Singkat (Bio)</label>
                                <textarea name="description" class="form-control form-control-solid form-control-sm" rows="3" placeholder="Tulis bio singkat...">{{ $link->settings['description'] ?? '' }}</textarea>
                            </div>

                            <!-- Toggle Visibility & Verification -->
                            <div class="card bg-light p-4 rounded-3 mb-5 border-0">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <label class="form-check-label fs-7 fw-semibold text-gray-800 mb-0 cursor-pointer" for="showAvatarSwitch">Tampilkan Foto Profil</label>
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" name="settings[show_avatar]" value="0">
                                        <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" role="switch" name="settings[show_avatar]" value="1" id="showAvatarSwitch" {{ ($link->settings['show_avatar'] ?? '1') == '1' ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-2"></div>
                                <div class="d-flex align-items-center justify-content-between mb-3 pt-1">
                                    <label class="form-check-label fs-7 fw-semibold text-gray-800 mb-0 cursor-pointer" for="showCoverSwitch">Tampilkan Foto Sampul</label>
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" name="settings[show_cover]" value="0">
                                        <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" role="switch" name="settings[show_cover]" value="1" id="showCoverSwitch" {{ ($link->settings['show_cover'] ?? '1') == '1' ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-2"></div>
                                <div class="d-flex align-items-center justify-content-between pt-1">
                                    <div>
                                        <label class="form-check-label fs-7 fw-semibold text-gray-800 mb-0 cursor-pointer" for="verifiedBadgeSwitch">
                                            <i class="ki-outline ki-verify fs-4 text-primary me-1"></i> Checklist Verified (Centang Biru)
                                        </label>
                                        <div class="text-muted fs-8">Tampilkan centang biru verified di samping judul biolink.</div>
                                    </div>
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" name="settings[verified_badge]" value="0">
                                        <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" role="switch" name="settings[verified_badge]" value="1" id="verifiedBadgeSwitch" {{ ((!empty($link->settings['verified_badge']) && $link->settings['verified_badge'] == '1') || $link->is_verified) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm fw-bold">Simpan Profil</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Styling -->
            <div class="tab-pane fade" id="styling-pane" role="tabpanel" aria-labelledby="styling-tab">
                <div class="card card-flush shadow-sm border-0 mb-6">
                    <div class="card-header pt-5">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">
                            <i class="ki-outline ki-color-swatch fs-3 text-primary me-2"></i> Kustomisasi Tampilan & Warna
                        </h3>
                    </div>
                    <div class="card-body p-6">
                        <form action="{{ route('biolinks.settings.update', $link->id) }}" method="POST" id="stylingTabForm">
                            @csrf
                            @method('PUT')
                            
                            <input type="hidden" name="title" value="{{ $link->settings['title'] ?? 'My Biolink' }}">
                            <input type="hidden" name="description" value="{{ $link->settings['description'] ?? '' }}">

                            <!-- Presets Section -->
                            <div class="mb-5">
                                <label class="form-label fs-7 fw-semibold text-gray-900 mb-2">Preset Kombinasi Siap Pakai</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                         data-bg-type="gradient"
                                         data-bg-start="#a4e5bd"
                                         data-bg-end="#7dd3a1"
                                         data-btn-bg="#ffffff"
                                         data-btn-text="#111827"
                                         data-text="#111827">
                                        <div style="width: 14px; height: 14px; border-radius: 50%; background: linear-gradient(135deg, #a4e5bd 0%, #7dd3a1 100%); flex-shrink: 0;"></div>
                                        <span class="fs-8 fw-semibold text-gray-800">Mint Tea</span>
                                    </div>

                                    <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                         data-bg-type="gradient"
                                         data-bg-start="#0f172a"
                                         data-bg-end="#1e1b4b"
                                         data-btn-bg="#1e293b"
                                         data-btn-text="#f8fafc"
                                         data-text="#f8fafc">
                                        <div style="width: 14px; height: 14px; border-radius: 50%; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); flex-shrink: 0;"></div>
                                        <span class="fs-8 fw-semibold text-gray-800">Midnight</span>
                                    </div>

                                    <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                         data-bg-type="gradient"
                                         data-bg-start="#ff7e5f"
                                         data-bg-end="#feb47b"
                                         data-btn-bg="#ffffff"
                                         data-btn-text="#ff7e5f"
                                         data-text="#ffffff">
                                        <div style="width: 14px; height: 14px; border-radius: 50%; background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%); flex-shrink: 0;"></div>
                                        <span class="fs-8 fw-semibold text-gray-800">Sunset</span>
                                    </div>

                                    <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                         data-bg-type="gradient"
                                         data-bg-start="#2b5876"
                                         data-bg-end="#4e4376"
                                         data-btn-bg="#ffffff"
                                         data-btn-text="#2b5876"
                                         data-text="#ffffff">
                                        <div style="width: 14px; height: 14px; border-radius: 50%; background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%); flex-shrink: 0;"></div>
                                        <span class="fs-8 fw-semibold text-gray-800">Ocean</span>
                                    </div>

                                    <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                         data-bg-type="solid"
                                         data-bg-color="#f3f4f6"
                                         data-btn-bg="#ffffff"
                                         data-btn-text="#1f2937"
                                         data-text="#1f2937">
                                        <div style="width: 14px; height: 14px; border-radius: 50%; background: #f3f4f6; border: 1px solid rgba(0,0,0,0.1); flex-shrink: 0;"></div>
                                        <span class="fs-8 fw-semibold text-gray-800">Minimalist</span>
                                    </div>

                                    <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                         data-bg-type="solid"
                                         data-bg-color="#121212"
                                         data-btn-bg="#1e1e1e"
                                         data-btn-text="#e0e0e0"
                                         data-text="#e0e0e0">
                                        <div style="width: 14px; height: 14px; border-radius: 50%; background: #121212; flex-shrink: 0;"></div>
                                        <span class="fs-8 fw-semibold text-gray-800">Obsidian</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Tipe Background</label>
                                <select name="settings[bg_type]" id="bgTypeSelectorTab" class="form-select form-select-solid form-select-sm">
                                    <option value="solid" {{ ($link->settings['bg_type'] ?? 'solid') == 'solid' ? 'selected' : '' }}>Warna Solid</option>
                                    <option value="gradient" {{ ($link->settings['bg_type'] ?? 'solid') == 'gradient' ? 'selected' : '' }}>Warna Gradasi (Gradient)</option>
                                    <option value="abstract_blobs" {{ ($link->settings['bg_type'] ?? 'solid') == 'abstract_blobs' ? 'selected' : '' }}>Abstract Blobs (Mesh Gradient)</option>
                                </select>
                            </div>

                            <!-- Solid Background Color Input -->
                            <div class="mb-4" id="solidBgWrapperTab">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Warna Background</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                        <input type="color" name="settings[bg_color]" class="color-picker-input" value="{{ $link->settings['bg_color'] ?? '#f3f4f1' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                    </div>
                                    <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_color'] ?? '#f3f4f1' }}" style="width: 110px; font-family: monospace; text-align: center;">
                                </div>
                            </div>

                            <!-- Gradient Background Color Inputs -->
                            <div class="mb-4 d-none" id="gradientBgWrapperTab">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label fs-7 fw-semibold text-gray-900">Gradasi Mulai</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                                <input type="color" name="settings[bg_gradient_start]" class="color-picker-input" value="{{ $link->settings['bg_gradient_start'] ?? '#a4e5bd' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                            </div>
                                            <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_gradient_end'] ?? '#a4e5bd' }}" style="font-family: monospace; text-align: center;">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fs-7 fw-semibold text-gray-900">Gradasi Selesai</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                                <input type="color" name="settings[bg_gradient_end]" class="color-picker-input" value="{{ $link->settings['bg_gradient_end'] ?? '#7dd3a1' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                            </div>
                                            <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_gradient_end'] ?? '#7dd3a1' }}" style="font-family: monospace; text-align: center;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Abstract Blobs Settings -->
                            <div id="abstractBlobsWrapperTab" class="d-none mb-4">
                                <div class="mb-3">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Warna Dasar Latar Belakang</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                            <input type="color" name="settings[bg_blob_base]" class="color-picker-input" value="{{ $link->settings['bg_blob_base'] ?? '#f8fafc' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                        </div>
                                        <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_blob_base'] ?? '#f8fafc' }}" style="width: 110px; font-family: monospace; text-align: center;">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label class="form-label fs-7 fw-semibold text-gray-900">Blob 1</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                                <input type="color" name="settings[bg_blob_1]" class="color-picker-input" value="{{ $link->settings['bg_blob_1'] ?? '#3b82f6' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                            </div>
                                            <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_blob_1'] ?? '#3b82f6' }}" style="font-family: monospace; text-align: center;">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label fs-7 fw-semibold text-gray-900">Blob 2</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                                <input type="color" name="settings[bg_blob_2]" class="color-picker-input" value="{{ $link->settings['bg_blob_2'] ?? '#ec4899' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                            </div>
                                            <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_blob_2'] ?? '#ec4899' }}" style="font-family: monospace; text-align: center;">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label fs-7 fw-semibold text-gray-900">Blob 3</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                                <input type="color" name="settings[bg_blob_3]" class="color-picker-input" value="{{ $link->settings['bg_blob_3'] ?? '#8b5cf6' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                            </div>
                                            <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_blob_3'] ?? '#8b5cf6' }}" style="font-family: monospace; text-align: center;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-5">
                                <div class="col-4">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Warna Tombol</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                            <input type="color" name="settings[btn_bg_color]" class="color-picker-input" value="{{ $link->settings['btn_bg_color'] ?? '#ffffff' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                        </div>
                                        <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['btn_bg_color'] ?? '#ffffff' }}" style="font-family: monospace; text-align: center;">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Teks Tombol</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                            <input type="color" name="settings[btn_text_color]" class="color-picker-input" value="{{ $link->settings['btn_text_color'] ?? '#111827' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                        </div>
                                        <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['btn_text_color'] ?? '#111827' }}" style="font-family: monospace; text-align: center;">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Teks Profil</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                            <input type="color" name="settings[text_color]" class="color-picker-input" value="{{ $link->settings['text_color'] ?? '#111827' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                        </div>
                                        <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['text_color'] ?? '#111827' }}" style="font-family: monospace; text-align: center;">
                                    </div>
                                </div>
                            </div>

                            <!-- Avatar Border & Shape Customization -->
                            <div class="separator separator-dashed my-5"></div>
                            <div class="mb-5">
                                <h4 class="text-gray-900 fw-bold fs-6 mb-3">
                                    <i class="ki-outline ki-user-square fs-4 text-primary me-1"></i> Border & Bentuk Foto Profil (Avatar)
                                </h4>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label fs-7 fw-semibold text-gray-900">Ketebalan Border</label>
                                        <select name="settings[avatar_border_width]" id="avatarBorderWidthSelector" class="form-select form-select-solid form-select-sm">
                                            <option value="0px" {{ ($link->settings['avatar_border_width'] ?? '4px') == '0px' ? 'selected' : '' }}>0px (Tanpa Border)</option>
                                            <option value="1px" {{ ($link->settings['avatar_border_width'] ?? '4px') == '1px' ? 'selected' : '' }}>1px (Sangat Tipis)</option>
                                            <option value="2px" {{ ($link->settings['avatar_border_width'] ?? '4px') == '2px' ? 'selected' : '' }}>2px (Tipis)</option>
                                            <option value="3px" {{ ($link->settings['avatar_border_width'] ?? '4px') == '3px' ? 'selected' : '' }}>3px (Sedang)</option>
                                            <option value="4px" {{ ($link->settings['avatar_border_width'] ?? '4px') == '4px' ? 'selected' : '' }}>4px (Standar / Tebal)</option>
                                            <option value="6px" {{ ($link->settings['avatar_border_width'] ?? '4px') == '6px' ? 'selected' : '' }}>6px (Sangat Tebal)</option>
                                            <option value="8px" {{ ($link->settings['avatar_border_width'] ?? '4px') == '8px' ? 'selected' : '' }}>8px (Ekstra Tebal)</option>
                                            <option value="10px" {{ ($link->settings['avatar_border_width'] ?? '4px') == '10px' ? 'selected' : '' }}>10px (Maksimal)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fs-7 fw-semibold text-gray-900">Warna Border Avatar</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="position-relative" style="width: 36px; height: 36px; border-radius: 8px; overflow: hidden; border: 1px solid var(--bs-border-color); flex-shrink: 0;">
                                                <input type="color" name="settings[avatar_border_color]" class="color-picker-input" value="{{ $link->settings['avatar_border_color'] ?? '#ffffff' }}" style="position: absolute; top: -10px; left: -10px; width: 56px; height: 56px; border: none; padding: 0; cursor: pointer;">
                                            </div>
                                            <input type="text" class="form-control form-control-solid form-control-sm text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['avatar_border_color'] ?? '#ffffff' }}" style="font-family: monospace; text-align: center;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fs-7 fw-semibold text-gray-900">Bentuk Sudut (Radius)</label>
                                        <select name="settings[avatar_border_radius]" id="avatarBorderRadiusSelector" class="form-select form-select-solid form-select-sm">
                                            <option value="50%" {{ ($link->settings['avatar_border_radius'] ?? '50%') == '50%' ? 'selected' : '' }}>Bulat Penuh (Circle)</option>
                                            <option value="24px" {{ ($link->settings['avatar_border_radius'] ?? '50%') == '24px' ? 'selected' : '' }}>Membulat Halus (Rounded)</option>
                                            <option value="12px" {{ ($link->settings['avatar_border_radius'] ?? '50%') == '12px' ? 'selected' : '' }}>Sedikit Membulat (12px)</option>
                                            <option value="0px" {{ ($link->settings['avatar_border_radius'] ?? '50%') == '0px' ? 'selected' : '' }}>Kotak Persegi (Square)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Verified Badge & Branding Settings -->
                            <div class="separator separator-dashed my-5"></div>
                            <div class="mb-5">
                                <h4 class="text-gray-900 fw-bold fs-6 mb-3">
                                    <i class="ki-outline ki-shield-tick fs-4 text-primary me-1"></i> Verifikasi & Footer Branding
                                </h4>
                                <div class="card bg-light p-4 rounded-3 border-0">
                                    <!-- Checklist Verified Toggle -->
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <label class="form-check-label fs-7 fw-semibold text-gray-800 mb-0 cursor-pointer" for="verifiedBadgeSwitchStyling">
                                                <i class="ki-outline ki-verify fs-5 text-primary me-1"></i> Checklist Verified (Centang Biru)
                                            </label>
                                            <div class="text-muted fs-8">Tampilkan centang verified resmi di samping nama/judul.</div>
                                        </div>
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input type="hidden" name="settings[verified_badge]" value="0">
                                            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" role="switch" name="settings[verified_badge]" value="1" id="verifiedBadgeSwitchStyling" {{ ((!empty($link->settings['verified_badge']) && $link->settings['verified_badge'] == '1') || $link->is_verified) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    
                                    <div class="separator separator-dashed my-2"></div>

                                    <!-- Hide Branding Switch -->
                                    <div class="d-flex align-items-center justify-content-between pt-1">
                                        <div>
                                            <label class="form-check-label fs-7 fw-semibold text-gray-800 mb-0 cursor-pointer" for="hideBrandingSwitch">
                                                <i class="ki-outline ki-eye-slash fs-5 text-danger me-1"></i> Sembunyikan "Powered by Newlink"
                                            </label>
                                            <div class="text-muted fs-8">Hapus teks watermark branding Newlink dari bagian bawah biolink.</div>
                                        </div>
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input type="hidden" name="settings[hide_branding]" value="0">
                                            <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" role="switch" name="settings[hide_branding]" value="1" id="hideBrandingSwitch" {{ ($link->settings['hide_branding'] ?? '0') == '1' ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm fw-bold">Simpan Tampilan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 4: Pratinjau (Mobile Only) -->
            <div class="tab-pane fade d-md-none" id="preview-pane" role="tabpanel" aria-labelledby="preview-tab">
                <div class="d-flex justify-content-center">
                    <div class="mockup-container position-relative shadow-2xl overflow-hidden" style="width: 375px; height: 720px; border-radius: 36px; border: 12px solid #111827; background: #000; flex-shrink:0;">
                        <!-- Camera Notch -->
                        <div class="position-absolute start-50 translate-middle-x" style="top: 0; width: 120px; height: 20px; background: #111827; border-radius: 0 0 12px 12px; z-index: 5;"></div>
                        <iframe src="{{ $previewUrl }}" class="w-100 h-100 border-0 bg-white" style="border-radius: 24px;"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Right Column: Desktop Preview Panel (d-none on mobile) -->
    <div class="col-lg-5 col-xl-4 d-none d-lg-flex justify-content-center align-items-start ps-lg-5 pb-10" style="padding-top: 58px;">
        <div class="mockup-container position-relative shadow-2xl overflow-hidden" style="width: 340px; height: calc(100vh - 200px); max-height: 660px; min-height: 500px; border-radius: 36px; border: 12px solid #111827; background: #000; flex-shrink: 0; position: sticky; top: 90px; z-index: 5; margin-bottom: 2rem;">
            <!-- Camera Notch -->
            <div class="position-absolute start-50 translate-middle-x" style="top: 0; width: 120px; height: 20px; background: #111827; border-radius: 0 0 12px 12px; z-index: 5;"></div>
            <iframe src="{{ $previewUrl }}" class="w-100 h-100 border-0 bg-white" style="border-radius: 24px;"></iframe>
        </div>
    </div>
</div>

<!-- Modal Add Link -->
<div class="modal fade" id="addLinkBlockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-lg">
            <form action="{{ route('biolinks.blocks.store', $link->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="link">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Tautan</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </div>
                </div>
                <div class="modal-body py-5">
                    <div class="mb-4">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Judul Tautan</label>
                        <input type="text" name="settings[title]" class="form-control form-control-solid form-control-sm" required placeholder="Cek Promo Terbaru!">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">URL Tujuan</label>
                        <input type="url" name="location_url" class="form-control form-control-solid form-control-sm" required placeholder="https://example.com/promo">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fs-7 fw-semibold text-gray-900">Icon Tautan</label>
                        <input type="hidden" name="settings[icon]" class="selected-icon-input" value="">
                        <div class="d-flex flex-wrap gap-2 p-3 border rounded-3 bg-light" style="max-height: 150px; overflow-y: auto;">
                            <div class="icon-option p-2 rounded-2 border d-flex align-items-center justify-content-center cursor-pointer bg-white border-primary border-2" data-icon="" title="Tanpa Icon" style="width: 38px; height: 38px;">
                                <i class="ki-outline ki-cross fs-3 text-muted"></i>
                            </div>
                            @foreach(['add_circle', 'airplay', 'alert_octagon', 'alert_triangle', 'align_bottom', 'align_center', 'android', 'app_dots', 'app', 'apple', 'approved', 'appstore', 'award', 'baby_carriage', 'bank', 'battery', 'bell_badge', 'bell', 'book_2', 'book_3', 'book', 'bookmark', 'box_2', 'box', 'bread', 'bridge', 'briefcase', 'brush_2', 'brush', 'bug', 'building', 'bus', 'cake', 'calendar', 'camera_square', 'camera', 'campground', 'candle', 'car', 'certificate', 'chart_pie', 'check_circle', 'chip', 'clapperboard', 'clipboard', 'clock', 'cloud_lightning', 'cloud_snow', 'coin_stack', 'compass', 'computer_camera', 'confetti', 'credit_card', 'currency_euro', 'dashboard', 'discount', 'disk', 'file', 'fire', 'folder_open', 'folder_upload', 'g_translate', 'id_card', 'info', 'lamp_2', 'lamp', 'location', 'marker', 'menu', 'message_2', 'message_3', 'message', 'moon_2', 'moon_stars', 'palette', 'rocket', 'settings', 'shopping_bag', 'slideshow', 'smartphone_vibration', 'smartphone', 'smartwatch', 'sun', 'target', 'toggle', 'translation', 'upload_file', 'user_card', 'user', 'world'] as $iconName)
                                <div class="icon-option p-2 rounded-2 border d-flex align-items-center justify-content-center cursor-pointer bg-white" data-icon="{{ $iconName }}" title="{{ $iconName }}" style="width: 38px; height: 38px;">
                                    <span data-duo-icons="{{ $iconName }}" style="width: 20px; height: 20px; color: var(--bs-primary);"></span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Tambah Tautan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Text -->
<div class="modal fade" id="addTextBlockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-lg">
            <form action="{{ route('biolinks.blocks.store', $link->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="text">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Teks</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </div>
                </div>
                <div class="modal-body py-5">
                    <div class="mb-3">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Konten Teks</label>
                        <textarea name="settings[content]" class="form-control form-control-solid form-control-sm" rows="4" required placeholder="Tulis sesuatu yang menarik..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Tambah Teks</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Image Cropper -->
<div class="modal fade" id="cropperModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="cropperModalTitle">Sesuaikan Gambar</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <div class="modal-body py-4">
                <div class="cropper-container-wrapper rounded-3 border">
                    <img id="cropperImage" src="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="cropAndSaveBtn" class="btn btn-primary btn-sm fw-bold">Potong & Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success toast with Metronic style
    function showSuccessToast(message) {
        const toast = $('<div class="position-fixed bottom-0 end-0 p-4" style="z-index: 9999;">' +
            '<div class="toast show align-items-center text-white bg-success border-0 shadow-lg rounded-3" role="alert">' +
            '<div class="d-flex py-3 px-4 align-items-center gap-2">' +
            '<i class="ki-outline ki-check-circle fs-2 text-white"></i>' +
            '<span class="fw-semibold fs-7">' + message + '</span>' +
            '</div>' +
            '</div>' +
            '</div>');
        $('body').append(toast);
        setTimeout(() => {
            toast.fadeOut(300, function() { $(this).remove(); });
        }, 2500);
    }

    // Initialize SortableJS for block sorting
    function initializeSortable() {
        const el = document.getElementById('blocks-container');
        if (el) {
            new Sortable(el, {
                animation: 150,
                ghostClass: 'bg-light',
                handle: '.drag-handle',
                onEnd: function() {
                    const orders = {};
                    $('#blocks-container .block-row').each(function(index) {
                        const blockId = $(this).attr('data-id');
                        orders[blockId] = index;
                    });

                    $.ajax({
                        url: '{{ route('biolinks.blocks.reorder', $link->id) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            orders: orders
                        },
                        dataType: 'json',
                        success: function(response) {
                            refreshBuilderUI(response.message || 'Urutan berhasil disimpan!');
                        },
                        error: function() {
                            alert('Gagal memperbarui urutan blok.');
                        }
                    });
                }
            });
        }
    }

    // Initial SortableJS load
    initializeSortable();

    // Toggle solid vs gradient background color picker in styling tab form
    function toggleBgSettings() {
        const bgType = $('#bgTypeSelectorTab').val();
        if (bgType === 'gradient') {
            $('#solidBgWrapperTab').addClass('d-none');
            $('#gradientBgWrapperTab').removeClass('d-none');
            $('#abstractBlobsWrapperTab').addClass('d-none');
        } else if (bgType === 'abstract_blobs') {
            $('#solidBgWrapperTab').addClass('d-none');
            $('#gradientBgWrapperTab').addClass('d-none');
            $('#abstractBlobsWrapperTab').removeClass('d-none');
        } else {
            $('#solidBgWrapperTab').removeClass('d-none');
            $('#gradientBgWrapperTab').addClass('d-none');
            $('#abstractBlobsWrapperTab').addClass('d-none');
        }
    }

    // Call on dropdown change
    $('#bgTypeSelectorTab').on('change', function() {
        toggleBgSettings();
        syncStylingToPreview();
    });
    
    // Run initially to match loaded settings
    toggleBgSettings();

    // Preset theme card click listener
    $('.preset-card').on('click', function() {
        $('.preset-card').removeClass('selected');
        $(this).addClass('selected');
        
        const bgType = $(this).attr('data-bg-type');
        const bgStart = $(this).attr('data-bg-start');
        const bgEnd = $(this).attr('data-bg-end');
        const bgColor = $(this).attr('data-bg-color') || bgStart;
        const btnBg = $(this).attr('data-btn-bg');
        const btnText = $(this).attr('data-btn-text');
        const text = $(this).attr('data-text');

        // Update input values
        $('#bgTypeSelectorTab').val(bgType);
        toggleBgSettings();

        $('input[name="settings[bg_color]"]').val(bgColor).closest('.d-flex').find('.color-hex-text').val(bgColor.toUpperCase());
        $('input[name="settings[bg_gradient_start]"]').val(bgStart).closest('.d-flex').find('.color-hex-text').val(bgStart.toUpperCase());
        $('input[name="settings[bg_gradient_end]"]').val(bgEnd).closest('.d-flex').find('.color-hex-text').val(bgEnd.toUpperCase());
        $('input[name="settings[btn_bg_color]"]').val(btnBg).closest('.d-flex').find('.color-hex-text').val(btnBg.toUpperCase());
        $('input[name="settings[btn_text_color]"]').val(btnText).closest('.d-flex').find('.color-hex-text').val(btnText.toUpperCase());
        $('input[name="settings[text_color]"]').val(text).closest('.d-flex').find('.color-hex-text').val(text.toUpperCase());

        syncStylingToPreview();
    });

    // Sync custom color pickers and text inputs in real-time
    $(document).on('input', '.color-picker-input', function() {
        const hex = $(this).val().toUpperCase();
        $(this).closest('.d-flex').find('.color-hex-text').val(hex);
        syncStylingToPreview();
    });

    $(document).on('input', '.color-hex-text', function() {
        let val = $(this).val().trim();
        if (val && !val.startsWith('#')) {
            val = '#' + val;
        }
        if (/^#[0-9A-F]{6}$/i.test(val)) {
            $(this).closest('.d-flex').find('.color-picker-input').val(val);
            syncStylingToPreview();
        }
    });

    // Handle Block Active / Inactive Toggle Switch
    $(document).on('change', '.block-toggle-switch', function() {
        const blockId = $(this).data('id');
        const isChecked = $(this).is(':checked') ? 1 : 0;
        
        $.ajax({
            url: `/biolink/{{ $link->id }}/blocks/${blockId}/toggle`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    const iframes = document.querySelectorAll('iframe');
                    iframes.forEach(ifr => {
                        ifr.contentWindow.location.reload();
                    });
                }
            },
            error: function() {
                $(`.block-toggle-switch[data-id="${blockId}"]`).prop('checked', !isChecked);
                alert('Gagal memperbarui status aktif/nonaktif blok.');
            }
        });
    });

    // Sync title & description inputs between Profile and Styling forms in real-time
    $('input[name="title"]').on('input', function() {
        const val = $(this).val();
        $('input[name="title"]').val(val);
        syncStylingToPreview();
    });
    $('textarea[name="description"]').on('input', function() {
        const val = $(this).val();
        $('textarea[name="description"]').val(val);
        syncStylingToPreview();
    });

    // Save and persist active tab ID in session storage to survive page reloads
    $('#builderTabs [data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        sessionStorage.setItem('active_builder_tab', e.target.id);
    });

    // Restore active tab on load
    const activeTabId = sessionStorage.getItem('active_builder_tab');
    if (activeTabId) {
        const tabEl = document.getElementById(activeTabId);
        if (tabEl) {
            const tab = new bootstrap.Tab(tabEl);
            tab.show();
        }
    }

    // Refresh layout, reload iframe
    function refreshBuilderUI(successMessage) {
        const iframes = document.querySelectorAll('iframe');
        iframes.forEach(ifr => {
            ifr.contentWindow.location.reload();
        });
        
        // Reload blocks list dynamically
        $('#blocks-container-wrapper').load(window.location.href + ' #blocks-container-wrapper > *', function() {
            if (window.DuoIcons) {
                DuoIcons.createIcons({
                    icons: DuoIcons.icons
                });
            }
            initializeSortable();
        });

        if (successMessage) {
            showSuccessToast(successMessage);
        }
    }

    // Intercept form submissions via AJAX (Add Block, Edit Block, Profile Form, Styling Form)
    $(document).on('submit', '#addLinkBlockModal form, #addTextBlockModal form, [id^=editLinkBlockModal] form, [id^=editTextBlockModal] form, #profileTabForm, #stylingTabForm', function(e) {
        e.preventDefault();
        const form = $(this);
        const modalEl = form.closest('.modal')[0];
        const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.text();

        submitBtn.prop('disabled', true).text('Menyimpan...');
        const formData = new FormData(form[0]);

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method') || 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            dataType: 'json',
            success: function(response) {
                if (modal) {
                    modal.hide();
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');
                }
                refreshBuilderUI(response.message || 'Berhasil disimpan!');
            },
            error: function(xhr) {
                let errMsg = 'Terjadi kesalahan, silakan coba lagi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                Swal.fire({
                    html: errMsg,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: { confirmButton: 'btn btn-primary btn-sm' }
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Block delete handler
    $(document).on('submit', '#blocks-container-wrapper form', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            dataType: 'json',
            success: function(response) {
                refreshBuilderUI(response.message || 'Blok berhasil dihapus!');
            },
            error: function(xhr) {
                let errMsg = 'Gagal menghapus blok.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    text: errMsg,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: { confirmButton: 'btn btn-primary btn-sm' }
                });
            }
        });
    });

    // ──────────────────────────────────────────────────────────────────────────
    // CROPPING & DRAG-AND-DROP FILE UPLOAD LOGIC
    // ──────────────────────────────────────────────────────────────────────────
    let cropper = null;
    let targetType = 'avatar'; // 'avatar' or 'cover'
    const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
    const cropperImage = document.getElementById('cropperImage');
    const cropAndSaveBtn = document.getElementById('cropAndSaveBtn');

    // Trigger file dialog on click
    $('#avatarDropzone').on('click', function() { $('#avatarInput').click(); });
    $('#coverDropzone').on('click', function() { $('#coverInput').click(); });

    // Handle file selection
    $('#avatarInput').on('change', function(e) { handleFileSelect(e.target.files[0], 'avatar'); });
    $('#coverInput').on('change', function(e) { handleFileSelect(e.target.files[0], 'cover'); });

    // Drag and drop listeners
    setupDragAndDrop(document.getElementById('avatarDropzone'), 'avatar');
    setupDragAndDrop(document.getElementById('coverDropzone'), 'cover');

    function setupDragAndDrop(element, type) {
        if (!element) return;
        element.addEventListener('dragover', (e) => {
            e.preventDefault();
            element.classList.add('border-primary');
        });
        element.addEventListener('dragleave', () => {
            element.classList.remove('border-primary');
        });
        element.addEventListener('drop', (e) => {
            e.preventDefault();
            element.classList.remove('border-primary');
            if (e.dataTransfer.files.length > 0) {
                handleFileSelect(e.dataTransfer.files[0], type);
            }
        });
    }

    function handleFileSelect(file, type) {
        if (!file || !file.type.match(/^image\//)) {
            alert('Silakan pilih file gambar yang valid.');
            return;
        }

        targetType = type;
        const reader = new FileReader();
        reader.onload = function(e) {
            cropperImage.src = e.target.result;
            document.getElementById('cropperModalTitle').textContent = type === 'avatar' ? 'Sesuaikan Foto Profil' : 'Sesuaikan Sampul Belakang';
            cropperModal.show();
        };
        reader.readAsDataURL(file);
    }

    // Initialize Cropper when modal finishes showing
    document.getElementById('cropperModal').addEventListener('shown.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
        }

        cropper = new Cropper(cropperImage, {
            aspectRatio: targetType === 'avatar' ? 1 : (16 / 6),
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 1,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false
        });
    });

    // Cleanup Cropper when modal is hidden
    document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        $('#avatarInput').val('');
        $('#coverInput').val('');
    });

    // Save Cropped image via AJAX
    cropAndSaveBtn.addEventListener('click', function() {
        if (!cropper) return;

        cropAndSaveBtn.disabled = true;
        cropAndSaveBtn.textContent = 'Menyimpan...';

        const canvas = cropper.getCroppedCanvas({
            width: targetType === 'avatar' ? 300 : 960,
            height: targetType === 'avatar' ? 300 : 360,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append(targetType, blob, targetType + '_cropped.png');
            
            formData.append('title', $('input[name="title"]').first().val() || '{{ $link->settings['title'] ?? '' }}');
            formData.append('description', $('textarea[name="description"]').first().val() || '{{ $link->settings['description'] ?? '' }}');
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');

            $.ajax({
                url: '{{ route('biolinks.settings.update', $link->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    cropperModal.hide();
                    showSuccessToast(response.message || 'Gambar berhasil diperbarui!');
                    
                    setTimeout(() => {
                        location.reload();
                    }, 1200);
                },
                error: function() {
                    alert('Gagal mengunggah foto. Silakan coba lagi.');
                    cropAndSaveBtn.disabled = false;
                    cropAndSaveBtn.textContent = 'Potong & Simpan';
                }
            });
        }, 'image/png');
    });

    // Icon Option Click Selector inside modals
    $(document).on('click', '.icon-option', function() {
        const parent = $(this).parent();
        parent.find('.icon-option').removeClass('border-primary border-2');
        $(this).addClass('border-primary border-2');
        
        const iconVal = $(this).attr('data-icon');
        parent.siblings('.selected-icon-input').val(iconVal);
    });

    // ──────────────────────────────────────────────────────────────────────────
    // AUTO PREVIEW FOR PROFILE & STYLING TABS
    // ──────────────────────────────────────────────────────────────────────────
    function syncStylingToPreview() {
        const iframes = document.querySelectorAll('iframe');
        iframes.forEach(iframe => {
            if (!iframe) return;
            
            let iframeDoc = null;
            try {
                iframeDoc = iframe.contentDocument || (iframe.contentWindow ? iframe.contentWindow.document : null);
            } catch (e) {
                console.warn("Cannot access iframe document:", e);
                return;
            }

            if (!iframeDoc || !iframeDoc.body) return;
            
            try {
                const titleVal = $('input[name="title"]').first().val();
                const descVal = $('textarea[name="description"]').first().val();
                const bgType = $('#bgTypeSelectorTab').val();
                const bgColor = $('input[name="settings[bg_color]"]').val();
                const bgStart = $('input[name="settings[bg_gradient_start]"]').val();
                const bgEnd = $('input[name="settings[bg_gradient_end]"]').val();
                const btnBg = $('input[name="settings[btn_bg_color]"]').val();
                const btnText = $('input[name="settings[btn_text_color]"]').val();
                const textColor = $('input[name="settings[text_color]"]').val();
                
                // 1. Cover & Avatar visibility & border sync
                const showAvatar = $('#showAvatarSwitch').is(':checked');
                const showCover = $('#showCoverSwitch').is(':checked');
                const avatarBorderWidth = $('select[name="settings[avatar_border_width]"]').val() || '4px';
                const avatarBorderColor = $('input[name="settings[avatar_border_color]"]').val() || '#ffffff';
                const avatarBorderRadius = $('select[name="settings[avatar_border_radius]"]').val() || '50%';
                
                const coverEl = iframeDoc.querySelector('.cover-photo-full');
                const contentEl = iframeDoc.querySelector('.biolink-content');
                const avatarEl = iframeDoc.querySelector('.profile-image');
                
                if (coverEl) {
                    coverEl.style.display = showCover ? 'block' : 'none';
                }
                if (contentEl) {
                    contentEl.style.marginTop = showCover ? '-65px' : '40px';
                }
                if (avatarEl) {
                    avatarEl.style.display = showAvatar ? 'block' : 'none';
                    avatarEl.style.border = `${avatarBorderWidth} solid ${avatarBorderColor}`;
                    avatarEl.style.borderRadius = avatarBorderRadius;
                }
                
                // 2. Title, Description text & Verified Badge sync
                const pTitleText = iframeDoc.querySelector('.profile-title-text');
                if (pTitleText && titleVal !== undefined) {
                    pTitleText.textContent = (titleVal && titleVal.trim() !== '') ? titleVal : 'My Biolink';
                }
                const pDesc = iframeDoc.querySelector('.profile-desc');
                if (pDesc && descVal !== undefined) {
                    pDesc.textContent = descVal;
                }
                const isVerified = $('#verifiedBadgeSwitch').is(':checked') || $('#verifiedBadgeSwitchStyling').is(':checked');
                const verifiedBadgeEl = iframeDoc.querySelector('.verified-badge-icon');
                if (verifiedBadgeEl) {
                    verifiedBadgeEl.style.display = isVerified ? 'inline-block' : 'none';
                }

                // 3. Update Background
                const blobContainer = iframeDoc.querySelector('.blob-bg-container');
                if (bgType === 'abstract_blobs') {
                    if (blobContainer) blobContainer.style.display = 'block';
                    const baseColor = $('input[name="settings[bg_blob_base]"]').val() || '#f8fafc';
                    iframeDoc.body.style.background = baseColor;
                    
                    const blob1 = iframeDoc.querySelector('.blob-1');
                    const blob2 = iframeDoc.querySelector('.blob-2');
                    const blob3 = iframeDoc.querySelector('.blob-3');
                    const b1Color = $('input[name="settings[bg_blob_1]"]').val() || '#3b82f6';
                    const b2Color = $('input[name="settings[bg_blob_2]"]').val() || '#ec4899';
                    const b3Color = $('input[name="settings[bg_blob_3]"]').val() || '#8b5cf6';
                    if (blob1) blob1.style.background = b1Color;
                    if (blob2) blob2.style.background = b2Color;
                    if (blob3) blob3.style.background = b3Color;
                } else {
                    if (blobContainer) blobContainer.style.display = 'none';
                    if (bgType === 'gradient') {
                        iframeDoc.body.style.background = `linear-gradient(135deg, ${bgStart} 0%, ${bgEnd} 100%)`;
                    } else {
                        iframeDoc.body.style.background = bgColor;
                    }
                }
                
                // 4. Update Button styles
                const buttons = iframeDoc.querySelectorAll('.block-link');
                buttons.forEach(btn => {
                    if (btnBg) btn.style.background = btnBg;
                    if (btnText) btn.style.color = btnText;
                });
                
                // 5. Update Text colors & Branding Footer visibility
                const hideBranding = $('#hideBrandingSwitch').is(':checked');
                const watermark = iframeDoc.querySelector('.watermark');
                if (watermark) {
                    watermark.style.display = hideBranding ? 'none' : 'block';
                }

                if (textColor) {
                    iframeDoc.body.style.color = textColor;
                    const pTitle = iframeDoc.querySelector('.profile-title');
                    if (pTitle) pTitle.style.color = textColor;
                    if (pDesc) pDesc.style.color = textColor;
                    if (watermark) watermark.style.color = textColor;
                    const bTexts = iframeDoc.querySelectorAll('.block-text');
                    bTexts.forEach(txt => txt.style.color = textColor);
                }
            } catch (e) {
                console.error("Auto-preview sync error:", e);
            }
        });
    }

    // Trigger preview sync on input/select change across Profile and Styling tabs
    $(document).on('input change', '#styling-pane input, #styling-pane select, #profile-pane input, #profile-pane textarea', function() {
        syncStylingToPreview();
    });

    // Listen to profile & verified visibility switches
    $(document).on('change', '#showAvatarSwitch, #showCoverSwitch, #hideBrandingSwitch', function() {
        syncStylingToPreview();
    });

    $(document).on('change', '#verifiedBadgeSwitch', function() {
        $('#verifiedBadgeSwitchStyling').prop('checked', $(this).is(':checked'));
        syncStylingToPreview();
    });

    $(document).on('change', '#verifiedBadgeSwitchStyling', function() {
        $('#verifiedBadgeSwitch').prop('checked', $(this).is(':checked'));
        syncStylingToPreview();
    });

    // Handle initial preview iframe load sync
    $('iframe').on('load', function() {
        syncStylingToPreview();
    });

    // Also run sync after slight delay on page load
    setTimeout(syncStylingToPreview, 600);
});
</script>
<script src="{{ asset('js/duo-icons.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.DuoIcons) {
            DuoIcons.createIcons({ icons: DuoIcons.icons });
        }
    });
</script>
@endsection
