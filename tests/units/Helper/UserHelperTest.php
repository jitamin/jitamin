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

use Jitamin\Core\Security\Role;
use Jitamin\Helper\UserHelper;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\ProjectRoleModel;
use Jitamin\Model\ProjectUserRoleModel;
use Jitamin\Model\UserModel;

class UserHelperTest extends Base
{
    public function testGetFullname()
    {
        $userModel = new UserModel($this->container);
        $userHelper = new UserHelper($this->container);

        $this->assertEquals(2, $userModel->create(['username' => 'user1', 'email' => 'user1@here']));
        $this->assertEquals(3, $userModel->create(['username' => 'user2', 'email' => 'user2@here', 'name' => 'User #2']));

        $user1 = $userModel->getById(2);
        $user2 = $userModel->getById(3);

        $this->assertEquals('user1', $userHelper->getFullname($user1));
        $this->assertEquals('User #2', $userHelper->getFullname($user2));
    }

    public function testInitials()
    {
        $helper = new UserHelper($this->container);

        $this->assertEquals('CN', $helper->getInitials('chuck norris'));
        $this->assertEquals('CN', $helper->getInitials('chuck norris #2'));
        $this->assertEquals('A', $helper->getInitials('admin'));
        $this->assertEquals('Ü君', $helper->getInitials('Ü 君が代'));
    }

    public function testGetRoleName()
    {
        $helper = new UserHelper($this->container);
        $this->assertEquals('Administrator', $helper->getRoleName(Role::APP_ADMIN));
        $this->assertEquals('Manager', $helper->getRoleName(Role::APP_MANAGER));
        $this->assertEquals('Project Viewer', $helper->getRoleName(Role::PROJECT_VIEWER));
    }

    public function testHasAccessWithoutSession()
    {
        $helper = new UserHelper($this->container);
        $this->assertFalse($helper->hasAccess('Admin/UserController', 'create'));
    }

    public function testHasAccessForAdmins()
    {
        $helper = new UserHelper($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_ADMIN,
        ];

        $this->assertTrue($helper->hasAccess('Admin/UserController', 'create'));
        $this->assertTrue($helper->hasAccess('Project/ProjectController', 'create'));
        $this->assertTrue($helper->hasAccess('Project/ProjectController', 'createPrivate'));
    }

    public function testHasAccessForManagers()
    {
        $helper = new UserHelper($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_MANAGER,
        ];

        $this->assertFalse($helper->hasAccess('Admin/UserController', 'show'));
        $this->assertTrue($helper->hasAccess('Project/ProjectController', 'create'));
        $this->assertTrue($helper->hasAccess('Project/ProjectController', 'createPrivate'));
    }

