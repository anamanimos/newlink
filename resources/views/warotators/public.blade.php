<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $link->settings['title'] ?? 'WhatsApp Rotator' }}</title>
    
    <!-- Meta Descriptions -->
    <meta name="description" content="{{ $link->settings['description'] ?? 'WhatsApp Rotator' }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --primary-color: {{ $link->settings['btn_bg_color'] ?? '#2ac3a6' }};
            --btn-text-color: {{ $link->settings['btn_text_color'] ?? '#ffffff' }};
            --text-color: {{ $link->settings['text_color'] ?? '#111827' }};
            
            /* Custom Form Input Colors */
            --form-label-color: {{ $link->settings['form_label_color'] ?? '#4b5563' }};
            --form-input-text-color: {{ $link->settings['form_input_text_color'] ?? '#111827' }};
            --form-input-bg-color: {{ $link->settings['form_input_bg_color'] ?? '#f3f4f6' }};
            --form-input-bg-active-color: {{ $link->settings['form_input_bg_active_color'] ?? '#ffffff' }};
            --form-input-border-active-color: {{ $link->settings['form_input_border_active_color'] ?? ($link->settings['btn_bg_color'] ?? '#2ac3a6') }};
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            @if(($link->settings['bg_type'] ?? 'solid') === 'gradient')
                background: linear-gradient(135deg, {{ $link->settings['bg_gradient_start'] ?? '#a4e5bd' }} 0%, {{ $link->settings['bg_gradient_end'] ?? '#2ac3a6' }} 100%);
            @elseif(($link->settings['bg_type'] ?? 'solid') === 'abstract_blobs')
                background-color: {{ $link->settings['bg_blob_base'] ?? '#f8fafc' }};
            @else
                background-color: {{ $link->settings['bg_color'] ?? '#f3f4f6' }};
            @endif
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Abstract Blobs Background styling */
        .blob-bg-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
            pointer-events: none;
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.45;
            pointer-events: none;
            transition: all 0.3s ease;
        }
        .blob-1 {
            top: -10%;
            left: -10%;
            width: 45vw;
            height: 45vw;
            background: {{ $link->settings['bg_blob_1'] ?? '#3b82f6' }};
        }
        .blob-2 {
            top: 35%;
            right: -10%;
            width: 50vw;
            height: 50vw;
            background: {{ $link->settings['bg_blob_2'] ?? '#ec4899' }};
        }
        .blob-3 {
            bottom: -15%;
            right: 15%;
            width: 40vw;
            height: 40vw;
            background: {{ $link->settings['bg_blob_3'] ?? '#8b5cf6' }};
        }


        .container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .avatar-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 16px;
            margin-top: 16px;
            z-index: 10;
        }

        .rotator-banner + .avatar-wrapper {
            margin-top: -60px;
            position: relative;
            z-index: 15;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            background: #e5e7eb;
        }

        .rotator-card {
            width: 100%;
            background: transparent;
            border-radius: 20px;
            padding: 24px;
            box-sizing: border-box;
            color: #1f2937;
            text-align: left;
            margin-bottom: 20px;
        }

        .rotator-banner {
            width: 100%;
            margin: 0 0 20px 0;
            height: 160px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 12px;
            background-color: #e5e7eb;
        }

        .rotator-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 8px;
            color: var(--text-color);
            text-align: center;
            letter-spacing: -0.3px;
        }

        .rotator-desc {
            font-size: 0.85rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .rotator-field {
            margin-bottom: 16px;
            position: relative;
        }

        .rotator-label {
            display: block;
            font-size: 0.775rem;
            font-weight: 600;
            color: var(--form-label-color);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rotator-input-wrapper {
            display: flex;
            align-items: center;
            background: var(--form-input-bg-color);
            border-radius: 12px;
            border: 1.5px solid transparent;
            transition: all 0.2s ease;
        }

        .rotator-input-wrapper:focus-within {
            border-color: var(--form-input-border-active-color);
            background: var(--form-input-bg-active-color);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.06);
        }

        .rotator-input-addon {
            padding: 12px 14px;
            font-weight: 700;
            color: #4b5563;
            font-size: 0.9rem;
            border-right: 1px solid rgba(0, 0, 0, 0.06);
            background: rgba(0, 0, 0, 0.02);
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .rotator-input {
            width: 100%;
            border: none;
            background: transparent;
            padding: 12px 16px;
            font-size: 0.9rem;
            color: var(--form-input-text-color);
            outline: none;
            box-sizing: border-box;
            border-radius: 12px;
        }

        .rotator-select {
            width: 100%;
            border: 1.5px solid transparent;
            background: #f3f4f6;
            padding: 12px 16px;
            font-size: 0.9rem;
            color: #111827;
            outline: none;
            box-sizing: border-box;
            border-radius: 12px;
            appearance: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rotator-select:focus {
            border-color: var(--primary-color);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(42, 195, 166, 0.12);
        }

        .rotator-select-wrapper {
            position: relative;
        }

        .rotator-select-wrapper::after {
            content: "";
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-top-color: #6b7280;
            pointer-events: none;
        }

        .rotator-textarea {
            width: 100%;
            border: 1.5px solid transparent;
            background: var(--form-input-bg-color);
            padding: 12px 16px;
            font-size: 0.9rem;
            color: var(--form-input-text-color);
            outline: none;
            box-sizing: border-box;
            border-radius: 12px;
            resize: vertical;
            min-height: 80px;
            transition: all 0.2s ease;
        }

        .rotator-textarea:focus {
            background: var(--form-input-bg-active-color);
            border-color: var(--form-input-border-active-color);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.06);
        }

        .rotator-btn {
            width: 100%;
            background: var(--primary-color);
            color: var(--btn-text-color);
            border: none;
            border-radius: 14px;
            padding: 14px 20px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
            letter-spacing: 0.2px;
        }

        .rotator-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .rotator-btn:active {
            transform: translateY(0);
        }

        .rotator-btn:disabled {
            background: #cbd5e1;
            box-shadow: none;
            cursor: not-allowed;
            color: #94a3b8;
        }

        .watermark {
            font-size: 0.8rem;
            color: var(--text-color);
            opacity: 0.6;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s ease;
        }

        .watermark:hover {
            opacity: 0.9;
        }

        /* ─── Custom Select2 Overrides ─── */
        .select2-container--default .select2-selection--single {
            background-color: var(--form-input-bg-color) !important;
            border: 1.5px solid transparent !important;
            border-radius: 12px !important;
            height: 48px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.2s ease !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--form-input-text-color) !important;
            font-size: 0.9rem !important;
            padding-left: 16px !important;
            padding-right: 36px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6b7280 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6b7280 transparent transparent transparent !important;
            border-width: 5px 4px 0 4px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--form-input-border-active-color) !important;
            background-color: var(--form-input-bg-active-color) !important;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.06) !important;
        }
        .select2-container--open .select2-dropdown {
            border-color: rgba(0, 0, 0, 0.08) !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
            z-index: 99999 !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            outline: none !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-color) !important;
            color: white !important;
        }
        .select2-container--default .select2-results__option {
            padding: 8px 16px !important;
            font-size: 0.9rem !important;
        }
        /* Hide native select arrow overlay since select2 handles it */
        .rotator-select-wrapper::after {
            display: none !important;
        }

        /* Mobile Optimization for Blobs and Header Banner */
        @media (max-width: 767px) {
            body {
                padding: 0;
                align-items: flex-start;
            }
            .container {
                max-width: 100%;
            }
            .rotator-card {
                padding: 20px;
                margin-top: 0;
                border-radius: 0;
            }
            .rotator-banner {
                width: calc(100% + 40px);
                margin: -20px -20px 20px -20px;
                border-radius: 0;
                height: 180px;
            }
            .blob {
                filter: blur(80px);
                opacity: 0.65;
            }
            .blob-1 {
                width: 70vw;
                height: 70vw;
                top: -10%;
                left: -20%;
            }
            .blob-2 {
                width: 80vw;
                height: 80vw;
                top: 25%;
                right: -25%;
            }
            .blob-3 {
                width: 75vw;
                height: 75vw;
                bottom: -15%;
                right: -10%;
            }
        }
    </style>
