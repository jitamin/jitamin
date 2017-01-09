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

use Jitamin\Foundation\Database\Model;

/**
 * Class SubtaskPositionModel.
 */
class SubtaskPositionModel extends Model
{
    /**
     * Change subtask position.
     *
     * @param int $task_id
     * @param int $subtask_id
     * @param int $position
     *
     * @return bool
     */
    public function changePosition($task_id, $subtask_id, $position)
    {
        if ($position < 1 || $position > $this->db->table(SubtaskModel::TABLE)->eq('task_id', $task_id)->count()) {
            return false;
        }

        $subtask_ids = $this->db->table(SubtaskModel::TABLE)->eq('task_id', $task_id)->neq('id', $subtask_id)->asc('position')->findAllByColumn('id');
        $offset = 1;
        $results = [];

        foreach ($subtask_ids as $current_subtask_id) {
            if ($offset == $position) {
                $offset++;
            }

            $results[] = $this->db->table(SubtaskModel::TABLE)->eq('id', $current_subtask_id)->update(['position' => $offset]);
            $offset++;
        }

        $results[] = $this->db->table(SubtaskModel::TABLE)->eq('id', $subtask_id)->update(['position' => $position]);

        return !in_array(false, $results, true);
    }
}
