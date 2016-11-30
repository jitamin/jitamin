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

use Hiject\Core\Security\Role;
use Hiject\Model\GroupMemberModel;
use Hiject\Model\GroupModel;
use Hiject\Model\ProjectGroupRoleModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\ProjectPermissionModel;
use Hiject\Model\ProjectUserRoleModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;
use Hiject\Model\UserModel;
use Hiject\Model\UserNotificationFilterModel;
use Hiject\Model\UserNotificationModel;

class UserNotificationTest extends Base
{
    public function testEnableDisableNotification()
    {
        $u = new UserModel($this->container);
        $p = new ProjectModel($this->container);
        $n = new UserNotificationModel($this->container);
        $pp = new ProjectUserRoleModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest1']));
        $this->assertEquals(2, $u->create(['username' => 'user1']));
        $this->assertTrue($pp->addUser(1, 2, Role::PROJECT_MEMBER));

        $this->assertEmpty($n->getUsersWithNotificationEnabled(1));
        $n->enableNotification(2);
        $this->assertNotEmpty($n->getUsersWithNotificationEnabled(1));
        $n->disableNotification(2);
        $this->assertEmpty($n->getUsersWithNotificationEnabled(1));
    }

    public function testReadWriteSettings()
    {
        $n = new UserNotificationModel($this->container);
        $p = new ProjectModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest1']));

        $n->saveSettings(1, [
            'notifications_enabled' => 1,
            'notifications_filter'  => UserNotificationFilterModel::FILTER_CREATOR,
            'notification_types'    => ['email' => 1],
            'notification_projects' => [],
        ]);

        $this->container['userNotificationTypeModel']
            ->expects($this->at(0))
            ->method('getSelectedTypes')
            ->will($this->returnValue(['email']));

        $this->container['userNotificationTypeModel']
            ->expects($this->at(1))
            ->method('getSelectedTypes')
            ->will($this->returnValue(['email']));

        $this->container['userNotificationTypeModel']
            ->expects($this->at(2))
            ->method('getSelectedTypes')
            ->with($this->equalTo(1))
            ->will($this->returnValue(['email', 'web']));

        $settings = $n->readSettings(1);
        $this->assertNotEmpty($settings);
        $this->assertEquals(1, $settings['notifications_enabled']);
        $this->assertEquals(UserNotificationFilterModel::FILTER_CREATOR, $settings['notifications_filter']);
        $this->assertEquals(['email'], $settings['notification_types']);
        $this->assertEmpty($settings['notification_projects']);

        $n->saveSettings(1, [
            'notifications_enabled' => 0,
        ]);

        $settings = $n->readSettings(1);
        $this->assertNotEmpty($settings);
        $this->assertEquals(0, $settings['notifications_enabled']);

        $n->saveSettings(1, [
            'notifications_enabled' => 1,
            'notifications_filter'  => UserNotificationFilterModel::FILTER_ASSIGNEE,
            'notification_types'    => ['web' => 1, 'email' => 1],
            'notification_projects' => [1 => 1],
        ]);

        $settings = $n->readSettings(1);
        $this->assertNotEmpty($settings);
        $this->assertEquals(1, $settings['notifications_enabled']);
        $this->assertEquals(UserNotificationFilterModel::FILTER_ASSIGNEE, $settings['notifications_filter']);
        $this->assertEquals(['email', 'web'], $settings['notification_types']);
        $this->assertEquals([1], $settings['notification_projects']);
    }

    public function testGetGroupMembersWithNotificationEnabled()
    {
        $userModel = new UserModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupMemberModel = new GroupMemberModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $userNotificationModel = new UserNotificationModel($this->container);
        $projectGroupRole = new ProjectGroupRoleModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);

        $this->assertEquals(2, $userModel->create(['username' => 'user1', 'email' => 'user1@here', 'notifications_enabled' => 1]));
        $this->assertEquals(3, $userModel->create(['username' => 'user2', 'email' => 'user2@here', 'notifications_enabled' => 1]));
        $this->assertEquals(4, $userModel->create(['username' => 'user3', 'email' => 'user3@here', 'notifications_enabled' => 1, 'is_active' => 0]));
        $this->assertEquals(5, $userModel->create(['username' => 'user4', 'email' => 'user4@here']));

        $this->assertEquals(1, $groupModel->create('G1'));
        $this->assertEquals(2, $groupModel->create('G2'));

