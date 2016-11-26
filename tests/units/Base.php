<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../../bootstrap/constants.php';

use Composer\Autoload\ClassLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;
use SimpleLogger\Logger;
use Hiject\Core\Session\FlashMessage;
use Hiject\Core\Session\SessionStorage;
use Hiject\Providers\ActionProvider;

abstract class Base extends PHPUnit_Framework_TestCase
{
    protected $container;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

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
        }

        $this->container = new Pimple\Container;
        $this->container->register(new Hiject\Providers\CacheProvider());
        $this->container->register(new Hiject\Providers\HelperProvider());
        $this->container->register(new Hiject\Providers\AuthenticationProvider());
        $this->container->register(new Hiject\Providers\DatabaseProvider());
        $this->container->register(new Hiject\Providers\ClassProvider());
        $this->container->register(new Hiject\Providers\NotificationProvider());
        $this->container->register(new Hiject\Providers\RouteProvider());
        $this->container->register(new Hiject\Providers\AvatarProvider());
        $this->container->register(new Hiject\Providers\FilterProvider());
        $this->container->register(new Hiject\Providers\JobProvider());
        $this->container->register(new Hiject\Providers\QueueProvider());

        $this->container['dispatcher'] = new TraceableEventDispatcher(
            new EventDispatcher,
            new Stopwatch
        );

        $this->dispatcher = $this->container['dispatcher'];

        $this->container['db']->getStatementHandler()->withLogging();
        $this->container['logger'] = new Logger();

        $this->container['httpClient'] = $this
            ->getMockBuilder('\Hiject\Core\Http\Client')
            ->setConstructorArgs([$this->container])
            ->setMethods(['get', 'getJson', 'postJson', 'postJsonAsync', 'postForm', 'postFormAsync'])
            ->getMock();

        $this->container['emailClient'] = $this
            ->getMockBuilder('\Hiject\Core\Mail\Client')
            ->setConstructorArgs([$this->container])
            ->setMethods(['send'])
            ->getMock();

        $this->container['userNotificationTypeModel'] = $this
            ->getMockBuilder('\Hiject\Model\UserNotificationTypeModel')
            ->setConstructorArgs([$this->container])
            ->setMethods(['getType', 'getSelectedTypes'])
            ->getMock();

        $this->container['objectStorage'] = $this
            ->getMockBuilder('\Hiject\Core\ObjectStorage\FileStorage')
            ->setConstructorArgs([$this->container])
            ->setMethods(['put', 'moveFile', 'remove', 'moveUploadedFile'])
            ->getMock();

        $this->container['sessionStorage'] = new SessionStorage;
        $this->container->register(new ActionProvider);

        $this->container['flash'] = function ($c) {
            return new FlashMessage($c);
        };

        $loader = new ClassLoader();
        $loader->addPsr4('Hiject\Plugin\\', PLUGINS_DIR);
        $loader->register();
    }

    public function tearDown()
    {
        $this->container['db']->closeConnection();
    }
}
