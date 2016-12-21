<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Bus\EventBuilder\TaskEventBuilder;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class TaskEventBuilderTest extends Base
{
    public function testWithMissingTask()
    {
        $taskEventBuilder = new TaskEventBuilder($this->container);
        $taskEventBuilder->withTaskId(42);
        $this->assertNull($taskEventBuilder->buildEvent());
    }

    public function testBuildWithTask()
    {
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskEventBuilder = new TaskEventBuilder($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'before', 'project_id' => 1]));

        $event = $taskEventBuilder
            ->withTaskId(1)
            ->withTask(['title' => 'before'])
            ->withChanges(['title' => 'after'])
            ->buildEvent();

        $this->assertInstanceOf('Hiject\Bus\Event\TaskEvent', $event);
        $this->assertNotEmpty($event['task']);
        $this->assertEquals(1, $event['task_id']);
        $this->assertEquals(['title' => 'after'], $event['changes']);
    }

    public function testBuildWithoutChanges()
    {
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskEventBuilder = new TaskEventBuilder($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));

        $event = $taskEventBuilder->withTaskId(1)->buildEvent();

        $this->assertInstanceOf('Hiject\Bus\Event\TaskEvent', $event);
        $this->assertNotEmpty($event['task']);
        $this->assertEquals(1, $event['task_id']);
        $this->assertArrayNotHasKey('changes', $event);
    }

    public function testBuildWithChanges()
    {
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskEventBuilder = new TaskEventBuilder($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));

        $event = $taskEventBuilder
            ->withTaskId(1)
            ->withChanges(['title' => 'new title'])
            ->buildEvent();

        $this->assertInstanceOf('Hiject\Bus\Event\TaskEvent', $event);
        $this->assertNotEmpty($event['task']);
        $this->assertNotEmpty($event['changes']);
        $this->assertEquals('new title', $event['changes']['title']);
    }

    public function testBuildWithChangesAndValues()
    {
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskEventBuilder = new TaskEventBuilder($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));

        $event = $taskEventBuilder
            ->withTaskId(1)
            ->withChanges(['title' => 'new title', 'project_id' => 1])
            ->withValues(['key' => 'value'])
            ->buildEvent();

        $this->assertInstanceOf('Hiject\Bus\Event\TaskEvent', $event);
        $this->assertNotEmpty($event['task']);
        $this->assertNotEmpty($event['changes']);
        $this->assertNotEmpty($event['key']);
        $this->assertEquals('value', $event['key']);

        $this->assertCount(1, $event['changes']);
        $this->assertEquals('new title', $event['changes']['title']);
    }
}
