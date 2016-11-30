<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Helper\ProjectActivityHelper;
use Hiject\Model\ProjectActivityModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class ProjectActivityHelperTest extends Base
{
    public function testGetProjectEvents()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));

        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertEquals(2, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertEquals(3, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));

        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));
        $this->assertNotFalse($projectActivityModel->createEvent(1, 2, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(2)]));
        $this->assertNotFalse($projectActivityModel->createEvent(1, 3, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(3)]));

        $helper = new ProjectActivityHelper($this->container);
        $events = $helper->getProjectEvents(1);

        $this->assertCount(3, $events);
        $this->assertEquals(3, $events[0]['task_id']);
        $this->assertNotEmpty($events[0]['event_content']);
        $this->assertNotEmpty($events[0]['event_title']);
        $this->assertNotEmpty($events[0]['author']);
        $this->assertInternalType('array', $events[0]['task']);
    }

    public function testGetProjectsEvents()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'P2']));
        $this->assertEquals(3, $projectModel->create(['name' => 'P3']));

        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertEquals(2, $taskCreation->create(['title' => 'Test', 'project_id' => 2]));
        $this->assertEquals(3, $taskCreation->create(['title' => 'Test', 'project_id' => 3]));

        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));
        $this->assertNotFalse($projectActivityModel->createEvent(2, 2, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(2)]));
        $this->assertNotFalse($projectActivityModel->createEvent(3, 3, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(3)]));

        $helper = new ProjectActivityHelper($this->container);
        $events = $helper->getProjectsEvents([1, 2]);

        $this->assertCount(2, $events);
        $this->assertEquals(2, $events[0]['task_id']);
        $this->assertNotEmpty($events[0]['event_content']);
        $this->assertNotEmpty($events[0]['event_title']);
        $this->assertNotEmpty($events[0]['author']);
        $this->assertInternalType('array', $events[0]['task']);
    }

    public function testGetTaskEvents()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectActivityModel = new ProjectActivityModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));

        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));
        $this->assertEquals(2, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));

        $this->assertNotFalse($projectActivityModel->createEvent(1, 1, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(1)]));
        $this->assertNotFalse($projectActivityModel->createEvent(1, 2, 1, TaskModel::EVENT_CREATE, ['task' => $taskFinder->getById(2)]));

        $helper = new ProjectActivityHelper($this->container);
        $events = $helper->getTaskEvents(1);

        $this->assertCount(1, $events);
        $this->assertEquals(1, $events[0]['task_id']);
        $this->assertNotEmpty($events[0]['event_content']);
        $this->assertNotEmpty($events[0]['event_title']);
        $this->assertNotEmpty($events[0]['author']);
        $this->assertInternalType('array', $events[0]['task']);
    }
}
