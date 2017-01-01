<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Analytic;

use Jitamin\Core\Base;

/**
 * Task Distribution.
 */
class TaskDistributionAnalytic extends Base
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
        $metrics = [];
        $total = 0;
        $columns = $this->columnModel->getAll($project_id);

        foreach ($columns as $column) {
            $nb_tasks = $this->taskFinderModel->countByColumnId($project_id, $column['id']);
            $total += $nb_tasks;

            $metrics[] = [
                'column_title' => $column['title'],
                'nb_tasks'     => $nb_tasks,
            ];
        }

        if ($total === 0) {
            return [];
        }

        foreach ($metrics as &$metric) {
            $metric['percentage'] = round(($metric['nb_tasks'] * 100) / $total, 2);
        }

        return $metrics;
    }
}
