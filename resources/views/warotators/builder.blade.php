@extends('layouts.app')

@section('title', 'WA Rotator Builder')

@section('content')
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
    /* ─── Drag & Drop Zone Styling ─── */
    .drag-drop-zone {
        border: 2px dashed rgba(100, 116, 139, 0.25);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        background: rgba(255,255,255,0.01);
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    .drag-drop-zone:hover, .drag-drop-zone.dragover {
        border-color: #2ac3a6;
        background: rgba(42, 195, 166, 0.04);
    }
    .drag-drop-zone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }
    .drag-drop-icon {
        color: #94a3b8;
        transition: color 0.2s ease;
    }
    .drag-drop-zone:hover .drag-drop-icon, .drag-drop-zone.dragover .drag-drop-icon {
        color: #2ac3a6;
    }
    [data-bs-theme="dark"] .drag-drop-zone {
        border-color: rgba(255, 255, 255, 0.1);
        background: rgba(0, 0, 0, 0.1);
    }
    .dropzone-text {
        font-size: 0.8rem !important;
        font-weight: 600;
    }
</style>
<!-- Page Header (Full Width) -->
@php
    $previewUrl = route('warotators.preview', $link->id);
    $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
    if (request()->isSecure() && str_starts_with($fullUrl, 'http://')) {
        $fullUrl = preg_replace('#^http://#', 'https://', $fullUrl);
    }
@endphp
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('warotators.index') }}" class="btn btn-sm btn-icon btn-light me-2">
            <i class="ki-outline ki-arrow-left fs-2"></i>
        </a>
        <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
            WA Rotator Builder: {{ $link->url }}
        </h1>
        <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1 ms-2">WhatsApp Rotator</span>
    </div>
    <div>
        <a href="{{ $fullUrl }}" target="_blank" class="btn btn-sm btn-light-primary fw-bold d-inline-flex align-items-center gap-2">
            <i class="ki-outline ki-exit-right-corner fs-4"></i> View Page
        </a>
    </div>
</div>

