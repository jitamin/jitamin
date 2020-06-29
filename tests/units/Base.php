<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../../vendor/autoload.php';

$config = require_once __DIR__.'/../../config/config.php';
require_once __DIR__.'/../../bootstrap/bootstrap.php';

use Composer\Autoload\ClassLoader;
use Jitamin\Foundation\Session\FlashMessage;
use Jitamin\Foundation\Session\SessionStorage;
use SimpleLogger\Logger;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Process\Process;
use Symfony\Component\Stopwatch\Stopwatch;

abstract class Base extends PHPUnit_Framework_TestCase
{
    protected $container;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    protected $process;

    public function setUp()
    {
        date_default_timezone_set('UTC');

        if (DB_DRIVER === 'mysql') {
            $pdo = new PDO('mysql:host='.DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
            $pdo->exec('DROP DATABASE '.DB_NAME);
            $pdo->exec('CREATE DATABASE '.DB_NAME);
            $pdo = null;
        } elseif (DB_DRIVER === 'pgsql') {
            $pdo = new PDO('pgsql:host='.DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
            $pdo->exec('DROP DATABASE '.DB_NAME);
            $pdo->exec('CREATE DATABASE '.DB_NAME.' WITH OWNER '.DB_USERNAME);
            $pdo = null;
        } elseif (DB_DRIVER === 'sqlite' && file_exists(DB_FILENAME)) {
            unlink(DB_FILENAME);
        }

        $this->process = new Process('');
        $this->process->setTimeout(null);
        $this->process->setCommandLine(implode(PHP_EOL, [
            'php vendor/bin/phinx migrate -c phinx.php -e '.DB_DRIVER,
            'php vendor/bin/phinx seed:run -c phinx.php -e '.DB_DRIVER,
        ]));
        $this->process->run();

        $this->container = new Pimple\Container();
        $this->container->register(new Jitamin\Providers\CacheServiceProvider());
        $this->container->register(new Jitamin\Providers\HelperServiceProvider());
        $this->container->register(new Jitamin\Providers\AuthServiceProvider());
        $this->container->register(new Jitamin\Providers\DatabaseServiceProvider());
        $this->container->register(new Jitamin\Providers\ClassServiceProvider());
        $this->container->register(new Jitamin\Providers\NotificationServiceProvider());
        $this->container->register(new Jitamin\Providers\RouteServiceProvider());
        $this->container->register(new Jitamin\Providers\AvatarServiceProvider());
        $this->container->register(new Jitamin\Providers\FilterServiceProvider());
        $this->container->register(new Jitamin\Providers\JobServiceProvider());
        $this->container->register(new Jitamin\Providers\QueueServiceProvider());

        $this->container['dispatcher'] = new TraceableEventDispatcher(
            new EventDispatcher(),
            new Stopwatch()
        );

        $this->dispatcher = $this->container['dispatcher'];

        $this->container['db']->getStatementHandler()->withLogging();
        $this->container['logger'] = new Logger();

        $this->container['httpClient'] = $this
            ->getMockBuilder('\Jitamin\Foundation\Http\Client')
            ->setConstructorArgs([$this->container])
            ->setMethods(['get', 'getJson', 'postJson', 'postJsonAsync', 'postForm', 'postFormAsync'])
            ->getMock();

        $this->container['emailClient'] = $this
            ->getMockBuilder('\Jitamin\Foundation\Mail\Client')
            ->setConstructorArgs([$this->container])
            ->setMethods(['send'])
            ->getMock();

        $this->container['userNotificationTypeModel'] = $this
            ->getMockBuilder('\Jitamin\Model\UserNotificationTypeModel')
            ->setConstructorArgs([$this->container])
            ->setMethods(['getType', 'getSelectedTypes'])
            ->getMock();

        $this->container['objectStorage'] = $this
            ->getMockBuilder('\Jitamin\Foundation\ObjectStorage\FileStorage')
            ->setConstructorArgs([$this->container])
            ->setMethods(['put', 'moveFile', 'remove', 'moveUploadedFile'])
            ->getMock();

        $this->container['sessionStorage'] = new SessionStorage();
        $this->container->register(new Jitamin\Providers\ActionServiceProvider());

        $this->container['flash'] = function ($c) {
            return new FlashMessage($c);
        };

        $loader = new ClassLoader();
        $loader->addPsr4('Jitamin\Plugin\\', PLUGINS_DIR);
        $loader->register();
    }

    public function tearDown()
    {
        $this->container['db']->closeConnection();
    }
}
