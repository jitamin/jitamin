<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Filter\TaskPriorityFilter;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class TaskPriorityFilterTest extends Base
{
    public function testWithDefinedPriority()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1, 'priority' => 2]));

        $filter = new TaskPriorityFilter();
        $filter->withQuery($query);
        $filter->withValue(2);
        $filter->apply();

        $this->assertCount(1, $query->findAll());
    }

    public function testWithNoPriority()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));

        $filter = new TaskPriorityFilter();
        $filter->withQuery($query);
        $filter->withValue(2);
        $filter->apply();

        $this->assertCount(0, $query->findAll());
    }
}
