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
                'adapter' => DB_DRIVER,
                'host'    => DB_HOST,
                'name'    => DB_NAME,
                'user'    => DB_USERNAME,
                'pass'    => DB_PASSWORD,
                'port'    => DB_PORT,
            ],
        ],
    ];
