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
use Hiject\Model\TaskModel;
use Hiject\Model\TaskFinderModel;

class SubtaskModelTest extends Base
{
    public function testCreation()
    {
        $taskModel = new TaskModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test 1', 'project_id' => 1]));
        $this->assertEquals(1, $subtaskModel->create(['title' => 'subtask #1', 'task_id' => 1]));

        $subtask = $subtaskModel->getById(1);
        $this->assertNotEmpty($subtask);
        $this->assertEquals(1, $subtask['id']);
        $this->assertEquals(1, $subtask['task_id']);
        $this->assertEquals('subtask #1', $subtask['title']);
        $this->assertEquals(SubtaskModel::STATUS_TODO, $subtask['status']);
        $this->assertEquals(0, $subtask['time_estimated']);
        $this->assertEquals(0, $subtask['time_spent']);
        $this->assertEquals(0, $subtask['user_id']);
        $this->assertEquals(1, $subtask['position']);
    }

    public function testCreationUpdateTaskTimeTracking()
    {
        $taskModel = new TaskModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test 1', 'project_id' => 1]));

        $this->assertEquals(1, $subtaskModel->create(['title' => 'subtask #1', 'task_id' => 1, 'time_estimated' => 2, 'time_spent' => 1]));
        $this->assertEquals(2, $subtaskModel->create(['title' => 'subtask #2', 'task_id' => 1, 'time_estimated' => 5, 'time_spent' => 5]));

        $task = $taskFinderModel->getById(1);
        $this->assertEquals(7, $task['time_estimated']);
        $this->assertEquals(6, $task['time_spent']);
    }

    public function testModification()
    {
        $taskModel = new TaskModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test 1', 'project_id' => 1]));

        $this->assertEquals(1, $subtaskModel->create(['title' => 'subtask #1', 'task_id' => 1]));
        $this->assertTrue($subtaskModel->update(['id' => 1, 'task_id' => 1, 'user_id' => 1, 'status' => SubtaskModel::STATUS_INPROGRESS]));

        $subtask = $subtaskModel->getById(1);
        $this->assertNotEmpty($subtask);
        $this->assertEquals(1, $subtask['id']);
        $this->assertEquals(1, $subtask['task_id']);
        $this->assertEquals('subtask #1', $subtask['title']);
        $this->assertEquals(SubtaskModel::STATUS_INPROGRESS, $subtask['status']);
        $this->assertEquals(0, $subtask['time_estimated']);
        $this->assertEquals(0, $subtask['time_spent']);
        $this->assertEquals(1, $subtask['user_id']);
        $this->assertEquals(1, $subtask['position']);
    }

    public function testModificationUpdateTaskTimeTracking()
    {
        $taskModel = new TaskModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test 1', 'project_id' => 1]));

        $this->assertEquals(1, $subtaskModel->create(['title' => 'subtask #1', 'task_id' => 1]));
        $this->assertEquals(2, $subtaskModel->create(['title' => 'subtask #2', 'task_id' => 1]));
        $this->assertTrue($subtaskModel->update(['id' => 1, 'task_id' => 1, 'time_estimated' => 2, 'time_spent' => 1]));
        $this->assertTrue($subtaskModel->update(['id' => 2, 'task_id' => 1, 'time_estimated' => 2, 'time_spent' => 1]));
        $this->assertTrue($subtaskModel->update(['id' => 1, 'task_id' => 1, 'time_estimated' => 5, 'time_spent' => 5]));

        $task = $taskFinderModel->getById(1);
        $this->assertEquals(7, $task['time_estimated']);
        $this->assertEquals(6, $task['time_spent']);
    }

    public function testRemove()
    {
        $taskModel = new TaskModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test 1', 'project_id' => 1]));
        $this->assertEquals(1, $subtaskModel->create(['title' => 'subtask #1', 'task_id' => 1]));

        $subtask = $subtaskModel->getById(1);
        $this->assertNotEmpty($subtask);

        $this->assertTrue($subtaskModel->remove(1));

        $subtask = $subtaskModel->getById(1);
        $this->assertEmpty($subtask);
    }

    public function testDuplicate()
    {
        $taskModel = new TaskModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $projectModel = new ProjectModel($this->container);

        // We create a project
        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));

        // We create 2 tasks
        $this->assertEquals(1, $taskModel->create(['title' => 'test 1', 'project_id' => 1, 'column_id' => 1, 'owner_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'test 2', 'project_id' => 1, 'column_id' => 1, 'owner_id' => 0]));

        // We create many subtasks for the first task
        $this->assertEquals(1, $subtaskModel->create(['title' => 'subtask #1', 'task_id' => 1, 'time_estimated' => 5, 'time_spent' => 3, 'status' => 1, 'another_subtask' => 'on']));
        $this->assertEquals(2, $subtaskModel->create(['title' => 'subtask #2', 'task_id' => 1, 'time_estimated' => '', 'time_spent' => '', 'status' => 2, 'user_id' => 1]));

        // We duplicate our subtasks
        $this->assertTrue($subtaskModel->duplicate(1, 2));
        $subtasks = $subtaskModel->getAll(2);

        $this->assertNotEmpty($subtasks);
        $this->assertEquals(2, count($subtasks));

        $this->assertEquals('subtask #1', $subtasks[0]['title']);
        $this->assertEquals('subtask #2', $subtasks[1]['title']);

        $this->assertEquals(2, $subtasks[0]['task_id']);
        $this->assertEquals(2, $subtasks[1]['task_id']);

        $this->assertEquals(5, $subtasks[0]['time_estimated']);
        $this->assertEquals(0, $subtasks[1]['time_estimated']);

        $this->assertEquals(0, $subtasks[0]['time_spent']);
        $this->assertEquals(0, $subtasks[1]['time_spent']);

        $this->assertEquals(0, $subtasks[0]['status']);
        $this->assertEquals(0, $subtasks[1]['status']);

        $this->assertEquals(0, $subtasks[0]['user_id']);
        $this->assertEquals(0, $subtasks[1]['user_id']);

        $this->assertEquals(1, $subtasks[0]['position']);
        $this->assertEquals(2, $subtasks[1]['position']);
    }

    public function testGetProjectId()
    {
        $taskModel = new TaskModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test 1', 'project_id' => 1]));
        $this->assertEquals(1, $subtaskModel->create(['title' => 'subtask #1', 'task_id' => 1]));

        $this->assertEquals(1, $subtaskModel->getProjectId(1));
        $this->assertEquals(0, $subtaskModel->getProjectId(2));
    }
}
