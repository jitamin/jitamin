<?php
    return array(
        "paths" => array(
            "migrations" => "database/migrations",
            "seeds" => "database/seeds",
        ),
        "environments" => array(
            "default_migration_table" => "migrations",
            "default_database" => "mysql",
            "mysql" => array(
                "adapter" => "mysql",
                "host" => '127.0.0.1',
                "name" => 'jitamin',
                "user" => 'root',
                "pass" => '',
                "port" => '3306',
                "charset" => 'utf8',
            ),
            "postgres" => array(
                "adapter" => "pgsql",
                "host" => '127.0.0.1',
                "name" => 'jitamin',
                "user" => 'postgres',
                "pass" => '',
                "port" => '5432',
                "charset" => 'utf8',
            ),
            "sqlite" => array(
                "adapter" => "sqlite",
                "name" => 'jitamin',
                "memory" => true
            )
        )
    );