<div class="row g-6 g-xl-9 align-items-start mb-12" style="min-height: 720px;">
    <!-- Left Panel: Builder Options & Settings -->
    <div class="col-lg-7 col-xl-8 pe-lg-5 pb-10">
        
        <!-- Tab Controls Navigation -->
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold mb-5" id="builderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-3 active" id="settings-tab" data-bs-toggle="tab" href="#settings-pane" role="tab">
                    Rotator
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-3" id="styling-tab" data-bs-toggle="tab" href="#styling-pane" role="tab">
                    Styling
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-3" id="profile-tab" data-bs-toggle="tab" href="#profile-pane" role="tab">
                    Avatar & Media
                </a>
            </li>
        </ul>

        <!-- Tab Content Panes -->
        <div class="tab-content flex-grow-1" id="builderTabsContent">
            
            <!-- TAB 1: Rotator Settings -->
            <div class="tab-pane fade show active" id="settings-pane" role="tabpanel">
                <div class="card card-flush shadow-sm border-0 mb-4">
                    <div class="card-body p-6">
                        <form action="{{ route('warotators.settings.update', $link->id) }}" method="POST" id="rotatorSettingsForm">
                            @csrf
                            @method('PUT')
                            
                            <h4 class="fw-bold text-gray-900 mb-5">Main Information</h4>

                            <!-- Domain & Path Alias -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Domain</label>
                                    <select name="domain_id" id="domain_id" class="form-select form-select-sm form-select-solid" data-control="select2" data-hide-search="true">
                                        <option value="0" {{ $link->domain_id == 0 ? 'selected' : '' }}>Default Domain ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                                        @foreach($domains as $domain)
                                            <option value="{{ $domain->id }}" {{ $link->domain_id == $domain->id ? 'selected' : '' }}>{{ $domain->host }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-7 fw-semibold text-gray-900 required">Alias URL</label>
                                    <input type="text" name="url" id="url" class="form-control form-control-sm form-control-solid" value="{{ $link->url }}" required />
                                </div>
                            </div>

                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900 required">Page Title</label>
                                <input type="text" name="settings[title]" class="form-control form-control-sm form-control-solid" value="{{ $link->settings['title'] ?? '' }}" required placeholder="e.g. CS Fast Response" />
                            </div>

                            <div class="fv-row mb-6">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Subtitle / Description</label>
                                <textarea name="settings[description]" class="form-control form-control-sm form-control-solid" rows="2" placeholder="e.g. Please fill the form to chat with our admin team.">{{ $link->settings['description'] ?? '' }}</textarea>
                            </div>

                            <div class="separator separator-dashed my-5"></div>
                            
                            <h4 class="fw-bold text-gray-900 mb-5">Rotation & WhatsApp Settings</h4>

                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900 required">Target WhatsApp Numbers (Rotated)</label>
                                <textarea name="settings[numbers]" class="form-control form-control-sm form-control-solid" rows="3" required placeholder="One per line or comma-separated (e.g. 628123456789, 628987654321)">{{ $link->settings['numbers'] ?? '' }}</textarea>
                                <div class="form-text text-muted fs-8">Use international format without + (628xxxxxxxx). Distributed round-robin.</div>
                            </div>

                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900 required">Message Template</label>
                                <textarea name="settings[template]" class="form-control form-control-sm form-control-solid" rows="3" required placeholder="Hello admin, my name is [nama]...">{{ $link->settings['template'] ?? '' }}</textarea>
                                <div class="form-text text-muted fs-8">Placeholders: <code>[nama]</code>, <code>[kota]</code>, <code>[nomor]</code>, <code>[pesan]</code>.</div>
                            </div>

                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900 required">Form Button Text</label>
                                <input type="text" name="settings[button_text]" class="form-control form-control-sm form-control-solid" value="{{ $link->settings['button_text'] ?? 'Claim Promo sekarang' }}" required placeholder="Contact CS Now" />
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-6">
                                <button type="submit" class="btn btn-sm btn-primary fw-bold">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Design Styling -->
            <div class="tab-pane fade" id="styling-pane" role="tabpanel">
                <div class="card card-flush shadow-sm border-0 mb-4">
                    <div class="card-body p-6">
                        <form action="{{ route('warotators.settings.update', $link->id) }}" method="POST" id="rotatorStylingForm">
                            @csrf
                            @method('PUT')

                            <h4 class="fw-bold text-gray-900 mb-5">Page Background</h4>
                            
                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Background Type</label>
                                <select name="settings[bg_type]" id="bg_type" class="form-select form-select-sm form-select-solid">
                                    <option value="solid" {{ ($link->settings['bg_type'] ?? 'solid') == 'solid' ? 'selected' : '' }}>Solid Color</option>
                                    <option value="gradient" {{ ($link->settings['bg_type'] ?? 'solid') == 'gradient' ? 'selected' : '' }}>Linear Gradient</option>
                                    <option value="abstract_blobs" {{ ($link->settings['bg_type'] ?? 'solid') == 'abstract_blobs' ? 'selected' : '' }}>Abstract Blobs (Mesh Gradient)</option>
                                </select>
                            </div>

                            <!-- Solid BG Color Picker -->
                            <div class="fv-row mb-4" id="solidBgField">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Background Color</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[bg_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['bg_color'] ?? '#f3f4f6' }}">
                                    <input type="text" class="form-control form-control-sm form-control-solid w-150px" readonly value="{{ $link->settings['bg_color'] ?? '#f3f4f6' }}">
                                </div>
                            </div>

                            <!-- Gradient BG Color Pickers -->
                            <div class="row g-4 mb-4 d-none" id="gradientBgFields">
                                <div class="col-md-6">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Gradient Start</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="settings[bg_gradient_start]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['bg_gradient_start'] ?? '#a4e5bd' }}">
                                        <input type="text" class="form-control form-control-sm form-control-solid" readonly value="{{ $link->settings['bg_gradient_start'] ?? '#a4e5bd' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Gradient End</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="settings[bg_gradient_end]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['bg_gradient_end'] ?? '#2ac3a6' }}">
                                        <input type="text" class="form-control form-control-sm form-control-solid" readonly value="{{ $link->settings['bg_gradient_end'] ?? '#2ac3a6' }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Abstract Blobs Settings -->
                            <div id="abstractBlobsFields" class="d-none">
                                <div class="fv-row mb-4">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Blob Base Color</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="settings[bg_blob_base]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['bg_blob_base'] ?? '#f8fafc' }}">
                                        <input type="text" class="form-control form-control-sm form-control-solid w-150px" readonly value="{{ $link->settings['bg_blob_base'] ?? '#f8fafc' }}">
                                    </div>
                                </div>
                                <div class="fv-row mb-4">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Blob 1 (Top Left)</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="settings[bg_blob_1]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['bg_blob_1'] ?? '#3b82f6' }}">
                                        <input type="text" class="form-control form-control-sm form-control-solid w-150px" readonly value="{{ $link->settings['bg_blob_1'] ?? '#3b82f6' }}">
                                    </div>
                                </div>
                                <div class="fv-row mb-4">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Blob 2 (Center Right)</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="settings[bg_blob_2]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['bg_blob_2'] ?? '#ec4899' }}">
                                        <input type="text" class="form-control form-control-sm form-control-solid w-150px" readonly value="{{ $link->settings['bg_blob_2'] ?? '#ec4899' }}">
                                    </div>
                                </div>
                                <div class="fv-row mb-4">
                                    <label class="form-label fs-7 fw-semibold text-gray-900">Blob 3 (Bottom Right)</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="settings[bg_blob_3]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['bg_blob_3'] ?? '#8b5cf6' }}">
                                        <input type="text" class="form-control form-control-sm form-control-solid w-150px" readonly value="{{ $link->settings['bg_blob_3'] ?? '#8b5cf6' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-5"></div>

                            <h4 class="fw-bold text-gray-900 mb-5">Buttons & Texts</h4>

                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Button Background Color</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[btn_bg_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['btn_bg_color'] ?? '#2ac3a6' }}">
                                    <input type="text" class="form-control form-control-sm form-control-solid w-150px" readonly value="{{ $link->settings['btn_bg_color'] ?? '#2ac3a6' }}">
                                </div>
                            </div>

                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Button Text Color</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[btn_text_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['btn_text_color'] ?? '#ffffff' }}">
                                    <input type="text" class="form-control form-control-sm form-control-solid w-150px" readonly value="{{ $link->settings['btn_text_color'] ?? '#ffffff' }}">
                                </div>
                            </div>

                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Title Text Color</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[text_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; cursor: pointer;" value="{{ $link->settings['text_color'] ?? '#111827' }}">
                                    <input type="text" class="form-control form-control-sm form-control-solid w-150px" readonly value="{{ $link->settings['text_color'] ?? '#111827' }}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-6">
                                <button type="submit" class="btn btn-sm btn-primary fw-bold">Save Styling</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Profile Images -->
            <div class="tab-pane fade" id="profile-pane" role="tabpanel">
                <div class="card card-flush shadow-sm border-0 mb-4">
                    <div class="card-body p-6">
                        <!-- Cover/Banner Image Upload Dropzone -->
                        <h4 class="fw-bold text-gray-900 mb-4">Header Banner</h4>
                        <form action="{{ route('warotators.settings.update', $link->id) }}" method="POST" enctype="multipart/form-data" class="profile-upload-form mb-6">
                            @csrf
                            @method('PUT')
                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Choose Banner Image</label>
                                <div class="drag-drop-zone" id="banner-dropzone">
                                    <i class="ki-outline ki-file-up fs-2x text-muted mb-2"></i>
                                    <p class="mb-1 fs-7 fw-semibold text-gray-800">Drag & drop image here or click to browse</p>
                                    <p class="mb-0 text-muted fs-8">Supports JPEG, PNG, JPG, GIF, WebP (Max 4MB)</p>
                                    <div class="image-preview-container d-none mt-2">
                                        <img class="img-preview rounded-3" style="max-height: 120px; max-width: 100%; object-fit: contain;" src="">
                                    </div>
                                    <input type="file" name="cover" id="cover-file-input" accept="image/*">
                                </div>
                            </div>
                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Or use Banner Image URL</label>
                                <input type="url" name="settings[banner_url]" id="banner-url-input" class="form-control form-control-sm form-control-solid" placeholder="https://example.com/banner.jpg" value="{{ $link->settings['banner_url'] ?? '' }}">
                                <div class="banner-url-preview-container {{ empty($link->settings['banner_url']) ? 'd-none' : '' }} mt-2">
                                    <img class="banner-url-preview rounded-3" style="max-height: 120px; max-width: 100%; object-fit: contain;" src="{{ $link->settings['banner_url'] ?? '' }}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary fw-bold">Upload Banner</button>
                            </div>
                        </form>

                        <div class="separator separator-dashed my-6"></div>

                        <!-- Avatar Upload Dropzone -->
                        <h4 class="fw-bold text-gray-900 mb-4">Profile Avatar</h4>
                        <form action="{{ route('warotators.settings.update', $link->id) }}" method="POST" enctype="multipart/form-data" class="profile-upload-form">
                            @csrf
                            @method('PUT')
                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Choose Avatar Image</label>
                                <div class="drag-drop-zone" id="avatar-dropzone">
                                    <i class="ki-outline ki-user-square fs-2x text-muted mb-2"></i>
                                    <p class="mb-1 fs-7 fw-semibold text-gray-800">Drag & drop avatar here or click to browse</p>
                                    <p class="mb-0 text-muted fs-8">Cropped automatically to 1:1 square ratio</p>
                                    <div class="image-preview-container d-none mt-2">
                                        <img class="img-preview rounded-circle" style="height: 100px; width: 100px; object-fit: cover;" src="">
                                    </div>
                                    <input type="file" name="avatar" id="avatar-file-input" accept="image/*">
                                </div>
                            </div>
                            <div class="fv-row mb-4">
                                <label class="form-label fs-7 fw-semibold text-gray-900">Or use Avatar Image URL</label>
                                <input type="url" name="settings[avatar_url]" id="avatar-url-input" class="form-control form-control-sm form-control-solid" placeholder="https://example.com/avatar.jpg" value="{{ $link->settings['avatar_url'] ?? '' }}">
                                <div class="avatar-url-preview-container {{ empty($link->settings['avatar_url']) ? 'd-none' : '' }} mt-2">
                                    <img class="avatar-url-preview rounded-circle" style="height: 100px; width: 100px; object-fit: cover;" src="{{ $link->settings['avatar_url'] ?? '' }}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary fw-bold">Upload Avatar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Right Panel: Live Mobile Mockup Preview Frame -->
    <div class="col-lg-5 col-xl-4 d-none d-lg-flex justify-content-center align-items-start ps-lg-5 pb-10" style="padding-top: 58px;">
        <div class="mockup-container position-relative shadow-2xl overflow-hidden" style="width: 340px; height: calc(100vh - 200px); max-height: 660px; min-height: 500px; border-radius: 36px; border: 12px solid #111827; background: #000; flex-shrink: 0; position: sticky; top: 90px; z-index: 5; margin-bottom: 2rem;">
            <!-- Camera Notch -->
            <div class="position-absolute start-50 translate-middle-x" style="top: 0; width: 120px; height: 20px; background: #111827; border-radius: 0 0 12px 12px; z-index: 5;"></div>
            
            <!-- Iframe Loading spinner -->
            <div class="iframe-spinner position-absolute top-50 start-50 translate-middle text-primary d-none">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            
            <!-- Real-time landing page frame -->
            <iframe id="livePreviewFrame" src="{{ $previewUrl }}" class="w-100 h-100 border-0 bg-white" style="border-radius: 24px;"></iframe>
        </div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="cropModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h3 class="modal-title fw-bold text-gray-900" id="cropModalLabel">
                    Crop Avatar Photo (1:1 Ratio)
                </h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body py-6 text-center">
                <div class="img-container d-flex justify-content-center align-items-center" style="max-height: 380px; overflow: hidden; border-radius: 8px;">
                    <img id="imageToCrop" src="" style="max-width: 100%; display: block;">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-6">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="cropButton" class="btn btn-primary fw-bold">Crop & Save</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const previewFrame = document.getElementById('livePreviewFrame');
        const spinner = document.querySelector('.iframe-spinner');

        // Function: Force refresh preview iframe
        function reloadPreview() {
            if (previewFrame) {
                spinner.classList.remove('d-none');
                
                // Add timestamp to prevent caching
                const urlObj = new URL(previewFrame.src);
                urlObj.searchParams.set('t', Date.now());
                previewFrame.src = urlObj.toString();
            }
        }

        // Live preview updates without iframe reload
        function updateLivePreview() {
            if (!previewFrame) return;
            const iframeDoc = previewFrame.contentDocument || previewFrame.contentWindow.document;
            if (!iframeDoc) return;
            const iframeBody = iframeDoc.body;
            if (!iframeBody) return;

            // 1. Texts
            const titleInput = document.querySelector('input[name="settings[title]"]');
            const descInput = document.querySelector('textarea[name="settings[description]"]');
            const btnTextInput = document.querySelector('input[name="settings[button_text]"]');

            const previewTitle = iframeDoc.querySelector('.rotator-title');
            const previewDesc = iframeDoc.querySelector('.rotator-desc');
            const previewBtn = iframeDoc.querySelector('.rotator-btn');

            if (titleInput && previewTitle) previewTitle.textContent = titleInput.value;
            if (descInput && previewDesc) previewDesc.textContent = descInput.value;
            if (btnTextInput && previewBtn) previewBtn.textContent = btnTextInput.value;

            // 2. Colors and CSS Variables
            const btnBgColor = document.querySelector('input[name="settings[btn_bg_color]"]').value;
            const btnTextColor = document.querySelector('input[name="settings[btn_text_color]"]').value;
            const textColor = document.querySelector('input[name="settings[text_color]"]').value;
            
            const labelColor = document.querySelector('input[name="settings[form_label_color]"]').value;
            const inputTextColor = document.querySelector('input[name="settings[form_input_text_color]"]').value;
            const inputBgColor = document.querySelector('input[name="settings[form_input_bg_color]"]').value;
            const inputBgActiveColor = document.querySelector('input[name="settings[form_input_bg_active_color]"]').value;
            const inputBorderActiveColor = document.querySelector('input[name="settings[form_input_border_active_color]"]').value;

            // Apply variables to body and documentElement
            iframeBody.style.setProperty('--primary-color', btnBgColor);
            iframeBody.style.setProperty('--btn-text-color', btnTextColor);
            iframeBody.style.setProperty('--text-color', textColor);
            
            iframeDoc.documentElement.style.setProperty('--primary-color', btnBgColor);
            iframeDoc.documentElement.style.setProperty('--btn-text-color', btnTextColor);
            iframeDoc.documentElement.style.setProperty('--text-color', textColor);
            
            iframeDoc.documentElement.style.setProperty('--form-label-color', labelColor);
            iframeDoc.documentElement.style.setProperty('--form-input-text-color', inputTextColor);
            iframeDoc.documentElement.style.setProperty('--form-input-bg-color', inputBgColor);
            iframeDoc.documentElement.style.setProperty('--form-input-bg-active-color', inputBgActiveColor);
            iframeDoc.documentElement.style.setProperty('--form-input-border-active-color', inputBorderActiveColor);

            // 3. Background type
            const bgType = document.getElementById('bg_type').value;
            const solidColor = document.querySelector('input[name="settings[bg_color]"]').value;
            const gradStart = document.querySelector('input[name="settings[bg_gradient_start]"]').value;
            const gradEnd = document.querySelector('input[name="settings[bg_gradient_end]"]').value;
            const blobBase = document.querySelector('input[name="settings[bg_blob_base]"]').value;
            const blob1 = document.querySelector('input[name="settings[bg_blob_1]"]').value;
            const blob2 = document.querySelector('input[name="settings[bg_blob_2]"]').value;
            const blob3 = document.querySelector('input[name="settings[bg_blob_3]"]').value;

            const blobContainer = iframeDoc.querySelector('.blob-bg-container');

            if (bgType === 'gradient') {
                if (blobContainer) blobContainer.style.display = 'none';
                iframeBody.style.background = 'linear-gradient(135deg, ' + gradStart + ' 0%, ' + gradEnd + ' 100%)';
            } else if (bgType === 'abstract_blobs') {
                iframeBody.style.background = blobBase;
                if (blobContainer) {
                    blobContainer.style.display = 'block';
                    const b1 = blobContainer.querySelector('.blob-1');
                    const b2 = blobContainer.querySelector('.blob-2');
                    const b3 = blobContainer.querySelector('.blob-3');
                    if (b1) b1.style.background = blob1;
                    if (b2) b2.style.background = blob2;
                    if (b3) b3.style.background = blob3;
                }
            } else {
                if (blobContainer) blobContainer.style.display = 'none';
                iframeBody.style.background = solidColor;
            }

            // 4. Banner image (URL)
            const bannerUrlInput = document.getElementById('banner-url-input');
            const previewBanner = iframeDoc.querySelector('.rotator-banner');
            if (bannerUrlInput && previewBanner) {
                if (bannerUrlInput.value) {
                    previewBanner.style.backgroundImage = "url('" + bannerUrlInput.value + "')";
                    previewBanner.style.display = 'block';
                } else if (!document.getElementById('banner-file-input').files.length) {
                    previewBanner.style.display = 'none';
                }
            }

            // 5. Avatar image (URL)
            const avatarUrlInput = document.getElementById('avatar-url-input');
            const previewAvatarImg = iframeDoc.querySelector('.avatar');
            const previewAvatarWrapper = iframeDoc.querySelector('.avatar-wrapper');
            if (avatarUrlInput && previewAvatarImg && previewAvatarWrapper) {
                if (avatarUrlInput.value) {
                    previewAvatarImg.src = avatarUrlInput.value;
                    previewAvatarWrapper.style.display = 'flex';
                } else if (!croppedAvatarBlob && !document.getElementById('avatar-file-input').files.length) {
                    previewAvatarWrapper.style.display = 'none';
                }
            }
        }

        // Bind live update listener to inputs
        const liveInputs = [
            'input[name="settings[title]"]',
            'textarea[name="settings[description]"]',
            'input[name="settings[button_text]"]',
            'select[name="settings[bg_type]"]',
            'input[name="settings[bg_color]"]',
            'input[name="settings[bg_gradient_start]"]',
            'input[name="settings[bg_gradient_end]"]',
            'input[name="settings[bg_blob_base]"]',
            'input[name="settings[bg_blob_1]"]',
            'input[name="settings[bg_blob_2]"]',
            'input[name="settings[bg_blob_3]"]',
            'input[name="settings[btn_bg_color]"]',
            'input[name="settings[btn_text_color]"]',
            'input[name="settings[text_color]"]',
            'input[name="settings[form_label_color]"]',
            'input[name="settings[form_input_text_color]"]',
            'input[name="settings[form_input_bg_color]"]',
            'input[name="settings[form_input_bg_active_color]"]',
            'input[name="settings[form_input_border_active_color]"]',
            '#banner-url-input',
            '#avatar-url-input'
        ];

        liveInputs.forEach(selector => {
            const el = document.querySelector(selector);
            if (el) {
                el.addEventListener('input', updateLivePreview);
                el.addEventListener('change', updateLivePreview);
            }
        });

        // Hide spinner when preview loads
        if (previewFrame) {
            previewFrame.addEventListener('load', function() {
                spinner.classList.add('d-none');
                updateLivePreview();
            });
        }

        // Intercept form submissions via AJAX to prevent page reloads
        $('#rotatorSettingsForm, #rotatorStylingForm, .profile-upload-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const origText = submitBtn.text();

            submitBtn.prop('disabled', true).text('Menyimpan...');

            const formData = new FormData(form[0]);

            // Replace avatar with cropped blob if available
            if (croppedAvatarBlob && form.find('#avatar-file-input').length > 0) {
                formData.delete('avatar');
                formData.append('avatar', croppedAvatarBlob, 'avatar.jpg');
            }

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method') || 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    submitBtn.prop('disabled', false).text(origText);
                    if (res.success) {
                        if (window.showSwalToast) {
                            window.showSwalToast(res.message, 'success');
                        } else if (window.showSwal) {
                            window.showSwal('success', res.message, true);
                        } else {
                            Swal.fire({ text: res.message, icon: 'success', buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary btn-sm' } });
                        }
                        
                        // Force live preview frame refresh
                        reloadPreview();

                        // Reset cropped blob after success
                        if (form.find('#avatar-file-input').length > 0) {
                            croppedAvatarBlob = null;
                        }
                    } else {
                        Swal.fire({ text: res.message || 'Gagal menyimpan pengaturan.', icon: 'error', buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger btn-sm' } });
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).text(origText);
                    Swal.fire({ text: 'Gagal mengirim data. Silakan coba lagi.', icon: 'error', buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger btn-sm' } });
                }
            });
        });

        // Background type change picker
        const bgTypeSelect = document.getElementById('bg_type');
        const solidBgField = document.getElementById('solidBgField');
        const gradientBgFields = document.getElementById('gradientBgFields');
        const abstractBlobsFields = document.getElementById('abstractBlobsFields');

        if (bgTypeSelect) {
            bgTypeSelect.addEventListener('change', function() {
                if (this.value === 'gradient') {
                    solidBgField.classList.add('d-none');
                    gradientBgFields.classList.remove('d-none');
                    abstractBlobsFields.classList.add('d-none');
                } else if (this.value === 'abstract_blobs') {
                    solidBgField.classList.add('d-none');
                    gradientBgFields.classList.add('d-none');
                    abstractBlobsFields.classList.remove('d-none');
                } else {
                    solidBgField.classList.remove('d-none');
                    gradientBgFields.classList.add('d-none');
                    abstractBlobsFields.classList.add('d-none');
                }
            });
            // Initial run
            bgTypeSelect.dispatchEvent(new Event('change'));
        }

        // Keep color pickers text input representation synced
        $('input[type="color"]').on('input', function() {
            $(this).next('input[type="text"]').val(this.value);
        });

        // ─── Cropper.js Variables & Handlers ───
        let cropper = null;
        let croppedAvatarBlob = null;

        $('#cropModal').on('shown.bs.modal', function() {
            const image = document.getElementById('imageToCrop');
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }).on('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        $('#cropButton').on('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                });
                
                canvas.toBlob(function(blob) {
                    croppedAvatarBlob = blob;
                    
                    // Show preview in dropzone
                    const dropzone = $('#avatar-dropzone');
                    const textEl = dropzone.find('.dropzone-text');
                    const iconEl = dropzone.find('.drag-drop-icon');
                    const previewContainer = dropzone.find('.image-preview-container');
                    const previewImg = dropzone.find('.img-preview');
                    
                    const fileName = document.getElementById('avatar-file-input').files[0]?.name || 'avatar.jpg';
                    textEl.html('✓ Berkas terpilih (Telah dipotong): <span class="text-success">' + fileName + '</span>');
                    
                    const blobUrl = URL.createObjectURL(blob);
                    previewImg.attr('src', blobUrl);
                    previewContainer.removeClass('d-none');
                    iconEl.addClass('d-none');

                    // Real-time update in live preview iframe
                    if (previewFrame) {
                        const iframeDoc = previewFrame.contentDocument || previewFrame.contentWindow.document;
                        if (iframeDoc) {
                            const previewAvatarImg = iframeDoc.querySelector('.avatar');
                            const previewAvatarWrapper = iframeDoc.querySelector('.avatar-wrapper');
                            if (previewAvatarImg && previewAvatarWrapper) {
                                previewAvatarImg.src = blobUrl;
                                previewAvatarWrapper.style.display = 'flex';
                            }
                        }
                    }
                    
                    $('#cropModal').modal('hide');
                }, 'image/jpeg');
            }
        });

        // ─── Drag & Drop Event Listeners with Image Previews ───
        $('.drag-drop-zone').on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });

        $('.drag-drop-zone').on('dragleave dragend drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });

        $('.drag-drop-zone input[type="file"]').on('change', function() {
            const input = this;
            const dropzone = $(input).closest('.drag-drop-zone');
            const isAvatar = input.id === 'avatar-file-input';
            const textEl = dropzone.find('.dropzone-text');
            const iconEl = dropzone.find('.drag-drop-icon');
            const previewContainer = dropzone.find('.image-preview-container');
            const previewImg = dropzone.find('.img-preview');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileName = file.name;
                
                if (isAvatar) {
                    // Open Crop Modal
                    const imageToCrop = document.getElementById('imageToCrop');
                    imageToCrop.src = URL.createObjectURL(file);
                    $('#cropModal').modal('show');
                } else {
                    textEl.html('✓ Berkas terpilih: <span class="text-success">' + fileName + '</span>');
                    // Live FileReader Preview for banner
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.attr('src', e.target.result);
                        previewContainer.removeClass('d-none');
                        iconEl.addClass('d-none');

                        // Real-time update in live preview iframe
                        if (previewFrame) {
                            const iframeDoc = previewFrame.contentDocument || previewFrame.contentWindow.document;
                            if (iframeDoc) {
                                const previewBanner = iframeDoc.querySelector('.rotator-banner');
                                if (previewBanner) {
                                    previewBanner.style.backgroundImage = "url('" + e.target.result + "')";
                                    previewBanner.style.display = 'block';
                                }
                            }
                        }
                    }
                    reader.readAsDataURL(file);
                }
            } else {
                textEl.text('Tarik & lepas gambar di sini atau klik untuk memilih');
                previewContainer.addClass('d-none');
                previewImg.attr('src', '');
                iconEl.removeClass('d-none');

                // Real-time clear in live preview iframe
                if (previewFrame) {
                    const iframeDoc = previewFrame.contentDocument || previewFrame.contentWindow.document;
                    if (iframeDoc) {
                        if (isAvatar) {
                            croppedAvatarBlob = null;
                            const previewAvatarWrapper = iframeDoc.querySelector('.avatar-wrapper');
                            if (previewAvatarWrapper) previewAvatarWrapper.style.display = 'none';
                        } else {
                            const previewBanner = iframeDoc.querySelector('.rotator-banner');
                            if (previewBanner) previewBanner.style.display = 'none';
                        }
                    }
                }
            }
        });

        // ─── URL Inputs Live Preview Handlers ───
        function handleUrlInputPreview(inputId, containerClass, imgClass) {
            const input = document.getElementById(inputId);
            if (input) {
                const checkPreview = () => {
                    const val = input.value.trim();
                    const container = $(input).siblings('.' + containerClass);
                    const img = container.find('.' + imgClass);
                    if (val) {
                        img.attr('src', val);
                        container.removeClass('d-none');
                    } else {
                        container.addClass('d-none');
                        img.attr('src', '');
                    }
                };
                
                input.addEventListener('input', checkPreview);
                input.addEventListener('change', checkPreview);
            }
        }
        
        handleUrlInputPreview('banner-url-input', 'banner-url-preview-container', 'banner-url-preview');
        handleUrlInputPreview('avatar-url-input', 'avatar-url-preview-container', 'avatar-url-preview');
    });
</script>
@endsection
