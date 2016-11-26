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
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\ProjectUserRoleModel;
use Hiject\Model\UserModel;
use Hiject\Action\TaskAssignUser;
use Hiject\Core\Security\Role;

class TaskAssignUserTest extends Base
{
    public function testChangeUser()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectUserRoleModel = new ProjectUserRoleModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test', 'owner_id' => 0]));
        $this->assertEquals(2, $userModel->create(['username' => 'user1']));
        $this->assertTrue($projectUserRoleModel->addUser(1, 2, Role::PROJECT_MEMBER));

        $event = new GenericEvent(['project_id' => 1, 'task_id' => 1, 'owner_id' => 2]);

        $action = new TaskAssignUser($this->container);
        $action->setProjectId(1);
        $action->addEvent('test.event', 'Test Event');

        $this->assertTrue($action->execute($event, 'test.event'));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(2, $task['owner_id']);
    }

    public function testWithNotAssignableUser()
    {
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));

        $event = new GenericEvent(['project_id' => 1, 'task_id' => 1, 'owner_id' => 1]);

        $action = new TaskAssignUser($this->container);
        $action->setProjectId(1);
        $action->addEvent('test.event', 'Test Event');

        $this->assertFalse($action->execute($event, 'test.event'));
    }
}
