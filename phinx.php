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
                'adapter' => 'mysql',
                'host'    => '127.0.0.1',
                'name'    => 'jitamin_unit_test',
                'user'    => $_ENV['DB_USERNAME'],
                'pass'    => $_ENV['DB_PASSWORD'],
                'port'    => $_ENV['DB_PORT'],
            ],
        ],
    ];
