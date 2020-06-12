<?php

return [
    'APP_NAME' => env('APP_NAME', 'MyApp'),
    'APP_ENV' => env('APP_ENV', 'local'),
    'APP_DEBUG' => env('APP_DEBUG', false),
    'APP_URL' => env('APP_URL', 'http://127.0.0.1'),

    'BCRYPT_ROUNDS' => env('BCRYPT_ROUNDS', 4),
    'LOG_CHANNEL' => env('LOG_CHANNEL', 'stack'),

    'DB_CONNECTION' => env('DB_CONNECTION', 'mysql'),
    'DB_DATABASE' => env('DB_DATABASE', false),
    'DB_USERNAME' => env('DB_USERNAME', false),
    'DB_PASSWORD' => env('DB_PASSWORD', false),

    'BROADCAST_DRIVER' => env('BROADCAST_DRIVER', 'log'),
    'CACHE_DRIVER' => env('CACHE_DRIVER', 'file'),
    'QUEUE_CONNECTION' => env('QUEUE_CONNECTION', 'sync'),
    'SESSION_DRIVER' => env('SESSION_DRIVER', 'file'),
    'SESSION_LIFETIME' => env('SESSION_LIFETIME', 120),

    'MAIL_DRIVER' => env('MAIL_DRIVER', 'smtp'),
    'MAIL_HOST' => env('MAIL_HOST', false),
    'MAIL_PORT' => env('MAIL_PORT', 2525),
    'MAIL_USERNAME' => env('MAIL_USERNAME', false),
    'MAIL_PASSWORD' => env('MAIL_PASSWORD', false),
    'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION', 'TLS'),
    'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS', 'some@address.com'),
    'MAIL_FROM_NAME' => env('MAIL_FROM_NAME', 'name@address.com'),

    'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SECRET_KEY', false),
    'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY', false),

    'ORG_DOMAIN' => env('ORG_DOMAIN', false)
];
