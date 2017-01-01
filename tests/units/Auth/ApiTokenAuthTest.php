<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Jitamin\Auth\ApiTokenAuth;
use Jitamin\Model\UserModel;

class ApiTokenAuthTest extends Base
{
    public function testGetName()
    {
        $provider = new ApiTokenAuth($this->container);
        $this->assertEquals('API Access Token', $provider->getName());
    }

    public function testAuthenticateWithoutToken()
    {
        $provider = new ApiTokenAuth($this->container);

        $provider->setUsername('admin');
        $provider->setPassword('admin');
        $this->assertFalse($provider->authenticate());
        $this->assertNull($provider->getUser());
    }

    public function testAuthenticateWithEmptyPassword()
    {
        $provider = new ApiTokenAuth($this->container);

        $provider->setUsername('admin');
        $provider->setPassword('');
        $this->assertFalse($provider->authenticate());
    }

    public function testAuthenticateWithTokenAndNoScope()
    {
        $provider = new ApiTokenAuth($this->container);
        $userModel = new UserModel($this->container);

        $userModel->update([
            'id'        => 1,
            'api_token' => 'test',
        ]);

        $provider->setUsername('admin');
        $provider->setPassword('test');
        $this->assertFalse($provider->authenticate());
    }

    public function testAuthenticateWithToken()
    {
        $this->container['sessionStorage']->scope = 'API';

        $provider = new ApiTokenAuth($this->container);
        $userModel = new UserModel($this->container);

        $userModel->update([
            'id'        => 1,
            'api_token' => 'test',
        ]);

        $provider->setUsername('admin');
        $provider->setPassword('test');
        $this->assertTrue($provider->authenticate());
        $this->assertInstanceOf('Jitamin\Services\Identity\DatabaseUserProvider', $provider->getUser());

        $provider->setUsername('admin');
        $provider->setPassword('something else');
        $this->assertFalse($provider->authenticate());
    }
}
