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
<div class="row g-4 h-100 align-items-stretch">
    <!-- Left Panel: Builder Options & Settings -->
    <div class="col-lg-6 d-flex flex-column border-end border-secondary border-opacity-10 pe-lg-5" style="max-height: calc(100vh - 120px); overflow-y: auto;">
        
        <!-- Breadcrumb / Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('warotators.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1.5 mb-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Kembali ke Dashboard
                </a>
                <h4 class="fw-bold mb-0 text-dark-custom" style="letter-spacing: -0.5px;">WA Rotator Builder</h4>
            </div>
            <div>
                @php
                    $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
                @endphp
                <a href="{{ $fullUrl }}" target="_blank" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-2 fw-semibold rounded-3 px-3 py-2 shadow-sm text-secondary" style="font-size: 0.825rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    Buka Halaman
                </a>
            </div>
        </div>

        <!-- Tab Controls Navigation -->
        <ul class="nav nav-tabs glass-tabs mb-4" id="builderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings-pane" type="button" role="tab" aria-selected="true">
                    Rotator
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="styling-tab" data-bs-toggle="tab" data-bs-target="#styling-pane" type="button" role="tab" aria-selected="false">
                    Desain Tampilan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-pane" type="button" role="tab" aria-selected="false">
                    Avatar & Gambar
                </button>
            </li>
        </ul>

        <!-- Tab Content Panes -->
        <div class="tab-content flex-grow-1" id="builderTabsContent">
            
            <!-- TAB 1: Rotator Settings -->
            <div class="tab-pane fade show active" id="settings-pane" role="tabpanel" tabindex="0">
                <div class="glass-card p-4 border border-secondary border-opacity-10 rounded-3 mb-4" style="background: var(--card-bg-blur);">
                    <form action="{{ route('warotators.settings.update', $link->id) }}" method="POST" id="rotatorSettingsForm">
                        @csrf
                        @method('PUT')
                        
                        <h6 class="fw-bold text-dark-custom mb-3">Informasi Utama Halaman</h6>

                        <!-- Domain & Path Alias -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Domain</label>
                                <select name="domain_id" id="domain_id" class="form-select bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 text-secondary small">
                                    <option value="0" {{ $link->domain_id == 0 ? 'selected' : '' }}>Domain Bawaan ({{ parse_url(url('/'), PHP_URL_HOST) }})</option>
                                    @foreach($domains as $domain)
                                        <option value="{{ $domain->id }}" {{ $link->domain_id == $domain->id ? 'selected' : '' }}>{{ $domain->host }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Alias URL <span class="text-danger">*</span></label>
                                <input type="text" name="url" id="url" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" value="{{ $link->url }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Judul Halaman <span class="text-danger">*</span></label>
                            <input type="text" name="settings[title]" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" value="{{ $link->settings['title'] ?? '' }}" required placeholder="Contoh: CS Fast Response">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Deskripsi / Subtitle Halaman</label>
                            <textarea name="settings[description]" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" rows="2" placeholder="Contoh: Silakan isi nama dan nomor WA untuk berkonsultasi gratis.">{{ $link->settings['description'] ?? '' }}</textarea>
                        </div>

                        <hr class="my-4 border-secondary border-opacity-15">
                        
                        <h6 class="fw-bold text-dark-custom mb-3">Pengaturan Rotasi & Form WhatsApp</h6>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nomor Tujuan WhatsApp (Dirotasi) <span class="text-danger">*</span></label>
                            <textarea name="settings[numbers]" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" rows="3" required placeholder="Satu nomor per baris atau dipisah koma (Contoh: 628123456789, 628987654321)">{{ $link->settings['numbers'] ?? '' }}</textarea>
                            <div class="form-text text-muted" style="font-size: 0.725rem;">Gunakan format kode negara tanpa simbol + (Contoh: 628xxxxxxxx). Pengiriman pesan didistribusikan secara round-robin adil ke setiap nomor ini.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Template Pesan WhatsApp <span class="text-danger">*</span></label>
                            <textarea name="settings[template]" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" rows="3" required placeholder="Contoh: Halo admin, nama saya [nama] dari [kota].">{{ $link->settings['template'] ?? '' }}</textarea>
                            <div class="form-text text-muted" style="font-size: 0.725rem;">Gunakan placeholders dinamis: <code>[nama]</code>, <code>[kota]</code>, <code>[nomor]</code>, <code>[pesan]</code>.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Teks Tombol Form <span class="text-danger">*</span></label>
                            <input type="text" name="settings[button_text]" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" value="{{ $link->settings['button_text'] ?? 'Claim Promo sekarang' }}" required placeholder="Contoh: Hubungi CS Sekarang">
                        </div>



                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold small" style="background-color: var(--primary-color); border-color: var(--primary-color);">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 2: Design Styling -->
            <div class="tab-pane fade" id="styling-pane" role="tabpanel" tabindex="0">
                <div class="glass-card p-4 border border-secondary border-opacity-10 rounded-3 mb-4" style="background: var(--card-bg-blur);">
                    <form action="{{ route('warotators.settings.update', $link->id) }}" method="POST" id="rotatorStylingForm">
                        @csrf
                        @method('PUT')

                        <h6 class="fw-bold text-dark-custom mb-3">Latar Belakang Halaman (Background)</h6>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Tipe Latar Belakang</label>
                            <select name="settings[bg_type]" id="bg_type" class="form-select bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 text-secondary small">
                                <option value="solid" {{ ($link->settings['bg_type'] ?? 'solid') == 'solid' ? 'selected' : '' }}>Warna Solid</option>
                                <option value="gradient" {{ ($link->settings['bg_type'] ?? 'solid') == 'gradient' ? 'selected' : '' }}>Warna Gradasi (Linear Gradient)</option>
                                <option value="abstract_blobs" {{ ($link->settings['bg_type'] ?? 'solid') == 'abstract_blobs' ? 'selected' : '' }}>Abstract Blobs (Mesh Gradient)</option>
                            </select>
                        </div>

                        <!-- Solid BG Color Picker -->
                        <div class="mb-3" id="solidBgField">
                            <label class="form-label small fw-semibold text-secondary">Warna Latar Belakang</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[bg_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['bg_color'] ?? '#f3f4f6' }}">
                                <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['bg_color'] ?? '#f3f4f6' }}">
                            </div>
                        </div>

                        <!-- Gradient BG Color Pickers -->
                        <div class="row mb-3 d-none" id="gradientBgFields">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Gradasi Mulai (Start)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[bg_gradient_start]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['bg_gradient_start'] ?? '#a4e5bd' }}">
                                    <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" readonly value="{{ $link->settings['bg_gradient_start'] ?? '#a4e5bd' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Gradasi Selesai (End)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[bg_gradient_end]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['bg_gradient_end'] ?? '#2ac3a6' }}">
                                    <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" readonly value="{{ $link->settings['bg_gradient_end'] ?? '#2ac3a6' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Abstract Blobs Settings -->
                        <div id="abstractBlobsFields" class="d-none">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Warna Dasar Latar Belakang</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[bg_blob_base]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['bg_blob_base'] ?? '#f8fafc' }}">
                                    <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['bg_blob_base'] ?? '#f8fafc' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Warna Blob 1 (Atas Kiri)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[bg_blob_1]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['bg_blob_1'] ?? '#3b82f6' }}">
                                    <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['bg_blob_1'] ?? '#3b82f6' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Warna Blob 2 (Tengah Kanan)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[bg_blob_2]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['bg_blob_2'] ?? '#ec4899' }}">
                                    <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['bg_blob_2'] ?? '#ec4899' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Warna Blob 3 (Bawah Kanan)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="settings[bg_blob_3]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['bg_blob_3'] ?? '#8b5cf6' }}">
                                    <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['bg_blob_3'] ?? '#8b5cf6' }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-secondary border-opacity-15">

                        <h6 class="fw-bold text-dark-custom mb-3">Tombol & Warna Teks</h6>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Warna Tombol (Background)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[btn_bg_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['btn_bg_color'] ?? '#2ac3a6' }}">
                                <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['btn_bg_color'] ?? '#2ac3a6' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Warna Teks Tombol</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[btn_text_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['btn_text_color'] ?? '#ffffff' }}">
                                <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['btn_text_color'] ?? '#ffffff' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Warna Teks Judul Utama</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[text_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['text_color'] ?? '#111827' }}">
                                <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['text_color'] ?? '#111827' }}">
                            </div>
                        </div>

                        <hr class="my-4 border-secondary border-opacity-15">

                        <h6 class="fw-bold text-dark-custom mb-3">Warna Form & Input</h6>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Warna Label Form</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[form_label_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['form_label_color'] ?? '#4b5563' }}">
                                <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['form_label_color'] ?? '#4b5563' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Warna Teks Input</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[form_input_text_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['form_input_text_color'] ?? '#111827' }}">
                                <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['form_input_text_color'] ?? '#111827' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Warna Background Input (Normal)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[form_input_bg_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['form_input_bg_color'] ?? '#f3f4f6' }}">
                                <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['form_input_bg_color'] ?? '#f3f4f6' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Warna Background Input (Active/Focus)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[form_input_bg_active_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['form_input_bg_active_color'] ?? '#ffffff' }}">
                                <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['form_input_bg_active_color'] ?? '#ffffff' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Warna Border Input (Active/Focus)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[form_input_border_active_color]" class="form-control form-control-color border-0 p-0 rounded-circle" style="width: 38px; height: 38px; background: transparent; cursor: pointer;" value="{{ $link->settings['form_input_border_active_color'] ?? '#2ac3a6' }}">
                                <input type="text" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small w-50" readonly value="{{ $link->settings['form_input_border_active_color'] ?? '#2ac3a6' }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold small" style="background-color: var(--primary-color); border-color: var(--primary-color);">Simpan Desain</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 3: Profil Images -->
            <div class="tab-pane fade" id="profile-pane" role="tabpanel" tabindex="0">
                <div class="glass-card p-4 border border-secondary border-opacity-10 rounded-3 mb-4" style="background: var(--card-bg-blur);">
                    
                    <!-- Cover/Banner Image Upload Dropzone -->
                    <h6 class="fw-bold text-dark-custom mb-3">Gambar Header (Banner)</h6>
                    <form action="{{ route('warotators.settings.update', $link->id) }}" method="POST" enctype="multipart/form-data" class="profile-upload-form mb-4">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Pilih File Banner</label>
                            <div class="drag-drop-zone" id="banner-dropzone">
                                <div class="drag-drop-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                </div>
                                <p class="mb-1 small fw-semibold text-dark-custom dropzone-text">Tarik & lepas gambar di sini atau klik untuk memilih</p>
                                <p class="mb-0 text-muted extra-small" style="font-size: 0.7rem;">Mendukung JPEG, PNG, JPG, GIF, WebP (Maks 4MB)</p>
                                <!-- Preview Container inside dropzone -->
                                <div class="image-preview-container d-none mt-2">
                                    <img class="img-preview rounded-3" style="max-height: 120px; max-width: 100%; object-fit: contain; border: 1px solid rgba(0,0,0,0.1);" src="">
                                </div>
                                <input type="file" name="cover" id="cover-file-input" accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Atau gunakan URL Gambar Banner</label>
                            <input type="url" name="settings[banner_url]" id="banner-url-input" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" placeholder="https://example.com/banner.jpg" value="{{ $link->settings['banner_url'] ?? '' }}">
                            <div class="banner-url-preview-container {{ empty($link->settings['banner_url']) ? 'd-none' : '' }} mt-2">
                                <img class="banner-url-preview rounded-3" style="max-height: 120px; max-width: 100%; object-fit: contain; border: 1px solid rgba(0,0,0,0.1);" src="{{ $link->settings['banner_url'] ?? '' }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm px-3 py-2 rounded-3 fw-semibold">Unggah Banner</button>
                        </div>
                    </form>

                    <hr class="my-4 border-secondary border-opacity-15">

                    <!-- Avatar/Logo Image Upload -->
                    <h6 class="fw-bold text-dark-custom mb-3">Gambar Logo / Avatar Profil</h6>
                    <form action="{{ route('warotators.settings.update', $link->id) }}" method="POST" enctype="multipart/form-data" class="profile-upload-form">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Pilih File Foto Profil</label>
                            <div class="drag-drop-zone" id="avatar-dropzone">
                                <div class="drag-drop-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                </div>
                                <p class="mb-1 small fw-semibold text-dark-custom dropzone-text">Tarik & lepas gambar di sini atau klik untuk memilih</p>
                                <p class="mb-0 text-muted extra-small" style="font-size: 0.7rem;">Mendukung JPEG, PNG, JPG, GIF, WebP (Maks 2MB)</p>
                                <!-- Preview Container inside dropzone -->
                                <div class="image-preview-container d-none mt-2">
                                    <img class="img-preview rounded-circle" style="height: 100px; width: 100px; object-fit: cover; border: 1px solid rgba(0,0,0,0.1);" src="">
                                </div>
                                <input type="file" name="avatar" id="avatar-file-input" accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Atau gunakan URL Foto Profil</label>
                            <input type="url" name="settings[avatar_url]" id="avatar-url-input" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" placeholder="https://example.com/avatar.jpg" value="{{ $link->settings['avatar_url'] ?? '' }}">
                            <div class="avatar-url-preview-container {{ empty($link->settings['avatar_url']) ? 'd-none' : '' }} mt-2">
                                <img class="avatar-url-preview rounded-circle" style="height: 100px; width: 100px; object-fit: cover; border: 1px solid rgba(0,0,0,0.1);" src="{{ $link->settings['avatar_url'] ?? '' }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm px-3 py-2 rounded-3 fw-semibold">Unggah Profil</button>
                        </div>
                    </form>

                </div>
            </div>
            
        </div>
    </div>

    <!-- Right Panel: Live Mobile Mockup Preview Frame -->
    <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center ps-lg-5" style="position: sticky; top: 100px; height: calc(100vh - 120px);">
        <div class="mockup-container position-relative shadow-2xl overflow-hidden" style="width: 375px; height: 750px; border-radius: 36px; border: 12px solid #111827; background: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4);">
            <!-- Camera Notch -->
            <div class="position-absolute start-50 translate-middle-x" style="top: 0; width: 120px; height: 20px; background: #111827; border-radius: 0 0 12px 12px; z-index: 100;"></div>
            
            <!-- Iframe Loading spinner -->
            <div class="iframe-spinner position-absolute top-50 start-50 translate-middle text-success d-none">
                <div class="spinner-border" role="status"></div>
            </div>
            
            <!-- Real-time landing page frame -->
            <iframe id="livePreviewFrame" src="{{ $fullUrl }}" class="w-100 h-100 border-0 bg-white" style="border-radius: 28px;"></iframe>
        </div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="cropModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border border-secondary border-opacity-15" style="background: var(--card-bg-blur); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-radius: 16px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.15);">
            <div class="modal-header border-bottom border-secondary border-opacity-15 p-3">
                <h6 class="modal-title fw-bold text-dark-custom m-0 d-flex align-items-center gap-2" id="cropModalLabel" style="font-size: 0.95rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2ac3a6" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.13 1L6 16a2 2 0 0 0 2 2h15"></path><path d="M1 6.13L16 6a2 2 0 0 1 2 2v15"></path></svg>
                    Potong Foto Profil (Aspek Rasio 1:1)
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center" style="background: rgba(0,0,0,0.02);">
                <div class="img-container d-flex justify-content-center align-items-center" style="max-height: 380px; overflow: hidden; border-radius: 8px;">
                    <img id="imageToCrop" src="" style="max-width: 100%; display: block;">
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-15 p-3">
                <button type="button" class="btn btn-sm btn-light border rounded-3 fw-semibold text-secondary px-3 py-1.5" data-bs-dismiss="modal" style="font-size: 0.775rem;">Batal</button>
                <button type="button" id="cropButton" class="btn btn-sm btn-primary rounded-3 fw-semibold px-4 py-1.5" style="background-color: var(--primary-color); border-color: var(--primary-color); font-size: 0.775rem;">Potong & Simpan</button>
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
                        // Toast success notification
                        if (window.showSwal) {
                            window.showSwal('success', res.message, true);
                        } else {
                            alert(res.message);
                        }
                        
                        // Force live preview frame refresh
                        reloadPreview();

                        // Reset cropped blob after success
                        if (form.find('#avatar-file-input').length > 0) {
                            croppedAvatarBlob = null;
                        }
                    } else {
                        alert(res.message || 'Gagal menyimpan pengaturan.');
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).text(origText);
                    alert('Gagal mengirim data. Silakan coba lagi.');
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
