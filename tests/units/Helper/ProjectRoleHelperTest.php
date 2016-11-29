<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Core\Security\Role;
use Hiject\Core\User\UserSession;
use Hiject\Helper\ProjectRoleHelper;
use Hiject\Model\ColumnMoveRestrictionModel;
use Hiject\Model\ColumnRestrictionModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\ProjectRoleModel;
use Hiject\Model\ProjectRoleRestrictionModel;
use Hiject\Model\ProjectUserRoleModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskStatusModel;
use Hiject\Model\UserModel;

require_once __DIR__.'/../Base.php';

class ProjectRoleHelperTest extends Base
{
    public function testCanCreateTaskInColumnWithProjectViewer()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_VIEWER));

        $this->assertFalse($projectRoleHelper->canCreateTaskInColumn(1, 1));
    }

    public function testCanCreateTaskInColumnWithProjectMember()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_MEMBER));

        $this->assertTrue($projectRoleHelper->canCreateTaskInColumn(1, 1));
    }

    public function testCanCreateTaskInColumnWithCustomProjectRole()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);
        $projectRoleModel = new ProjectRoleModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));

        $this->assertEquals(1, $projectRoleModel->create(1, 'Custom Role'));
        $this->assertTrue($projectUserRole->addUser(1, 2, 'Custom Role'));

        $this->assertTrue($projectRoleHelper->canCreateTaskInColumn(1, 1));
    }

    public function testCanCreateTaskInColumnWithCustomProjectRoleAndRestrictions()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);
        $projectRoleModel = new ProjectRoleModel($this->container);
        $projectRoleRestrictionModel = new ProjectRoleRestrictionModel($this->container);
        $columnRestrictionModel = new ColumnRestrictionModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));

        $this->assertEquals(1, $projectRoleModel->create(1, 'Custom Role'));
        $this->assertTrue($projectUserRole->addUser(1, 2, 'Custom Role'));

        $this->assertEquals(1, $projectRoleRestrictionModel->create(1, 1, ProjectRoleRestrictionModel::RULE_TASK_CREATION));
        $this->assertEquals(1, $columnRestrictionModel->create(1, 1, 1, ColumnRestrictionModel::RULE_ALLOW_TASK_CREATION));

        $this->assertTrue($projectRoleHelper->canCreateTaskInColumn(1, 1));
        $this->assertFalse($projectRoleHelper->canCreateTaskInColumn(1, 2));
    }

    public function testCanChangeTaskStatusInColumnWithProjectViewer()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_VIEWER));

        $this->assertFalse($projectRoleHelper->canChangeTaskStatusInColumn(1, 1));
    }

    public function testCanChangeTaskStatusInColumnWithProjectMember()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_MEMBER));

        $this->assertTrue($projectRoleHelper->canChangeTaskStatusInColumn(1, 1));
    }

    public function testCanChangeTaskStatusInColumnWithCustomProjectRole()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);
        $projectRoleModel = new ProjectRoleModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));

        $this->assertEquals(1, $projectRoleModel->create(1, 'Custom Role'));
        $this->assertTrue($projectUserRole->addUser(1, 2, 'Custom Role'));

        $this->assertTrue($projectRoleHelper->canChangeTaskStatusInColumn(1, 1));
    }

    public function testCanChangeTaskStatusInColumnWithCustomProjectRoleAndRestrictions()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);
        $projectRoleModel = new ProjectRoleModel($this->container);
        $projectRoleRestrictionModel = new ProjectRoleRestrictionModel($this->container);
        $columnRestrictionModel = new ColumnRestrictionModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));

        $this->assertEquals(1, $projectRoleModel->create(1, 'Custom Role'));
        $this->assertTrue($projectUserRole->addUser(1, 2, 'Custom Role'));

        $this->assertEquals(1, $projectRoleRestrictionModel->create(1, 1, ProjectRoleRestrictionModel::RULE_TASK_OPEN_CLOSE));
        $this->assertEquals(1, $columnRestrictionModel->create(1, 1, 1, ColumnRestrictionModel::RULE_ALLOW_TASK_OPEN_CLOSE));

        $this->assertTrue($projectRoleHelper->canChangeTaskStatusInColumn(1, 1));
        $this->assertFalse($projectRoleHelper->canChangeTaskStatusInColumn(1, 2));
    }

    public function testIsDraggableWithProjectMember()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_MEMBER));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));

        $task = $taskFinderModel->getById(1);
        $this->assertTrue($projectRoleHelper->isDraggable($task));
    }

    public function testIsDraggableWithClosedTask()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $taskStatusModel = new TaskStatusModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertTrue($projectUserRole->addUser(1, 2, Role::PROJECT_MEMBER));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertTrue($taskStatusModel->close(1));

        $task = $taskFinderModel->getById(1);
        $this->assertFalse($projectRoleHelper->isDraggable($task));
    }

    public function testIsDraggableWithColumnRestrictions()
    {
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectUserRole = new ProjectUserRoleModel($this->container);
        $userModel = new UserModel($this->container);
        $projectRoleModel = new ProjectRoleModel($this->container);
        $columnMoveRestrictionModel = new ColumnMoveRestrictionModel($this->container);

        $this->container['sessionStorage']->user = [
            'id' => 2,
            'role' => Role::APP_USER,
        ];

        $this->assertEquals(2, $userModel->create(['username' => 'user', 'email' => 'user@here']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));

        $this->assertEquals(1, $projectRoleModel->create(1, 'Custom Role'));
        $this->assertEquals(1, $columnMoveRestrictionModel->create(1, 1, 2, 3));

        $this->assertTrue($projectUserRole->addUser(1, 2, 'Custom Role'));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test', 'column_id' => 1]));
        $this->assertEquals(2, $taskCreationModel->create(['project_id' => 1, 'title' => 'test', 'column_id' => 2]));
        $this->assertEquals(3, $taskCreationModel->create(['project_id' => 1, 'title' => 'test', 'column_id' => 3]));
        $this->assertEquals(4, $taskCreationModel->create(['project_id' => 1, 'title' => 'test', 'column_id' => 4]));

        $task = $taskFinderModel->getById(1);
        $this->assertFalse($projectRoleHelper->isDraggable($task));

        $task = $taskFinderModel->getById(2);
        $this->assertTrue($projectRoleHelper->isDraggable($task));

        $task = $taskFinderModel->getById(3);
        $this->assertTrue($projectRoleHelper->isDraggable($task));

        $task = $taskFinderModel->getById(4);
        $this->assertFalse($projectRoleHelper->isDraggable($task));
    }

    public function testCanRemoveTask()
    {
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectRoleHelper = new ProjectRoleHelper($this->container);
        $projectModel = new ProjectModel($this->container);
        $userModel = new UserModel($this->container);
        $userSessionModel = new UserSession($this->container);

        $this->assertNotFalse($userModel->create(['username' => 'toto', 'email' =>'toto@here', 'password' => '123456']));
        $this->assertNotFalse($userModel->create(['username' => 'toto2', 'email' => 'toto2@here', 'password' => '123456']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'TaskViewController #1', 'project_id' => 1, 'creator_id' => 1]));
        $this->assertEquals(2, $taskCreationModel->create(['title' => 'TaskViewController #2', 'project_id' => 1, 'creator_id' => 2]));
        $this->assertEquals(3, $taskCreationModel->create(['title' => 'TaskViewController #3', 'project_id' => 1, 'creator_id' => 3]));
        $this->assertEquals(4, $taskCreationModel->create(['title' => 'TaskViewController #4', 'project_id' => 1]));

        // User #1 can remove everything
        $user = $userModel->getById(1);
        $this->assertNotEmpty($user);
        $userSessionModel->initialize($user);

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertTrue($projectRoleHelper->canRemoveTask($task));

        // User #2 can't remove the TaskViewController #1
        $user = $userModel->getById(2);
        $this->assertNotEmpty($user);
        $userSessionModel->initialize($user);

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertFalse($projectRoleHelper->canRemoveTask($task));

        // User #1 can remove everything
        $user = $userModel->getById(1);
        $this->assertNotEmpty($user);
        $userSessionModel->initialize($user);

        $task = $taskFinderModel->getById(2);
        $this->assertNotEmpty($task);
        $this->assertTrue($projectRoleHelper->canRemoveTask($task));

        // User #2 can remove his own TaskViewController
        $user = $userModel->getById(2);
        $this->assertNotEmpty($user);
        $userSessionModel->initialize($user);

        $task = $taskFinderModel->getById(2);
        $this->assertNotEmpty($task);
        $this->assertTrue($projectRoleHelper->canRemoveTask($task));

        // User #1 can remove everything
        $user = $userModel->getById(1);
        $this->assertNotEmpty($user);
        $userSessionModel->initialize($user);

        $task = $taskFinderModel->getById(3);
        $this->assertNotEmpty($task);
        $this->assertTrue($projectRoleHelper->canRemoveTask($task));

        // User #2 can't remove the TaskViewController #3
        $user = $userModel->getById(2);
        $this->assertNotEmpty($user);
        $userSessionModel->initialize($user);

        $task = $taskFinderModel->getById(3);
        $this->assertNotEmpty($task);
        $this->assertFalse($projectRoleHelper->canRemoveTask($task));

        // User #1 can remove everything
        $user = $userModel->getById(1);
        $this->assertNotEmpty($user);
        $userSessionModel->initialize($user);

        $task = $taskFinderModel->getById(4);
        $this->assertNotEmpty($task);
        $this->assertTrue($projectRoleHelper->canRemoveTask($task));

        // User #2 can't remove the TaskViewController #4
        $user = $userModel->getById(2);
        $this->assertNotEmpty($user);
        $userSessionModel->initialize($user);

        $task = $taskFinderModel->getById(4);
        $this->assertNotEmpty($task);
        $this->assertFalse($projectRoleHelper->canRemoveTask($task));
    }
}
