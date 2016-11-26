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

use Hiject\Bus\Event\TaskEvent;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\UserModel;
use Hiject\Action\TaskEmail;

class TaskEmailTest extends Base
{
    public function testSuccess()
    {
        $userModel = new UserModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertTrue($userModel->update(['id' => 1, 'email' => 'admin@localhost']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task' => $taskFinderModel->getDetails(1)
        ]);

        $action = new TaskEmail($this->container);
        $action->setProjectId(1);
        $action->setParam('column_id', 1);
        $action->setParam('user_id', 1);
        $action->setParam('subject', 'My email subject');

        $this->container['emailClient']->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo('admin@localhost'),
                $this->equalTo('admin'),
                $this->equalTo('My email subject'),
                $this->stringContains('test')
            );

        $this->assertTrue($action->execute($event, TaskModel::EVENT_CLOSE));
    }

    public function testWithWrongColumn()
    {
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));

        $event = new TaskEvent([
            'task_id' => 1,
            'task' => [
                'project_id' => 1,
                'column_id' => 3,
            ]
        ]);

        $action = new TaskEmail($this->container);
        $action->setProjectId(1);
        $action->setParam('column_id', 2);
        $action->setParam('user_id', 1);
        $action->setParam('subject', 'My email subject');

        $this->assertFalse($action->execute($event, TaskModel::EVENT_CLOSE));
    }
}
