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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'firebase' => [
        'base_url' => env('FIREBASE_BASE_URL', 'https://fcm.googleapis.com/v1/projects/'),
        'service_file' => env('FIREBASE_SERVICE_FILE', storage_path('../firebase/dev.json')),
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'messaging' => [],
    ],

    'myfatoorah' => [
        'api_key' => env('MYFATOORAH_API_KEY'),
        'vc_code' => env('MYFATOORAH_VC_CODE'),
        'is_test' => env('MYFATOORAH_IS_TEST', config('app.env') !== 'production'),
    ],

    'geoapify' => [
        'api_key' => env('GEOAPIFY_API_KEY'),
    ],

];
