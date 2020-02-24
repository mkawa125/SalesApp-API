<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'google' => [
        'client_id' => '566947147971-g5g7oii3qln9vhk9l2qluq3fmp9o0aof.apps.googleusercontent.com',
        'client_secret' => 'YgOYJOBkIZIR2_bHaLhIyxoW',
        'redirect' => 'http://127.0.0.1:8000/auth/callback/google',
    ],
    'github' => [
        'client_id' => '2204ea3f59315c176a2e',
        'client_secret' => 'c88b1e899eade25cdcba0b7afbc0efd3bd8123d8',
        'redirect' => 'http://127.0.0.1:8000/auth/callback/github',
    ],
];
