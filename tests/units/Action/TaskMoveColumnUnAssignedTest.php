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

use Hiject\Action\TaskMoveColumnUnAssigned;
use Hiject\Bus\Event\TaskEvent;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;

class TaskMoveColumnUnAssignedTest extends Base
{
    public function testSuccess()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'test2']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task'    => [
                'project_id'  => 1,
                'column_id'   => 1,
                'owner_id'    => 0,
                'position'    => 1,
                'swimlane_id' => 0,
            ],
        ]);

        $action = new TaskMoveColumnUnAssigned($this->container);
        $action->setProjectId(1);
        $action->setParam('src_column_id', 1);
        $action->setParam('dest_column_id', 2);

        $this->assertTrue($action->execute($event, TaskModel::EVENT_ASSIGNEE_CHANGE));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals('test', $task['title']);
        $this->assertEquals(2, $task['column_id']);
    }

    public function testWithWrongColumn()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'test2']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task'    => [
                'project_id' => 1,
                'column_id'  => 2,
                'owner_id'   => 0,
            ],
        ]);

        $action = new TaskMoveColumnUnAssigned($this->container);
        $action->setProjectId(1);
        $action->setParam('src_column_id', 1);
        $action->setParam('dest_column_id', 2);

        $this->assertFalse($action->execute($event, TaskModel::EVENT_ASSIGNEE_CHANGE));
    }

    public function testWithWrongUser()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'test2']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task'    => [
                'project_id' => 1,
                'column_id'  => 1,
                'owner_id'   => 1,
            ],
        ]);

        $action = new TaskMoveColumnUnAssigned($this->container);
        $action->setProjectId(1);
        $action->setParam('src_column_id', 1);
        $action->setParam('dest_column_id', 2);

        $this->assertFalse($action->execute($event, TaskModel::EVENT_ASSIGNEE_CHANGE));
    }
}
