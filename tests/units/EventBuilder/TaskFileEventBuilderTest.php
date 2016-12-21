<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Bus\EventBuilder\TaskFileEventBuilder;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskFileModel;
use Hiject\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class TaskFileEventBuilderTest extends Base
{
    public function testWithMissingFile()
    {
        $taskFileEventBuilder = new TaskFileEventBuilder($this->container);
        $taskFileEventBuilder->withFileId(42);
        $this->assertNull($taskFileEventBuilder->buildEvent());
    }

    public function testBuild()
    {
        $taskFileModel = new TaskFileModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskFileEventBuilder = new TaskFileEventBuilder($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(1, $taskFileModel->create(1, 'Test', '/tmp/test', 123));

        $event = $taskFileEventBuilder->withFileId(1)->buildEvent();

        $this->assertInstanceOf('Hiject\Bus\Event\TaskFileEvent', $event);
        $this->assertNotEmpty($event['file']);
        $this->assertNotEmpty($event['task']);
    }
}
