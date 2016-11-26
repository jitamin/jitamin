<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Bus\Job\TaskEventJob;
use Hiject\Model\ProjectModel;
use Hiject\Model\SwimlaneModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskModificationModel;
use Hiject\Model\TaskPositionModel;
use Hiject\Model\TaskProjectMoveModel;
use Hiject\Model\TaskStatusModel;

require_once __DIR__.'/../Base.php';

class TaskEventJobTest extends Base
{
    public function testJobParams()
    {
        $taskEventJob = new TaskEventJob($this->container);
        $taskEventJob->withParams(123, ['foobar'], ['k' => 'v'], ['k1' => 'v1'], ['k2' => 'v2']);

        $this->assertSame(
            [123, ['foobar'], ['k' => 'v'], ['k1' => 'v1'], ['k2' => 'v2']],
            $taskEventJob->getJobParams()
        );
    }

    public function testWithMissingTask()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_CREATE, function () {});

        $taskEventJob = new TaskEventJob($this->container);
        $taskEventJob->execute(42, [TaskModel::EVENT_CREATE]);

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertEmpty($called);
    }

    public function testTriggerCreateEvent()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_CREATE, function () {});
        $this->container['dispatcher']->addListener(TaskModel::EVENT_CREATE_UPDATE, function () {});

        $taskCreationModel = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test', 'project_id' => 1]));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_CREATE.'.closure', $called);
        $this->assertArrayHasKey(TaskModel::EVENT_CREATE_UPDATE.'.closure', $called);
    }

    public function testTriggerUpdateEvent()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_UPDATE, function () {});
        $this->container['dispatcher']->addListener(TaskModel::EVENT_CREATE_UPDATE, function () {});

        $taskCreationModel = new TaskCreationModel($this->container);
        $taskModificationModel = new TaskModificationModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertTrue($taskModificationModel->update(['id' => 1, 'title' => 'new title']));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_UPDATE.'.closure', $called);
        $this->assertArrayHasKey(TaskModel::EVENT_CREATE_UPDATE.'.closure', $called);
    }

    public function testTriggerAssigneeChangeEvent()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_ASSIGNEE_CHANGE, function () {});

        $taskCreationModel = new TaskCreationModel($this->container);
        $taskModificationModel = new TaskModificationModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertTrue($taskModificationModel->update(['id' => 1, 'owner_id' => 1]));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_ASSIGNEE_CHANGE.'.closure', $called);
    }

    public function testTriggerCloseEvent()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_CLOSE, function () {});

        $taskCreationModel = new TaskCreationModel($this->container);
        $taskStatusModel = new TaskStatusModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertTrue($taskStatusModel->close(1));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_CLOSE.'.closure', $called);
    }

    public function testTriggerOpenEvent()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_OPEN, function () {});

        $taskCreationModel = new TaskCreationModel($this->container);
        $taskStatusModel = new TaskStatusModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertTrue($taskStatusModel->close(1));
        $this->assertTrue($taskStatusModel->open(1));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_OPEN.'.closure', $called);
    }

    public function testTriggerMovePositionEvent()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_MOVE_POSITION, function () {});

        $taskCreationModel = new TaskCreationModel($this->container);
        $taskPositionModel = new TaskPositionModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test 1', 'project_id' => 1]));
        $this->assertEquals(2, $taskCreationModel->create(['title' => 'test 2', 'project_id' => 1]));
        $this->assertTrue($taskPositionModel->movePosition(1, 1, 1, 2));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_MOVE_POSITION.'.closure', $called);
    }

    public function testTriggerMoveColumnEvent()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_MOVE_COLUMN, function () {});

        $taskCreationModel = new TaskCreationModel($this->container);
        $taskPositionModel = new TaskPositionModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test 1', 'project_id' => 1]));
        $this->assertTrue($taskPositionModel->movePosition(1, 1, 2, 2));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_MOVE_COLUMN.'.closure', $called);
    }

    public function testTriggerMoveSwimlaneEvent()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_MOVE_SWIMLANE, function () {});

        $taskCreationModel = new TaskCreationModel($this->container);
        $taskPositionModel = new TaskPositionModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $swimlaneModel = new SwimlaneModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $swimlaneModel->create(['name' => 'S1', 'project_id' => 1]));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test 1', 'project_id' => 1]));
        $this->assertTrue($taskPositionModel->movePosition(1, 1, 1, 1, 1));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_MOVE_SWIMLANE.'.closure', $called);
    }

    public function testTriggerMoveProjectEvent()
    {
        $this->container['dispatcher']->addListener(TaskModel::EVENT_MOVE_PROJECT, function () {});

        $taskCreationModel = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskProjectMoveModel = new TaskProjectMoveModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'test2']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test 1', 'project_id' => 1]));
        $this->assertTrue($taskProjectMoveModel->moveToProject(1, 1));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(TaskModel::EVENT_MOVE_PROJECT.'.closure', $called);
    }
}
