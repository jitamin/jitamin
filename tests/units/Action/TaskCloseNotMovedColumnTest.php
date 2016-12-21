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

use Hiject\Action\TaskCloseNotMovedColumn;
use Hiject\Bus\Event\TaskListEvent;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;

class TaskCloseNotMovedColumnTest extends Base
{
    public function testClose()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(2, $taskModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(3, $taskModel->create(['project_id' => 1, 'title' => 'test', 'column_id' => 2]));

        $this->container['db']->table(TaskModel::TABLE)->in('id', [1, 3])->update(['date_moved' => strtotime('-10days')]);

        $tasks = $taskFinderModel->getAll(1);
        $event = new TaskListEvent(['tasks' => $tasks, 'project_id' => 1]);

        $action = new TaskCloseNotMovedColumn($this->container);
        $action->setProjectId(1);
        $action->setParam('duration', 2);
        $action->setParam('column_id', 2);

        $this->assertTrue($action->execute($event, TaskModel::EVENT_DAILY_CRONJOB));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(1, $task['is_active']);

        $task = $taskFinderModel->getById(2);
        $this->assertNotEmpty($task);
        $this->assertEquals(1, $task['is_active']);

        $task = $taskFinderModel->getById(3);
        $this->assertNotEmpty($task);
        $this->assertEquals(0, $task['is_active']);
    }
}
