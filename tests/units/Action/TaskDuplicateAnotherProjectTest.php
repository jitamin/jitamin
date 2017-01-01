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

use Jitamin\Action\TaskDuplicateAnotherProject;
use Jitamin\Bus\Event\TaskEvent;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskFinderModel;
use Jitamin\Model\TaskModel;

class TaskDuplicateAnotherProjectTest extends Base
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
                'project_id' => 1,
                'column_id'  => 2,
            ],
        ]);

        $action = new TaskDuplicateAnotherProject($this->container);
        $action->setProjectId(1);
        $action->setParam('project_id', 2);
        $action->setParam('column_id', 2);

        $this->assertTrue($action->execute($event, TaskModel::EVENT_CLOSE));

        $task = $taskFinderModel->getById(2);
        $this->assertNotEmpty($task);
        $this->assertEquals('test', $task['title']);
        $this->assertEquals(2, $task['project_id']);
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
                'column_id'  => 3,
            ],
        ]);

        $action = new TaskDuplicateAnotherProject($this->container);
        $action->setProjectId(1);
        $action->setParam('project_id', 2);
        $action->setParam('column_id', 2);

        $this->assertFalse($action->execute($event, TaskModel::EVENT_CLOSE));
    }
}
