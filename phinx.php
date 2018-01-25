<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
|--------------------------------------------------------------------------
| Please DO NOT modify me.
|--------------------------------------------------------------------------
*/
// Register The Auto Loader
require __DIR__.'/bootstrap/autoload.php';

$db = require __DIR__.'/config/config.php';

return [
    'paths' => [
        'migrations' => 'database/migrations',
        'seeds'      => 'database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database'        => $db['db_driver'],
        'mysql'                   => [
            'adapter' => 'mysql',
            'host'    => $db['db_connections']['mysql']['host'],
            'name'    => $db['db_connections']['mysql']['database'],
            'user'    => $db['db_connections']['mysql']['username'],
            'pass'    => $db['db_connections']['mysql']['password'],
            'port'    => $db['db_connections']['mysql']['port'],
            'charset' => 'utf8',
        ],
        'pgsql' => [
            'adapter' => 'pgsql',
            'host'    => $db['db_connections']['pgsql']['host'],
            'name'    => $db['db_connections']['pgsql']['database'],
            'user'    => $db['db_connections']['pgsql']['username'],
            'pass'    => $db['db_connections']['pgsql']['password'],
            'port'    => $db['db_connections']['pgsql']['port'],
            'charset' => 'utf8',
        ],
        'sqlite' => [
            'adapter' => 'sqlite',
            'name'    => $db['db_connections']['sqlite']['database'],
        ],
    ],
];
