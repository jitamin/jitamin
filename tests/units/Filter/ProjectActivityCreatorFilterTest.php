<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Filter\ProjectActivityCreatorFilter;
use Jitamin\Model\ProjectActivityModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskFinderModel;
use Jitamin\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class ProjectActivityCreatorFilterTest extends Base
{
    public function testWithUsername()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));

        $query = $projectActivityModel->getQuery();
        $filter = new ProjectActivityCreatorFilter('admin');
        $filter->withQuery($query)->apply();

        $events = $query->findAll();
        $this->assertCount(1, $events);
    }

    public function testWithAnotherUsername()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));

        $query = $projectActivityModel->getQuery();
        $filter = new ProjectActivityCreatorFilter('John Doe');
        $filter->withQuery($query)->apply();

        $events = $query->findAll();
        $this->assertCount(0, $events);
    }

    public function testWithCurrentUser()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));

        $query = $projectActivityModel->getQuery();
        $filter = new ProjectActivityCreatorFilter('me');
        $filter->setCurrentUserId(1);
        $filter->withQuery($query)->apply();

        $events = $query->findAll();
        $this->assertCount(1, $events);
    }

    public function testWithAnotherCurrentUser()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));

        $query = $projectActivityModel->getQuery();
        $filter = new ProjectActivityCreatorFilter('me');
        $filter->setCurrentUserId(2);
        $filter->withQuery($query)->apply();

        $events = $query->findAll();
        $this->assertCount(0, $events);
    }
}
