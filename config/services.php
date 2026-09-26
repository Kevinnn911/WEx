<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, Supabase, Google Drive, and more.
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

    /*
    |--------------------------------------------------------------------------
    | Supabase Configuration
    |--------------------------------------------------------------------------
    | Digunakan untuk koneksi managed database PostgreSQL Supabase serta
    | API Key untuk integrasi service Supabase.
    */
    'supabase' => [
        'url' => env('SUPABASE_URL'),
        'anon_key' => env('SUPABASE_ANON_KEY', env('SUPABASE_PUBLISHABLE_KEY')),
        'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY', env('SUPABASE_SECRET_KEY')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Drive Storage Configuration
    |--------------------------------------------------------------------------
    | Digunakan khusus untuk penyimpanan berkas foto selfie absensi dan
    | tanda tangan digital siswa (PRD Section 19).
    */
    'google_drive' => [
        'enabled' => env('GOOGLE_DRIVE_ENABLED', false),
        'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
        // Mode 1: OAuth2 Refresh Token
        'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
        'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
        // Mode 2: Service Account JSON
        'service_account_json' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON'),
    ],

];
