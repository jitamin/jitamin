<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Analytic;

use Hiject\Core\Base;
use Hiject\Model\TaskModel;

/**
 * Average Lead and Cycle Time.
 */
class AverageLeadCycleTimeAnalytic extends Base
{
    /**
     * Build report.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function build($project_id)
    {
        $stats = [
            'count'            => 0,
            'total_lead_time'  => 0,
            'total_cycle_time' => 0,
            'avg_lead_time'    => 0,
            'avg_cycle_time'   => 0,
        ];

        $tasks = $this->getTasks($project_id);

        foreach ($tasks as &$task) {
            $stats['count']++;
            $stats['total_lead_time'] += $this->calculateLeadTime($task);
            $stats['total_cycle_time'] += $this->calculateCycleTime($task);
        }

        $stats['avg_lead_time'] = $this->calculateAverage($stats, 'total_lead_time');
        $stats['avg_cycle_time'] = $this->calculateAverage($stats, 'total_cycle_time');

        return $stats;
    }

    /**
     * Calculate average.
     *
     * @param array  &$stats
     * @param string $field
     *
     * @return float
     */
    private function calculateAverage(array &$stats, $field)
    {
        if ($stats['count'] > 0) {
            return (int) ($stats[$field] / $stats['count']);
        }

        return 0;
    }

    /**
     * Calculate lead time.
     *
     * @param array &$task
     *
     * @return int
     */
    private function calculateLeadTime(array &$task)
    {
        $end = $task['date_completed'] ?: time();
        $start = $task['date_creation'];

        return $end - $start;
    }

    /**
     * Calculate cycle time.
     *
     * @param array &$task
     *
     * @return int
     */
    private function calculateCycleTime(array &$task)
    {
        $end = (int) $task['date_completed'] ?: time();
        $start = (int) $task['date_started'];

        // Start date can be in the future when defined with the Gantt chart
        if ($start > 0 && $end > $start) {
            return $end - $start;
        }

        return 0;
    }

    /**
     * Get the 1000 last created tasks.
     *
     * @param int $project_id
     *
     * @return array
     */
    private function getTasks($project_id)
    {
        return $this->db
            ->table(TaskModel::TABLE)
            ->columns('date_completed', 'date_creation', 'date_started')
            ->eq('project_id', $project_id)
            ->desc('id')
            ->limit(1000)
            ->findAll();
    }
}
