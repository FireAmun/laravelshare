<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Security Settings
    |--------------------------------------------------------------------------
    */
    'files' => [
        'max_size' => env('MAX_FILE_SIZE', 5 * 1024 * 1024), // 5MB - Very conservative for free hosting
        'allowed_extensions' => [
            // Documents (typically small)
            'pdf', 'doc', 'docx', 'txt', 'rtf',
            // Images (compressed)
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            // Small compressed files only
            'zip',
            // Spreadsheets (small)
            'csv', 'xlsx',
            // No videos due to size constraints
        ],
        'blocked_extensions' => [
            'exe', 'bat', 'cmd', 'com', 'scr', 'pif',
            'php', 'asp', 'aspx', 'jsp', 'py', 'rb',
            'sh', 'bash', 'ps1', 'vbs', 'js'
        ],
        'scan_for_malware' => env('SCAN_FILES_FOR_MALWARE', false),
        'encrypt_files' => env('ENCRYPT_FILES', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limiting' => [
        'uploads_per_hour' => env('UPLOADS_PER_HOUR', 5), // Reduced for free hosting
        'downloads_per_hour' => env('DOWNLOADS_PER_HOUR', 25), // Reduced for free hosting
        'login_attempts' => env('LOGIN_ATTEMPTS', 3), // More restrictive
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    */
    'headers' => [
        'hsts_max_age' => 31536000,
        'content_type_nosniff' => true,
        'x_frame_options' => 'DENY',
        'x_xss_protection' => true,
        'referrer_policy' => 'strict-origin-when-cross-origin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    */
    'session' => [
        'lifetime' => env('SESSION_LIFETIME', 120),
        'secure_cookies' => env('SESSION_SECURE_COOKIES', false),
        'http_only' => true,
        'same_site' => 'strict',
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    */
    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'max_age_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | Two Factor Authentication
    |--------------------------------------------------------------------------
    */
    '2fa' => [
        'enabled' => env('TWO_FACTOR_ENABLED', false),
        'issuer' => env('APP_NAME', 'Laravel File Share'),
    ],
];
