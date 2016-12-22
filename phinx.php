<?php
    return array(
        "paths" => array(
            "migrations" => "database/migrations"
        ),
        "environments" => array(
            "default_migration_table" => "migrations",
            "default_database" => "dev",
            "dev" => array(
                "adapter" => $_ENV['DB_DRIVER'],
                "host" => $_ENV['DB_HOST'],
                "name" => $_ENV['DB_NAME'],
                "user" => $_ENV['DB_USER'],
                "pass" => $_ENV['DB_PASS'],
                "port" => $_ENV['DB_PORT']
            )
        )
    );