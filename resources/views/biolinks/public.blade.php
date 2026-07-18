<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $link->settings['title'] ?? 'Biolink' }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @php
        $bgType = $link->settings['bg_type'] ?? 'solid';
        $bgColor = $link->settings['bg_color'] ?? '#f3f4f1';
        $bgGradientStart = $link->settings['bg_gradient_start'] ?? '#a4e5bd';
        $bgGradientEnd = $link->settings['bg_gradient_end'] ?? '#7dd3a1';
        $btnBgColor = $link->settings['btn_bg_color'] ?? '#ffffff';
        $btnTextColor = $link->settings['btn_text_color'] ?? '#111827';
        $textColor = $link->settings['text_color'] ?? '#111827';

        if ($bgType === 'gradient') {
            $backgroundStyle = "linear-gradient(135deg, {$bgGradientStart} 0%, {$bgGradientEnd} 100%)";
        } elseif ($bgType === 'abstract_blobs') {
            $backgroundStyle = $link->settings['bg_blob_base'] ?? '#f8fafc';
        } else {
            $backgroundStyle = $bgColor;
        }
    @endphp
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: {{ $backgroundStyle }};
            min-height: 100vh;
            color: {{ $textColor }};
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
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

        @media (max-width: 767px) {
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
        .cover-photo-full {
            width: 100%;
            height: 240px;
            background: {{ isset($link->settings['cover_url']) ? 'url(' . $link->settings['cover_url'] . ') center/cover no-repeat' : 'linear-gradient(135deg, #a4e5bd 0%, #7dd3a1 100%)' }};
        }
        .biolink-content {
            width: 100%;
            max-width: 580px;
            padding: 0 20px 60px 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: -65px; /* Pull profile avatar up over the cover photo */
            position: relative;
            z-index: 10;
        }
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            object-fit: cover;
            background-color: #ffffff;
            margin-bottom: 12px;
        }
        .profile-title {
            font-size: 1.35rem;
            font-weight: 750;
            margin: 0 0 6px 0;
            color: {{ $textColor }};
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .profile-desc {
            font-size: 0.925rem;
            font-weight: 500;
            margin: 0 0 28px 0;
            text-align: center;
            color: {{ $textColor }};
            opacity: 0.85;
            max-width: 440px;
            line-height: 1.5;
        }
        .blocks-section {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 14px;
            align-items: center;
        }
        .block-link {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: {{ $btnBgColor }};
            padding: 15px 20px;
            border-radius: 12px;
            text-decoration: none;
            color: {{ $btnTextColor }};
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-sizing: border-box;
        }
        .block-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.08);
            opacity: 0.92;
        }
        .block-text {
            width: 100%;
            text-align: center;
            margin: 8px 0;
            line-height: 1.6;
            color: {{ $textColor }};
            font-size: 0.95rem;
        }
        .watermark {
            margin-top: 40px;
            font-size: 0.8rem;
            color: {{ $textColor }};
            opacity: 0.6;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s ease;
        }
        .watermark:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="blob-bg-container" style="display: {{ ($link->settings['bg_type'] ?? 'solid') === 'abstract_blobs' ? 'block' : 'none' }};">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Cover Photo Header (Full Width edge-to-edge) -->
    @if(($link->settings['show_cover'] ?? '1') == '1')
        <div class="cover-photo-full"></div>
    @endif

    <!-- Biolink Profile and Blocks Content -->
    <div class="biolink-content" style="{{ ($link->settings['show_cover'] ?? '1') == '0' ? 'margin-top: 40px;' : '' }}">
        <!-- Profile Avatar -->
        @if(($link->settings['show_avatar'] ?? '1') == '1')
            <img src="{{ $link->settings['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($link->settings['title'] ?? 'BL') . '&background=a4e5bd&color=111827&size=128' }}" alt="Profile" class="profile-image">
        @endif
        
        <!-- Profile Title & Verified Badge -->
        <h1 class="profile-title">
            {{ $link->settings['title'] ?? 'My Biolink' }}
            @if($link->is_verified)
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#0095f6" style="color: white; flex-shrink: 0; margin-top: 1px;" title="Verified Profile">
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                </svg>
            @endif
        </h1>

        <!-- Profile Description -->
        <p class="profile-desc">{{ $link->settings['description'] ?? '' }}</p>

        <!-- Content Blocks -->
        <div class="blocks-section">
            @foreach($blocks as $block)
                @if($block->type == 'link')
                    <a href="{{ route('biolinks.blocks.redirect', $block->id) }}" class="block-link d-flex align-items-center justify-content-center gap-2" target="_blank" rel="noopener">
                        @if(!empty($block->settings['icon']))
                            <span data-duo-icons="{{ $block->settings['icon'] }}" style="width: 20px; height: 20px; flex-shrink: 0; color: inherit;"></span>
                        @endif
                        <span>{{ $block->settings['title'] ?? 'Link' }}</span>
                    </a>
                @elseif($block->type == 'text')
                    <div class="block-text">
                        {{ $block->settings['content'] ?? '' }}
                    </div>
                @endif
            @endforeach

            <a href="{{ url('/') }}" class="watermark">Powered by Newlink</a>
        </div>
    </div>

    <!-- Duo Icons JS -->
    <script src="{{ asset('js/duo-icons.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.DuoIcons) {
                DuoIcons.createIcons({ icons: DuoIcons.icons });
            }
        });
    </script>
</body>
</html>
