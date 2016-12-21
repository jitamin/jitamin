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

use Hiject\Model\ProjectActivityModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;

class ProjectActivityTest extends Base
{
    public function testCreation()
    {
        $projectActivity = new ProjectActivityModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Task #1', 'project_id' => 1]));
        $this->assertEquals(2, $taskCreation->create(['title' => 'Task #2', 'project_id' => 1]));

        $this->assertTrue($projectActivity->createEvent(1, 1, 1, TaskModel::EVENT_CLOSE, ['task' => $taskFinder->getById(1)]));
        $this->assertTrue($projectActivity->createEvent(1, 2, 1, TaskModel::EVENT_UPDATE, ['task' => $taskFinder->getById(2)]));
        $this->assertFalse($projectActivity->createEvent(1, 1, 0, TaskModel::EVENT_OPEN, ['task' => $taskFinder->getById(1)]));

        $events = $projectActivity->getQuery()->desc('id')->findAll();

        $this->assertCount(2, $events);
        $this->assertEquals(time(), $events[0]['date_creation'], '', 1);
        $this->assertEquals(TaskModel::EVENT_UPDATE, $events[0]['event_name']);
        $this->assertEquals(TaskModel::EVENT_CLOSE, $events[1]['event_name']);
    }

    public function testCleanup()
    {
        $projectActivity = new ProjectActivityModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Project #1']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Task #1', 'project_id' => 1]));

        $max = 15;
        $nb_events = 100;
        $task = $taskFinder->getById(1);

        for ($i = 0; $i < $nb_events; $i++) {
            $this->assertTrue($projectActivity->createEvent(1, 1, 1, TaskModel::EVENT_CLOSE, ['task' => $task]));
        }

        $this->assertEquals($nb_events, $this->container['db']->table('project_activities')->count());
        $projectActivity->cleanup($max);

        $events = $projectActivity->getQuery()->desc('id')->findAll();

        $this->assertNotEmpty($events);
        $this->assertCount($max, $events);
        $this->assertEquals(100, $events[0]['id']);
        $this->assertEquals(99, $events[1]['id']);
        $this->assertEquals(86, $events[14]['id']);
    }
}
