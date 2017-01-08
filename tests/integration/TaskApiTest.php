<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/BaseApiTest.php';

class TaskApiTest extends BaseApiTest
{
    protected $projectName = 'My project to test tasks';

    public function testAll()
    {
        $this->assertCreateTeamProject();
        $this->assertCreateTask();
        $this->assertUpdateTask();
        $this->assertGetTaskById();
        $this->assertGetTaskByReference();
        $this->assertGetAllTasks();
        $this->assertOpenCloseTask();
    }

    public function assertUpdateTask()
    {
        $this->assertTrue($this->app->updateTask(['id' => $this->taskId, 'color_id' => 'red']));
    }

    public function assertGetTaskById()
    {
        $task = $this->app->getTask($this->taskId);
        $this->assertNotNull($task);
        $this->assertEquals('red', $task['color_id']);
        $this->assertEquals($this->taskTitle, $task['title']);
    }

    public function assertGetTaskByReference()
    {
        $taskId = $this->app->createTask(['title' => 'task with reference', 'project_id' => $this->projectId, 'reference' => 'test']);
        $this->assertNotFalse($taskId);

        $task = $this->app->getTaskByReference($this->projectId, 'test');
        $this->assertNotNull($task);
        $this->assertEquals($taskId, $task['id']);
    }

    public function assertGetAllTasks()
    {
        $tasks = $this->app->getAllTasks($this->projectId);
        $this->assertInternalType('array', $tasks);
        $this->assertNotEmpty($tasks);
    }

    public function assertOpenCloseTask()
    {
        $this->assertTrue($this->app->closeTask($this->taskId));
        $this->assertTrue($this->app->openTask($this->taskId));
    }
}
