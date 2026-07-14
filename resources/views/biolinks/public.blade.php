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
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f1;
            min-height: 100vh;
            color: #111827;
            display: flex;
            flex-direction: column;
            align-items: center;
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
            color: #111827;
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
            color: #4b5563;
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
            display: block;
            background: #ffffff;
            padding: 15px 20px;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            color: #111827;
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
            border-color: rgba(164, 229, 189, 0.4);
        }
        .block-text {
            width: 100%;
            text-align: center;
            margin: 8px 0;
            line-height: 1.6;
            color: #374151;
            font-size: 0.95rem;
        }
        .watermark {
            margin-top: 40px;
            font-size: 0.8rem;
            color: #9ca3af;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        .watermark:hover {
            color: #4b5563;
        }
    </style>
</head>
<body>

    <!-- Cover Photo Header (Full Width edge-to-edge) -->
    <div class="cover-photo-full"></div>

    <!-- Biolink Profile and Blocks Content -->
    <div class="biolink-content">
        <!-- Profile Avatar -->
        <img src="{{ $link->settings['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($link->settings['title'] ?? 'BL') . '&background=a4e5bd&color=111827&size=128' }}" alt="Profile" class="profile-image">
        
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
                    <a href="{{ $block->location_url }}" class="block-link" target="_blank" rel="noopener">
                        {{ $block->settings['title'] ?? 'Link' }}
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

</body>
</html>