        $this->assertTrue($groupMemberModel->addUser(1, 2));
        $this->assertTrue($groupMemberModel->addUser(1, 3));
        $this->assertTrue($groupMemberModel->addUser(1, 4));
        $this->assertTrue($groupMemberModel->addUser(1, 5));
        $this->assertTrue($groupMemberModel->addUser(2, 2));
        $this->assertTrue($groupMemberModel->addUser(2, 3));

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));

        $this->assertTrue($projectGroupRole->addGroup(1, 1, Role::PROJECT_MEMBER));
        $this->assertTrue($projectGroupRole->addGroup(1, 2, Role::PROJECT_VIEWER));

        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_MEMBER));

        $users = $userNotificationModel->getUsersWithNotificationEnabled(1);
        $this->assertCount(2, $users);
        $this->assertEquals('user1', $users[0]['username']);
        $this->assertEquals('user2', $users[1]['username']);
    }

    public function testGetProjectMembersWithNotifications()
    {
        $u = new UserModel($this->container);
        $p = new ProjectModel($this->container);
        $n = new UserNotificationModel($this->container);
        $pp = new ProjectUserRoleModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest1']));

        // Email + Notifications enabled
        $this->assertEquals(2, $u->create(['username' => 'user1', 'email' => 'user1@here', 'notifications_enabled' => 1]));

        // Email + Notifications enabled
        $this->assertEquals(3, $u->create(['username' => 'user2', 'email' => 'user2@here', 'notifications_enabled' => 1]));

        // Email + Notifications enabled
        $this->assertEquals(4, $u->create(['username' => 'user3', 'email' => 'user3@here', 'notifications_enabled' => 1]));

        // User disabled
        $this->assertEquals(5, $u->create(['username' => 'user4', 'email' => 'user4@here', 'notifications_enabled' => 1, 'is_active' => 0]));

        // No email + notifications disabled
        $this->assertEquals(6, $u->create(['username' => 'user5']));

        // Nobody is member of any projects
        $this->assertEmpty($pp->getUsers(1));
        $this->assertEmpty($n->getUsersWithNotificationEnabled(1));

        // We allow all users to be member of our projects
        $this->assertTrue($pp->addUser(1, 2, Role::PROJECT_MEMBER));
        $this->assertTrue($pp->addUser(1, 3, Role::PROJECT_MEMBER));
        $this->assertTrue($pp->addUser(1, 4, Role::PROJECT_MEMBER));
        $this->assertTrue($pp->addUser(1, 5, Role::PROJECT_MEMBER));
        $this->assertTrue($pp->addUser(1, 6, Role::PROJECT_MEMBER));

        $this->assertNotEmpty($pp->getUsers(1));
        $users = $n->getUsersWithNotificationEnabled(1);

        $this->assertNotEmpty($users);
        $this->assertCount(3, $users);
        $this->assertEquals('user1@here', $users[0]['email']);
        $this->assertEquals('user2@here', $users[1]['email']);
        $this->assertEquals('user3@here', $users[2]['email']);
    }

    public function testGetUsersWithNotificationsWhenEverybodyAllowed()
    {
        $u = new UserModel($this->container);
        $p = new ProjectModel($this->container);
        $n = new UserNotificationModel($this->container);
        $pp = new ProjectPermissionModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest1', 'is_everybody_allowed' => 1]));
        $this->assertTrue($pp->isEverybodyAllowed(1));

        // Email + Notifications enabled
        $this->assertEquals(2, $u->create(['username' => 'user1', 'email' => 'user1@here', 'notifications_enabled' => 1]));

        // Email + Notifications enabled
        $this->assertEquals(3, $u->create(['username' => 'user2', 'email' => 'user2@here', 'notifications_enabled' => 1]));

        // Email + Notifications enabled
        $this->assertEquals(4, $u->create(['username' => 'user3', 'email' => 'user3@here', 'notifications_enabled' => 1]));

        // User disabled
        $this->assertEquals(5, $u->create(['username' => 'user4', 'email' => 'user4@here', 'notifications_enabled' => 1, 'is_active' => 0]));

        // Email + notifications disabled
        $this->assertEquals(6, $u->create(['username' => 'user5', 'email' => 'user5@here']));

        $users = $n->getUsersWithNotificationEnabled(1);

        $this->assertNotEmpty($users);
        $this->assertCount(3, $users);
        $this->assertEquals('user1@here', $users[0]['email']);
        $this->assertEquals('user2@here', $users[1]['email']);
        $this->assertEquals('user3@here', $users[2]['email']);
    }

    public function testSendNotifications()
    {
        $u = new UserModel($this->container);
        $n = new UserNotificationModel($this->container);
        $p = new ProjectModel($this->container);
        $tc = new TaskCreationModel($this->container);
        $tf = new TaskFinderModel($this->container);
        $pp = new ProjectPermissionModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest1', 'is_everybody_allowed' => 1]));
        $this->assertEquals(1, $tc->create(['title' => 'test', 'project_id' => 1]));
        $this->assertTrue($u->update(['id' => 1, 'email' => 'test@localhost']));
        $this->assertTrue($pp->isEverybodyAllowed(1));

        $n->saveSettings(1, [
            'notifications_enabled' => 1,
            'notifications_filter'  => UserNotificationFilterModel::FILTER_NONE,
            'notification_types'    => ['web' => 1, 'email' => 1],
        ]);

        $notifier = $this
            ->getMockBuilder('Stdclass')
            ->setMethods(['notifyUser'])
            ->getMock();

        $notifier
            ->expects($this->exactly(2))
            ->method('notifyUser');

        $this->container['userNotificationTypeModel']
            ->expects($this->at(0))
            ->method('getSelectedTypes')
            ->will($this->returnValue(['email', 'web']));

        $this->container['userNotificationTypeModel']
            ->expects($this->at(1))
            ->method('getType')
            ->with($this->equalTo('email'))
            ->will($this->returnValue($notifier));

        $this->container['userNotificationTypeModel']
            ->expects($this->at(2))
            ->method('getType')
            ->with($this->equalTo('web'))
            ->will($this->returnValue($notifier));

        $n->sendNotifications(TaskModel::EVENT_CREATE, ['task' => $tf->getDetails(1)]);
    }
}
