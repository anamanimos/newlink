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
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            color: #333;
        }
        .biolink-container {
            width: 100%;
            max-width: 680px;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        .profile-image {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background-color: #ddd;
            margin-bottom: 8px;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .profile-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 4px 0;
        }
        .profile-desc {
            font-size: 0.95rem;
            margin: 0 0 24px 0;
            text-align: center;
            color: #666;
        }
        .block-link {
            width: 100%;
            display: block;
            background: white;
            padding: 16px;
            border-radius: 50px;
            text-align: center;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .block-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .block-text {
            width: 100%;
            text-align: center;
            margin: 16px 0;
            line-height: 1.6;
        }
        .watermark {
            margin-top: 40px;
            font-size: 0.8rem;
            color: #999;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="biolink-container" style="padding-top: 0; max-width: 580px; background: white; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); overflow: hidden; margin: 40px auto; min-height: 90vh; display: flex; flex-direction: column; align-items: center; box-sizing: border-box; position: relative;">
        
        <!-- Cover Photo Header -->
        <div class="cover-photo-wrapper" style="width: 100%; height: 160px; background: {{ isset($link->settings['cover_url']) ? 'url(' . $link->settings['cover_url'] . ') center/cover no-repeat' : 'linear-gradient(135deg, #a4e5bd 0%, #7dd3a1 100%)' }}; position: relative;">
        </div>

        <!-- Profile Section -->
        <div class="profile-section" style="display: flex; flex-direction: column; align-items: center; margin-top: -60px; width: 100%; padding: 0 24px 20px 24px; box-sizing: border-box; gap: 8px;">
            <!-- Profile Avatar -->
            <img src="{{ $link->settings['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($link->settings['title'] ?? 'BL') . '&background=a4e5bd&color=111827&size=128' }}" alt="Profile" class="profile-image" style="width: 110px; height: 110px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.15); object-fit: cover; background: #fff; z-index: 2; margin-bottom: 0;">
            
            <!-- Profile Title & Verified Badge -->
            <div style="display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 4px; z-index: 2; position: relative;">
                <h1 class="profile-title" style="margin: 0; font-size: 1.35rem; font-weight: 750; color: #111827; text-align: center; font-family: 'Inter', sans-serif;">{{ $link->settings['title'] ?? 'My Biolink' }}</h1>
                @if($link->is_verified)
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#0095f6" style="color: white; flex-shrink: 0; margin-top: 1px;" title="Verified Profile">
                        <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                    </svg>
                @endif
            </div>

            <!-- Profile Description -->
            <p class="profile-desc" style="margin: 0; font-size: 0.925rem; font-weight: 500; color: #4b5563; text-align: center; max-width: 420px; line-height: 1.5; font-family: 'Inter', sans-serif;">{{ $link->settings['description'] ?? '' }}</p>
        </div>

        <!-- Content Blocks -->
        <div class="blocks-section" style="width: 100%; padding: 0 24px 40px 24px; box-sizing: border-box; display: flex; flex-direction: column; gap: 14px; align-items: center;">
            @foreach($blocks as $block)
                @if($block->type == 'link')
                    <a href="{{ $block->location_url }}" class="block-link" target="_blank" rel="noopener" style="width: 100%; display: block; background: #f3f4f1; padding: 14px 20px; border-radius: 12px; text-align: center; text-decoration: none; color: #111827; font-weight: 600; font-size: 0.95rem; transition: all 0.2s ease; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 2px 4px rgba(0,0,0,0.02); box-sizing: border-box; font-family: 'Inter', sans-serif;">
                        {{ $block->settings['title'] ?? 'Link' }}
                    </a>
                @elseif($block->type == 'text')
                    <div class="block-text" style="width: 100%; text-align: center; margin: 8px 0; line-height: 1.6; color: #374151; font-size: 0.95rem; font-family: 'Inter', sans-serif;">
                        {{ $block->settings['content'] ?? '' }}
                    </div>
                @endif
            @endforeach

            <a href="{{ url('/') }}" class="watermark" style="margin-top: 30px; font-size: 0.8rem; color: #9ca3af; text-decoration: none; font-weight: 500; font-family: 'Inter', sans-serif;">Powered by Newlink</a>
        </div>
    </div>

</body>
</html>
