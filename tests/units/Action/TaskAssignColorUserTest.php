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

use Hiject\Bus\Event\TaskEvent;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Action\TaskAssignColorUser;

class TaskAssignColorUserTest extends Base
{
    public function testChangeColor()
    {
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task' => [
                'project_id' => 1,
                'owner_id' => 1,
            ]
        ]);

        $action = new TaskAssignColorUser($this->container);
        $action->setProjectId(1);
        $action->setParam('color_id', 'red');
        $action->setParam('user_id', 1);

        $this->assertTrue($action->execute($event, TaskModel::EVENT_ASSIGNEE_CHANGE));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals('red', $task['color_id']);
    }

    public function testWithWrongUser()
    {
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task' => [
                'project_id' => 1,
                'owner_id' => 2,
            ]
        ]);

        $action = new TaskAssignColorUser($this->container);
        $action->setProjectId(1);
        $action->setParam('color_id', 'red');
        $action->setParam('user_id', 1);

        $this->assertFalse($action->execute($event, TaskModel::EVENT_ASSIGNEE_CHANGE));
    }
}
