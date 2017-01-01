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
 * Class SubtaskTaskConversionModel.
 */
class SubtaskTaskConversionModel extends Model
{
    /**
     * Convert a subtask to a task.
     *
     * @param int $project_id
     * @param int $subtask_id
     *
     * @return int
     */
    public function convertToTask($project_id, $subtask_id)
    {
        $subtask = $this->subtaskModel->getById($subtask_id);

        $task_id = $this->taskModel->create([
            'project_id'     => $project_id,
            'title'          => $subtask['title'],
            'time_estimated' => $subtask['time_estimated'],
            'time_spent'     => $subtask['time_spent'],
            'owner_id'       => $subtask['user_id'],
        ]);

        if ($task_id !== false) {
            $this->subtaskModel->remove($subtask_id);
        }

        return $task_id;
    }
}
