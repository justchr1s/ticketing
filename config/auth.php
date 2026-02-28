<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'techniciens'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'techniciens',
        ],

        'technicien' => [
            'driver' => 'session',
            'provider' => 'techniciens',
        ],

        'client' => [
            'driver' => 'session',
            'provider' => 'clients',
        ],
    ],

    'providers' => [
        'techniciens' => [
            'driver' => 'eloquent',
            'model' => App\Models\Technicien::class,
        ],

        'clients' => [
            'driver' => 'eloquent',
            'model' => App\Models\Client::class,
        ],
    ],

    'passwords' => [
        'techniciens' => [
            'provider' => 'techniciens',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'clients' => [
            'provider' => 'clients',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
