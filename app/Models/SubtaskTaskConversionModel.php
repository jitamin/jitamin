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
 * Class SubtaskTaskConversionModel.
 */
class SubtaskTaskConversionModel extends Base
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

        $task_id = $this->taskCreationModel->create([
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
