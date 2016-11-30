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
 * Project Task Priority Model.
 */
class ProjectTaskPriorityModel extends Base
{
    /**
     * Get Priority range from a project.
     *
     * @param array $project
     *
     * @return array
     */
    public function getPriorities(array $project)
    {
        $range = range($project['priority_start'], $project['priority_end']);

        return array_combine($range, $range);
    }

    /**
     * Get task priority settings.
     *
     * @param int $project_id
     *
     * @return array|null
     */
    public function getPrioritySettings($project_id)
    {
        return $this->db
            ->table(ProjectModel::TABLE)
            ->columns('priority_default', 'priority_start', 'priority_end')
            ->eq('id', $project_id)
            ->findOne();
    }

    /**
     * Get default task priority.
     *
     * @param int $project_id
     *
     * @return int
     */
    public function getDefaultPriority($project_id)
    {
        return $this->db->table(ProjectModel::TABLE)->eq('id', $project_id)->findOneColumn('priority_default') ?: 0;
    }

    /**
     * Get priority for a destination project.
     *
     * @param int $dst_project_id
     * @param int $priority
     *
     * @return int
     */
    public function getPriorityForProject($dst_project_id, $priority)
    {
        $settings = $this->getPrioritySettings($dst_project_id);

        if ($priority >= $settings['priority_start'] && $priority <= $settings['priority_end']) {
            return $priority;
        }

        return $settings['priority_default'];
    }
}
