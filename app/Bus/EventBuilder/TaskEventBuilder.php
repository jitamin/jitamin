<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\EventBuilder;

use Jitamin\Bus\Event\TaskEvent;
use Jitamin\Model\TaskModel;

/**
 * Class TaskEventBuilder.
 */
class TaskEventBuilder extends BaseEventBuilder
{
    /**
     * TaskId.
     *
     * @var int
     */
    protected $taskId = 0;

    /**
     * Task.
     *
     * @var array
     */
    protected $task = [];

    /**
     * Extra values.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Changed values.
     *
     * @var array
     */
    protected $changes = [];

    /**
     * Set TaskId.
     *
     * @param int $taskId
     *
     * @return $this
     */
    public function withTaskId($taskId)
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * Set task.
     *
     * @param array $task
     *
     * @return $this
     */
    public function withTask(array $task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Set values.
     *
     * @param array $values
     *
     * @return $this
     */
    public function withValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Set changes.
     *
     * @param array $changes
     *
     * @return $this
     */
    public function withChanges(array $changes)
    {
        $this->changes = $changes;

        return $this;
    }

    /**
     * Build event data.
     *
     * @return TaskEvent|null
     */
    public function buildEvent()
    {
        $eventData = [];
        $eventData['task_id'] = $this->taskId;
        $eventData['task'] = $this->taskFinderModel->getDetails($this->taskId);

        if (empty($eventData['task'])) {
            $this->logger->debug(__METHOD__.': Task not found');

            return;
        }

        if (!empty($this->changes)) {
            if (empty($this->task)) {
                $this->task = $eventData['task'];
            }

            $eventData['changes'] = array_diff_assoc($this->changes, $this->task);
            unset($eventData['changes']['date_modification']);
        }

        return new TaskEvent(array_merge($eventData, $this->values));
    }

    /**
     * Get event title with author.
     *
     * @param string $author
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    public function buildTitleWithAuthor($author, $eventName, array $eventData)
    {
        switch ($eventName) {
            case TaskModel::EVENT_ASSIGNEE_CHANGE:
                $assignee = $eventData['task']['assignee_name'] ?: $eventData['task']['assignee_username'];

                if (!empty($assignee)) {
                    return l('%s changed the assignee of the task #%d to %s', $author, $eventData['task']['id'], $assignee);
                }

                return l('%s removed the assignee of the task %s', $author, l('#%d', $eventData['task']['id']));
            case TaskModel::EVENT_UPDATE:
                return l('%s updated the task #%d', $author, $eventData['task']['id']);
            case TaskModel::EVENT_CREATE:
                return l('%s created the task #%d', $author, $eventData['task']['id']);
            case TaskModel::EVENT_CLOSE:
                return l('%s closed the task #%d', $author, $eventData['task']['id']);
            case TaskModel::EVENT_OPEN:
                return l('%s opened the task #%d', $author, $eventData['task']['id']);
            case TaskModel::EVENT_MOVE_COLUMN:
                return e(
                    '%s moved the task #%d to the column "%s"',
                    $author,
                    $eventData['task']['id'],
                    $eventData['task']['column_title']
                );
            case TaskModel::EVENT_MOVE_POSITION:
                return e(
                    '%s moved the task #%d to the position %d in the column "%s"',
                    $author,
                    $eventData['task']['id'],
                    $eventData['task']['position'],
                    $eventData['task']['column_title']
                );
            case TaskModel::EVENT_MOVE_SWIMLANE:
                if ($eventData['task']['swimlane_id'] == 0) {
                    return l('%s moved the task #%d to the first swimlane', $author, $eventData['task']['id']);
                }

                return e(
                    '%s moved the task #%d to the swimlane "%s"',
                    $author,
                    $eventData['task']['id'],
                    $eventData['task']['swimlane_name']
                );

            case TaskModel::EVENT_USER_MENTION:
                return l('%s mentioned you in the task #%d', $author, $eventData['task']['id']);
            default:
                return '';
        }
    }

    /**
     * Get event title without author.
     *
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    public function buildTitleWithoutAuthor($eventName, array $eventData)
    {
        switch ($eventName) {
            case TaskModel::EVENT_CREATE:
                return l('New task #%d: %s', $eventData['task']['id'], $eventData['task']['title']);
            case TaskModel::EVENT_UPDATE:
                return l('Task updated #%d', $eventData['task']['id']);
            case TaskModel::EVENT_CLOSE:
                return l('Task #%d closed', $eventData['task']['id']);
            case TaskModel::EVENT_OPEN:
                return l('Task #%d opened', $eventData['task']['id']);
            case TaskModel::EVENT_MOVE_COLUMN:
                return l('Column changed for task #%d', $eventData['task']['id']);
            case TaskModel::EVENT_MOVE_POSITION:
                return l('New position for task #%d', $eventData['task']['id']);
            case TaskModel::EVENT_MOVE_SWIMLANE:
                return l('Swimlane changed for task #%d', $eventData['task']['id']);
            case TaskModel::EVENT_ASSIGNEE_CHANGE:
                return l('Assignee changed on task #%d', $eventData['task']['id']);
            case TaskModel::EVENT_OVERDUE:
                $nb = count($eventData['tasks']);

                return $nb > 1 ? l('%d overdue tasks', $nb) : l('Task #%d is overdue', $eventData['tasks'][0]['id']);
            case TaskModel::EVENT_USER_MENTION:
                return l('You were mentioned in the task #%d', $eventData['task']['id']);
            default:
                return '';
        }
    }
}
