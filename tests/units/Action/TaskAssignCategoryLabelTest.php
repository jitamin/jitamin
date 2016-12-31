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

use Jitamin\Action\TaskAssignCategoryLabel;
use Jitamin\Bus\Event\GenericEvent;
use Jitamin\Model\CategoryModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskFinderModel;
use Jitamin\Model\TaskModel;

class TaskAssignCategoryLabelTest extends Base
{
    public function testChangeCategory()
    {
        $categoryModel = new CategoryModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'c1', 'project_id' => 1]));

        $event = new GenericEvent(['project_id' => 1, 'task_id' => 1, 'label' => 'foobar']);

        $action = new TaskAssignCategoryLabel($this->container);
        $action->setProjectId(1);
        $action->addEvent('test.event', 'Test Event');
        $action->setParam('label', 'foobar');
        $action->setParam('category_id', 1);

        $this->assertTrue($action->execute($event, 'test.event'));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(1, $task['category_id']);
    }

    public function testWithWrongLabel()
    {
        $categoryModel = new CategoryModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'c1', 'project_id' => 1]));

        $event = new GenericEvent(['project_id' => 1, 'task_id' => 1, 'label' => 'something']);

        $action = new TaskAssignCategoryLabel($this->container);
        $action->setProjectId(1);
        $action->addEvent('test.event', 'Test Event');
        $action->setParam('label', 'foobar');
        $action->setParam('category_id', 1);

        $this->assertFalse($action->execute($event, 'test.event'));
    }

    public function testWithExistingCategory()
    {
        $categoryModel = new CategoryModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $categoryModel->create(['name' => 'c1', 'project_id' => 1]));
        $this->assertEquals(2, $categoryModel->create(['name' => 'c2', 'project_id' => 1]));

        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test', 'category_id' => 2]));

        $event = new GenericEvent(['project_id' => 1, 'task_id' => 1, 'label' => 'foobar', 'category_id' => 2]);

        $action = new TaskAssignCategoryLabel($this->container);
        $action->setProjectId(1);
        $action->addEvent('test.event', 'Test Event');
        $action->setParam('label', 'foobar');
        $action->setParam('category_id', 1);

        $this->assertFalse($action->execute($event, 'test.event'));
    }
}
