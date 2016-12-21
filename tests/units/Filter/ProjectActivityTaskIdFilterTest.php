<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Filter\ProjectActivityTaskIdFilter;
use Hiject\Model\ProjectActivityModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class ProjectActivityTaskIdFilterTest extends Base
{
    public function testFilterByTaskId()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);
        $query = $projectActivityModel->getQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));

        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertEquals(2, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));

        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));
        $this->assertNotFalse($projectActivityModel->createEvent(1, 2, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(2)]));

        $filter = new ProjectActivityTaskIdFilter(1);
        $filter->withQuery($query)->apply();
        $this->assertCount(1, $query->findAll());
    }
}
