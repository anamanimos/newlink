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

    <div class="biolink-container">
        <!-- Optional Profile Image -->
        <img src="https://ui-avatars.com/api/?name={{ urlencode($link->settings['title'] ?? 'BL') }}&background=0D8ABC&color=fff&size=128" alt="Profile" class="profile-image">
        <h1 class="profile-title">{{ $link->settings['title'] ?? 'My Biolink' }}</h1>
        <p class="profile-desc">{{ $link->settings['description'] ?? '' }}</p>

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

</body>
</html>
