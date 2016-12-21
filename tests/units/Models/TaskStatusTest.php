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

use Hiject\Model\ProjectModel;
use Hiject\Model\SubtaskModel;
use Hiject\Model\SwimlaneModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskStatusModel;

class TaskStatusTest extends Base
{
    public function testCloseBySwimlaneAndColumn()
    {
        $tc = new TaskModel($this->container);
        $tf = new TaskFinderModel($this->container);
        $ts = new TaskStatusModel($this->container);
        $p = new ProjectModel($this->container);
        $s = new SwimlaneModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'test']));
        $this->assertEquals(1, $s->create(['name' => 'test', 'project_id' => 1]));
        $this->assertEquals(1, $tc->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(2, $tc->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(3, $tc->create(['title' => 'test', 'project_id' => 1, 'column_id' => 2]));
        $this->assertEquals(4, $tc->create(['title' => 'test', 'project_id' => 1, 'swimlane_id' => 1]));
        $this->assertEquals(5, $tc->create(['title' => 'test', 'project_id' => 1, 'is_active' => 0, 'date_completed' => strtotime('2015-01-01')]));

        $taskBefore = $tf->getById(5);

        $this->assertEquals(2, $tf->countByColumnAndSwimlaneId(1, 1, 0));
        $this->assertEquals(1, $tf->countByColumnAndSwimlaneId(1, 1, 1));
        $this->assertEquals(1, $tf->countByColumnAndSwimlaneId(1, 2, 0));

        $ts->closeTasksBySwimlaneAndColumn(0, 1);
        $this->assertEquals(0, $tf->countByColumnAndSwimlaneId(1, 1, 0));
        $this->assertEquals(1, $tf->countByColumnAndSwimlaneId(1, 1, 1));
        $this->assertEquals(1, $tf->countByColumnAndSwimlaneId(1, 2, 0));

        $ts->closeTasksBySwimlaneAndColumn(1, 1);
        $this->assertEquals(0, $tf->countByColumnAndSwimlaneId(1, 1, 0));
        $this->assertEquals(0, $tf->countByColumnAndSwimlaneId(1, 1, 1));
        $this->assertEquals(1, $tf->countByColumnAndSwimlaneId(1, 2, 0));

        $ts->closeTasksBySwimlaneAndColumn(0, 2);
        $this->assertEquals(0, $tf->countByColumnAndSwimlaneId(1, 1, 0));
        $this->assertEquals(0, $tf->countByColumnAndSwimlaneId(1, 1, 1));
        $this->assertEquals(0, $tf->countByColumnAndSwimlaneId(1, 2, 0));

        $taskAfter = $tf->getById(5);
        $this->assertEquals(strtotime('2015-01-01'), $taskAfter['date_completed']);
        $this->assertEquals($taskBefore['date_modification'], $taskAfter['date_modification']);
    }

    public function testStatus()
    {
        $tc = new TaskModel($this->container);
        $tf = new TaskFinderModel($this->container);
        $ts = new TaskStatusModel($this->container);
        $p = new ProjectModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'test']));
        $this->assertEquals(1, $tc->create(['title' => 'test', 'project_id' => 1]));

        // The task must be open

        $this->assertTrue($ts->isOpen(1));

        $task = $tf->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(TaskModel::STATUS_OPEN, $task['is_active']);
        $this->assertEquals(0, $task['date_completed']);
        $this->assertEquals(time(), $task['date_modification'], '', 1);

        // We close the task

        $this->container['dispatcher']->addListener(TaskModel::EVENT_CLOSE, [$this, 'onTaskClose']);
        $this->container['dispatcher']->addListener(TaskModel::EVENT_OPEN, [$this, 'onTaskOpen']);

        $this->assertTrue($ts->close(1));
        $this->assertTrue($ts->isClosed(1));

        $task = $tf->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(TaskModel::STATUS_CLOSED, $task['is_active']);
        $this->assertEquals(time(), $task['date_completed'], 'Bad completion timestamp', 1);
        $this->assertEquals(time(), $task['date_modification'], 'Bad modification timestamp', 1);

        // We open the task again

        $this->assertTrue($ts->open(1));
        $this->assertTrue($ts->isOpen(1));

        $task = $tf->getById(1);
        $this->assertNotEmpty($task);
        $this->assertEquals(TaskModel::STATUS_OPEN, $task['is_active']);
        $this->assertEquals(0, $task['date_completed']);
        $this->assertEquals(time(), $task['date_modification'], '', 1);

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey('task.close.TaskStatusTest::onTaskClose', $called);
        $this->assertArrayHasKey('task.open.TaskStatusTest::onTaskOpen', $called);
    }

    public function onTaskOpen($event)
    {
        $this->assertInstanceOf('Hiject\Bus\Event\TaskEvent', $event);
        $this->assertArrayHasKey('task_id', $event);
        $this->assertNotEmpty($event['task_id']);
    }

    public function onTaskClose($event)
    {
        $this->assertInstanceOf('Hiject\Bus\Event\TaskEvent', $event);
        $this->assertArrayHasKey('task_id', $event);
        $this->assertNotEmpty($event['task_id']);
    }

    public function testThatAllSubtasksAreClosed()
    {
        $ts = new TaskStatusModel($this->container);
        $tc = new TaskModel($this->container);
        $s = new SubtaskModel($this->container);
        $p = new ProjectModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'test1']));
        $this->assertEquals(1, $tc->create(['title' => 'test 1', 'project_id' => 1]));

        $this->assertEquals(1, $s->create(['title' => 'subtask #1', 'task_id' => 1]));
        $this->assertEquals(2, $s->create(['title' => 'subtask #2', 'task_id' => 1]));

        $this->assertTrue($ts->close(1));

        $subtasks = $s->getAll(1);
        $this->assertNotEmpty($subtasks);

        foreach ($subtasks as $subtask) {
            $this->assertEquals(SubtaskModel::STATUS_DONE, $subtask['status']);
        }
    }
}
