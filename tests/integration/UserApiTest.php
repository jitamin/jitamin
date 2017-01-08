<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/BaseApiTest.php';

class UserApiTest extends BaseApiTest
{
    public function testAll()
    {
        $this->assertCreateUser();
        $this->assertGetUserById();
        $this->assertGetUserByName();
        $this->assertGetAllUsers();
        $this->assertEnableDisableUser();
        $this->assertUpdateUser();
        $this->assertRemoveUser();
    }

    public function assertGetUserById()
    {
        $user = $this->app->getUser($this->userId);
        $this->assertNotNull($user);
        $this->assertEquals($this->username, $user['username']);
    }

    public function assertGetUserByName()
    {
        $user = $this->app->getUserByName($this->username);
        $this->assertNotNull($user);
        $this->assertEquals($this->username, $user['username']);
    }

    public function assertGetAllUsers()
    {
        $users = $this->app->getAllUsers();
        $this->assertInternalType('array', $users);
        $this->assertNotEmpty($users);
    }

    public function assertEnableDisableUser()
    {
        $this->assertTrue($this->app->disableUser($this->userId));
        $this->assertFalse($this->app->isActiveUser($this->userId));
        $this->assertTrue($this->app->enableUser($this->userId));
        $this->assertTrue($this->app->isActiveUser($this->userId));
    }

    public function assertUpdateUser()
    {
        $this->assertTrue($this->app->updateUser([
            'id'   => $this->userId,
            'name' => 'My user',
        ]));

        $user = $this->app->getUser($this->userId);
        $this->assertNotNull($user);
        $this->assertEquals('My user', $user['name']);
    }

    public function assertRemoveUser()
    {
        $this->assertTrue($this->app->removeUser($this->userId));
    }
}
