<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    // Enable/Disable debug
    'debug' => env('APP_DEBUG', false),

    // Available log drivers: syslog, stderr, stdout or file
    'log_driver' => env('APP_LOG', 'file'),

    // Available cache drivers are "file", "memory" and "memcached"
    'cache_driver' => 'memcached',

    // Hide login form, useful if all your users use Google/Github/ReverseProxy authentication
    'hide_login_form' => false,

    // Enable/disable url rewrite
    'enable_url_rewrite' => true,

    // Available db drivers are "mysql", "sqlite" and "postgres"
    'db_driver' => env('DB_CONNECTION', 'mysql'),

    'db_connections' => [
        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => 'jitamin',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'jitamin'),
            'username'  => env('DB_USERNAME', 'jitamin'),
            'password'  => env('DB_PASSWORD', ''),
            'port'      => env('DB_PORT', '3306'),
            'charset'   => 'utf8',
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'jitamin'),
            'username' => env('DB_USERNAME', 'postgres'),
            'password' => env('DB_PASSWORD', ''),
            'port'     => '5432',
            'charset'  => 'utf8',
        ],
    ],
];
