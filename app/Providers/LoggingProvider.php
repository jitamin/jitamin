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

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LogLevel;
use SimpleLogger\File;
use SimpleLogger\Logger;
use SimpleLogger\Stderr;
use SimpleLogger\Stdout;
use SimpleLogger\Syslog;

/**
 * Class LoggingProvider.
 */
class LoggingProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $logger = new Logger();
        $driver = null;

        switch (LOG_DRIVER) {
            case 'syslog':
                $driver = new Syslog('jitamin');
                break;
            case 'stdout':
                $driver = new Stdout();
                break;
            case 'stderr':
                $driver = new Stderr();
                break;
            case 'file':
                $driver = new File(LOG_FILE);
                break;
        }

        if ($driver !== null) {
            if (!DEBUG) {
                $driver->setLevel(LogLevel::INFO);
            }

            $logger->setLogger($driver);
        }

        $container['logger'] = $logger;

        return $container;
    }
}
