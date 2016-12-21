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
 * Calendar event formatter for task filter.
 */
class TaskCalendarFormatter extends BaseTaskCalendarFormatter implements FormatterInterface
{
    /**
     * Full day event flag.
     *
     * @var bool
     */
    private $fullDay = false;

    /**
     * When called calendar events will be full day.
     *
     * @return FormatterInterface
     */
    public function setFullDay()
    {
        $this->fullDay = true;

        return $this;
    }

    /**
     * Transform tasks to calendar events.
     *
     * @return array
     */
    public function format()
    {
        $events = [];

        foreach ($this->query->findAll() as $task) {
            $events[] = [
                'timezoneParam'   => $this->timezoneModel->getCurrentTimezone(),
                'id'              => $task['id'],
                'title'           => t('#%d', $task['id']).' '.$task['title'],
                'backgroundColor' => $this->colorModel->getBackgroundColor($task['color_id']),
                'borderColor'     => $this->colorModel->getBorderColor($task['color_id']),
                'textColor'       => 'black',
                'url'             => $this->helper->url->to('TaskViewController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]),
                'start'           => date($this->getDateTimeFormat(), $task[$this->startColumn]),
                'end'             => date($this->getDateTimeFormat(), $task[$this->endColumn] ?: time()),
                'editable'        => $this->fullDay,
                'allday'          => $this->fullDay,
            ];
        }

        return $events;
    }

    /**
     * Get DateTime format for event.
     *
     * @return string
     */
    private function getDateTimeFormat()
    {
        return $this->fullDay ? 'Y-m-d' : 'Y-m-d\TH:i:s';
    }
}
