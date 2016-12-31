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
 * Task Analytic.
 */
class TaskAnalyticModel extends Model
{
    /**
     * Get the time between date_creation and date_completed or now if empty.
     *
     * @param array $task
     *
     * @return int
     */
    public function getLeadTime(array $task)
    {
        return ($task['date_completed'] ?: time()) - $task['date_creation'];
    }

    /**
     * Get the time between date_started and date_completed or now if empty.
     *
     * @param array $task
     *
     * @return int
     */
    public function getCycleTime(array $task)
    {
        if (empty($task['date_started'])) {
            return 0;
        }

        return ($task['date_completed'] ?: time()) - $task['date_started'];
    }

    /**
     * Get the average time spent in each column.
     *
     * @param array $task
     *
     * @return array
     */
    public function getTimeSpentByColumn(array $task)
    {
        $result = [];
        $columns = $this->columnModel->getList($task['project_id']);
        $sums = $this->transitionModel->getTimeSpentByTask($task['id']);

        foreach ($columns as $column_id => $column_title) {
            $time_spent = isset($sums[$column_id]) ? $sums[$column_id] : 0;

            if ($task['column_id'] == $column_id) {
                $time_spent += ($task['date_completed'] ?: time()) - $task['date_moved'];
            }

            $result[] = [
                'id'         => $column_id,
                'title'      => $column_title,
                'time_spent' => $time_spent,
            ];
        }

        return $result;
    }
}
