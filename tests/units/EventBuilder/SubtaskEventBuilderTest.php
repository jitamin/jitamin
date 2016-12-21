<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Bus\EventBuilder\SubtaskEventBuilder;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\SubtaskModel;
use Jitamin\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class SubtaskEventBuilderTest extends Base
{
    public function testWithMissingSubtask()
    {
        $subtaskEventBuilder = new SubtaskEventBuilder($this->container);
        $subtaskEventBuilder->withSubtaskId(42);
        $this->assertNull($subtaskEventBuilder->buildEvent());
    }

    public function testBuildWithoutChanges()
    {
        $subtaskModel = new SubtaskModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $subtaskEventBuilder = new SubtaskEventBuilder($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(1, $subtaskModel->create(['task_id' => 1, 'title' => 'test']));

        $event = $subtaskEventBuilder->withSubtaskId(1)->buildEvent();

        $this->assertInstanceOf('Jitamin\Bus\Event\SubtaskEvent', $event);
        $this->assertNotEmpty($event['subtask']);
        $this->assertNotEmpty($event['task']);
        $this->assertArrayNotHasKey('changes', $event);
    }

    public function testBuildWithChanges()
    {
        $subtaskModel = new SubtaskModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $subtaskEventBuilder = new SubtaskEventBuilder($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(1, $subtaskModel->create(['task_id' => 1, 'title' => 'test']));

        $event = $subtaskEventBuilder
            ->withSubtaskId(1)
            ->withValues(['title' => 'new title', 'user_id' => 1])
            ->buildEvent();

        $this->assertInstanceOf('Jitamin\Bus\Event\SubtaskEvent', $event);
        $this->assertNotEmpty($event['subtask']);
        $this->assertNotEmpty($event['task']);
        $this->assertNotEmpty($event['changes']);
        $this->assertCount(2, $event['changes']);
        $this->assertEquals('new title', $event['changes']['title']);
        $this->assertEquals(1, $event['changes']['user_id']);
    }
}
