<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Job;

use Jitamin\Bus\Event\TaskEvent;
use Jitamin\Bus\EventBuilder\TaskEventBuilder;
use Jitamin\Model\TaskModel;

/**
 * Class TaskEventJob.
 */
class TaskEventJob extends BaseJob
{
    /**
     * Set job params.
     *
     * @param int   $taskId
     * @param array $eventNames
     * @param array $changes
     * @param array $values
     * @param array $task
     *
     * @return $this
     */
    public function withParams($taskId, array $eventNames, array $changes = [], array $values = [], array $task = [])
    {
        $this->jobParams = [$taskId, $eventNames, $changes, $values, $task];

        return $this;
    }

    /**
     * Execute job.
     *
     * @param int   $taskId
     * @param array $eventNames
     * @param array $changes
     * @param array $values
     * @param array $task
     *
     * @return $this
     */
    public function execute($taskId, array $eventNames, array $changes = [], array $values = [], array $task = [])
    {
        $event = TaskEventBuilder::getInstance($this->container)
            ->withTaskId($taskId)
            ->withChanges($changes)
            ->withValues($values)
            ->withTask($task)
            ->buildEvent();

        if ($event !== null) {
            foreach ($eventNames as $eventName) {
                $this->fireEvent($eventName, $event);
            }
        }
    }

    /**
     * Trigger event.
     *
     * @param string    $eventName
     * @param TaskEvent $event
     */
    protected function fireEvent($eventName, TaskEvent $event)
    {
        $this->logger->debug(__METHOD__.' Event fired: '.$eventName);
        $this->dispatcher->dispatch($eventName, $event);

        if ($eventName === TaskModel::EVENT_CREATE) {
            $this->userMentionModel->fireEvents($event['task']['description'], TaskModel::EVENT_USER_MENTION, $event);
        }
    }
}
