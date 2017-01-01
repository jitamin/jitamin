<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskModel;
use Jitamin\Pagination\TaskPagination;

require_once __DIR__.'/../Base.php';

class TaskPaginationTest extends Base
{
    public function testDashboardPagination()
    {
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskPagination = new TaskPagination($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'Task #1', 'project_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'Task #2', 'project_id' => 1, 'column_id' => 2, 'owner_id' => 1]));

        $this->assertCount(1, $taskPagination->getDashboardPaginator(1, 'tasks', 5)->getCollection());
        $this->assertCount(0, $taskPagination->getDashboardPaginator(2, 'tasks', 5)->getCollection());
        $this->assertCount(1, $taskPagination->getDashboardPaginator(1, 'tasks', 5)->setOrder(TaskModel::TABLE.'.id')->getCollection());
        $this->assertCount(1, $taskPagination->getDashboardPaginator(1, 'tasks', 5)->setOrder('project_name')->getCollection());
        $this->assertCount(1, $taskPagination->getDashboardPaginator(1, 'tasks', 5)->setOrder(TaskModel::TABLE.'.title')->getCollection());
        $this->assertCount(1, $taskPagination->getDashboardPaginator(1, 'tasks', 5)->setOrder(TaskModel::TABLE.'.priority')->getCollection());
        $this->assertCount(1, $taskPagination->getDashboardPaginator(1, 'tasks', 5)->setOrder(TaskModel::TABLE.'.date_due')->getCollection());
    }
}
