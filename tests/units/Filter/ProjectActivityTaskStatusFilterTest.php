<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Filter\ProjectActivityTaskStatusFilter;
use Jitamin\Model\ProjectActivityModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskFinderModel;
use Jitamin\Model\TaskModel;
use Jitamin\Model\TaskStatusModel;

require_once __DIR__.'/../Base.php';

class ProjectActivityTaskStatusFilterTest extends Base
{
    public function testFilterByTaskStatus()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskStatus = new TaskStatusModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));

        $this->assertEquals(1, $taskModel->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'Test', 'project_id' => 1]));

        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));
        $this->assertNotFalse($projectActivityModel->createEvent(1, 2, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(2)]));

        $this->assertTrue($taskStatus->close(1));

        $query = $projectActivityModel->getQuery();
        $filter = new ProjectActivityTaskStatusFilter('open');
        $filter->withQuery($query)->apply();

        $events = $query->findAll();
        $this->assertCount(1, $events);
        $this->assertEquals(2, $events[0]['task_id']);

        $query = $projectActivityModel->getQuery();
        $filter = new ProjectActivityTaskStatusFilter('closed');
        $filter->withQuery($query)->apply();

        $events = $query->findAll();
        $this->assertCount(1, $events);
        $this->assertEquals(1, $events[0]['task_id']);
    }
}
