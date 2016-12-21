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

use Hiject\Action\TaskAssignCurrentUserColumn;
use Hiject\Bus\Event\TaskEvent;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;

class TaskAssignCurrentUserColumnTest extends Base
{
    public function testChangeUser()
    {
        $this->container['sessionStorage']->user = ['id' => 1];

        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task'    => [
                'project_id' => 1,
                'column_id'  => 2,
            ],
        ]);

        $action = new TaskAssignCurrentUserColumn($this->container);
        $action->setProjectId(1);
        $action->setParam('column_id', 2);

        $this->assertTrue($action->execute($event, TaskModel::EVENT_MOVE_COLUMN));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(1, $task['owner_id']);
    }

    public function testWithWrongColumn()
    {
        $this->container['sessionStorage']->user = ['id' => 1];

        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task'    => [
                'project_id' => 1,
                'column_id'  => 3,
            ],
        ]);

        $action = new TaskAssignCurrentUserColumn($this->container);
        $action->setProjectId(1);
        $action->setParam('column_id', 2);

        $this->assertFalse($action->execute($event, TaskModel::EVENT_MOVE_COLUMN));
    }

    public function testWithNoUserSession()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task'    => [
                'project_id' => 1,
                'column_id'  => 2,
            ],
        ]);

        $action = new TaskAssignCurrentUserColumn($this->container);
        $action->setProjectId(1);
        $action->setParam('column_id', 2);

        $this->assertFalse($action->execute($event, TaskModel::EVENT_MOVE_COLUMN));
    }
}
