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

use Hiject\Bus\Event\GenericEvent;
use Hiject\Core\Security\Role;
use Hiject\Model\ProjectModel;
use Hiject\Model\ProjectUserRoleModel;
use Hiject\Model\TaskModel;
use Hiject\Model\UserMentionModel;
use Hiject\Model\UserModel;

class UserMentionTest extends Base
{
    public function testGetMentionedUsersWithNoMentions()
    {
        $userModel = new UserModel($this->container);
        $userMentionModel = new UserMentionModel($this->container);

        $this->assertNotFalse($userModel->create(['username' => 'user1', 'email' => 'user1@user1']));
        $this->assertEmpty($userMentionModel->getMentionedUsers('test'));
    }

    public function testGetMentionedUsersWithNotficationDisabled()
    {
        $userModel = new UserModel($this->container);
        $userMentionModel = new UserMentionModel($this->container);

        $this->assertNotFalse($userModel->create(['username' => 'user1', 'email' => 'user1@user1']));
        $this->assertEmpty($userMentionModel->getMentionedUsers('test @user1'));
    }

    public function testGetMentionedUsersWithNotficationEnabled()
    {
        $userModel = new UserModel($this->container);
        $userMentionModel = new UserMentionModel($this->container);

        $this->assertNotFalse($userModel->create(['username' => 'user1', 'email' => 'user1@user1']));
        $this->assertNotFalse($userModel->create(['username' => 'user2', 'email' => 'user2@user2', 'name' => 'Foobar', 'notifications_enabled' => 1]));

        $users = $userMentionModel->getMentionedUsers('test @user2');
        $this->assertCount(1, $users);
        $this->assertEquals('user2', $users[0]['username']);
        $this->assertEquals('Foobar', $users[0]['name']);
        $this->assertEquals('user2@user2', $users[0]['email']);
        $this->assertEquals('', $users[0]['language']);
    }

    public function testGetMentionedUsersWithNotficationEnabledAndUserLoggedIn()
    {
        $this->container['sessionStorage']->user = ['id' => 3];
        $userModel = new UserModel($this->container);
        $userMentionModel = new UserMentionModel($this->container);

        $this->assertNotFalse($userModel->create(['username' => 'user1', 'email' => 'user1@user1']));
        $this->assertNotFalse($userModel->create(['username' => 'user2', 'email' => 'user2@user2', 'name' => 'Foobar', 'notifications_enabled' => 1]));

        $this->assertEmpty($userMentionModel->getMentionedUsers('test @user2'));
    }

    public function testFireEventsWithMultipleMentions()
    {
        $projectUserRoleModel = new ProjectUserRoleModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $userModel = new UserModel($this->container);
        $userMentionModel = new UserMentionModel($this->container);
        $event = new GenericEvent(['project_id' => 1]);

        $this->assertEquals(2, $userModel->create(['username' => 'user1', 'email' => 'user1@user1', 'name' => 'User 1', 'notifications_enabled' => 1]));
        $this->assertEquals(3, $userModel->create(['username' => 'user2', 'email' => 'user2@user2', 'name' => 'User 2', 'notifications_enabled' => 1]));

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertTrue($projectUserRoleModel->addUser(1, 3, Role::PROJECT_MEMBER));

        $this->container['dispatcher']->addListener(TaskModel::EVENT_USER_MENTION, [$this, 'onUserMention']);

        $userMentionModel->fireEvents('test @user1 @user2', TaskModel::EVENT_USER_MENTION, $event);

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_USER_MENTION.'.UserMentionTest::onUserMention', $called);
    }

    public function testFireEventsWithNoProjectId()
    {
        $projectUserRoleModel = new ProjectUserRoleModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $userModel = new UserModel($this->container);
        $userMentionModel = new UserMentionModel($this->container);
        $event = new GenericEvent(['task_id' => 1]);

        $this->assertEquals(2, $userModel->create(['username' => 'user1', 'email' => 'user1@user1', 'name' => 'User 1', 'notifications_enabled' => 1]));
        $this->assertEquals(3, $userModel->create(['username' => 'user2', 'email' => 'user2@user2', 'name' => 'User 2', 'notifications_enabled' => 1]));

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertTrue($projectUserRoleModel->addUser(1, 3, Role::PROJECT_MEMBER));

        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'Task 1']));

        $this->container['dispatcher']->addListener(TaskModel::EVENT_USER_MENTION, [$this, 'onUserMention']);

        $userMentionModel->fireEvents('test @user1 @user2', TaskModel::EVENT_USER_MENTION, $event);

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_USER_MENTION.'.UserMentionTest::onUserMention', $called);
    }

    public function onUserMention($event)
    {
        $this->assertInstanceOf('Hiject\Bus\Event\GenericEvent', $event);
        $this->assertEquals(['id' => '3', 'username' => 'user2', 'name' => 'User 2', 'email' => 'user2@user2', 'language' => null], $event['mention']);
    }
}
