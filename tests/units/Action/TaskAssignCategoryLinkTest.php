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

use Jitamin\Action\TaskAssignCategoryLink;
use Jitamin\Bus\EventBuilder\TaskLinkEventBuilder;
use Jitamin\Model\CategoryModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskFinderModel;
use Jitamin\Model\TaskLinkModel;
use Jitamin\Model\TaskModel;

class TaskAssignCategoryLinkTest extends Base
{
    public function testAssignCategory()
    {
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);
        $taskLinkModel = new TaskLinkModel($this->container);

        $action = new TaskAssignCategoryLink($this->container);
        $action->setProjectId(1);
        $action->setParam('category_id', 1);
        $action->setParam('link_id', 2);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'C1', 'project_id' => 1]));
        $this->assertEquals(1, $taskModel->create(['title' => 'T1', 'project_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'T2', 'project_id' => 1]));
        $this->assertEquals(1, $taskLinkModel->create(1, 2, 2));

        $event = TaskLinkEventBuilder::getInstance($this->container)
            ->withTaskLinkId(1)
            ->buildEvent();

        $this->assertTrue($action->execute($event, TaskLinkModel::EVENT_CREATE_UPDATE));

        $task = $taskFinderModel->getById(1);
        $this->assertEquals(1, $task['category_id']);
    }

    public function testWhenLinkDontMatch()
    {
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);
        $taskLinkModel = new TaskLinkModel($this->container);

        $action = new TaskAssignCategoryLink($this->container);
        $action->setProjectId(1);
        $action->setParam('category_id', 1);
        $action->setParam('link_id', 2);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'C1', 'project_id' => 1]));
        $this->assertEquals(1, $taskModel->create(['title' => 'T1', 'project_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'T2', 'project_id' => 1]));
        $this->assertEquals(1, $taskLinkModel->create(1, 2, 1));

        $event = TaskLinkEventBuilder::getInstance($this->container)
            ->withTaskLinkId(1)
            ->buildEvent();

        $this->assertFalse($action->execute($event, TaskLinkModel::EVENT_CREATE_UPDATE));

        $task = $taskFinderModel->getById(1);
        $this->assertEquals(0, $task['category_id']);
    }

    public function testThatExistingCategoryWillNotChange()
    {
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $categoryModel = new CategoryModel($this->container);
        $taskLinkModel = new TaskLinkModel($this->container);

        $action = new TaskAssignCategoryLink($this->container);
        $action->setProjectId(1);
        $action->setParam('category_id', 1);
        $action->setParam('link_id', 2);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'C1', 'project_id' => 1]));
        $this->assertEquals(1, $taskModel->create(['title' => 'T1', 'project_id' => 1, 'category_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'T2', 'project_id' => 1]));
        $this->assertEquals(1, $taskLinkModel->create(1, 2, 2));

        $event = TaskLinkEventBuilder::getInstance($this->container)
            ->withTaskLinkId(1)
            ->buildEvent();

        $this->assertFalse($action->execute($event, TaskLinkModel::EVENT_CREATE_UPDATE));

        $task = $taskFinderModel->getById(1);
        $this->assertEquals(1, $task['category_id']);
    }
}
