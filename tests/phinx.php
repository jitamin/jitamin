<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
                'host'    => '127.0.0.1',
                'name'    => 'jitamin',
                'user'    => 'root',
                'pass'    => '',
                'port'    => '3306',
                'charset' => 'utf8',
            ],
            'postgres' => [
                'adapter' => 'pgsql',
                'host'    => '127.0.0.1',
                'name'    => 'jitamin',
                'user'    => 'postgres',
                'pass'    => '',
                'port'    => '5432',
                'charset' => 'utf8',
            ],
            'sqlite' => [
                'adapter' => 'sqlite',
                'name'    => 'jitamin',
                'memory'  => true,
            ],
        ],
    ];
