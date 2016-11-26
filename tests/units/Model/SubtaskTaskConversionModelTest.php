<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Model\ProjectModel;
use Hiject\Model\SubtaskModel;
use Hiject\Model\SubtaskTaskConversionModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;

require_once __DIR__.'/../Base.php';

class SubtaskTaskConversionModelTest extends Base
{
    public function testConvertToTask()
    {
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $subtaskConversion = new SubtaskTaskConversionModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test 1', 'project_id' => 1]));

        $this->assertEquals(1, $subtaskModel->create(['title' => 'subtask #1', 'task_id' => 1, 'user_id' => 1, 'time_spent' => 2, 'time_estimated' => 3]));
        $task_id = $subtaskConversion->convertToTask(1, 1);

        $this->assertNotFalse($task_id);
        $this->assertEmpty($subtaskModel->getById(1));

        $task = $taskFinderModel->getById($task_id);
        $this->assertEquals('subtask #1', $task['title']);
        $this->assertEquals(1, $task['project_id']);
        $this->assertEquals(1, $task['owner_id']);
        $this->assertEquals(2, $task['time_spent']);
        $this->assertEquals(3, $task['time_estimated']);
    }
}