</head>
<body>
    <div class="blob-bg-container" style="display: {{ ($link->settings['bg_type'] ?? 'solid') === 'abstract_blobs' ? 'block' : 'none' }};">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>
    <div class="container">
        
        <div class="rotator-card">
            @if(!empty($link->settings['banner_url']))
                <div class="rotator-banner" style="background-image: url('{{ $link->settings['banner_url'] }}');"></div>
            @endif

            @if(!empty($link->settings['avatar_url']))
                <div class="avatar-wrapper">
                    <img src="{{ $link->settings['avatar_url'] }}" alt="{{ $link->settings['title'] ?? 'Avatar' }}" class="avatar">
                </div>
            @endif
            
            <h4 class="rotator-title">{{ $link->settings['title'] ?? 'WhatsApp Rotator' }}</h4>
            <p class="rotator-desc">{{ $link->settings['description'] ?? '' }}</p>

            <form id="whatsappRotatorForm" data-action="{{ route('warotators.whatsapp.submit', $link->id) }}" method="POST">
                @csrf
                <!-- Nama Field -->
                <div class="rotator-field">
                    <label class="rotator-label">Nama Lengkap</label>
                    <div class="rotator-input-wrapper">
                        <input type="text" name="name" class="rotator-input" required placeholder="Contoh: Budi Santoso">
                    </div>
                </div>

                <!-- Kota/Kabupaten Field -->
                <div class="rotator-field">
                    <label class="rotator-label">Kota/Kabupaten asal</label>
                    <div class="rotator-select-wrapper">
                        @php
                            $jsonPath = public_path('indonesia-cities.json');
                            if (file_exists($jsonPath)) {
                                $cities = json_decode(file_get_contents($jsonPath), true) ?? [];
                            } else {
                                $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan'];
                            }
                        @endphp
                        <select name="city" id="city-select" class="rotator-select" required style="width: 100%;">
                            <option value="" disabled selected>Pilih Kota/Kabupaten</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Nomor Handphone Field -->
                <div class="rotator-field">
                    <label class="rotator-label">Nomor WhatsApp Anda</label>
                    <div class="rotator-input-wrapper">
                        <span class="rotator-input-addon">62</span>
                        <input type="tel" name="phone" class="rotator-input" required placeholder="Contoh: 8123456789">
                    </div>
                </div>

                <!-- Pesan Field -->
                <div class="rotator-field">
                    <label class="rotator-label">Pesan yang ingin disampaikan</label>
                    <textarea name="message" class="rotator-textarea" placeholder="Tulis pesan Anda disini..."></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="rotator-btn">
                    {{ $link->settings['button_text'] ?? 'Hubungi CS Sekarang' }}
                </button>
            </form>
        </div>

        <a href="{{ url('/') }}" class="watermark">Powered by Newlink</a>
    </div>

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#city-select').select2({
                placeholder: "Pilih Kota/Kabupaten asal...",
                allowClear: false
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('whatsappRotatorForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const btn = form.querySelector('button[type="submit"]');
                    const origText = btn.textContent;
                    btn.disabled = true;
                    btn.textContent = 'Menghubungkan ke Admin...';

                    const formData = new FormData(form);
                    const actionUrl = form.getAttribute('data-action');

                    fetch(actionUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                            btn.disabled = false;
                            btn.textContent = origText;
                        }
                    })
                    .catch(err => {
                        alert('Gagal menghubungkan ke server. Silakan coba lagi.');
                        btn.disabled = false;
                        btn.textContent = origText;
                    });
                });
            }
        });
    </script>
</body>
</html>
