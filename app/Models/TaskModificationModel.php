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

use Hiject\Core\Base;

/**
 * Task Modification.
 */
class TaskModificationModel extends Base
{
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

        $this->updateTags($values, $task);
        $this->prepare($values);
        $result = $this->db->table(TaskModel::TABLE)->eq('id', $task['id'])->update($values);

        if ($fire_events && $result) {
            $this->fireEvents($task, $values);
        }

        return $result;
    }

    /**
     * Fire events.
     *
     * @param array $task
     * @param array $changes
     */
    protected function fireEvents(array $task, array $changes)
    {
        $events = [];

        if ($this->isAssigneeChanged($task, $changes)) {
            $events[] = TaskModel::EVENT_ASSIGNEE_CHANGE;
        } elseif ($this->isModified($task, $changes)) {
            $events[] = TaskModel::EVENT_CREATE_UPDATE;
            $events[] = TaskModel::EVENT_UPDATE;
        }

        if (!empty($events)) {
            $this->queueManager->push($this->taskEventJob
                ->withParams($task['id'], $events, $changes, [], $task)
            );
        }
    }

    /**
     * Return true if the task have been modified.
     *
     * @param array $task
     * @param array $changes
     *
     * @return bool
     */
    protected function isModified(array $task, array $changes)
    {
        $diff = array_diff_assoc($changes, $task);
        unset($diff['date_modification']);

        return count($diff) > 0;
    }

    /**
     * Return true if the field is the only modified value.
     *
     * @param array $task
     * @param array $changes
     *
     * @return bool
     */
    protected function isAssigneeChanged(array $task, array $changes)
    {
        $diff = array_diff_assoc($changes, $task);
        unset($diff['date_modification']);

        return isset($changes['owner_id']) && $task['owner_id'] != $changes['owner_id'] && count($diff) === 1;
    }

    /**
     * Prepare data before task modification.
     *
     * @param array $values
     */
    protected function prepare(array &$values)
    {
        $values = $this->dateParser->convert($values, ['date_due']);
        $values = $this->dateParser->convert($values, ['date_started'], true);

        $this->helper->model->removeFields($values, ['id']);
        $this->helper->model->resetFields($values, ['date_due', 'date_started', 'score', 'category_id', 'time_estimated', 'time_spent']);
        $this->helper->model->convertIntegerFields($values, ['priority', 'is_active', 'recurrence_status', 'recurrence_trigger', 'recurrence_factor', 'recurrence_timeframe', 'recurrence_basedate']);

        $values['date_modification'] = time();

        $this->hook->reference('model:task:modification:prepare', $values);
    }

    /**
     * Update tags.
     *
     * @param array $values
     * @param array $original_task
     */
    protected function updateTags(array &$values, array $original_task)
    {
        if (isset($values['tags'])) {
            $this->taskTagModel->save($original_task['project_id'], $values['id'], $values['tags']);
            unset($values['tags']);
        }
    }
}
