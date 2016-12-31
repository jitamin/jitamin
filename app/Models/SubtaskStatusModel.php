<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Core\Database\Model;

/**
 * Class SubtaskStatusModel.
 */
class SubtaskStatusModel extends Model
{
    /**
     * Get the subtask in progress for this user.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getSubtaskInProgress($user_id)
    {
        return $this->db->table(SubtaskModel::TABLE)
            ->eq('status', SubtaskModel::STATUS_INPROGRESS)
            ->eq('user_id', $user_id)
            ->findOne();
    }

    /**
     * Return true if the user have a subtask in progress.
     *
     * @param int $user_id
     *
     * @return bool
     */
    public function hasSubtaskInProgress($user_id)
    {
        return $this->settingModel->get('subtask_restriction') == 1 &&
            $this->db->table(SubtaskModel::TABLE)
                ->eq('status', SubtaskModel::STATUS_INPROGRESS)
                ->eq('user_id', $user_id)
                ->exists();
    }

    /**
     * Change the status of subtask.
     *
     * @param int $subtask_id
     *
     * @return bool|int
     */
    public function toggleStatus($subtask_id)
    {
        $subtask = $this->subtaskModel->getById($subtask_id);
        $status = ($subtask['status'] + 1) % 3;

        $values = [
            'id'      => $subtask['id'],
            'status'  => $status,
            'task_id' => $subtask['task_id'],
        ];

        if (empty($subtask['user_id']) && $this->userSession->isLogged()) {
            $values['user_id'] = $this->userSession->getId();
            $subtask['user_id'] = $values['user_id'];
        }

        $this->subtaskTimeTrackingModel->toggleTimer($subtask_id, $subtask['user_id'], $status);

        return $this->subtaskModel->update($values) ? $status : false;
    }

    /**
     * Close all subtasks of a task.
     *
     * @param int $task_id
     *
     * @return bool
     */
    public function closeAll($task_id)
    {
        return $this->db
            ->table(SubtaskModel::TABLE)
            ->eq('task_id', $task_id)
            ->update(['status' => SubtaskModel::STATUS_DONE]);
    }
}
