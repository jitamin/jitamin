<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Formatter;

use Hiject\Core\Filter\FormatterInterface;

/**
 * Gantt chart formatter for projects.
 */
class ProjectGanttFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Format projects to be displayed in the Gantt chart.
     *
     * @return array
     */
    public function format()
    {
        $projects = $this->query->findAll();
        $colors = $this->colorModel->getDefaultColors();
        $bars = [];

        foreach ($projects as $project) {
            $start = empty($project['start_date']) ? time() : strtotime($project['start_date']);
            $end = empty($project['end_date']) ? $start : strtotime($project['end_date']);
            $color = next($colors) ?: reset($colors);

            $bars[] = [
                'type'  => 'project',
                'id'    => $project['id'],
                'title' => $project['name'],
                'start' => [
                    (int) date('Y', $start),
                    (int) date('n', $start),
                    (int) date('j', $start),
                ],
                'end' => [
                    (int) date('Y', $end),
                    (int) date('n', $end),
                    (int) date('j', $end),
                ],
                'link'        => $this->helper->url->href('ProjectSettingsController', 'show', ['project_id' => $project['id']]),
                'board_link'  => $this->helper->url->href('BoardController', 'show', ['project_id' => $project['id']]),
                'gantt_link'  => $this->helper->url->href('TaskGanttController', 'show', ['project_id' => $project['id']]),
                'color'       => $color,
                'not_defined' => empty($project['start_date']) || empty($project['end_date']),
                'users'       => $this->projectUserRoleModel->getAllUsersGroupedByRole($project['id']),
            ];
        }

        return $bars;
    }
}