    public function testHasAccessForUsers()
    {
        $helper = new UserHelper($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertFalse($helper->hasAccess('Admin/UserController', 'create'));
        $this->assertFalse($helper->hasAccess('Project/ProjectController', 'create'));
        $this->assertTrue($helper->hasAccess('Project/ProjectController', 'createPrivate'));
    }

    public function testHasProjectAccessWithoutSession()
    {
        $helper = new UserHelper($this->container);
        $project = new ProjectModel($this->container);

        $this->assertEquals(1, $project->create(['name' => 'My project']));
        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 1));
    }

    public function testHasProjectAccessForAdmins()
    {
        $helper = new UserHelper($this->container);
        $project = new ProjectModel($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_ADMIN,
        ];

        $this->assertEquals(1, $project->create(['name' => 'My project']));

        $this->assertTrue($helper->hasProjectAccess('Project/ProjectController', 'edit', 1));
        $this->assertTrue($helper->hasProjectAccess('Project/Board/BoardController', 'show', 1));
    }

    public function testHasProjectAccessForManagers()
    {
        $helper = new UserHelper($this->container);
        $project = new ProjectModel($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_MANAGER,
        ];

        $this->assertEquals(1, $project->create(['name' => 'My project']));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 1));
        $this->assertFalse($helper->hasProjectAccess('Project/Board/BoardController', 'show', 1));
    }

    public function testHasProjectAccessForUsers()
    {
        $helper = new UserHelper($this->container);
        $project = new ProjectModel($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(1, $project->create(['name' => 'My project']));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 1));
        $this->assertFalse($helper->hasProjectAccess('Project/Board/BoardController', 'show', 1));
    }

    public function testHasProjectAccessForAppManagerAndProjectManagers()
    {
        $helper = new UserHelper($this->container);
        $user = new UserModel($this->container);
        $project = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_MANAGER,
        ];

        $this->assertEquals(1, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $user->create(['username' => 'user', 'email' => 'user@user']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_MANAGER));

        $this->assertTrue($helper->hasProjectAccess('Project/ProjectController', 'edit', 1));
        $this->assertTrue($helper->hasProjectAccess('Project/Board/BoardController', 'show', 1));
        $this->assertTrue($helper->hasProjectAccess('Task/TaskController', 'show', 1));
        $this->assertTrue($helper->hasProjectAccess('Task/TaskController', 'store', 1));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 2));
        $this->assertFalse($helper->hasProjectAccess('Project/Board/BoardController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'create', 2));
    }

    public function testHasProjectAccessForProjectManagers()
    {
        $helper = new UserHelper($this->container);
        $user = new UserModel($this->container);
        $project = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(1, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $user->create(['username' => 'user', 'email' => 'user@user']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_MANAGER));

        $this->assertTrue($helper->hasProjectAccess('Project/ProjectController', 'edit', 1));
        $this->assertTrue($helper->hasProjectAccess('Project/Board/BoardController', 'show', 1));
        $this->assertTrue($helper->hasProjectAccess('Task/TaskController', 'show', 1));
        $this->assertTrue($helper->hasProjectAccess('Task/TaskController', 'create', 1));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 2));
        $this->assertFalse($helper->hasProjectAccess('Project/Board/BoardController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'create', 2));
    }

    public function testHasProjectAccessForProjectMembers()
    {
        $helper = new UserHelper($this->container);
        $user = new UserModel($this->container);
        $project = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(1, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $user->create(['username' => 'user', 'email' => 'user@user']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_MEMBER));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 1));
        $this->assertTrue($helper->hasProjectAccess('Project/Board/BoardController', 'show', 1));
        $this->assertTrue($helper->hasProjectAccess('Task/TaskController', 'show', 1));
        $this->assertTrue($helper->hasProjectAccess('Task/TaskController', 'create', 1));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 2));
        $this->assertFalse($helper->hasProjectAccess('Project/Board/BoardController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'create', 2));
    }

    public function testHasProjectAccessForProjectViewers()
    {
        $helper = new UserHelper($this->container);
        $user = new UserModel($this->container);
        $project = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(1, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $user->create(['username' => 'user', 'email' => 'user@user']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_VIEWER));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 1));
        $this->assertTrue($helper->hasProjectAccess('Project/Board/BoardController', 'show', 1));
        $this->assertTrue($helper->hasProjectAccess('Task/TaskController', 'show', 1));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'create', 1));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 2));
        $this->assertFalse($helper->hasProjectAccess('Project/Board/BoardController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'create', 2));
    }

    public function testHasProjectAccessForCustomProjectRole()
    {
        $helper = new UserHelper($this->container);
        $user = new UserModel($this->container);
        $project = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $projectRole = new ProjectRoleModel($this->container);

        $this->container['sessionStorage']->user = [
            'id'   => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(1, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $project->create(['name' => 'My project']));
        $this->assertEquals(2, $user->create(['username' => 'user', 'email' => 'user@user']));
        $this->assertEquals(1, $projectRole->create(1, 'Custom Role'));

        $this->assertTrue($projectUserRole->addUser(1, 2, 'Custom Role'));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 1));
        $this->assertTrue($helper->hasProjectAccess('Project/Board/BoardController', 'show', 1));
        $this->assertTrue($helper->hasProjectAccess('Task/TaskController', 'show', 1));
        $this->assertTrue($helper->hasProjectAccess('Task/TaskController', 'create', 1));

        $this->assertFalse($helper->hasProjectAccess('Project/ProjectController', 'edit', 2));
        $this->assertFalse($helper->hasProjectAccess('Project/Board/BoardController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'show', 2));
        $this->assertFalse($helper->hasProjectAccess('Task/TaskController', 'create', 2));
    }
}
