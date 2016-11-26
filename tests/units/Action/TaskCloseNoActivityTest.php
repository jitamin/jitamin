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

use Hiject\Bus\Event\TaskListEvent;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Action\TaskCloseNoActivity;

class TaskCloseNoActivityTest extends Base
{
    public function testClose()
    {
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(2, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));

        $this->container['db']->table(TaskModel::TABLE)->eq('id', 1)->update(['date_modification' => strtotime('-10days')]);

        $tasks = $taskFinderModel->getAll(1);
        $event = new TaskListEvent(['tasks' => $tasks, 'project_id' => 1]);

        $action = new TaskCloseNoActivity($this->container);
        $action->setProjectId(1);
        $action->setParam('duration', 2);

        $this->assertTrue($action->execute($event, TaskModel::EVENT_DAILY_CRONJOB));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(0, $task['is_active']);

        $task = $taskFinderModel->getById(2);
        $this->assertNotEmpty($task);
        $this->assertEquals(1, $task['is_active']);
    }
}
