<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Formatter;

use Jitamin\Core\Filter\FormatterInterface;

/**
 * Task Gantt Formatter.
 */
class TaskGanttFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Local cache for project columns.
     *
     * @var array
     */
    private $columns = [];

    /**
     * Apply formatter.
     *
     * @return array
     */
    public function format()
    {
        $bars = [];

        foreach ($this->query->findAll() as $task) {
            $bars[] = $this->formatTask($task);
        }

        return $bars;
    }

    /**
     * Format a single task.
     *
     * @param array $task
     *
     * @return array
     */
    private function formatTask(array $task)
    {
        if (!isset($this->columns[$task['project_id']])) {
            $this->columns[$task['project_id']] = $this->columnModel->getList($task['project_id']);
        }

        $start = $task['date_started'] ?: time();
        $end = $task['date_due'] ?: $start;

        return [
            'type'  => 'task',
            'id'    => $task['id'],
            'title' => $task['title'],
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
            'column_title' => $task['column_name'],
            'assignee'     => $task['assignee_name'] ?: $task['assignee_username'],
            'progress'     => $this->taskModel->getProgress($task, $this->columns[$task['project_id']]).'%',
            'link'         => $this->helper->url->href('Task/TaskController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]),
            'color'        => $this->colorModel->getColorProperties($task['color_id']),
            'not_defined'  => empty($task['date_due']) || empty($task['date_started']),
        ];
    }
}
