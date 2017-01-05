<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

use Jitamin\Api\Authorization\SubtaskAuthorization;
use Jitamin\Api\Authorization\TaskAuthorization;

/**
 * Subtask API controller.
 */
class SubtaskProcedure extends BaseProcedure
{
    /**
     * Get a subtask by the id.
     *
     * @param int $subtask_id Subtask id
     *
     * @return array
     */
    public function getSubtask($subtask_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getSubtask', $subtask_id);

        return $this->subtaskModel->getById($subtask_id);
    }

    /**
     * Get all subtasks for a given task.
     *
     * @param int $task_id Task id
     *
     * @return array
     */
    public function getAllSubtasks($task_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getAllSubtasks', $task_id);

        return $this->subtaskModel->getAll($task_id);
    }

    /**
     * Remove.
     *
     * @param int $subtask_id Subtask id
     *
     * @return bool
     */
    public function removeSubtask($subtask_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeSubtask', $subtask_id);

        return $this->subtaskModel->remove($subtask_id);
    }

    /**
     * Create a new subtask.
     *
     * @param int    $task_id
     * @param string $title
     * @param int    $user_id
     * @param int    $time_estimated
     * @param int    $time_spent
     * @param int    $status
     *
     * @return bool|int
     */
    public function createSubtask($task_id, $title, $user_id = 0, $time_estimated = 0, $time_spent = 0, $status = 0)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'createSubtask', $task_id);

        $values = [
            'title'          => $title,
            'task_id'        => $task_id,
            'user_id'        => $user_id,
            'time_estimated' => $time_estimated,
            'time_spent'     => $time_spent,
            'status'         => $status,
        ];

        list($valid) = $this->subtaskValidator->validateCreation($values);

        return $valid ? $this->subtaskModel->create($values) : false;
    }

    /**
     * Create a new subtask.
     *
     * @param int    $id
     * @param int    $task_id
     * @param string $title
     * @param int    $user_id
     * @param int    $time_estimated
     * @param int    $time_spent
     * @param int    $status
     *
     * @return bool
     */
    public function updateSubtask($id, $task_id, $title = null, $user_id = null, $time_estimated = null, $time_spent = null, $status = null)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'updateSubtask', $task_id);

        $values = [
            'id'             => $id,
            'task_id'        => $task_id,
            'title'          => $title,
            'user_id'        => $user_id,
            'time_estimated' => $time_estimated,
            'time_spent'     => $time_spent,
            'status'         => $status,
        ];

        foreach ($values as $key => $value) {
            if (is_null($value)) {
                unset($values[$key]);
            }
        }

        list($valid) = $this->subtaskValidator->validateApiModification($values);

        return $valid && $this->subtaskModel->update($values);
    }
}
