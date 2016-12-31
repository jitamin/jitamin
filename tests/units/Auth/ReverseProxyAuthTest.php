<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Auth\ReverseProxyAuth;
use Jitamin\Core\Security\Role;
use Jitamin\Model\UserModel;

require_once __DIR__.'/../Base.php';

class ReverseProxyAuthTest extends Base
{
    public function setUp()
    {
        parent::setUp();

        $this->container['request'] = $this
            ->getMockBuilder('\Jitamin\Core\Http\Request')
            ->setConstructorArgs([$this->container])
            ->setMethods(['getRemoteUser'])
            ->getMock();
    }

    public function testGetName()
    {
        $provider = new ReverseProxyAuth($this->container);
        $this->assertEquals('ReverseProxy', $provider->getName());
    }

    public function testAuthenticateSuccess()
    {
        $this->container['request']
            ->expects($this->once())
            ->method('getRemoteUser')
            ->will($this->returnValue('admin'));

        $provider = new ReverseProxyAuth($this->container);
        $this->assertTrue($provider->authenticate());
    }

    public function testAuthenticateFailure()
    {
        $this->container['request']
            ->expects($this->once())
            ->method('getRemoteUser')
            ->will($this->returnValue(''));

        $provider = new ReverseProxyAuth($this->container);
        $this->assertFalse($provider->authenticate());
    }

    public function testValidSession()
    {
        $this->container['request']
            ->expects($this->once())
            ->method('getRemoteUser')
            ->will($this->returnValue('admin'));

        $this->container['sessionStorage']->user = [
            'username' => 'admin',
        ];

        $provider = new ReverseProxyAuth($this->container);
        $this->assertTrue($provider->isValidSession());
    }

    public function testInvalidSession()
    {
        $this->container['request']
            ->expects($this->once())
            ->method('getRemoteUser')
            ->will($this->returnValue('foobar'));

        $this->container['sessionStorage']->user = [
            'username' => 'admin',
        ];

        $provider = new ReverseProxyAuth($this->container);
        $this->assertFalse($provider->isValidSession());
    }

    public function testRoleForNewUser()
    {
        $this->container['request']
            ->expects($this->once())
            ->method('getRemoteUser')
            ->will($this->returnValue('someone'));

        $provider = new ReverseProxyAuth($this->container);
        $this->assertTrue($provider->authenticate());

        $user = $provider->getUser();
        $this->assertEquals(Role::APP_USER, $user->getRole());
    }

    public function testRoleIsPreservedForExistingUser()
    {
        $this->container['request']
            ->expects($this->once())
            ->method('getRemoteUser')
            ->will($this->returnValue('someone'));

        $provider = new ReverseProxyAuth($this->container);
        $userModel = new UserModel($this->container);

        $this->assertEquals(2, $userModel->create(['username' => 'someone', 'email' => 'someone@here', 'role' => Role::APP_MANAGER]));

        $this->assertTrue($provider->authenticate());

        $user = $provider->getUser();
        $this->assertEquals(Role::APP_MANAGER, $user->getRole());
    }
}
