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
    
    <style>
        :root {
            --primary-color: {{ $link->settings['btn_bg_color'] ?? '#2ac3a6' }};
            --btn-text-color: {{ $link->settings['btn_text_color'] ?? '#ffffff' }};
            --text-color: {{ $link->settings['text_color'] ?? '#111827' }};
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
            @else
                background-color: {{ $link->settings['bg_color'] ?? '#f3f4f6' }};
            @endif
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
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
            position: relative;
            margin-bottom: -40px;
            z-index: 10;
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
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
            padding: 60px 24px 24px 24px;
            box-sizing: border-box;
            color: #1f2937;
            text-align: left;
            margin-bottom: 20px;
        }

        .rotator-banner {
            width: calc(100% + 48px);
            margin: -60px -24px 20px -24px;
            height: 160px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
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
            color: #4b5563;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rotator-input-wrapper {
            display: flex;
            align-items: center;
            background: #f3f4f6;
            border-radius: 12px;
            border: 1.5px solid transparent;
            transition: all 0.2s ease;
        }

        .rotator-input-wrapper:focus-within {
            border-color: var(--primary-color);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(42, 195, 166, 0.12);
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
            color: #111827;
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
            background: #f3f4f6;
            padding: 12px 16px;
            font-size: 0.9rem;
            color: #111827;
            outline: none;
            box-sizing: border-box;
            border-radius: 12px;
            resize: vertical;
            min-height: 80px;
            transition: all 0.2s ease;
        }

        .rotator-textarea:focus {
            background: #ffffff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(42, 195, 166, 0.12);
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
    </style>
</head>
<body>
    <div class="container">
        
        @if(!empty($link->settings['avatar_url']))
            <div class="avatar-wrapper">
                <img src="{{ $link->settings['avatar_url'] }}" alt="{{ $link->settings['title'] ?? 'Avatar' }}" class="avatar">
            </div>
        @endif

        <div class="rotator-card">
            @if(!empty($link->settings['banner_url']))
                <div class="rotator-banner" style="background-image: url('{{ $link->settings['banner_url'] }}');"></div>
            @endif
            
            <h4 class="rotator-title">{{ $link->settings['title'] ?? 'WhatsApp Rotator' }}</h4>
            @if(!empty($link->settings['description']))
                <p class="rotator-desc">{{ $link->settings['description'] }}</p>
            @endif

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
                        <select name="city" class="rotator-select" required>
                            <option value="" disabled selected>Pilih Kota/Kabupaten</option>
                            @php
                                $citiesStr = $link->settings['cities'] ?? 'Jakarta, Bandung, Surabaya, Semarang, Yogyakarta, Medan';
                                $cities = array_filter(array_map('trim', explode(',', $citiesStr)));
                            @endphp
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

    <script>
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
