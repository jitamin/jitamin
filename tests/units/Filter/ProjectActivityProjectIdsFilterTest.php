<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Filter\ProjectActivityProjectIdsFilter;
use Jitamin\Model\ProjectActivityModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskFinderModel;
use Jitamin\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class ProjectActivityProjectIdsFilterTest extends Base
{
    public function testFilterByProjectIds()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);
        $query = $projectActivityModel->getQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'P2']));
        $this->assertEquals(3, $projectModel->create(['name' => 'P3']));

        $this->assertEquals(1, $taskModel->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'Test', 'project_id' => 2]));
        $this->assertEquals(3, $taskModel->create(['title' => 'Test', 'project_id' => 3]));

        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));
        $this->assertNotFalse($projectActivityModel->createEvent(2, 2, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(2)]));
        $this->assertNotFalse($projectActivityModel->createEvent(3, 3, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(3)]));

        $filter = new ProjectActivityProjectIdsFilter([1, 2]);
        $filter->withQuery($query)->apply();
        $this->assertCount(2, $query->findAll());
    }

    public function testWithEmptyArgument()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);
        $query = $projectActivityModel->getQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'P2']));
        $this->assertEquals(3, $projectModel->create(['name' => 'P3']));

        $this->assertEquals(1, $taskModel->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'Test', 'project_id' => 2]));
        $this->assertEquals(3, $taskModel->create(['title' => 'Test', 'project_id' => 3]));

        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, $taskFinder->getById(1)));
        $this->assertNotFalse($projectActivityModel->createEvent(2, 2, 1, TaskModel::EVENT_CREATE, $taskFinder->getById(2)));
        $this->assertNotFalse($projectActivityModel->createEvent(3, 3, 1, TaskModel::EVENT_CREATE, $taskFinder->getById(3)));

        $filter = new ProjectActivityProjectIdsFilter([]);
        $filter->withQuery($query)->apply();
        $this->assertCount(0, $query->findAll());
    }
}
