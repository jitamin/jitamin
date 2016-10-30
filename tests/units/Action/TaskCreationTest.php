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
use Hiject\Model\TaskFinderModel;
use Hiject\Model\ProjectModel;
use Hiject\Action\TaskCreation as TaskCreationAction;

class TaskCreationActionTest extends Base
{
    public function testSuccess()
    {
        $projectModel = new ProjectModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(array('name' => 'test1')));

        $event = new GenericEvent(array('project_id' => 1, 'task_id' => 1, 'title' => 'test123', 'reference' => 'ref123', 'description' => 'test'));

        $action = new TaskCreationAction($this->container);
        $action->setProjectId(1);
        $action->addEvent('test.event', 'Test Event');

        $this->assertTrue($action->execute($event, 'test.event'));

        $task = $taskFinderModel->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals('test123', $task['title']);
        $this->assertEquals('ref123', $task['reference']);
        $this->assertEquals('test', $task['description']);
    }

    public function testWithNoTitle()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(array('name' => 'test1')));

        $event = new GenericEvent(array('project_id' => 1, 'task_id' => 1, 'reference' => 'ref123', 'description' => 'test'));

        $action = new TaskCreationAction($this->container);
        $action->setProjectId(1);
        $action->addEvent('test.event', 'Test Event');

        $this->assertFalse($action->execute($event, 'test.event'));
    }
}
