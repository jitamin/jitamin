<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Jitamin\Foundation\Http\Request;
use Jitamin\Foundation\Http\Route;
use Jitamin\Foundation\Http\Router;

class RouterTest extends Base
{
    public function testGetPathWithFolder()
    {
        $router = new Router($this->container);
        $this->container['request'] = new Request($this->container, ['PHP_SELF' => '/index.php', 'REQUEST_URI' => '/a/b/c', 'REQUEST_METHOD' => 'GET']);
        $this->assertEquals('a/b/c', $router->getPath());
    }

    public function testGetPathWithQueryString()
    {
        $router = new Router($this->container);
        $this->container['request'] = new Request($this->container, ['PHP_SELF' => '/index.php', 'REQUEST_URI' => '/a/b/something?test=a', 'QUERY_STRING' => 'test=a', 'REQUEST_METHOD' => 'GET']);
        $this->assertEquals('a/b/something', $router->getPath());
    }

    public function testGetPathWithSubFolderAndQueryString()
    {
        $router = new Router($this->container);
        $this->container['request'] = new Request($this->container, ['PHP_SELF' => '/a/index.php', 'REQUEST_URI' => '/a/b/something?test=a', 'QUERY_STRING' => 'test=a', 'REQUEST_METHOD' => 'GET']);
        $this->assertEquals('b/something', $router->getPath());
    }

    public function testDispatcherWithNoUrlRewrite()
    {
        $this->container['request'] = new Request(
            $this->container,
            [
                'PHP_SELF'       => '/jitamin/index.php',
                'REQUEST_URI'    => '/jitamin/?controller=FakeController&action=myAction&myvar=value1',
                'QUERY_STRING'   => 'controller=FakeController&action=myAction&myvar=value1',
                'REQUEST_METHOD' => 'GET',
            ],
            [
                'controller' => 'FakeController',
                'action'     => 'myAction',
                'myvar'      => 'value1',
            ]
        );

        $dispatcher = new Router($this->container);
        $dispatcher->dispatch();

        $this->assertEquals('FakeController', $dispatcher->getController());
        $this->assertEquals('myAction', $dispatcher->getAction());
        $this->assertEquals('', $dispatcher->getPlugin());
        $this->assertEquals('value1', $this->container['request']->getStringParam('myvar'));
    }

    public function testDispatcherWithNoUrlRewriteAndPlugin()
    {
        $this->container['request'] = new Request(
            $this->container,
            [
                'PHP_SELF'       => '/jitamin/index.php',
                'REQUEST_URI'    => '/jitamin/?controller=FakeController&action=myAction&myvar=value1&plugin=myplugin',
                'QUERY_STRING'   => 'controller=FakeController&action=myAction&myvar=value1&plugin=myplugin',
                'REQUEST_METHOD' => 'GET',
            ],
            [
                'controller' => 'FakeController',
                'action'     => 'myAction',
                'myvar'      => 'value1',
                'plugin'     => 'myplugin',
            ]
        );

        $dispatcher = new Router($this->container);
        $dispatcher->dispatch();

        $this->assertEquals('FakeController', $dispatcher->getController());
        $this->assertEquals('myAction', $dispatcher->getAction());
        $this->assertEquals('Myplugin', $dispatcher->getPlugin());
        $this->assertEquals('value1', $this->container['request']->getStringParam('myvar'));
    }

    public function testDispatcherWithUrlRewrite()
    {
        $this->container['request'] = new Request(
            $this->container,
            [
                'PHP_SELF'       => '/jitamin/index.php',
                'REQUEST_URI'    => '/jitamin/my/route/123?myvar=value1',
                'QUERY_STRING'   => 'myvar=value1',
                'REQUEST_METHOD' => 'GET',
            ],
            [
                'myvar' => 'value1',
            ]
        );

        $this->container['route'] = new Route($this->container);
        $this->container['route']->enable();
        $this->container['route']->addRoute('/my/route/{param}', 'FakeController', 'myAction');

        $dispatcher = new Router($this->container);
        $dispatcher->dispatch();

        $this->assertEquals('FakeController', $dispatcher->getController());
        $this->assertEquals('myAction', $dispatcher->getAction());
        $this->assertEquals('', $dispatcher->getPlugin());
        $this->assertEquals('value1', $this->container['request']->getStringParam('myvar'));
        $this->assertEquals('123', $this->container['request']->getStringParam('param'));
    }

    public function testDispatcherWithUrlRewriteWithPlugin()
    {
        $this->container['request'] = new Request(
            $this->container,
            [
                'PHP_SELF'       => '/jitamin/index.php',
                'REQUEST_URI'    => '/jitamin/my/plugin/route/123?myvar=value1',
                'QUERY_STRING'   => 'myvar=value1',
                'REQUEST_METHOD' => 'GET',
            ],
            [
                'myvar' => 'value1',
            ]
        );

        $this->container['route'] = new Route($this->container);
        $this->container['route']->enable();
        $this->container['route']->addRoute('/my/plugin/route/{param}', 'fakeController', 'myAction', 'Myplugin');

        $dispatcher = new Router($this->container);
        $dispatcher->dispatch();

        $this->assertEquals('FakeController', $dispatcher->getController());
        $this->assertEquals('myAction', $dispatcher->getAction());
        $this->assertEquals('Myplugin', $dispatcher->getPlugin());
        $this->assertEquals('value1', $this->container['request']->getStringParam('myvar'));
        $this->assertEquals('123', $this->container['request']->getStringParam('param'));
    }
}
