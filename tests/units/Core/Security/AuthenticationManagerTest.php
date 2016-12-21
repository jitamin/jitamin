<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Jitamin\Auth\DatabaseAuth;
use Jitamin\Auth\ReverseProxyAuth;
use Jitamin\Auth\TotpAuth;
use Jitamin\Core\Http\Request;
use Jitamin\Core\Security\AuthenticationManager;

class AuthenticationManagerTest extends Base
{
    public function testRegister()
    {
        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new DatabaseAuth($this->container));
        $provider = $authManager->getProvider('Database');

        $this->assertInstanceOf('Jitamin\Core\Security\AuthenticationProviderInterface', $provider);
    }

    public function testGetProviderNotFound()
    {
        $authManager = new AuthenticationManager($this->container);
        $this->setExpectedException('LogicException');
        $authManager->getProvider('Dababase');
    }

    public function testGetPostProviderNotFound()
    {
        $authManager = new AuthenticationManager($this->container);
        $this->setExpectedException('LogicException');
        $authManager->getPostAuthenticationProvider();
    }

    public function testGetPostProvider()
    {
        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new TotpAuth($this->container));
        $provider = $authManager->getPostAuthenticationProvider();

        $this->assertInstanceOf('Jitamin\Core\Security\PostAuthenticationProviderInterface', $provider);
    }

    public function testCheckSessionWhenNobodyIsLogged()
    {
        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new DatabaseAuth($this->container));

        $this->assertFalse($this->container['userSession']->isLogged());
        $this->assertTrue($authManager->checkCurrentSession());
    }

    public function testCheckSessionWhenSomeoneIsLogged()
    {
        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new DatabaseAuth($this->container));

        $this->container['sessionStorage']->user = ['id' => 1];

        $this->assertTrue($this->container['userSession']->isLogged());
        $this->assertTrue($authManager->checkCurrentSession());
    }

    public function testCheckSessionWhenNotValid()
    {
        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new DatabaseAuth($this->container));

        $this->container['sessionStorage']->user = ['id' => 2];

        $this->assertTrue($this->container['userSession']->isLogged());
        $this->assertFalse($authManager->checkCurrentSession());
        $this->assertFalse($this->container['userSession']->isLogged());
    }

    public function testPreAuthenticationSuccessful()
    {
        $this->container['request'] = new Request($this->container, [REVERSE_PROXY_USER_HEADER => 'admin']);
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_SUCCESS, [$this, 'onSuccess']);
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_FAILURE, [$this, 'onFailure']);

        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new ReverseProxyAuth($this->container));

        $this->assertTrue($authManager->preAuthentication());

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(AuthenticationManager::EVENT_SUCCESS.'.AuthenticationManagerTest::onSuccess', $called);
        $this->assertArrayNotHasKey(AuthenticationManager::EVENT_FAILURE.'.AuthenticationManagerTest::onFailure', $called);
    }

    public function testPreAuthenticationFailed()
    {
        $this->container['request'] = new Request($this->container, [REVERSE_PROXY_USER_HEADER => '']);
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_SUCCESS, [$this, 'onSuccess']);
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_FAILURE, [$this, 'onFailure']);

        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new ReverseProxyAuth($this->container));

        $this->assertFalse($authManager->preAuthentication());

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayNotHasKey(AuthenticationManager::EVENT_SUCCESS.'.AuthenticationManagerTest::onSuccess', $called);
        $this->assertArrayNotHasKey(AuthenticationManager::EVENT_FAILURE.'.AuthenticationManagerTest::onFailure', $called);
    }

    public function testPasswordAuthenticationSuccessful()
    {
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_SUCCESS, [$this, 'onSuccess']);
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_FAILURE, [$this, 'onFailure']);

        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new DatabaseAuth($this->container));

        $this->assertTrue($authManager->passwordAuthentication('admin', 'admin'));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(AuthenticationManager::EVENT_SUCCESS.'.AuthenticationManagerTest::onSuccess', $called);
        $this->assertArrayNotHasKey(AuthenticationManager::EVENT_FAILURE.'.AuthenticationManagerTest::onFailure', $called);
    }

    public function testPasswordAuthenticationFailed()
    {
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_SUCCESS, [$this, 'onSuccess']);
        $this->container['dispatcher']->addListener(AuthenticationManager::EVENT_FAILURE, [$this, 'onFailure']);

        $authManager = new AuthenticationManager($this->container);
        $authManager->register(new DatabaseAuth($this->container));

        $this->assertFalse($authManager->passwordAuthentication('admin', 'wrong password'));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayNotHasKey(AuthenticationManager::EVENT_SUCCESS.'.AuthenticationManagerTest::onSuccess', $called);
        $this->assertArrayHasKey(AuthenticationManager::EVENT_FAILURE.'.AuthenticationManagerTest::onFailure', $called);
    }

    public function onSuccess($event)
    {
        $this->assertInstanceOf('Jitamin\Bus\Event\AuthSuccessEvent', $event);
        $this->assertTrue(in_array($event->getAuthType(), ['Database', 'ReverseProxy']));
    }

    public function onFailure($event)
    {
        $this->assertInstanceOf('Jitamin\Bus\Event\AuthFailureEvent', $event);
        $this->assertEquals('admin', $event->getUsername());
    }
}
