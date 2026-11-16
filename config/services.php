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

    'authorize_net' => [
        'login_id' => env('AUTHORIZE_NET_LOGIN_ID'),
        'transaction_key' => env('AUTHORIZE_NET_TRANSACTION_KEY'),
        'environment' => env('AUTHORIZE_NET_ENVIRONMENT', 'sandbox'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Web chat
    |--------------------------------------------------------------------------
    |
    | The existing PHP Live! chat engine is served by the production site. Keep
    | it configurable so the beta/staging site can point at its own install.
    |
    */

    'webchat' => [
        'widget_url' => env('WEBCHAT_WIDGET_URL', 'https://jmor.com/WebChat/widget.js'),
        'chat_url' => env('WEBCHAT_CHAT_URL', 'https://jmor.com/WebChat/chat'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Instagram feed
    |--------------------------------------------------------------------------
    |
    | A professional (Business/Creator) account is required. Set either the
    | Facebook Login host (graph.facebook.com) with a Facebook user token, or
    | the Instagram Login host (graph.instagram.com) with an Instagram token.
    | Leave the id/token empty to render the profile fallback.
    |
    */

    'instagram' => [
        'graph_url' => env('INSTAGRAM_GRAPH_URL', 'https://graph.facebook.com/v27.0'),
        'user_id' => env('INSTAGRAM_USER_ID'),
        'access_token' => env('INSTAGRAM_ACCESS_TOKEN'),
        'handle' => env('INSTAGRAM_HANDLE', 'gosocialjmor'),
        'profile_url' => env('INSTAGRAM_PROFILE_URL', 'https://www.instagram.com/gosocialjmor/'),
        'cache_ttl' => (int) env('INSTAGRAM_CACHE_TTL', 1800),
    ],
];
