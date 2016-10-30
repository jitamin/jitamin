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

use Hiject\Model\TaskModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\ProjectModel;

class TaskModelTest extends Base
{
    public function testRemove()
    {
        $taskModel = new TaskModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(array('name' => 'UnitTest')));
        $this->assertEquals(1, $taskCreationModel->create(array('title' => 'Task #1', 'project_id' => 1)));

        $this->assertTrue($taskModel->remove(1));
        $this->assertFalse($taskModel->remove(1234));
    }

    public function testGetTaskIdFromText()
    {
        $taskModel = new TaskModel($this->container);
        $this->assertEquals(123, $taskModel->getTaskIdFromText('My task #123'));
        $this->assertEquals(0, $taskModel->getTaskIdFromText('My task 123'));
    }
}
