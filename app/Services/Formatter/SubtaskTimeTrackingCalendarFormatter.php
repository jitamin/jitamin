<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Formatter;

use Jitamin\Foundation\Filter\FormatterInterface;

/**
 * Subtask time-tracking calendar formatter.
 */
class SubtaskTimeTrackingCalendarFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Format calendar events.
     *
     * @return array
     */
    public function format()
    {
        $events = [];

        foreach ($this->query->findAll() as $row) {
            $user = isset($row['username']) ? ' ('.($row['user_fullname'] ?: $row['username']).')' : '';

            $events[] = [
                'id'              => $row['id'],
                'subtask_id'      => $row['subtask_id'],
                'title'           => t('#%d', $row['task_id']).' '.$row['subtask_title'].$user,
                'start'           => date('Y-m-d\TH:i:s', $row['start']),
                'end'             => date('Y-m-d\TH:i:s', $row['end'] ?: time()),
                'backgroundColor' => $this->colorModel->getBorderLeftColor($row['color_id']),
                'borderColor'     => $this->colorModel->getBorderWidth($row['color_id']),
                'textColor'       => 'black',
                'url'             => $this->helper->url->to('Task/TaskController', 'show', ['task_id' => $row['task_id'], 'project_id' => $row['project_id']]),
                'editable'        => false,
            ];
        }

        return $events;
    }
}
