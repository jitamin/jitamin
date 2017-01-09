<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Helper;

use Jitamin\Filter\TaskDueDateRangeFilter;
use Jitamin\Formatter\SubtaskTimeTrackingCalendarFormatter;
use Jitamin\Formatter\TaskCalendarFormatter;
use Jitamin\Foundation\Base;
use Jitamin\Foundation\Filter\QueryBuilder;

/**
 * Calendar Helper.
 */
class CalendarHelper extends Base
{
    /**
     * Get formatted calendar task due events.
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $start
     * @param string       $end
     *
     * @return array
     */
    public function getTaskDateDueEvents(QueryBuilder $queryBuilder, $start, $end)
    {
        $formatter = new TaskCalendarFormatter($this->container);
        $formatter->setFullDay();
        $formatter->setColumns('date_due');

        return $queryBuilder
            ->withFilter(new TaskDueDateRangeFilter([$start, $end]))
            ->format($formatter);
    }

    /**
     * Get formatted calendar task events.
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $start
     * @param string       $end
     *
     * @return array
     */
    public function getTaskEvents(QueryBuilder $queryBuilder, $start, $end)
    {
        $startColumn = $this->settingModel->get('calendar_project_tasks', 'date_started');

        $queryBuilder->getQuery()->addCondition($this->getCalendarCondition(
            $this->dateParser->getTimestampFromIsoFormat($start),
            $this->dateParser->getTimestampFromIsoFormat($end),
            $startColumn,
            'date_due'
        ));

        $formatter = new TaskCalendarFormatter($this->container);
        $formatter->setColumns($startColumn, 'date_due');

        return $queryBuilder->format($formatter);
    }

    /**
     * Get formatted calendar subtask time tracking events.
     *
     * @param int    $user_id
     * @param string $start
     * @param string $end
     *
     * @return array
     */
    public function getSubtaskTimeTrackingEvents($user_id, $start, $end)
    {
        $formatter = new SubtaskTimeTrackingCalendarFormatter($this->container);

        return $formatter
            ->withQuery($this->subtaskTimeTrackingModel->getUserQuery($user_id)
                ->addCondition($this->getCalendarCondition(
                    $this->dateParser->getTimestampFromIsoFormat($start),
                    $this->dateParser->getTimestampFromIsoFormat($end),
                    'start',
                    'end'
                ))
            )
            ->format();
    }

    /**
     * Build SQL condition for a given time range.
     *
     * @param string $start_time   Start timestamp
     * @param string $end_time     End timestamp
     * @param string $start_column Start column name
     * @param string $end_column   End column name
     *
     * @return string
     */
    public function getCalendarCondition($start_time, $end_time, $start_column, $end_column)
    {
        $start_column = $this->db->escapeIdentifier($start_column);
        $end_column = $this->db->escapeIdentifier($end_column);

        $conditions = [
            "($start_column >= '$start_time' AND $start_column <= '$end_time')",
            "($start_column <= '$start_time' AND $end_column >= '$start_time')",
            "($start_column <= '$start_time' AND ($end_column = '0' OR $end_column IS NULL))",
        ];

        return $start_column.' IS NOT NULL AND '.$start_column.' > 0 AND ('.implode(' OR ', $conditions).')';
    }
}
