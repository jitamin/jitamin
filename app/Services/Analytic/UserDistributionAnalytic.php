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

/**
 * User Distribution.
 */
class UserDistributionAnalytic extends Base
{
    /**
     * Build Report.
     *
     * @param int $project_id
     *
     * @return array
     */
    public function build($project_id)
    {
        $metrics = [];
        $total = 0;
        $tasks = $this->taskFinderModel->getAll($project_id);
        $users = $this->projectUserRoleModel->getAssignableUsersList($project_id);

        foreach ($tasks as $task) {
            $user = isset($users[$task['owner_id']]) ? $users[$task['owner_id']] : $users[0];
            $total++;

            if (!isset($metrics[$user])) {
                $metrics[$user] = [
                    'nb_tasks'   => 0,
                    'percentage' => 0,
                    'user'       => $user,
                ];
            }

            $metrics[$user]['nb_tasks']++;
        }

        if ($total === 0) {
            return [];
        }

        foreach ($metrics as &$metric) {
            $metric['percentage'] = round(($metric['nb_tasks'] * 100) / $total, 2);
        }

        ksort($metrics);

        return array_values($metrics);
    }
}
