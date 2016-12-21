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

use Jitamin\Action\TaskAssignPrioritySwimlane;
use Jitamin\Bus\Event\TaskEvent;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskFinderModel;
use Jitamin\Model\TaskModel;

class TaskAssignPrioritySwimlaneTest extends Base
{
    public function testChangeSwimlane()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test', 'priority' => 1]));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(1, $task['priority']);

        $event = new TaskEvent([
            'task_id' => 1,
            'task'    => [
                'project_id'  => 1,
                'swimlane_id' => 2,
            ],
        ]);

        $action = new TaskAssignPrioritySwimlane($this->container);
        $action->setProjectId(1);
        $action->setParam('priority', 2);
        $action->setParam('swimlane_id', 2);

        $this->assertTrue($action->execute($event, TaskModel::EVENT_MOVE_SWIMLANE));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(2, $task['priority']);
    }
}
