@extends('layouts.app')

@section('title', 'Biolink Builder')

@section('content')
@php
    $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
@endphp

<!-- CSS for Cropper.js & Interactive Editor -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
    /* Tab customizations matching theme */
    #builderTabs .nav-link {
        background-color: transparent;
        color: var(--text-secondary);
        transition: all 0.2s ease;
        margin-right: 4px;
        border-radius: 8px !important;
    }
    #builderTabs .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.03);
        color: var(--text-primary);
    }
    #builderTabs .nav-link.active {
        background-color: var(--primary-color) !important;
        color: var(--active-text) !important;
    }
    [data-bs-theme="dark"] #builderTabs .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Preset theme card highlights */
    .preset-card {
        cursor: pointer;
        border: 1px solid rgba(0, 0, 0, 0.08);
        background: var(--card-bg-blur);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 8px 16px;
        border-radius: 50px !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        user-select: none;
    }
    .preset-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary-color) !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        background-color: rgba(0, 0, 0, 0.01);
    }
    .preset-card.selected {
        border-color: var(--primary-color) !important;
        background-color: var(--primary-light) !important;
        box-shadow: inset 0 0 0 1px var(--primary-color);
    }
    [data-bs-theme="dark"] .preset-card {
        border-color: rgba(255, 255, 255, 0.08);
    }
    [data-bs-theme="dark"] .preset-card:hover {
        background-color: rgba(255, 255, 255, 0.02);
    }
    [data-bs-theme="dark"] .preset-card.selected {
        background-color: rgba(56, 232, 173, 0.15) !important;
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

    /* Floating Action Button (FAB) Speed Dial styles */
    .fab-wrapper:hover .fab-menu,
    .fab-wrapper.active .fab-menu {
        opacity: 1 !important;
        transform: translateY(0) !important;
        pointer-events: auto !important;
    }
    .fab-wrapper:hover #fabIcon,
    .fab-wrapper.active #fabIcon {
        transform: rotate(45deg);
    }

    /* Clean block row styles */
    .block-row {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
        background: transparent;
        transition: background-color 0.2s ease;
    }
    .block-row:hover {
        background-color: rgba(0, 0, 0, 0.02) !important;
    }
    [data-bs-theme="dark"] .block-row:hover {
        background-color: rgba(255, 255, 255, 0.025) !important;
    }

    /* Glassmorphism buttons */
    .btn-glass {
        background: rgba(255, 255, 255, 0.3) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border: 1px solid rgba(255, 255, 255, 0.45) !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03) !important;
        transition: all 0.2s ease !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-glass-edit {
        color: #4f46e5 !important;
    }
    .btn-glass-edit:hover {
        background: rgba(79, 70, 229, 0.12) !important;
        border-color: rgba(79, 70, 229, 0.25) !important;
        transform: translateY(-1px);
    }
    .btn-glass-delete {
        color: #ef4444 !important;
    }
    .btn-glass-delete:hover {
        background: rgba(239, 68, 68, 0.12) !important;
        border-color: rgba(239, 68, 68, 0.25) !important;
        transform: translateY(-1px);
    }

    [data-bs-theme="dark"] .btn-glass {
        background: rgba(255, 255, 255, 0.06) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
    }
    [data-bs-theme="dark"] .btn-glass-edit {
        color: #a5b4fc !important;
    }
    [data-bs-theme="dark"] .btn-glass-edit:hover {
        background: rgba(165, 180, 252, 0.15) !important;
    }
    [data-bs-theme="dark"] .btn-glass-delete {
        color: #fca5a5 !important;
    }
    [data-bs-theme="dark"] .btn-glass-delete:hover {
        background: rgba(252, 165, 165, 0.15) !important;
    }

    /* Glassmorphic Lihat Halaman Button */
    .btn-glass-view {
        background: rgba(255, 255, 255, 0.55) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border: 1px solid rgba(255, 255, 255, 0.6) !important;
        color: var(--text-primary) !important;
        transition: all 0.2s ease !important;
    }
    .btn-glass-view:hover {
        background: rgba(255, 255, 255, 0.75) !important;
        border-color: rgba(255, 255, 255, 0.85) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }
    [data-bs-theme="dark"] .btn-glass-view {
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: #f3f4f6 !important;
    }
    [data-bs-theme="dark"] .btn-glass-view:hover {
        background: rgba(255, 255, 255, 0.15) !important;
    }
    /* Glassmorphic Tab Bar */
    #builderTabs {
        background: rgba(255, 255, 255, 0.65) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.6) !important;
        padding: 5px !important;
        border-radius: 12px !important;
        display: inline-flex !important;
        gap: 4px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02) !important;
        width: fit-content !important;
    }
    [data-bs-theme="dark"] #builderTabs {
        background: rgba(15, 23, 42, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    #builderTabs .nav-link {
        color: var(--text-primary) !important;
        background: transparent !important;
        border-radius: 8px !important;
        border: 0 !important;
        transition: all 0.2s ease !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        padding: 8px 16px !important;
        opacity: 0.85;
    }
    #builderTabs .nav-link.active {
        background: #ffffff !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        color: var(--primary-color) !important;
        opacity: 1;
    }
    [data-bs-theme="dark"] #builderTabs .nav-link.active {
        background: rgba(255, 255, 255, 0.12) !important;
        color: var(--primary-color) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }
    #builderTabs .nav-link:hover:not(.active) {
        background: rgba(255, 255, 255, 0.25) !important;
        opacity: 1;
    }
    [data-bs-theme="dark"] #builderTabs .nav-link:hover:not(.active) {
        background: rgba(255, 255, 255, 0.08) !important;
    }

    /* Glassmorphic Form Switchers */
    .form-switch .form-check-input {
        appearance: none !important;
        -webkit-appearance: none !important;
        width: 44px !important;
        height: 24px !important;
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border-radius: 100px !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        box-shadow: 
            0 4px 10px rgba(0, 0, 0, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.4),
            inset 0 -1px 0 rgba(255, 255, 255, 0.1) !important;
        position: relative !important;
        cursor: pointer !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        background-image: none !important;
        margin-top: 0 !important;
    }
    
    .form-switch .form-check-input::before {
        content: '' !important;
        position: absolute !important;
        top: 2px !important;
        left: 2px !important;
        width: 18px !important;
        height: 18px !important;
        background: rgba(255, 255, 255, 0.3) !important;
        backdrop-filter: blur(4px) !important;
        -webkit-backdrop-filter: blur(4px) !important;
        border-radius: 50% !important;
        border: 1px solid rgba(255, 255, 255, 0.6) !important;
        box-shadow: 
            0 2px 4px rgba(0, 0, 0, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.5) !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    
    [data-bs-theme="dark"] .form-switch .form-check-input {
        background: rgba(255, 255, 255, 0.06) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        box-shadow: 
            0 4px 10px rgba(0, 0, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
    }
    
    [data-bs-theme="dark"] .form-switch .form-check-input::before {
        background: rgba(255, 255, 255, 0.15) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
    }
    
    .form-switch .form-check-input:checked {
        background: var(--primary-light) !important;
        border-color: var(--primary-color) !important;
        box-shadow: 
            0 0 12px rgba(37, 99, 235, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.2) !important;
    }
    [data-bs-theme="dark"] .form-switch .form-check-input:checked {
        box-shadow: 
            0 0 12px rgba(96, 165, 250, 0.3) !important;
    }
    
    .form-switch .form-check-input:checked::before {
        left: 22px !important;
        background: #ffffff !important;
        border-color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25) !important;
    }
    [data-bs-theme="dark"] .form-switch .form-check-input:checked::before {
        box-shadow: 0 2px 6px rgba(96, 165, 250, 0.3) !important;
    }
    
    .form-switch .form-check-input:focus {
        outline: none !important;
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }
</style>

<div class="row g-4">
    <!-- Left Column (Tab Content Area) -->
    <div class="col-md-7 col-lg-8 border-end border-secondary border-opacity-10 pe-md-4 pe-lg-5">
        <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('biolinks.index') }}" class="btn btn-light d-flex align-items-center justify-content-center p-2 rounded-circle shadow-sm" style="width: 38px; height: 38px; border-radius: 50% !important; border: 1px solid rgba(0,0,0,0.05);" title="Kembali ke Daftar Biolink">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                </a>
                <h4 class="fw-bold mb-0 d-flex align-items-center text-dark-custom" style="font-size: 1.5rem; letter-spacing: -0.5px;">
                    Biolink Builder: {{ $link->url }}
                </h4>
            </div>
            <a href="{{ $fullUrl }}" target="_blank" class="btn btn-glass btn-glass-view d-flex align-items-center gap-2 py-1.5 px-3 fw-semibold shadow-sm" style="border-radius: 8px !important; font-size: 0.825rem; height: 34px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
                Lihat Halaman
            </a>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs border-bottom-0 mb-4 g-2" id="builderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold px-4 py-2.5 d-flex align-items-center gap-2 border-0" id="blocks-tab" data-bs-toggle="tab" data-bs-target="#blocks-pane" type="button" role="tab" aria-controls="blocks-pane" aria-selected="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
                    Blok Konten
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-4 py-2.5 d-flex align-items-center gap-2 border-0" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-pane" type="button" role="tab" aria-controls="profile-pane" aria-selected="false">
                    <span data-duo-icons="user" style="width: 16px; height: 16px;"></span>
                    Profil
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-4 py-2.5 d-flex align-items-center gap-2 border-0" id="styling-tab" data-bs-toggle="tab" data-bs-target="#styling-pane" type="button" role="tab" aria-controls="styling-pane" aria-selected="false">
                    <span data-duo-icons="settings" style="width: 16px; height: 16px;"></span>
                    Styling
                </button>
            </li>
            <li class="nav-item d-md-none" role="presentation">
                <button class="nav-link fw-bold px-4 py-2.5 d-flex align-items-center gap-2 border-0" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview-pane" type="button" role="tab" aria-controls="preview-pane" aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                    Pratinjau
                </button>
            </li>
        </ul>

        <div class="tab-content" id="builderTabContent">
            
            <!-- TAB 1: Blok Konten -->
            <div class="tab-pane fade show active position-relative" id="blocks-pane" role="tabpanel" aria-labelledby="blocks-tab" tabindex="0" style="min-height: 480px;">
                <div id="blocks-container-wrapper" class="glass-card p-0 overflow-hidden">
                    <div class="p-4 pb-3 border-bottom border-secondary border-opacity-10">
                        <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                            <span data-duo-icons="folder-open" style="width: 18px; height: 18px;" class="text-muted"></span>
                            Blok Konten
                        </h6>
                    </div>
                    
                    @if($blocks->isEmpty())
                        <div class="text-center py-5 px-4 text-secondary">
                            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background-color: var(--primary-light) !important;">
                                <span data-duo-icons="info" style="width: 32px; height: 32px; color: var(--primary-color);"></span>
                            </div>
                            <p class="mb-1 fw-bold text-dark-custom">Belum ada blok konten</p>
                            <p class="text-muted small mb-0">Mulai tambahkan tautan atau teks menggunakan tombol + di sudut bawah.</p>
                        </div>
                    @else
                        <div class="d-flex flex-column" id="blocks-container">
                            @foreach($blocks as $block)
                                <div class="block-row d-flex align-items-center justify-content-between px-4 border-bottom border-secondary border-opacity-10" data-id="{{ $block->id }}" style="background: transparent; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Drag Handle -->
                                        <div class="drag-handle text-muted" style="cursor: grab; display: flex; align-items: center; padding: 4px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="9" y1="5" x2="9" y2="19" opacity="0.3"></line>
                                                <line x1="15" y1="5" x2="15" y2="19" opacity="0.3"></line>
                                                <line x1="9" y1="9" x2="9" y2="9" stroke-width="3"></line>
                                                <line x1="15" y1="9" x2="15" y2="9" stroke-width="3"></line>
                                                <line x1="9" y1="15" x2="9" y2="15" stroke-width="3"></line>
                                                <line x1="15" y1="15" x2="15" y2="15" stroke-width="3"></line>
                                            </svg>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Icon Box Left of Title/URL -->
                                            <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 38px; height: 38px; background-color: rgba(255, 255, 255, 0.45) !important; border: 1px solid rgba(255, 255, 255, 0.6); flex-shrink: 0;">
                                                @if($block->type == 'link')
                                                    @if(!empty($block->settings['icon']))
                                                        <span data-duo-icons="{{ $block->settings['icon'] }}" style="width: 20px; height: 20px; color: var(--primary-color);"></span>
                                                    @else
                                                        <span data-duo-icons="link" style="width: 20px; height: 20px; color: var(--primary-color);"></span>
                                                    @endif
                                                @elseif($block->type == 'text')
                                                    <span data-duo-icons="paragraph" style="width: 20px; height: 20px; color: #4b5563;"></span>
                                                @endif
                                            </div>

                                            <!-- Details: title, url only -->
                                            <div class="d-flex flex-column gap-0.5">
                                                <div>
                                                    @if($block->type == 'link')
                                                        <span class="fw-bold text-dark-custom" style="font-size: 0.95rem;">{{ $block->settings['title'] ?? 'Tanpa Judul' }}</span>
                                                    @elseif($block->type == 'text')
                                                        <span class="fw-bold text-dark-custom" style="font-size: 0.95rem;">{{ Str::limit(strip_tags($block->settings['content'] ?? ''), 30) }}</span>
                                                    @endif
                                                </div>
                                                @if($block->type == 'link')
                                                    <a href="{{ $block->location_url }}" target="_blank" class="small text-muted text-decoration-none d-block" style="word-break: break-all; opacity: 0.85; margin-top: -1px; font-size: 0.825rem;">{{ $block->location_url }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Middle Section: Category Badge (in the orange marked area!) -->
                                    <div class="d-flex align-items-center ms-auto me-5">
                                        @if($block->type == 'link')
                                            <span class="badge rounded-pill px-2.5 py-1" style="background-color: var(--primary-light) !important; color: var(--primary-color) !important; border: 1px solid rgba(59, 130, 246, 0.25); font-weight: 600; font-size: 0.725rem;">Link</span>
                                        @elseif($block->type == 'text')
                                            <span class="badge rounded-pill px-2.5 py-1" style="background-color: rgba(107, 114, 128, 0.1) !important; color: #374151 !important; border: 1px solid rgba(107, 114, 128, 0.15); font-weight: 600; font-size: 0.725rem;">Text</span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <!-- Active/Inactive Toggle Switch -->
                                        <div class="form-check form-switch mb-0 me-2 d-flex align-items-center" style="min-height: auto;">
                                            <input class="form-check-input block-toggle-switch cursor-pointer" type="checkbox" role="switch" data-id="{{ $block->id }}" {{ $block->is_enabled ? 'checked' : '' }} style="width: 36px; height: 18px; cursor: pointer; margin-top: 0;" title="{{ $block->is_enabled ? 'Nonaktifkan Blok' : 'Aktifkan Blok' }}">
                                        </div>

                                        <!-- Edit Button -->
                                        @if($block->type == 'link')
                                            <button type="button" class="btn btn-sm btn-glass btn-glass-edit p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#editLinkBlockModal-{{ $block->id }}" title="Edit Tautan">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>
                                        @elseif($block->type == 'text')
                                            <button type="button" class="btn btn-sm btn-glass btn-glass-edit p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#editTextBlockModal-{{ $block->id }}" title="Edit Teks">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>
                                        @endif

                                        <form action="{{ route('biolinks.blocks.destroy', [$link->id, $block->id]) }}" method="POST" onsubmit="return confirm('Hapus blok ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-glass btn-glass-delete p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if($block->type == 'link')
                                    <div class="modal fade" id="editLinkBlockModal-{{ $block->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                                <form action="{{ route('biolinks.blocks.update', [$link->id, $block->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header border-bottom-0 pb-1">
                                                        <h5 class="modal-title fw-bold">Edit Tautan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body py-2">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-secondary">Judul Tautan <span class="text-danger">*</span></label>
                                                            <input type="text" name="settings[title]" class="form-control" required placeholder="Cek Promo Terbaru!" value="{{ $block->settings['title'] ?? '' }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-secondary">URL Tujuan <span class="text-danger">*</span></label>
                                                            <input type="url" name="location_url" class="form-control" required placeholder="https://example.com/promo" value="{{ $block->location_url ?? '' }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-secondary">Icon Tautan (Duotone)</label>
                                                            <input type="hidden" name="settings[icon]" class="selected-icon-input" value="{{ $block->settings['icon'] ?? '' }}">
                                                            <div class="d-flex flex-wrap gap-2 p-2 border border-secondary border-opacity-15 rounded-3" style="max-height: 150px; overflow-y: auto; background: rgba(0,0,0,0.01); border-radius: 8px;">
                                                                <div class="icon-option p-2 rounded-2 border border-secondary border-opacity-10 d-flex align-items-center justify-content-center cursor-pointer {{ empty($block->settings['icon']) ? 'border-primary border-opacity-100 bg-primary bg-opacity-10' : '' }}" data-icon="" title="Tanpa Icon" style="width: 38px; height: 38px; cursor: pointer;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                </div>
                                                                @foreach(['add_circle', 'airplay', 'alert_octagon', 'alert_triangle', 'align_bottom', 'align_center', 'android', 'app_dots', 'app', 'apple', 'approved', 'appstore', 'award', 'baby_carriage', 'bank', 'battery', 'bell_badge', 'bell', 'book_2', 'book_3', 'book', 'bookmark', 'box_2', 'box', 'bread', 'bridge', 'briefcase', 'brush_2', 'brush', 'bug', 'building', 'bus', 'cake', 'calendar', 'camera_square', 'camera', 'campground', 'candle', 'car', 'certificate', 'chart_pie', 'check_circle', 'chip', 'clapperboard', 'clipboard', 'clock', 'cloud_lightning', 'cloud_snow', 'coin_stack', 'compass', 'computer_camera', 'confetti', 'credit_card', 'currency_euro', 'dashboard', 'discount', 'disk', 'file', 'fire', 'folder_open', 'folder_upload', 'g_translate', 'id_card', 'info', 'lamp_2', 'lamp', 'location', 'marker', 'menu', 'message_2', 'message_3', 'message', 'moon_2', 'moon_stars', 'palette', 'rocket', 'settings', 'shopping_bag', 'slideshow', 'smartphone_vibration', 'smartphone', 'smartwatch', 'sun', 'target', 'toggle', 'translation', 'upload_file', 'user_card', 'user', 'world'] as $iconName)
                                                                    <div class="icon-option p-2 rounded-2 border border-secondary border-opacity-10 d-flex align-items-center justify-content-center cursor-pointer {{ ($block->settings['icon'] ?? '') == $iconName ? 'border-primary border-opacity-100 bg-primary bg-opacity-10' : '' }}" data-icon="{{ $iconName }}" title="{{ $iconName }}" style="width: 38px; height: 38px; cursor: pointer;">
                                                                        <span data-duo-icons="{{ $iconName }}" style="width: 20px; height: 20px; color: var(--primary-color);"></span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-1">
                                                        <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($block->type == 'text')
                                    <div class="modal fade" id="editTextBlockModal-{{ $block->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                                <form action="{{ route('biolinks.blocks.update', [$link->id, $block->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header border-bottom-0 pb-1">
                                                        <h5 class="modal-title fw-bold">Edit Teks</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body py-2">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-secondary">Konten Teks <span class="text-danger">*</span></label>
                                                            <textarea name="settings[content]" class="form-control" rows="4" required placeholder="Tulis sesuatu... ">{{ $block->settings['content'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-1">
                                                        <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div class="px-4 py-3 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center" style="background: transparent;">
                        <span class="small text-muted">Total: <strong class="text-dark-custom">{{ $blocks->count() }}</strong> blok konten</span>
                        <span class="small text-muted d-flex align-items-center gap-1.5">
                            <span class="d-inline-block rounded-circle bg-success animate-pulse" style="width: 8px; height: 8px; box-shadow: 0 0 8px #10b981;"></span>
                            Tersinkronisasi otomatis
                        </span>
                    </div>
                </div>

                <!-- Flying Action Button (FAB) Speed Dial -->
                <div class="fab-wrapper" style="position: absolute; bottom: 24px; right: 24px; z-index: 999; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                    <!-- Floating Speed Dial Menu Options -->
                    <div class="fab-menu d-flex flex-column gap-2 mb-1" style="opacity: 0; transform: translateY(15px); pointer-events: none; transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                        <button type="button" class="btn rounded-circle d-flex align-items-center justify-content-center shadow-lg border-0" style="width: 44px; height: 44px; background-color: var(--primary-color);" data-bs-toggle="modal" data-bs-target="#addLinkBlockModal" title="Tambah Tautan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#111827" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 17H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                                <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                        </button>
                        <button type="button" class="btn rounded-circle d-flex align-items-center justify-content-center shadow-lg border-0" style="width: 44px; height: 44px; background-color: #6b7280;" data-bs-toggle="modal" data-bs-target="#addTextBlockModal" title="Tambah Teks">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="21" y1="10" x2="3" y2="10" opacity="0.3"></line>
                                <line x1="21" y1="18" x2="3" y2="18" opacity="0.3"></line>
                                <line x1="17" y1="6" x2="3" y2="6"></line>
                                <line x1="17" y1="14" x2="3" y2="14"></line>
                            </svg>
                        </button>
                    </div>
                    <!-- Main FAB Button -->
                    <button type="button" id="mainFabBtn" class="btn rounded-circle d-flex align-items-center justify-content-center shadow-lg border-0" style="width: 56px; height: 56px; background-color: var(--primary-color);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#111827" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" id="fabIcon" style="transition: transform 0.25s ease;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- TAB 2: Profil -->
            <div class="tab-pane fade" id="profile-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <!-- Visual Cover & Avatar Editor Zone -->
                <div class="glass-card mb-4 overflow-hidden position-relative" style="border-radius: 16px; border: 1px solid var(--card-border);">
                    <div id="coverDropzone" class="position-relative" style="height: 160px; background: {{ isset($link->settings['cover_url']) ? 'url(' . $link->settings['cover_url'] . ') center/cover no-repeat' : 'linear-gradient(135deg, #a4e5bd 0%, #7dd3a1 100%)' }}; cursor: pointer; display: block;">
                        <div class="dropzone-overlay d-flex flex-column align-items-center justify-content-center text-white" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.45); opacity: 0; transition: opacity 0.2s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                            <span class="small fw-semibold">Ubah Cover (Seret & Jatuhkan)</span>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column align-items-center" style="margin-top: -60px; padding-bottom: 24px;">
                        <div id="avatarDropzone" class="position-relative rounded-circle" style="width: 110px; height: 110px; border: 4px solid var(--card-bg-blur); box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; overflow: hidden; background: #fff; display: block;">
                            <img id="avatarPreview" src="{{ $link->settings['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($link->settings['title'] ?? 'BL') . '&background=a4e5bd&color=111827&size=128' }}" style="width:100%; height:100%; object-fit:cover;">
                            <div class="dropzone-overlay d-flex flex-column align-items-center justify-content-center text-white" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.55); opacity: 0; transition: opacity 0.2s ease; border-radius: 50%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                <span style="font-size: 0.65rem;" class="fw-semibold">Ubah Foto</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-1.5 mt-3 mb-1">
                            <h5 class="fw-bold mb-0 text-dark-custom">{{ $link->settings['title'] ?? 'My Biolink' }}</h5>
                            @if($link->is_verified)
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#0095f6" style="color: white; flex-shrink: 0;" title="Verified Profile">
                                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                                </svg>
                            @endif
                        </div>
                        <p class="text-secondary small mb-0 px-4 text-center text-truncate" style="max-width: 100%;">{{ $link->settings['description'] ?? 'Belum ada deskripsi bio.' }}</p>
                    </div>
                </div>
                <input type="file" id="coverInput" class="d-none" accept="image/*">
                <input type="file" id="avatarInput" class="d-none" accept="image/*">

                <div class="glass-card p-4">
                    <form action="{{ route('biolinks.settings.update', $link->id) }}" method="POST" id="profileTabForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nama / Judul</label>
                            <div class="input-group glass-input-group">
                                <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; border: none; background: transparent;">
                                    <span data-duo-icons="user" style="width: 18px; height: 18px;"></span>
                                </span>
                                <input type="text" name="title" class="form-control border-0 ps-1 pe-0 bg-transparent" placeholder="Nama Anda" value="{{ $link->settings['title'] ?? '' }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Deskripsi Singkat</label>
                            <div class="input-group glass-input-group align-items-start">
                                <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; border: none; background: transparent;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                                        <line x1="21" y1="10" x2="3" y2="10" opacity="0.3"></line>
                                        <line x1="21" y1="18" x2="3" y2="18" opacity="0.3"></line>
                                        <line x1="17" y1="6" x2="3" y2="6"></line>
                                        <line x1="17" y1="14" x2="3" y2="14"></line>
                                    </svg>
                                </span>
                                <textarea name="description" class="form-control border-0 ps-1 pe-0 pt-2.5 bg-transparent" rows="3" placeholder="Tulis bio singkat...">{{ $link->settings['description'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- Toggle Show/Hide Avatar and Cover -->
                        <div class="glass-card p-3 mb-3 d-flex flex-column gap-3" style="border-radius: 12px; background: rgba(255, 255, 255, 0.2) !important;">
                            <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between">
                                <label class="form-check-label small fw-semibold text-secondary mb-0 cursor-pointer" for="showAvatarSwitch">Tampilkan Foto Profil</label>
                                <div class="position-relative">
                                    <input type="hidden" name="settings[show_avatar]" value="0">
                                    <input class="form-check-input cursor-pointer" type="checkbox" role="switch" name="settings[show_avatar]" value="1" id="showAvatarSwitch" {{ ($link->settings['show_avatar'] ?? '1') == '1' ? 'checked' : '' }} style="width: 38px; height: 20px; cursor: pointer; margin-top: 0;">
                                </div>
                            </div>
                            <hr class="my-0 border-secondary border-opacity-10">
                            <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between">
                                <label class="form-check-label small fw-semibold text-secondary mb-0 cursor-pointer" for="showCoverSwitch">Tampilkan Foto Sampul</label>
                                <div class="position-relative">
                                    <input type="hidden" name="settings[show_cover]" value="0">
                                    <input class="form-check-input cursor-pointer" type="checkbox" role="switch" name="settings[show_cover]" value="1" id="showCoverSwitch" {{ ($link->settings['show_cover'] ?? '1') == '1' ? 'checked' : '' }} style="width: 38px; height: 20px; cursor: pointer; margin-top: 0;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2.5 fw-semibold rounded-3 shadow-sm border-0" style="background-color: var(--primary-color); color: var(--active-text);">Simpan Profil</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 3: Styling -->
            <div class="tab-pane fade" id="styling-pane" role="tabpanel" aria-labelledby="styling-tab" tabindex="0">
                <div class="glass-card p-4">
                    <form action="{{ route('biolinks.settings.update', $link->id) }}" method="POST" id="stylingTabForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Keep Title and Description values inside hidden sync fields -->
                        <input type="hidden" name="title" value="{{ $link->settings['title'] ?? '' }}">
                        <input type="hidden" name="description" value="{{ $link->settings['description'] ?? '' }}">

                        <!-- Presets Section -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary mb-2">Preset Kombinasi Tampilan Siap Pakai</label>
                            <div class="d-flex flex-wrap gap-2">
                                <!-- Preset 1: Mint Tea (Default green tosca) -->
                                <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                     data-bg-type="gradient"
                                     data-bg-start="#a4e5bd"
                                     data-bg-end="#7dd3a1"
                                     data-btn-bg="#ffffff"
                                     data-btn-text="#111827"
                                     data-text="#111827">
                                    <div style="width: 14px; height: 14px; border-radius: 50%; background: linear-gradient(135deg, #a4e5bd 0%, #7dd3a1 100%); border: 1px solid rgba(0,0,0,0.1); flex-shrink: 0;"></div>
                                    <span style="font-size: 0.75rem;" class="fw-semibold text-dark-custom">Mint Tea</span>
                                </div>

                                <!-- Preset 2: Midnight Aurora -->
                                <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                     data-bg-type="gradient"
                                     data-bg-start="#0f172a"
                                     data-bg-end="#1e1b4b"
                                     data-btn-bg="#1e293b"
                                     data-btn-text="#f8fafc"
                                     data-text="#f8fafc">
                                    <div style="width: 14px; height: 14px; border-radius: 50%; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border: 1px solid rgba(255,255,255,0.15); flex-shrink: 0;"></div>
                                    <span style="font-size: 0.75rem;" class="fw-semibold text-dark-custom">Midnight</span>
                                </div>

                                <!-- Preset 3: Sunset Peach -->
                                <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                     data-bg-type="gradient"
                                     data-bg-start="#ff7e5f"
                                     data-bg-end="#feb47b"
                                     data-btn-bg="#ffffff"
                                     data-btn-text="#ff7e5f"
                                     data-text="#ffffff">
                                    <div style="width: 14px; height: 14px; border-radius: 50%; background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%); border: 1px solid rgba(0,0,0,0.1); flex-shrink: 0;"></div>
                                    <span style="font-size: 0.75rem;" class="fw-semibold text-dark-custom">Sunset</span>
                                </div>

                                <!-- Preset 4: Ocean Breeze -->
                                <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                     data-bg-type="gradient"
                                     data-bg-start="#2b5876"
                                     data-bg-end="#4e4376"
                                     data-btn-bg="#ffffff"
                                     data-btn-text="#2b5876"
                                     data-text="#ffffff">
                                    <div style="width: 14px; height: 14px; border-radius: 50%; background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%); border: 1px solid rgba(0,0,0,0.1); flex-shrink: 0;"></div>
                                    <span style="font-size: 0.75rem;" class="fw-semibold text-dark-custom">Ocean</span>
                                </div>

                                <!-- Preset 5: Minimalist Light -->
                                <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                     data-bg-type="solid"
                                     data-bg-color="#f3f4f6"
                                     data-btn-bg="#ffffff"
                                     data-btn-text="#1f2937"
                                     data-text="#1f2937">
                                    <div style="width: 14px; height: 14px; border-radius: 50%; background: #f3f4f6; border: 1px solid rgba(0,0,0,0.1); flex-shrink: 0;"></div>
                                    <span style="font-size: 0.75rem;" class="fw-semibold text-dark-custom">Minimalist</span>
                                </div>

                                <!-- Preset 6: Obsidian Black -->
                                <div class="preset-card px-3 py-1.5 rounded-pill border d-flex align-items-center gap-2 shadow-sm"
                                     data-bg-type="solid"
                                     data-bg-color="#121212"
                                     data-btn-bg="#1e1e1e"
                                     data-btn-text="#e0e0e0"
                                     data-text="#e0e0e0">
                                    <div style="width: 14px; height: 14px; border-radius: 50%; background: #121212; border: 1px solid rgba(255,255,255,0.15); flex-shrink: 0;"></div>
                                    <span style="font-size: 0.75rem;" class="fw-semibold text-dark-custom">Obsidian</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Tipe Background</label>
                            <select name="settings[bg_type]" id="bgTypeSelectorTab" class="form-select bg-transparent border border-secondary border-opacity-15 py-2.5 rounded-3 text-secondary small" style="border-radius: 8px !important;">
                                <option value="solid" {{ ($link->settings['bg_type'] ?? 'solid') == 'solid' ? 'selected' : '' }}>Warna Solid</option>
                                <option value="gradient" {{ ($link->settings['bg_type'] ?? 'solid') == 'gradient' ? 'selected' : '' }}>Warna Gradasi (Gradient)</option>
                                <option value="abstract_blobs" {{ ($link->settings['bg_type'] ?? 'solid') == 'abstract_blobs' ? 'selected' : '' }}>Abstract Blobs (Mesh Gradient)</option>
                            </select>
                        </div>

                        <!-- Solid Background Color Input -->
                        <div class="mb-3" id="solidBgWrapperTab">
                            <label class="form-label small fw-semibold text-secondary mb-1.5">Warna Background</label>
                            <div class="d-flex align-items-center gap-2">
                                <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                    <input type="color" name="settings[bg_color]" class="color-picker-input" value="{{ $link->settings['bg_color'] ?? '#f3f4f1' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                </div>
                                <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_color'] ?? '#f3f4f1' }}" style="width: 100px; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                            </div>
                        </div>

                        <!-- Gradient Background Color Inputs -->
                        <div class="mb-3 d-none" id="gradientBgWrapperTab">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1.5">Gradasi Mulai</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                            <input type="color" name="settings[bg_gradient_start]" class="color-picker-input" value="{{ $link->settings['bg_gradient_start'] ?? '#a4e5bd' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                        </div>
                                        <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_gradient_start'] ?? '#a4e5bd' }}" style="width: 100%; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-semibold text-secondary mb-1.5">Gradasi Selesai</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                            <input type="color" name="settings[bg_gradient_end]" class="color-picker-input" value="{{ $link->settings['bg_gradient_end'] ?? '#7dd3a1' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                        </div>
                                        <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_gradient_end'] ?? '#7dd3a1' }}" style="width: 100%; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Abstract Blobs Settings -->
                        <div id="abstractBlobsWrapperTab" class="d-none">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary mb-1.5">Warna Dasar Latar Belakang</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                        <input type="color" name="settings[bg_blob_base]" class="color-picker-input" value="{{ $link->settings['bg_blob_base'] ?? '#f8fafc' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                    </div>
                                    <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_blob_base'] ?? '#f8fafc' }}" style="width: 100px; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary mb-1.5">Warna Blob 1 (Atas Kiri)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                        <input type="color" name="settings[bg_blob_1]" class="color-picker-input" value="{{ $link->settings['bg_blob_1'] ?? '#3b82f6' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                    </div>
                                    <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_blob_1'] ?? '#3b82f6' }}" style="width: 100px; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary mb-1.5">Warna Blob 2 (Tengah Kanan)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                        <input type="color" name="settings[bg_blob_2]" class="color-picker-input" value="{{ $link->settings['bg_blob_2'] ?? '#ec4899' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                    </div>
                                    <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_blob_2'] ?? '#ec4899' }}" style="width: 100px; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary mb-1.5">Warna Blob 3 (Bawah Kanan)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                        <input type="color" name="settings[bg_blob_3]" class="color-picker-input" value="{{ $link->settings['bg_blob_3'] ?? '#8b5cf6' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                    </div>
                                    <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['bg_blob_3'] ?? '#8b5cf6' }}" style="width: 100px; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <label class="form-label small fw-semibold text-secondary mb-1.5">Warna Tombol</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                        <input type="color" name="settings[btn_bg_color]" class="color-picker-input" value="{{ $link->settings['btn_bg_color'] ?? '#ffffff' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                    </div>
                                    <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['btn_bg_color'] ?? '#ffffff' }}" style="width: 100%; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="form-label small fw-semibold text-secondary mb-1.5">Teks Tombol</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                        <input type="color" name="settings[btn_text_color]" class="color-picker-input" value="{{ $link->settings['btn_text_color'] ?? '#111827' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                    </div>
                                    <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['btn_text_color'] ?? '#111827' }}" style="width: 100%; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="form-label small fw-semibold text-secondary mb-1.5">Teks Profil</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative" style="width: 38px; height: 38px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.12); flex-shrink: 0; cursor: pointer;">
                                        <input type="color" name="settings[text_color]" class="color-picker-input" value="{{ $link->settings['text_color'] ?? '#111827' }}" style="position: absolute; top: -10px; left: -10px; width: 58px; height: 58px; border: none; padding: 0; cursor: pointer;">
                                    </div>
                                    <input type="text" class="form-control text-uppercase fw-semibold color-hex-text" value="{{ $link->settings['text_color'] ?? '#111827' }}" style="width: 100%; height: 38px; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.12); background: transparent; text-align: center;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2.5 fw-semibold rounded-3 shadow-sm border-0" style="background-color: var(--primary-color); color: var(--active-text);">Simpan Tampilan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 4: Pratinjau (Mobile Only) -->
            <div class="tab-pane fade d-md-none" id="preview-pane" role="tabpanel" aria-labelledby="preview-tab" tabindex="0">
                <div class="d-flex justify-content-center">
                    <div class="mockup-container position-relative shadow-2xl overflow-hidden" style="width: 375px; height: 750px; border-radius: 36px; border: 12px solid #111827; background: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4); flex-shrink:0;">
                        <!-- Camera Notch -->
                        <div class="position-absolute start-50 translate-middle-x" style="top: 0; width: 120px; height: 20px; background: #111827; border-radius: 0 0 12px 12px; z-index: 100;"></div>
                        
                        <iframe src="{{ $fullUrl }}" class="w-100 h-100 border-0 bg-white" style="border-radius: 28px;"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Right Column: Desktop Preview Panel (d-none on mobile) -->
    <div class="col-md-5 col-lg-4 d-none d-md-flex justify-content-center align-items-start ps-md-4 ps-lg-5">
        <div class="mockup-container position-relative shadow-2xl overflow-hidden" style="width: 375px; height: 750px; border-radius: 36px; border: 12px solid #111827; background: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4); flex-shrink:0; position: sticky; top: 100px; margin-top: -110px;">
            <!-- Camera Notch -->
            <div class="position-absolute start-50 translate-middle-x" style="top: 0; width: 120px; height: 20px; background: #111827; border-radius: 0 0 12px 12px; z-index: 100;"></div>
            
            <iframe src="{{ $fullUrl }}" class="w-100 h-100 border-0 bg-white" style="border-radius: 28px;"></iframe>
        </div>
    </div>
</div>

<!-- Modal Add Link -->
<div class="modal fade" id="addLinkBlockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form action="{{ route('biolinks.blocks.store', $link->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="link">
                <div class="modal-header border-bottom-0 pb-1">
                    <h5 class="modal-title fw-bold">Tambah Tautan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Judul Tautan <span class="text-danger">*</span></label>
                        <div class="input-group glass-input-group">
                            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; border: none; background: transparent;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7" stroke-width="3"></line>
                                </svg>
                            </span>
                            <input type="text" name="settings[title]" class="form-control border-0 ps-1 pe-0 bg-transparent" required placeholder="Cek Promo Terbaru!">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">URL Tujuan <span class="text-danger">*</span></label>
                        <div class="input-group glass-input-group">
                            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; border: none; background: transparent;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                                    <path d="M9 17H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                                    <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </span>
                            <input type="url" name="location_url" class="form-control border-0 ps-1 pe-0 bg-transparent" required placeholder="https://example.com/promo">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Icon Tautan (Duotone)</label>
                        <input type="hidden" name="settings[icon]" class="selected-icon-input" value="">
                        <div class="d-flex flex-wrap gap-2 p-2 border border-secondary border-opacity-15 rounded-3" style="max-height: 150px; overflow-y: auto; background: rgba(0,0,0,0.01); border-radius: 8px;">
                            <div class="icon-option p-2 rounded-2 border border-secondary border-opacity-10 d-flex align-items-center justify-content-center cursor-pointer border-primary border-opacity-100 bg-primary bg-opacity-10" data-icon="" title="Tanpa Icon" style="width: 38px; height: 38px; cursor: pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </div>
                            @foreach(['add_circle', 'airplay', 'alert_octagon', 'alert_triangle', 'align_bottom', 'align_center', 'android', 'app_dots', 'app', 'apple', 'approved', 'appstore', 'award', 'baby_carriage', 'bank', 'battery', 'bell_badge', 'bell', 'book_2', 'book_3', 'book', 'bookmark', 'box_2', 'box', 'bread', 'bridge', 'briefcase', 'brush_2', 'brush', 'bug', 'building', 'bus', 'cake', 'calendar', 'camera_square', 'camera', 'campground', 'candle', 'car', 'certificate', 'chart_pie', 'check_circle', 'chip', 'clapperboard', 'clipboard', 'clock', 'cloud_lightning', 'cloud_snow', 'coin_stack', 'compass', 'computer_camera', 'confetti', 'credit_card', 'currency_euro', 'dashboard', 'discount', 'disk', 'file', 'fire', 'folder_open', 'folder_upload', 'g_translate', 'id_card', 'info', 'lamp_2', 'lamp', 'location', 'marker', 'menu', 'message_2', 'message_3', 'message', 'moon_2', 'moon_stars', 'palette', 'rocket', 'settings', 'shopping_bag', 'slideshow', 'smartphone_vibration', 'smartphone', 'smartwatch', 'sun', 'target', 'toggle', 'translation', 'upload_file', 'user_card', 'user', 'world'] as $iconName)
                                <div class="icon-option p-2 rounded-2 border border-secondary border-opacity-10 d-flex align-items-center justify-content-center cursor-pointer" data-icon="{{ $iconName }}" title="{{ $iconName }}" style="width: 38px; height: 38px; cursor: pointer;">
                                    <span data-duo-icons="{{ $iconName }}" style="width: 20px; height: 20px; color: var(--primary-color);"></span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-1">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Text -->
<div class="modal fade" id="addTextBlockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form action="{{ route('biolinks.blocks.store', $link->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="text">
                <div class="modal-header border-bottom-0 pb-1">
                    <h5 class="modal-title fw-bold">Tambah Teks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Konten Teks <span class="text-danger">*</span></label>
                        <div class="input-group glass-input-group align-items-start">
                            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; border: none; background: transparent;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                                    <line x1="21" y1="10" x2="3" y2="10" opacity="0.3"></line>
                                    <line x1="21" y1="18" x2="3" y2="18" opacity="0.3"></line>
                                    <line x1="17" y1="6" x2="3" y2="6"></line>
                                    <line x1="17" y1="14" x2="3" y2="14"></line>
                                </svg>
                            </span>
                            <textarea name="settings[content]" class="form-control border-0 ps-1 pe-0 pt-2.5 bg-transparent" rows="4" required placeholder="Tulis sesuatu yang menarik..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-1">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Image Cropper -->
<div class="modal fade" id="cropperModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pb-1">
                <h5 class="modal-title fw-bold" id="cropperModalTitle">Sesuaikan Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-2">
                <div class="cropper-container-wrapper rounded-3 border">
                    <img id="cropperImage" src="">
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-1">
                <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="cropAndSaveBtn" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Potong & Simpan</button>
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
    // Show success toast
    function showSuccessToast(message) {
        const toast = $('<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">' +
            '<div class="toast show align-items-center text-white border-0" role="alert" style="background-color: #10b981; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">' +
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
                    $('#blocks-container .card').each(function(index) {
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
                            const iframe = document.querySelector('iframe');
                            if (iframe) {
                                iframe.contentWindow.location.reload();
                            }
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
    $('#bgTypeSelectorTab').on('change', toggleBgSettings);
    
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

        // Update input values and trigger input event for live visual sync
        $('#bgTypeSelectorTab').val(bgType).trigger('change');
        $('input[name="settings[bg_color]"]').val(bgColor).trigger('input');
        $('input[name="settings[bg_gradient_start]"]').val(bgStart).trigger('input');
        $('input[name="settings[bg_gradient_end]"]').val(bgEnd).trigger('input');
        $('input[name="settings[btn_bg_color]"]').val(btnBg).trigger('input');
        $('input[name="settings[btn_text_color]"]').val(btnText).trigger('input');
        $('input[name="settings[text_color]"]').val(text).trigger('input');
    });

    // Sync custom color pickers and text inputs in real-time
    $(document).on('input', '.color-picker-input', function() {
        const hex = $(this).val().toUpperCase();
        $(this).closest('.d-flex').find('.color-hex-text').val(hex);
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
                    // Refresh preview iframe
                    const iframe = document.querySelectorAll('iframe');
                    iframe.forEach(ifr => {
                        ifr.contentWindow.location.reload();
                    });
                }
            },
            error: function() {
                // Revert switch state on failure
                $(`.block-toggle-switch[data-id="${blockId}"]`).prop('checked', !isChecked);
                alert('Gagal memperbarui status aktif/nonaktif blok.');
            }
        });
    });

    $(document).on('input', '.color-hex-text', function() {
        let val = $(this).val().trim();
        if (val && !val.startsWith('#')) {
            val = '#' + val;
        }
        if (/^#[0-9A-F]{6}$/i.test(val)) {
            $(this).closest('.d-flex').find('.color-picker-input').val(val);
        }
    });

    // Sync title & description inputs between Profile and Styling forms in real-time
    $('input[name="title"]').on('input', function() {
        $('input[name="title"]').val($(this).val());
    });
    $('textarea[name="description"]').on('input', function() {
        $('textarea[name="description"]').val($(this).val());
    });

    // Save and persist active tab ID in session storage to survive page reloads
    $('#builderTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
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

    // Mobile tap support for Floating Action Button (FAB) Speed Dial
    $('#mainFabBtn').on('click', function(e) {
        e.stopPropagation();
        $('.fab-wrapper').toggleClass('active');
    });
    $(document).on('click', function() {
        $('.fab-wrapper').removeClass('active');
    });

    // Refresh layout, reload iframe
    function refreshBuilderUI(successMessage) {
        const iframe = document.querySelectorAll('iframe');
        iframe.forEach(ifr => {
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
    $('#addLinkBlockModal form, #addTextBlockModal form, #addWhatsappRotatorBlockModal form, [id^=editLinkBlockModal] form, [id^=editTextBlockModal] form, [id^=editWhatsappRotatorBlockModal] form, #profileTabForm, #stylingTabForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const modalEl = form.closest('.modal')[0];
        const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.text();

        submitBtn.prop('disabled', true).text('Loading...');
        const formData = new FormData(form[0]);

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method') || 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (modal) {
                    modal.hide();
                    form[0].reset();
                }
                refreshBuilderUI(response.message || 'Berhasil disimpan!');
            },
            error: function() {
                alert('Terjadi kesalahan, silakan coba lagi.');
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
            dataType: 'json',
            success: function(response) {
                refreshBuilderUI(response.message || 'Blok berhasil dihapus!');
            },
            error: function() {
                alert('Gagal menghapus blok.');
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
            
            // Sync values from active form fields
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
                    
                    // Reload window slightly after visual sync
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
        parent.find('.icon-option').removeClass('border-primary border-opacity-100 bg-primary bg-opacity-10');
        $(this).addClass('border-primary border-opacity-100 bg-primary bg-opacity-10');
        
        const iconVal = $(this).attr('data-icon');
        parent.siblings('.selected-icon-input').val(iconVal);
    });

    // ──────────────────────────────────────────────────────────────────────────
    // AUTO PREVIEW FOR STYLING TAB
    // ──────────────────────────────────────────────────────────────────────────
    function syncStylingToPreview() {
        const iframes = document.querySelectorAll('iframe');
        iframes.forEach(iframe => {
            if (!iframe || !iframe.contentWindow || !iframe.contentDocument) return;
            
            try {
                const iframeDoc = iframe.contentDocument;
                
                const bgType = $('#bgTypeSelectorTab').val();
                const bgColor = $('input[name="settings[bg_color]"]').val();
                const bgStart = $('input[name="settings[bg_gradient_start]"]').val();
                const bgEnd = $('input[name="settings[bg_gradient_end]"]').val();
                const btnBg = $('input[name="settings[btn_bg_color]"]').val();
                const btnText = $('input[name="settings[btn_text_color]"]').val();
                const textColor = $('input[name="settings[text_color]"]').val();
                const btnRadius = $('select[name="settings[btn_border_radius]"]').val();
                const font = $('select[name="settings[font]"]').val();
                
                // Cover & Avatar visibility sync
                const showAvatar = $('#showAvatarSwitch').is(':checked');
                const showCover = $('#showCoverSwitch').is(':checked');
                
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
                }
                
                // 1. Update Background
                const blobContainer = iframeDoc.querySelector('.blob-bg-container');
                if (bgType === 'abstract_blobs') {
                    if (blobContainer) blobContainer.style.display = 'block';
                    iframeDoc.body.style.background = '#f8fafc';
                    
                    // Update blobs
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
                
                // 2. Update Button styles
                const buttons = iframeDoc.querySelectorAll('.block-link');
                buttons.forEach(btn => {
                    btn.style.background = btnBg;
                    btn.style.color = btnText;
                    if (btnRadius) {
                        btn.style.borderRadius = btnRadius === 'round' ? '30px' : (btnRadius === 'rounded' ? '12px' : '0px');
                    }
                });
                
                // 3. Update Text colors
                iframeDoc.body.style.color = textColor;
                const pTitle = iframeDoc.querySelector('.profile-title');
                const pDesc = iframeDoc.querySelector('.profile-desc');
                const bTexts = iframeDoc.querySelectorAll('.block-text');
                const watermark = iframeDoc.querySelector('.watermark');
                
                if (pTitle) pTitle.style.color = textColor;
                if (pDesc) pDesc.style.color = textColor;
                if (watermark) watermark.style.color = textColor;
                bTexts.forEach(txt => txt.style.color = textColor);
                
                // 4. Update font family
                if (font) {
                    let fontCSS = 'sans-serif';
                    if (font === 'Inter') fontCSS = "'Inter', sans-serif";
                    else if (font === 'Roboto') fontCSS = "'Roboto', sans-serif";
                    else if (font === 'Outfit') fontCSS = "'Outfit', sans-serif";
                    else if (font === 'Poppins') fontCSS = "'Poppins', sans-serif";
                    else if (font === 'Playfair Display') fontCSS = "'Playfair Display', serif";
                    
                    iframeDoc.body.style.fontFamily = fontCSS;
                }
            } catch (e) {
                console.error("Auto-preview sync failed for iframe:", e);
            }
        });
    }

    // Trigger preview sync on input/select change under the styling panel
    $(document).on('input change', '#styling-pane input, #styling-pane select', function() {
        syncStylingToPreview();
    });

    // Listen to profile visibility switches
    $(document).on('change', '#showAvatarSwitch, #showCoverSwitch', function() {
        syncStylingToPreview();
    });

    // Handle initial preview iframe load sync
    $('iframe').on('load', function() {
        syncStylingToPreview();
    });
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
