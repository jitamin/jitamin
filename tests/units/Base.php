<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../../bootstrap/constants.php';

use Composer\Autoload\ClassLoader;
use Jitamin\Core\Session\FlashMessage;
use Jitamin\Core\Session\SessionStorage;
use Jitamin\Providers\ActionProvider;
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
        } elseif (DB_DRIVER === 'postgres') {
            $pdo = new PDO('pgsql:host='.DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
            $pdo->exec('DROP DATABASE '.DB_NAME);
            $pdo->exec('CREATE DATABASE '.DB_NAME.' WITH OWNER '.DB_USERNAME);
            $pdo = null;
        } elseif (DB_DRIVER === 'sqlite') {
            unlink(DB_NAME.'.sqlite');
        }

        $this->process = new Process('');
        $this->process->setTimeout(null);
        $command = 'php vendor/bin/phinx --configuration=phinx.php migrate -e '.DB_DRIVER.';';
        $command .= 'php vendor/bin/phinx --configuration=phinx.php seed:run -e '.DB_DRIVER.';';
        $this->process->setCommandLine($command);
        $this->process->run();

        $this->container = new Pimple\Container();
        $this->container->register(new Jitamin\Providers\CacheProvider());
        $this->container->register(new Jitamin\Providers\HelperProvider());
        $this->container->register(new Jitamin\Providers\AuthenticationProvider());
        $this->container->register(new Jitamin\Providers\DatabaseProvider());
        $this->container->register(new Jitamin\Providers\ClassProvider());
        $this->container->register(new Jitamin\Providers\NotificationProvider());
        $this->container->register(new Jitamin\Providers\RouteProvider());
        $this->container->register(new Jitamin\Providers\AvatarProvider());
        $this->container->register(new Jitamin\Providers\FilterProvider());
        $this->container->register(new Jitamin\Providers\JobProvider());
        $this->container->register(new Jitamin\Providers\QueueProvider());

        $this->container['dispatcher'] = new TraceableEventDispatcher(
            new EventDispatcher(),
            new Stopwatch()
        );

        $this->dispatcher = $this->container['dispatcher'];

        $this->container['db']->getStatementHandler()->withLogging();
        $this->container['logger'] = new Logger();

        $this->container['httpClient'] = $this
            ->getMockBuilder('\Jitamin\Core\Http\Client')
            ->setConstructorArgs([$this->container])
            ->setMethods(['get', 'getJson', 'postJson', 'postJsonAsync', 'postForm', 'postFormAsync'])
            ->getMock();

        $this->container['emailClient'] = $this
            ->getMockBuilder('\Jitamin\Core\Mail\Client')
            ->setConstructorArgs([$this->container])
            ->setMethods(['send'])
            ->getMock();

        $this->container['userNotificationTypeModel'] = $this
            ->getMockBuilder('\Jitamin\Model\UserNotificationTypeModel')
            ->setConstructorArgs([$this->container])
            ->setMethods(['getType', 'getSelectedTypes'])
            ->getMock();

        $this->container['objectStorage'] = $this
            ->getMockBuilder('\Jitamin\Core\ObjectStorage\FileStorage')
            ->setConstructorArgs([$this->container])
            ->setMethods(['put', 'moveFile', 'remove', 'moveUploadedFile'])
            ->getMock();

        $this->container['sessionStorage'] = new SessionStorage();
        $this->container->register(new ActionProvider());

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
