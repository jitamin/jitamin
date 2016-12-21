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

use Hiject\Action\TaskAssignColorLink;
use Hiject\Bus\EventBuilder\TaskLinkEventBuilder;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskLinkModel;

class TaskAssignColorLinkTest extends Base
{
    public function testChangeColor()
    {
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskLinkModel = new TaskLinkModel($this->container);

        $action = new TaskAssignColorLink($this->container);
        $action->setProjectId(1);
        $action->setParam('link_id', 2);
        $action->setParam('color_id', 'red');

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'T1', 'project_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'T2', 'project_id' => 1]));
        $this->assertEquals(1, $taskLinkModel->create(1, 2, 2));

        $event = TaskLinkEventBuilder::getInstance($this->container)
            ->withTaskLinkId(1)
            ->buildEvent();

        $this->assertTrue($action->execute($event, TaskLinkModel::EVENT_CREATE_UPDATE));

        $task = $taskFinderModel->getById(1);
        $this->assertEquals('red', $task['color_id']);
    }

    public function testWithWrongLink()
    {
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskLinkModel = new TaskLinkModel($this->container);

        $action = new TaskAssignColorLink($this->container);
        $action->setProjectId(1);
        $action->setParam('link_id', 2);
        $action->setParam('color_id', 'red');

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'T1', 'project_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'T2', 'project_id' => 1]));
        $this->assertEquals(1, $taskLinkModel->create(1, 2, 1));

        $event = TaskLinkEventBuilder::getInstance($this->container)
            ->withTaskLinkId(1)
            ->buildEvent();

        $this->assertFalse($action->execute($event, TaskLinkModel::EVENT_CREATE_UPDATE));

        $task = $taskFinderModel->getById(1);
        $this->assertEquals('yellow', $task['color_id']);
    }
}
