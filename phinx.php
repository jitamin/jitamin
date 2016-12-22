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
        ],
        'environments' => [
            'default_migration_table' => 'migrations',
            'default_database'        => 'dev',
            'dev'                     => [
                'adapter' => $_ENV['DB_DRIVER'],
                'host'    => $_ENV['DB_HOST'],
                'name'    => $_ENV['DB_NAME'],
                'user'    => $_ENV['DB_USER'],
                'pass'    => $_ENV['DB_PASS'],
                'port'    => $_ENV['DB_PORT'],
            ],
        ],
    ];
