<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'../config/config.php';

    return [
        'paths' => [
            'migrations' => 'database/migrations',
            'seeds'      => 'database/seeds',
        ],
        'environments' => [
            'default_migration_table' => 'migrations',
            'default_database'        => 'mysql',
            'mysql'                   => [
                'adapter' => 'mysql',
                'host'    => DB_HOSTNAME,
                'name'    => DB_NAME,
                'user'    => DB_USERNAME,
                'pass'    => DB_PASSWORD,
                'port'    => DB_PORT,
                'charset' => 'utf8',
            ],
            'postgres' => [
                'adapter' => 'pgsql',
                'host'    => DB_HOSTNAME,
                'name'    => DB_NAME,
                'user'    => DB_USERNAME,
                'pass'    => DB_PASSWORD,
                'port'    => DB_PORT,
                'charset' => 'utf8',
            ],
            'sqlite' => [
                'adapter' => 'sqlite',
                'name'    => 'jitamin',
                'memory'  => true,
            ],
        ],
    ];
