<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Hiject\Core\Security\Role;
use Hiject\Core\User\UserProfile;
use Hiject\User\DatabaseUserProvider;
use Hiject\User\LdapUserProvider;

class UserProfileTest extends Base
{
    public function testInitializeLocalUser()
    {
        $userProfile = new UserProfile($this->container);
        $user = new DatabaseUserProvider(['id' => 1]);

        $this->assertTrue($userProfile->initialize($user));
        $this->assertNotEmpty($this->container['sessionStorage']->user);
        $this->assertEquals('admin', $this->container['sessionStorage']->user['username']);
    }

    public function testInitializeLocalUserNotFound()
    {
        $userProfile = new UserProfile($this->container);
        $user = new DatabaseUserProvider(['id' => 2]);

        $this->assertFalse($userProfile->initialize($user));
        $this->assertFalse(isset($this->container['sessionStorage']->user));
    }

    public function testInitializeRemoteUser()
    {
        $userProfile = new UserProfile($this->container);
        $user = new LdapUserProvider('ldapId', 'bob', 'Bob', 'bob@bob', Role::APP_MANAGER, []);

        $this->assertTrue($userProfile->initialize($user));
        $this->assertNotEmpty($this->container['sessionStorage']->user);
        $this->assertEquals(2, $this->container['sessionStorage']->user['id']);
        $this->assertEquals('bob', $this->container['sessionStorage']->user['username']);
        $this->assertEquals(Role::APP_MANAGER, $this->container['sessionStorage']->user['role']);

        $user = new LdapUserProvider('ldapId', 'bob', 'Bob', 'bob@bob', Role::APP_MANAGER, []);

        $this->assertTrue($userProfile->initialize($user));
        $this->assertNotEmpty($this->container['sessionStorage']->user);
        $this->assertEquals(2, $this->container['sessionStorage']->user['id']);
        $this->assertEquals('bob', $this->container['sessionStorage']->user['username']);
    }

    public function testAssignRemoteUser()
    {
        $userProfile = new UserProfile($this->container);
        $user = new LdapUserProvider('ldapId', 'bob', 'Bob', 'bob@bob', Role::APP_MANAGER, []);

        $this->assertTrue($userProfile->assign(1, $user));
        $this->assertNotEmpty($this->container['sessionStorage']->user);
        $this->assertEquals(1, $this->container['sessionStorage']->user['id']);
        $this->assertEquals('admin', $this->container['sessionStorage']->user['username']);
        $this->assertEquals('Bob', $this->container['sessionStorage']->user['name']);
        $this->assertEquals('bob@bob', $this->container['sessionStorage']->user['email']);
        $this->assertEquals(Role::APP_MANAGER, $this->container['sessionStorage']->user['role']);
    }
}
