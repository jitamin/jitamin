<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Bus\Job\TaskFileEventJob;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskFileModel;
use Hiject\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class TaskFileEventJobTest extends Base
{
    public function testJobParams()
    {
        $taskFileEventJob = new TaskFileEventJob($this->container);
        $taskFileEventJob->withParams(123, 'foobar');

        $this->assertSame([123, 'foobar'], $taskFileEventJob->getJobParams());
    }

    public function testWithMissingFile()
    {
        $this->container['dispatcher']->addListener(TaskFileModel::EVENT_CREATE, function () {
        });

        $taskFileEventJob = new TaskFileEventJob($this->container);
        $taskFileEventJob->execute(42, TaskFileModel::EVENT_CREATE);

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertEmpty($called);
    }

    public function testTriggerEvents()
    {
        $this->container['dispatcher']->addListener(TaskFileModel::EVENT_CREATE, function () {
        });

        $taskFileModel = new TaskFileModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(1, $taskFileModel->create(1, 'Test', '/tmp/test', 123));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskFileModel::EVENT_CREATE.'.closure', $called);
    }
}
