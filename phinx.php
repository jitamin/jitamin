<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$db = require __DIR__.'/config/database.php';

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
                'host'    => $db['connections']['mysql']['host'],
                'name'    => $db['connections']['mysql']['database'],
                'user'    => $db['connections']['mysql']['username'],
                'pass'    => $db['connections']['mysql']['password'],
                'port'    => $db['connections']['mysql']['port'],
                'charset' => 'utf8',
            ],
            'postgres' => [
                'adapter' => 'pgsql',
                'host'    => $db['connections']['pgsql']['host'],
                'name'    => $db['connections']['pgsql']['database'],
                'user'    => $db['connections']['pgsql']['username'],
                'pass'    => $db['connections']['pgsql']['password'],
                'port'    => $db['connections']['pgsql']['port'],
                'charset' => 'utf8',
            ],
            'sqlite' => [
                'adapter' => 'sqlite',
                'name'    => 'jitamin',
            ],
        ],
    ];
