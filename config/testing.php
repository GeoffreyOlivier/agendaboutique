<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration des tests
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient la configuration spécifique aux tests de l'application.
    |
    */

    'database' => [
        'connection' => env('DB_CONNECTION', 'sqlite'),
        'database' => env('DB_DATABASE', ':memory:'),
    ],

    'cache' => [
        'driver' => 'array',
    ],

    'session' => [
        'driver' => 'array',
    ],

    'queue' => [
        'default' => 'sync',
    ],

    'mail' => [
        'driver' => 'array',
    ],

    'filesystems' => [
        'default' => 'local',
        'disks' => [
            'local' => [
                'driver' => 'local',
                'root' => storage_path('framework/testing'),
            ],
        ],
    ],
];
