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
    'debug' => true,

    // Available log drivers: syslog, stderr, stdout or file
    'log_driver' => 'file',

    // Available cache drivers are "file", "memory" and "memcached"
    'cache_driver' => 'memory',

    // Hide login form, useful if all your users use Google/Github/ReverseProxy authentication
    'hide_login_form' => false,

    // Available db drivers are "mysql", "sqlite" and "postgres"
    'db_driver' => 'mysql',

    'db_connections' => [
        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => 'jitamin',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'jitamin',
            'username'  => 'root',
            'password'  => '',
            'port'      => '3306',
            'charset'   => 'utf8',
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => 'localhost',
            'database' => 'jitamin',
            'username' => 'postgres',
            'password' => '',
            'port'     => '5432',
            'charset'  => 'utf8',
        ],
    ],
];
