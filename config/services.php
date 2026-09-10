<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'sso' => [
        'enabled' => env('SSO_ENABLED', true),
        'base_url' => env('SSO_BASE_URL', 'https://app.damaijaya.my.id'),
        'client_id' => env('SSO_CLIENT_ID', ''),
        'client_secret' => env('SSO_CLIENT_SECRET', ''),
        'redirect_uri' => env('SSO_REDIRECT_URI', ''),
        'button_text' => env('SSO_BUTTON_TEXT', 'Masuk dengan Akun Damai Jaya (SSO)'),
    ],

];
