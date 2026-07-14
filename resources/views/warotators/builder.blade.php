@extends('layouts.app')

@section('title', 'WA Rotator Builder')

@section('content')
<div class="row g-4 h-100 align-items-stretch">
    <!-- Left Panel: Builder Options & Settings -->
    <div class="col-lg-6 d-flex flex-column" style="max-height: calc(100vh - 120px); overflow-y: auto;">
        
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

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Pilihan Kota / Kabupaten (Pisah Koma)</label>
                            <textarea name="settings[cities]" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" rows="2" placeholder="Contoh: Jakarta, Bandung, Surabaya, Yogyakarta, Semarang, Medan">{{ $link->settings['cities'] ?? 'Jakarta, Bandung, Surabaya, Yogyakarta, Semarang, Medan' }}</textarea>
                            <div class="form-text text-muted" style="font-size: 0.725rem;">Pisahkan pilihan opsi kota dropdown menggunakan tanda koma.</div>
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
                            <input type="file" name="cover" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Atau gunakan URL Gambar Banner</label>
                            <input type="url" name="settings[banner_url]" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" placeholder="https://example.com/banner.jpg" value="{{ $link->settings['banner_url'] ?? '' }}">
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
                            <input type="file" name="avatar" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Atau gunakan URL Foto Profil</label>
                            <input type="url" name="settings[avatar_url]" class="form-control bg-transparent border border-secondary border-opacity-15 py-2 rounded-3 small" placeholder="https://example.com/avatar.jpg" value="{{ $link->settings['avatar_url'] ?? '' }}">
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
    <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center" style="position: sticky; top: 100px; height: calc(100vh - 120px);">
        <div class="mockup-container position-relative shadow-2xl overflow-hidden" style="width: 320px; height: 640px; border-radius: 40px; border: 12px solid #111827; background: #000; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4);">
            <!-- Iframe Loading spinner -->
            <div class="iframe-spinner position-absolute top-50 start-50 translate-middle text-success d-none">
                <div class="spinner-border" role="status"></div>
            </div>
            
            <!-- Real-time landing page frame -->
            <iframe id="livePreviewFrame" src="{{ $fullUrl }}" class="w-100 h-100 border-0 bg-white" style="border-radius: 28px;"></iframe>
        </div>
    </div>
</div>

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

        // Hide spinner when preview loads
        if (previewFrame) {
            previewFrame.addEventListener('load', function() {
                spinner.classList.add('d-none');
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

        if (bgTypeSelect) {
            bgTypeSelect.addEventListener('change', function() {
                if (this.value === 'gradient') {
                    solidBgField.classList.add('d-none');
                    gradientBgFields.classList.remove('d-none');
                } else {
                    solidBgField.classList.remove('d-none');
                    gradientBgFields.classList.add('d-none');
                }
            });
            // Initial run
            bgTypeSelect.dispatchEvent(new Event('change'));
        }

        // Keep color pickers text input representation synced
        $('input[type="color"]').on('input', function() {
            $(this).next('input[type="text"]').val(this.value);
        });
    });
</script>
@endsection
