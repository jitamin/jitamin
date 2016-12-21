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

use Hiject\Action\TaskEmailNoActivity;
use Hiject\Bus\Event\TaskListEvent;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;
use Hiject\Model\UserModel;

class TaskEmailNoActivityTest extends Base
{
    public function testSendEmail()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(2, $userModel->create(['username' => 'test', 'email' => 'chuck@norris', 'name' => 'Chuck Norris']));
        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(2, $taskModel->create(['project_id' => 1, 'title' => 'test']));

        $this->container['db']->table(TaskModel::TABLE)->eq('id', 1)->update(['date_modification' => strtotime('-10days')]);

        $tasks = $taskFinderModel->getAll(1);
        $event = new TaskListEvent(['tasks' => $tasks, 'project_id' => 1]);

        $action = new TaskEmailNoActivity($this->container);
        $action->setProjectId(1);
        $action->setParam('user_id', 2);
        $action->setParam('subject', 'Old tasks');
        $action->setParam('duration', 2);

        $this->container['emailClient']
            ->expects($this->once())
            ->method('send')
            ->with('chuck@norris', 'Chuck Norris', 'Old tasks', $this->anything());

        $this->assertTrue($action->execute($event, TaskModel::EVENT_DAILY_CRONJOB));
    }

    public function testTooRecent()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(2, $userModel->create(['username' => 'test', 'email' => 'chuck@norris', 'name' => 'Chuck Norris']));
        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(2, $taskModel->create(['project_id' => 1, 'title' => 'test']));

        $tasks = $taskFinderModel->getAll(1);
        $event = new TaskListEvent(['tasks' => $tasks, 'project_id' => 1]);

        $action = new TaskEmailNoActivity($this->container);
        $action->setProjectId(1);
        $action->setParam('user_id', 2);
        $action->setParam('subject', 'Old tasks');
        $action->setParam('duration', 2);

        $this->container['emailClient']
            ->expects($this->never())
            ->method('send');

        $this->assertFalse($action->execute($event, TaskModel::EVENT_DAILY_CRONJOB));
    }
}
