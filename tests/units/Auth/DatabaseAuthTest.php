<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Jitamin\Auth\DatabaseAuth;
use Jitamin\Model\UserModel;

class DatabaseAuthTest extends Base
{
    public function testGetName()
    {
        $provider = new DatabaseAuth($this->container);
        $this->assertEquals('Database', $provider->getName());
    }

    public function testAuthenticate()
    {
        $provider = new DatabaseAuth($this->container);

        $provider->setUsername('admin');
        $provider->setPassword('admin');
        $this->assertTrue($provider->authenticate());

        $provider->setUsername('admin');
        $provider->setPassword('test');
        $this->assertFalse($provider->authenticate());
    }

    public function testGetUser()
    {
        $provider = new DatabaseAuth($this->container);
        $this->assertEquals(null, $provider->getUser());

        $provider = new DatabaseAuth($this->container);
        $provider->setUsername('admin');
        $provider->setPassword('admin');

        $this->assertTrue($provider->authenticate());
        $this->assertInstanceOf('Jitamin\Services\Identity\DatabaseUserProvider', $provider->getUser());
    }

    public function testIsvalidSession()
    {
        $userModel = new UserModel($this->container);
        $provider = new DatabaseAuth($this->container);

        $this->assertFalse($provider->isValidSession());

        $this->assertEquals(2, $userModel->create(['username' => 'foobar', 'email' => 'foobar@here']));

        $this->container['sessionStorage']->user = ['id' => 2];
        $this->assertTrue($provider->isValidSession());

        $this->container['sessionStorage']->user = ['id' => 3];
        $this->assertFalse($provider->isValidSession());

        $this->assertTrue($userModel->disable(2));

        $this->container['sessionStorage']->user = ['id' => 2];
        $this->assertFalse($provider->isValidSession());
    }
}
