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

use Jitamin\Core\Security\Role;
use Jitamin\Model\GroupMemberModel;
use Jitamin\Model\GroupModel;
use Jitamin\Model\ProjectGroupRoleModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\UserModel;

class ProjectGroupRoleTest extends Base
{
    public function testGetUserRole()
    {
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);
        $groupMemberModel = new GroupMemberModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $groupModel->create('Group A'));

        $this->assertTrue($groupMemberModel->addUser(1, 1));
        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));

        $this->assertEquals(Role::PROJECT_VIEWER, $groupRoleModel->getUserRole(1, 1));
        $this->assertEquals('', $groupRoleModel->getUserRole(1, 2));
    }

    public function testAddGroup()
    {
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $groupModel->create('Test'));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));
        $this->assertFalse($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));

        $groups = $groupRoleModel->getGroups(1);
        $this->assertCount(1, $groups);
        $this->assertEquals(1, $groups[0]['id']);
        $this->assertEquals('Test', $groups[0]['name']);
        $this->assertEquals(Role::PROJECT_VIEWER, $groups[0]['role']);
    }

    public function testRemoveGroup()
    {
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $groupModel->create('Test'));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_MEMBER));
        $this->assertTrue($groupRoleModel->removeGroup(1, 1));
        $this->assertFalse($groupRoleModel->removeGroup(1, 1));

        $this->assertEmpty($groupRoleModel->getGroups(1));
    }

    public function testChangeRole()
    {
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $groupModel->create('Test'));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));
        $this->assertTrue($groupRoleModel->changeGroupRole(1, 1, Role::PROJECT_MANAGER));

        $groups = $groupRoleModel->getGroups(1);
        $this->assertCount(1, $groups);
        $this->assertEquals(1, $groups[0]['id']);
        $this->assertEquals('Test', $groups[0]['name']);
        $this->assertEquals(Role::PROJECT_MANAGER, $groups[0]['role']);
    }

    public function testGetGroups()
    {
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $groupModel->create('Group C'));
        $this->assertEquals(2, $groupModel->create('Group B'));
        $this->assertEquals(3, $groupModel->create('Group A'));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));
        $this->assertTrue($groupRoleModel->addGroup(1, 2, Role::PROJECT_MEMBER));
        $this->assertTrue($groupRoleModel->addGroup(1, 3, Role::PROJECT_MANAGER));

        $groups = $groupRoleModel->getGroups(1);
        $this->assertCount(3, $groups);

        $this->assertEquals(3, $groups[0]['id']);
        $this->assertEquals('Group A', $groups[0]['name']);
        $this->assertEquals(Role::PROJECT_MANAGER, $groups[0]['role']);

        $this->assertEquals(2, $groups[1]['id']);
        $this->assertEquals('Group B', $groups[1]['name']);
        $this->assertEquals(Role::PROJECT_MEMBER, $groups[1]['role']);

        $this->assertEquals(1, $groups[2]['id']);
        $this->assertEquals('Group C', $groups[2]['name']);
        $this->assertEquals(Role::PROJECT_VIEWER, $groups[2]['role']);
    }

    public function testGetUsers()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupMemberModel = new GroupMemberModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project 1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'Project 2']));

        $this->assertEquals(2, $userModel->create(['username' => 'user 1', 'email' => 'user1@here', 'name' => 'User #1']));
        $this->assertEquals(3, $userModel->create(['username' => 'user 2', 'email' => 'user2@here']));
        $this->assertEquals(4, $userModel->create(['username' => 'user 3', 'email' => 'user3@here']));

        $this->assertEquals(1, $groupModel->create('Group A'));
        $this->assertEquals(2, $groupModel->create('Group B'));
        $this->assertEquals(3, $groupModel->create('Group C'));

        $this->assertTrue($groupMemberModel->addUser(1, 4));
        $this->assertTrue($groupMemberModel->addUser(2, 3));
        $this->assertTrue($groupMemberModel->addUser(3, 2));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));
        $this->assertTrue($groupRoleModel->addGroup(1, 2, Role::PROJECT_MEMBER));
        $this->assertTrue($groupRoleModel->addGroup(1, 3, Role::PROJECT_MANAGER));

        $users = $groupRoleModel->getUsers(2);
        $this->assertCount(0, $users);

        $users = $groupRoleModel->getUsers(1);
        $this->assertCount(3, $users);

        $this->assertEquals(2, $users[0]['id']);
        $this->assertEquals('user 1', $users[0]['username']);
        $this->assertEquals('User #1', $users[0]['name']);
        $this->assertEquals(Role::PROJECT_MANAGER, $users[0]['role']);

        $this->assertEquals(3, $users[1]['id']);
        $this->assertEquals('user 2', $users[1]['username']);
        $this->assertEquals('', $users[1]['name']);
        $this->assertEquals(Role::PROJECT_MEMBER, $users[1]['role']);

        $this->assertEquals(4, $users[2]['id']);
        $this->assertEquals('user 3', $users[2]['username']);
        $this->assertEquals('', $users[2]['name']);
        $this->assertEquals(Role::PROJECT_VIEWER, $users[2]['role']);
    }

    public function testGetAssignableUsers()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupMemberModel = new GroupMemberModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project 1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'Project 2']));

        $this->assertEquals(2, $userModel->create(['username' => 'user 1', 'email' => 'user1@here', 'name' => 'User #1']));
        $this->assertEquals(3, $userModel->create(['username' => 'user 2', 'email' => 'user2@here']));
        $this->assertEquals(4, $userModel->create(['username' => 'user 3', 'email' => 'user3@here']));

        $this->assertEquals(1, $groupModel->create('Group A'));
        $this->assertEquals(2, $groupModel->create('Group B'));
        $this->assertEquals(3, $groupModel->create('Group C'));

        $this->assertTrue($groupMemberModel->addUser(1, 4));
        $this->assertTrue($groupMemberModel->addUser(2, 3));
        $this->assertTrue($groupMemberModel->addUser(3, 2));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));
        $this->assertTrue($groupRoleModel->addGroup(1, 2, Role::PROJECT_MEMBER));
        $this->assertTrue($groupRoleModel->addGroup(1, 3, Role::PROJECT_MANAGER));

        $users = $groupRoleModel->getAssignableUsers(2);
        $this->assertCount(0, $users);

        $users = $groupRoleModel->getAssignableUsers(1);
        $this->assertCount(2, $users);

        $this->assertEquals(2, $users[0]['id']);
        $this->assertEquals('user 1', $users[0]['username']);
        $this->assertEquals('User #1', $users[0]['name']);

        $this->assertEquals(3, $users[1]['id']);
        $this->assertEquals('user 2', $users[1]['username']);
        $this->assertEquals('', $users[1]['name']);
    }

    public function testGetAssignableUsersWithDisabledUsers()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupMemberModel = new GroupMemberModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project 1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'Project 2']));

        $this->assertEquals(2, $userModel->create(['username' => 'user 1', 'email' => 'user1@here', 'name' => 'User #1']));
        $this->assertEquals(3, $userModel->create(['username' => 'user 2', 'email' => 'user2@here', 'is_active' => 0]));
        $this->assertEquals(4, $userModel->create(['username' => 'user 3', 'email' => 'user3@here']));

        $this->assertEquals(1, $groupModel->create('Group A'));
        $this->assertEquals(2, $groupModel->create('Group B'));
        $this->assertEquals(3, $groupModel->create('Group C'));

        $this->assertTrue($groupMemberModel->addUser(1, 4));
        $this->assertTrue($groupMemberModel->addUser(2, 3));
        $this->assertTrue($groupMemberModel->addUser(3, 2));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));
        $this->assertTrue($groupRoleModel->addGroup(1, 2, Role::PROJECT_MEMBER));
        $this->assertTrue($groupRoleModel->addGroup(1, 3, Role::PROJECT_MANAGER));

        $users = $groupRoleModel->getAssignableUsers(2);
        $this->assertCount(0, $users);

        $users = $groupRoleModel->getAssignableUsers(1);
        $this->assertCount(1, $users);

        $this->assertEquals(2, $users[0]['id']);
        $this->assertEquals('user 1', $users[0]['username']);
        $this->assertEquals('User #1', $users[0]['name']);
    }

    public function testGetProjectsByUser()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupMemberModel = new GroupMemberModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project 1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'Project 2']));

        $this->assertEquals(2, $userModel->create(['username' => 'user 1', 'email' => 'user1@here', 'name' => 'User #1']));
        $this->assertEquals(3, $userModel->create(['username' => 'user 2', 'email' => 'user2@here']));
        $this->assertEquals(4, $userModel->create(['username' => 'user 3', 'email' => 'user3@here']));
        $this->assertEquals(5, $userModel->create(['username' => 'user 4', 'email' => 'user4@here']));

        $this->assertEquals(1, $groupModel->create('Group C'));
        $this->assertEquals(2, $groupModel->create('Group B'));
        $this->assertEquals(3, $groupModel->create('Group A'));

        $this->assertTrue($groupMemberModel->addUser(1, 4));
        $this->assertTrue($groupMemberModel->addUser(2, 5));
        $this->assertTrue($groupMemberModel->addUser(3, 3));
        $this->assertTrue($groupMemberModel->addUser(3, 2));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));
        $this->assertTrue($groupRoleModel->addGroup(2, 2, Role::PROJECT_MEMBER));
        $this->assertTrue($groupRoleModel->addGroup(1, 3, Role::PROJECT_MANAGER));

        $projects = $groupRoleModel->getProjectsByUser(2);
        $this->assertCount(1, $projects);
        $this->assertEquals('Project 1', $projects[1]);

        $projects = $groupRoleModel->getProjectsByUser(3);
        $this->assertCount(1, $projects);
        $this->assertEquals('Project 1', $projects[1]);

        $projects = $groupRoleModel->getProjectsByUser(4);
        $this->assertCount(1, $projects);
        $this->assertEquals('Project 1', $projects[1]);

        $projects = $groupRoleModel->getProjectsByUser(5);
        $this->assertCount(1, $projects);
        $this->assertEquals('Project 2', $projects[2]);
    }

    public function testGetActiveProjectsByUser()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupMemberModel = new GroupMemberModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project 1', 'is_active' => 0]));
        $this->assertEquals(2, $projectModel->create(['name' => 'Project 2']));

        $this->assertEquals(2, $userModel->create(['username' => 'user 1', 'email' => 'user1@here', 'name' => 'User #1']));
        $this->assertEquals(3, $userModel->create(['username' => 'user 2', 'email' => 'user2@here']));
        $this->assertEquals(4, $userModel->create(['username' => 'user 3', 'email' => 'user3@here']));
        $this->assertEquals(5, $userModel->create(['username' => 'user 4', 'email' => 'user4@here']));

        $this->assertEquals(1, $groupModel->create('Group C'));
        $this->assertEquals(2, $groupModel->create('Group B'));
        $this->assertEquals(3, $groupModel->create('Group A'));

        $this->assertTrue($groupMemberModel->addUser(1, 4));
        $this->assertTrue($groupMemberModel->addUser(2, 5));
        $this->assertTrue($groupMemberModel->addUser(3, 3));
        $this->assertTrue($groupMemberModel->addUser(3, 2));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));
        $this->assertTrue($groupRoleModel->addGroup(2, 2, Role::PROJECT_MEMBER));
        $this->assertTrue($groupRoleModel->addGroup(1, 3, Role::PROJECT_MANAGER));

        $projects = $groupRoleModel->getProjectsByUser(2, [ProjectModel::ACTIVE]);
        $this->assertCount(0, $projects);

        $projects = $groupRoleModel->getProjectsByUser(3, [ProjectModel::ACTIVE]);
        $this->assertCount(0, $projects);

        $projects = $groupRoleModel->getProjectsByUser(4, [ProjectModel::ACTIVE]);
        $this->assertCount(0, $projects);

        $projects = $groupRoleModel->getProjectsByUser(5, [ProjectModel::ACTIVE]);
        $this->assertCount(1, $projects);
        $this->assertEquals('Project 2', $projects[2]);
    }

    public function testGetInactiveProjectsByUser()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupMemberModel = new GroupMemberModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project 1', 'is_active' => 0]));
        $this->assertEquals(2, $projectModel->create(['name' => 'Project 2']));

        $this->assertEquals(2, $userModel->create(['username' => 'user 1', 'email' => 'user1@here', 'name' => 'User #1']));
        $this->assertEquals(3, $userModel->create(['username' => 'user 2', 'email' => 'user2@here']));
        $this->assertEquals(4, $userModel->create(['username' => 'user 3', 'email' => 'user3@here']));
        $this->assertEquals(5, $userModel->create(['username' => 'user 4', 'email' => 'user4@here']));

        $this->assertEquals(1, $groupModel->create('Group C'));
        $this->assertEquals(2, $groupModel->create('Group B'));
        $this->assertEquals(3, $groupModel->create('Group A'));

        $this->assertTrue($groupMemberModel->addUser(1, 4));
        $this->assertTrue($groupMemberModel->addUser(2, 5));
        $this->assertTrue($groupMemberModel->addUser(3, 3));
        $this->assertTrue($groupMemberModel->addUser(3, 2));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_VIEWER));
        $this->assertTrue($groupRoleModel->addGroup(2, 2, Role::PROJECT_MEMBER));
        $this->assertTrue($groupRoleModel->addGroup(1, 3, Role::PROJECT_MANAGER));

        $projects = $groupRoleModel->getProjectsByUser(2, [ProjectModel::INACTIVE]);
        $this->assertCount(1, $projects);
        $this->assertEquals('Project 1', $projects[1]);

        $projects = $groupRoleModel->getProjectsByUser(3, [ProjectModel::INACTIVE]);
        $this->assertCount(1, $projects);
        $this->assertEquals('Project 1', $projects[1]);

        $projects = $groupRoleModel->getProjectsByUser(4, [ProjectModel::INACTIVE]);
        $this->assertCount(1, $projects);
        $this->assertEquals('Project 1', $projects[1]);

        $projects = $groupRoleModel->getProjectsByUser(5, [ProjectModel::INACTIVE]);
        $this->assertCount(0, $projects);
    }

    public function testUserInMultipleGroupsShouldReturnHighestRole()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $groupModel = new GroupModel($this->container);
        $groupRoleModel = new ProjectGroupRoleModel($this->container);
        $groupMemberModel = new GroupMemberModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(2, $userModel->create(['username' => 'My user', 'email' => 'myuser@here']));

        $this->assertEquals(1, $groupModel->create('Group A'));
        $this->assertEquals(2, $groupModel->create('Group B'));

        $this->assertTrue($groupMemberModel->addUser(1, 1));
        $this->assertTrue($groupMemberModel->addUser(2, 1));

        $this->assertTrue($groupRoleModel->addGroup(1, 1, Role::PROJECT_MEMBER));
        $this->assertTrue($groupRoleModel->addGroup(1, 2, Role::PROJECT_MANAGER));

        $this->assertEquals(Role::PROJECT_MANAGER, $groupRoleModel->getUserRole(1, 1));
    }
}
