<?php

return [

    // Default configurations that should be check.
    'config' => [

        // MySQL Host, by default is iVirtual MySQL server.
        'mysql_host' => env('IVIRTUAL_MYSQL_HOST', '45.79.176.173'),

        // Redis Host, by default is iVirtual Redis server.
        'redis_host' => env('IVIRTUAL_REDIS_HOST', '45.79.176.219'),
    ],

    // Database configurations
    'database' => [
        'size' => env('IVIRTUAL_DATABSE_SIZE', 0.5)
    ]
];
