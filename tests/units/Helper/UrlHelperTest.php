<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Hiject\Core\Http\Request;
use Hiject\Helper\UrlHelper;
use Hiject\Model\SettingModel;

class UrlHelperTest extends Base
{
    public function testPluginLink()
    {
        $h = new UrlHelper($this->container);
        $this->assertEquals(
            '<a href="?controller=a&amp;action=b&amp;d=e&amp;plugin=something" class="f" title=\'g\' target="_blank">label</a>',
            $h->link('label', 'a', 'b', ['d' => 'e', 'plugin' => 'something'], false, 'f', 'g', true)
        );
    }

    public function testPluginLinkWithRouteDefined()
    {
        $this->container['route']->enable();
        $this->container['route']->addRoute('/myplugin/something/:d', 'a', 'b', 'something');

        $h = new UrlHelper($this->container);
        $this->assertEquals(
            '<a href="myplugin/something/e" class="f" title=\'g\' target="_blank">label</a>',
            $h->link('label', 'a', 'b', ['d' => 'e', 'plugin' => 'something'], false, 'f', 'g', true)
        );
    }

    public function testAppLink()
    {
        $h = new UrlHelper($this->container);
        $this->assertEquals(
            '<a href="?controller=a&amp;action=b&amp;d=e" class="f" title=\'g\' target="_blank">label</a>',
            $h->link('label', 'a', 'b', ['d' => 'e'], false, 'f', 'g', true)
        );
    }

    public function testHref()
    {
        $h = new UrlHelper($this->container);
        $this->assertEquals(
            '?controller=a&amp;action=b&amp;d=e',
            $h->href('a', 'b', ['d' => 'e'])
        );
    }

    public function testTo()
    {
        $h = new UrlHelper($this->container);
        $this->assertEquals(
            '?controller=a&action=b&d=e',
            $h->to('a', 'b', ['d' => 'e'])
        );
    }

    public function testDir()
    {
        $this->container['request'] = new Request($this->container, [
                'PHP_SELF'       => '/hiject/index.php',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $h = new UrlHelper($this->container);
        $this->assertEquals('/hiject/', $h->dir());

        $this->container['request'] = new Request($this->container, [
                'PHP_SELF'       => '/index.php',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $h = new UrlHelper($this->container);
        $this->assertEquals('/', $h->dir());
    }

    public function testServer()
    {
        $this->container['request'] = new Request($this->container, [
                'PHP_SELF'       => '/index.php',
                'REQUEST_METHOD' => 'GET',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );

        $h = new UrlHelper($this->container);
        $this->assertEquals('http://localhost/', $h->server());

        $this->container['request'] = new Request($this->container, [
                'PHP_SELF'       => '/index.php',
                'REQUEST_METHOD' => 'GET',
                'SERVER_NAME'    => 'hj',
                'SERVER_PORT'    => 1234,
            ]
        );

        $h = new UrlHelper($this->container);
        $this->assertEquals('http://hj:1234/', $h->server());
    }

    public function testBase()
    {
        $this->container['request'] = new Request($this->container, [
                'PHP_SELF'       => '/index.php',
                'REQUEST_METHOD' => 'GET',
                'SERVER_NAME'    => 'hj',
                'SERVER_PORT'    => 1234,
            ]
        );

        $h = new UrlHelper($this->container);
        $this->assertEquals('http://hj:1234/', $h->base());

        $c = new SettingModel($this->container);
        $c->save(['application_url' => 'https://myhiject/']);
        $this->container['memoryCache']->flush();

        $h = new UrlHelper($this->container);
        $this->assertEquals('https://myhiject/', $c->get('application_url'));
        $this->assertEquals('https://myhiject/', $h->base());
    }
}
