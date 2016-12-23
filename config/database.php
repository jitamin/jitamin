<?php

return [
    'fetch' => PDO::FETCH_CLASS,

    'default' => 'mysql',

    'connections' => [

        'mysql' => [
            'driver'    => 'mysql',
            'host'  => 'localhost',
            'database' => 'hiject',
            'username' => 'root',
            'password' => 'root12',
            'port' => '3306',
            'charset' => 'utf8',
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'  => 'localhost',
            'database' => 'jitamin',
            'username' => 'postgres',
            'password' => '',
            'port' => '5432',
            'charset' => 'utf8',
        ],
    ],
];