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
use Hiject\Bus\Subscriber\RecurringTaskSubscriber;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class RecurringTaskSubscriberTest extends Base
{
    public function testWithNoRecurrence()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $subscriber = new RecurringTaskSubscriber($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));

        $event = TaskEventBuilder::getInstance($this->container)
            ->withTaskId(1)
            ->buildEvent();

        $subscriber->onMove($event);
        $subscriber->onClose($event);

        $this->assertEquals(1, $taskFinderModel->countByProjectId(1));
    }

    public function testWithRecurrenceFirstColumn()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $subscriber = new RecurringTaskSubscriber($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create([
            'title'              => 'test',
            'project_id'         => 1,
            'recurrence_status'  => TaskModel::RECURRING_STATUS_PENDING,
            'recurrence_trigger' => TaskModel::RECURRING_TRIGGER_FIRST_COLUMN,
        ]));

        $event = TaskEventBuilder::getInstance($this->container)
            ->withTaskId(1)
            ->withValues(['src_column_id' => 1])
            ->buildEvent();

        $subscriber->onMove($event);
        $subscriber->onClose($event);

        $this->assertEquals(2, $taskFinderModel->countByProjectId(1));
    }

    public function testWithRecurrenceFirstColumnWithWrongColumn()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $subscriber = new RecurringTaskSubscriber($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create([
            'title'              => 'test',
            'project_id'         => 1,
            'recurrence_status'  => TaskModel::RECURRING_STATUS_PENDING,
            'recurrence_trigger' => TaskModel::RECURRING_TRIGGER_FIRST_COLUMN,
            'column_id'          => 2,
        ]));

        $event = TaskEventBuilder::getInstance($this->container)
            ->withTaskId(1)
            ->withValues(['src_column_id' => 2])
            ->buildEvent();

        $subscriber->onMove($event);
        $subscriber->onClose($event);

        $this->assertEquals(1, $taskFinderModel->countByProjectId(1));
    }

    public function testWithRecurrenceLastColumn()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $subscriber = new RecurringTaskSubscriber($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create([
            'title'              => 'test',
            'project_id'         => 1,
            'recurrence_status'  => TaskModel::RECURRING_STATUS_PENDING,
            'recurrence_trigger' => TaskModel::RECURRING_TRIGGER_LAST_COLUMN,
        ]));

        $event = TaskEventBuilder::getInstance($this->container)
            ->withTaskId(1)
            ->withValues(['dst_column_id' => 4])
            ->buildEvent();

        $subscriber->onMove($event);
        $subscriber->onClose($event);

        $this->assertEquals(2, $taskFinderModel->countByProjectId(1));
    }

    public function testWithRecurrenceLastColumnWithWrongColumn()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $subscriber = new RecurringTaskSubscriber($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create([
            'title'              => 'test',
            'project_id'         => 1,
            'recurrence_status'  => TaskModel::RECURRING_STATUS_PENDING,
            'recurrence_trigger' => TaskModel::RECURRING_TRIGGER_LAST_COLUMN,
        ]));

        $event = TaskEventBuilder::getInstance($this->container)
            ->withTaskId(1)
            ->withValues(['dst_column_id' => 2])
            ->buildEvent();

        $subscriber->onMove($event);
        $subscriber->onClose($event);

        $this->assertEquals(1, $taskFinderModel->countByProjectId(1));
    }

    public function testWithRecurrenceOnClose()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);
        $subscriber = new RecurringTaskSubscriber($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create([
            'title'              => 'test',
            'project_id'         => 1,
            'recurrence_status'  => TaskModel::RECURRING_STATUS_PENDING,
            'recurrence_trigger' => TaskModel::RECURRING_TRIGGER_CLOSE,
        ]));

        $event = TaskEventBuilder::getInstance($this->container)
            ->withTaskId(1)
            ->withChanges(['is_active' => 0])
            ->buildEvent();

        $subscriber->onMove($event);
        $subscriber->onClose($event);

        $this->assertEquals(2, $taskFinderModel->countByProjectId(1));
    }
}
