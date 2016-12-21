<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Subscriber;

use Jitamin\Bus\Event\TaskEvent;
use Jitamin\Model\TaskModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Recurring task Subscriber.
 */
class RecurringTaskSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    /**
     * Get event listeners.
     *
     * @static
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TaskModel::EVENT_MOVE_COLUMN => 'onMove',
            TaskModel::EVENT_CLOSE       => 'onClose',
        ];
    }

    /**
     * Move the task.
     *
     * @param TaskEvent $event
     */
    public function onMove(TaskEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $task = $event['task'];

        if ($task['recurrence_status'] == TaskModel::RECURRING_STATUS_PENDING) {
            if ($task['recurrence_trigger'] == TaskModel::RECURRING_TRIGGER_FIRST_COLUMN && $this->columnModel->getFirstColumnId($task['project_id']) == $event['src_column_id']) {
                $this->taskRecurrenceModel->duplicateRecurringTask($task['id']);
            } elseif ($task['recurrence_trigger'] == TaskModel::RECURRING_TRIGGER_LAST_COLUMN && $this->columnModel->getLastColumnId($task['project_id']) == $event['dst_column_id']) {
                $this->taskRecurrenceModel->duplicateRecurringTask($task['id']);
            }
        }
    }

    /**
     * Close the task.
     *
     * @param TaskEvent $event
     */
    public function onClose(TaskEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $task = $event['task'];

        if ($task['recurrence_status'] == TaskModel::RECURRING_STATUS_PENDING && $task['recurrence_trigger'] == TaskModel::RECURRING_TRIGGER_CLOSE) {
            $this->taskRecurrenceModel->duplicateRecurringTask($event['task_id']);
        }
    }
}
