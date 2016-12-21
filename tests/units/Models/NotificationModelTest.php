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

use Hiject\Bus\Subscriber\NotificationSubscriber;
use Hiject\Model\CommentModel;
use Hiject\Model\NotificationModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\SubtaskModel;
use Hiject\Model\TaskFileModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskLinkModel;
use Hiject\Model\TaskModel;

class NotificationModelTest extends Base
{
    public function testGetTitle()
    {
        $notificationModel = new NotificationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $taskModel = new TaskModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $commentModel = new CommentModel($this->container);
        $taskFileModel = new TaskFileModel($this->container);
        $taskLinkModel = new TaskLinkModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(2, $taskModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(1, $subtaskModel->create(['title' => 'test', 'task_id' => 1]));
        $this->assertEquals(1, $commentModel->create(['comment' => 'test', 'task_id' => 1, 'user_id' => 1]));
        $this->assertEquals(1, $taskFileModel->create(1, 'test', 'blah', 123));
        $this->assertEquals(1, $taskLinkModel->create(1, 2, 1));

        $task = $taskFinderModel->getDetails(1);
        $subtask = $subtaskModel->getById(1, true);
        $comment = $commentModel->getById(1);
        $file = $commentModel->getById(1);
        $tasklink = $taskLinkModel->getById(1);

        foreach (NotificationSubscriber::getSubscribedEvents() as $eventName => $values) {
            $eventData = [
                'task'      => $task,
                'comment'   => $comment,
                'subtask'   => $subtask,
                'file'      => $file,
                'task_link' => $tasklink,
                'changes'   => [],
            ];

            $this->assertNotEmpty($notificationModel->getTitleWithoutAuthor($eventName, $eventData));
            $this->assertNotEmpty($notificationModel->getTitleWithAuthor('Foobar', $eventName, $eventData));
        }

        $this->assertNotEmpty($notificationModel->getTitleWithoutAuthor(TaskModel::EVENT_OVERDUE, ['tasks' => [['id' => 1]]]));
        $this->assertNotEmpty($notificationModel->getTitleWithoutAuthor('unknown', []));
    }

    public function testGetTaskIdFromEvent()
    {
        $notificationModel = new NotificationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $taskModel = new TaskModel($this->container);
        $subtaskModel = new SubtaskModel($this->container);
        $commentModel = new CommentModel($this->container);
        $taskFileModel = new TaskFileModel($this->container);
        $taskLinkModel = new TaskLinkModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(1, $subtaskModel->create(['title' => 'test', 'task_id' => 1]));
        $this->assertEquals(1, $commentModel->create(['comment' => 'test', 'task_id' => 1, 'user_id' => 1]));
        $this->assertEquals(1, $taskFileModel->create(1, 'test', 'blah', 123));

        $task = $taskFinderModel->getDetails(1);
        $subtask = $subtaskModel->getById(1, true);
        $comment = $commentModel->getById(1);
        $file = $commentModel->getById(1);
        $tasklink = $taskLinkModel->getById(1);

        $this->assertNotEmpty($task);
        $this->assertNotEmpty($subtask);
        $this->assertNotEmpty($comment);
        $this->assertNotEmpty($file);

        foreach (NotificationSubscriber::getSubscribedEvents() as $eventName => $values) {
            $task_id = $notificationModel->getTaskIdFromEvent($eventName, [
                'task'      => $task,
                'comment'   => $comment,
                'subtask'   => $subtask,
                'file'      => $file,
                'task_link' => $tasklink,
                'changes'   => [],
            ]);

            $this->assertEquals($task_id, $task['id']);
        }

        $this->assertEquals(1, $notificationModel->getTaskIdFromEvent(TaskModel::EVENT_OVERDUE, ['tasks' => [['id' => 1]]]));
    }
}
