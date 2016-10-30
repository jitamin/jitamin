<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Hiject\Bus\Event\GenericEvent;
use Hiject\Model\CategoryModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\ProjectModel;
use Hiject\Action\TaskAssignCategoryLabel;

class TaskAssignCategoryLabelTest extends Base
{
    public function testChangeCategory()
    {
        $categoryModel = new CategoryModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(array('name' => 'test1')));
        $this->assertEquals(1, $taskCreationModel->create(array('project_id' => 1, 'title' => 'test')));
        $this->assertEquals(1, $categoryModel->create(array('name' => 'c1', 'project_id' => 1)));

        $event = new GenericEvent(array('project_id' => 1, 'task_id' => 1, 'label' => 'foobar'));

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
        $taskCreationModel = new TaskCreationModel($this->container);

        $this->assertEquals(1, $projectModel->create(array('name' => 'test1')));
        $this->assertEquals(1, $taskCreationModel->create(array('project_id' => 1, 'title' => 'test')));
        $this->assertEquals(1, $categoryModel->create(array('name' => 'c1', 'project_id' => 1)));

        $event = new GenericEvent(array('project_id' => 1, 'task_id' => 1, 'label' => 'something'));

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
        $taskCreationModel = new TaskCreationModel($this->container);

        $this->assertEquals(1, $projectModel->create(array('name' => 'test1')));
        $this->assertEquals(1, $categoryModel->create(array('name' => 'c1', 'project_id' => 1)));
        $this->assertEquals(2, $categoryModel->create(array('name' => 'c2', 'project_id' => 1)));

        $this->assertEquals(1, $taskCreationModel->create(array('project_id' => 1, 'title' => 'test', 'category_id' => 2)));

        $event = new GenericEvent(array('project_id' => 1, 'task_id' => 1, 'label' => 'foobar', 'category_id' => 2));

        $action = new TaskAssignCategoryLabel($this->container);
        $action->setProjectId(1);
        $action->addEvent('test.event', 'Test Event');
        $action->setParam('label', 'foobar');
        $action->setParam('category_id', 1);

        $this->assertFalse($action->execute($event, 'test.event'));
    }
}
