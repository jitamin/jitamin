<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Model;

use Hiject\Core\Database\Model;

/**
 * Task Model.
 */
class TaskModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'tasks';

    /**
     * Task status.
     *
     * @var int
     */
    const STATUS_OPEN = 1;
    const STATUS_CLOSED = 0;

    /**
     * Events.
     *
     * @var string
     */
    const EVENT_MOVE_PROJECT = 'task.move.project';
    const EVENT_MOVE_COLUMN = 'task.move.column';
    const EVENT_MOVE_POSITION = 'task.move.position';
    const EVENT_MOVE_SWIMLANE = 'task.move.swimlane';
    const EVENT_UPDATE = 'task.update';
    const EVENT_CREATE = 'task.create';
    const EVENT_CLOSE = 'task.close';
    const EVENT_OPEN = 'task.open';
    const EVENT_CREATE_UPDATE = 'task.create_update';
    const EVENT_ASSIGNEE_CHANGE = 'task.assignee_change';
    const EVENT_OVERDUE = 'task.overdue';
    const EVENT_USER_MENTION = 'task.user.mention';
    const EVENT_DAILY_CRONJOB = 'task.cronjob.daily';

    /**
     * Recurrence: status.
     *
     * @var int
     */
    const RECURRING_STATUS_NONE = 0;
    const RECURRING_STATUS_PENDING = 1;
    const RECURRING_STATUS_PROCESSED = 2;

    /**
     * Recurrence: trigger.
     *
     * @var int
     */
    const RECURRING_TRIGGER_FIRST_COLUMN = 0;
    const RECURRING_TRIGGER_LAST_COLUMN = 1;
    const RECURRING_TRIGGER_CLOSE = 2;

    /**
     * Recurrence: timeframe.
     *
     * @var int
     */
    const RECURRING_TIMEFRAME_DAYS = 0;
    const RECURRING_TIMEFRAME_MONTHS = 1;
    const RECURRING_TIMEFRAME_YEARS = 2;

    /**
     * Recurrence: base date used to calculate new due date.
     *
     * @var int
     */
    const RECURRING_BASEDATE_DUEDATE = 0;
    const RECURRING_BASEDATE_TRIGGERDATE = 1;

    /**
     * Create a task.
     *
     * @param array $values Form values
     *
     * @return int
     */
    public function create(array $values)
    {
        $position = empty($values['position']) ? 0 : $values['position'];
        $tags = [];

        if (isset($values['tags'])) {
            $tags = $values['tags'];
            unset($values['tags']);
        }

        $this->prepare($values);
        $task_id = $this->db->table(self::TABLE)->persist($values);

        if ($task_id !== false) {
            if ($position > 0 && $values['position'] > 1) {
                $this->taskPositionModel->movePosition($values['project_id'], $task_id, $values['column_id'], $position, $values['swimlane_id'], false);
            }

            if (!empty($tags)) {
                $this->taskTagModel->save($values['project_id'], $task_id, $tags);
            }

            $this->queueManager->push($this->taskEventJob->withParams(
                $task_id,
                [self::EVENT_CREATE_UPDATE, self::EVENT_CREATE]
            ));
        }

        return (int) $task_id;
    }

    /**
     * Update a task.
     *
     * @param array $values
     * @param bool  $fire_events
     *
     * @return bool
     */
    public function update(array $values, $fire_events = true)
    {
        $task = $this->taskFinderModel->getById($values['id']);

        if (isset($values['tags'])) {
            $this->taskTagModel->save($task['project_id'], $values['id'], $values['tags']);
            unset($values['tags']);
        }

        $values = $this->dateParser->convert($values, ['date_due']);
        $values = $this->dateParser->convert($values, ['date_started'], true);

        $this->helper->model->removeFields($values, ['id']);
        $this->helper->model->resetFields($values, ['date_due', 'date_started', 'score', 'category_id', 'time_estimated', 'time_spent']);
        $this->helper->model->convertIntegerFields($values, ['priority', 'is_active', 'recurrence_status', 'recurrence_trigger', 'recurrence_factor', 'recurrence_timeframe', 'recurrence_basedate']);

        $values['date_modification'] = time();

        $this->hook->reference('model:task:modification:prepare', $values);

        $result = $this->db->table(self::TABLE)->eq('id', $task['id'])->update($values);

        if ($fire_events && $result) {
            $events = [];

            $diff = array_diff_assoc($values, $task);
            unset($diff['date_modification']);

            if (isset($values['owner_id']) && $task['owner_id'] != $values['owner_id'] && count($diff) === 1) {
                $events[] = self::EVENT_ASSIGNEE_CHANGE;
            } elseif (count($diff) > 0) {
                $events[] = self::EVENT_CREATE_UPDATE;
                $events[] = self::EVENT_UPDATE;
            }

            if (!empty($events)) {
                $this->queueManager->push($this->taskEventJob
                    ->withParams($task['id'], $events, $values, [], $task)
                );
            }
        }

        return $result;
    }

    /**
     * Remove a task.
     *
     * @param int $task_id Task id
     *
     * @return bool
     */
    public function remove($task_id)
    {
        if (!$this->taskFinderModel->exists($task_id)) {
            return false;
        }

        $this->taskFileModel->removeAll($task_id);

        return $this->db->table(self::TABLE)->eq('id', $task_id)->remove();
    }

    /**
     * Get a the task id from a text.
     *
     * Example: "Fix bug #1234" will return 1234
     *
     * @param string $message Text
     *
     * @return int
     */
    public function getTaskIdFromText($message)
    {
        if (preg_match('!#(\d+)!i', $message, $matches) && isset($matches[1])) {
            return $matches[1];
        }

        return 0;
    }

    /**
     * Get task progress based on the column position.
     *
     * @param array $task
     * @param array $columns
     *
     * @return int
     */
    public function getProgress(array $task, array $columns)
    {
        if ($task['is_active'] == self::STATUS_CLOSED) {
            return 100;
        }

        return $task['progress'] ?: 0;
        /*
        $position = 0;

        foreach ($columns as $column_id => $column_title) {
            if ($column_id == $task['column_id']) {
                break;
            }

            $position++;
        }

        return round(($position * 100) / count($columns), 1);
        */
    }

    /**
     * Prepare data.
     *
     * @param array $values Form values
     */
    protected function prepare(array &$values)
    {
        $values = $this->dateParser->convert($values, ['date_due']);
        $values = $this->dateParser->convert($values, ['date_started'], true);

        $this->helper->model->removeFields($values, ['another_task', 'duplicate_multiple_projects']);
        $this->helper->model->resetFields($values, ['creator_id', 'owner_id', 'swimlane_id', 'date_due', 'date_started', 'score', 'progress', 'category_id', 'time_estimated', 'time_spent']);

        if (empty($values['column_id'])) {
            $values['column_id'] = $this->columnModel->getFirstColumnId($values['project_id']);
        }

        if (empty($values['color_id'])) {
            $values['color_id'] = $this->colorModel->getDefaultColor();
        }

        if (empty($values['title'])) {
            $values['title'] = t('Untitled');
        }

        if ($this->userSession->isLogged()) {
            $values['creator_id'] = $this->userSession->getId();
        }

        $values['swimlane_id'] = empty($values['swimlane_id']) ? 0 : $values['swimlane_id'];
        $values['date_creation'] = time();
        $values['date_modification'] = $values['date_creation'];
        $values['date_moved'] = $values['date_creation'];
        $values['position'] = $this->taskFinderModel->countByColumnAndSwimlaneId($values['project_id'], $values['column_id'], $values['swimlane_id']) + 1;

        $this->hook->reference('model:task:creation:prepare', $values);
    }
}
