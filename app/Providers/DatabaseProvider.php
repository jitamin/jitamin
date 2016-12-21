<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use LogicException;
use PicoDb\Database;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use RuntimeException;

/**
 * Class DatabaseProvider.
 */
class DatabaseProvider implements ServiceProviderInterface
{
    /**
     * Register provider.
     *
     * @param Container $container
     *
     * @return Container
     */
    public function register(Container $container)
    {
        $container['db'] = $this->getInstance();

        if (DEBUG) {
            $container['db']->getStatementHandler()
                ->withLogging()
                ->withStopWatch();
        }

        return $container;
    }

    /**
     * Setup the database driver and execute schema migration.
     *
     * @return \PicoDb\Database
     */
    public function getInstance()
    {
        switch (DB_DRIVER) {
            case 'sqlite':
                $db = $this->getSqliteInstance();
                break;
            case 'mysql':
                $db = $this->getMysqlInstance();
                break;
            case 'postgres':
                $db = $this->getPostgresInstance();
                break;
            default:
                throw new LogicException('Database driver not supported');
        }

        if ($db->schema()->check(\Schema\VERSION)) {
            return $db;
        } else {
            $messages = $db->getLogMessages();
            throw new RuntimeException('Unable to run SQL migrations: '.implode(', ', $messages).' (You may have to fix it manually)');
        }
    }

    /**
     * Setup the Sqlite database driver.
     *
     * @return \PicoDb\Database
     */
    private function getSqliteInstance()
    {
        require_once __DIR__.'/../Schema/Sqlite.php';

        return new Database([
            'driver'   => 'sqlite',
            'filename' => DB_FILENAME,
        ]);
    }

    /**
     * Setup the Mysql database driver.
     *
     * @return \PicoDb\Database
     */
    private function getMysqlInstance()
    {
        require_once __DIR__.'/../Schema/Mysql.php';

        return new Database([
            'driver'   => 'mysql',
            'hostname' => DB_HOSTNAME,
            'username' => DB_USERNAME,
            'password' => DB_PASSWORD,
            'database' => DB_NAME,
            'charset'  => 'utf8',
            'port'     => DB_PORT,
            'ssl_key'  => DB_SSL_KEY,
            'ssl_ca'   => DB_SSL_CA,
            'ssl_cert' => DB_SSL_CERT,
        ]);
    }

    /**
     * Setup the Postgres database driver.
     *
     * @return \PicoDb\Database
     */
    private function getPostgresInstance()
    {
        require_once __DIR__.'/../Schema/Postgres.php';

        return new Database([
            'driver'   => 'postgres',
            'hostname' => DB_HOSTNAME,
            'username' => DB_USERNAME,
            'password' => DB_PASSWORD,
            'database' => DB_NAME,
            'port'     => DB_PORT,
        ]);
    }
}
