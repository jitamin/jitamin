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

use Eluceo\iCal\Component\Calendar as iCalendar;
use Jitamin\Filter\TaskDueDateRangeFilter;
use Jitamin\Formatter\TaskICalFormatter;
use Jitamin\Foundation\Base;
use Jitamin\Foundation\Filter\QueryBuilder;

/**
 * ICal Helper.
 */
class ICalHelper extends Base
{
    /**
     * Get formatted calendar task due events.
     *
     * @param QueryBuilder $queryBuilder
     * @param iCalendar    $calendar
     * @param string       $start
     * @param string       $end
     */
    public function addTaskDateDueEvents(QueryBuilder $queryBuilder, iCalendar $calendar, $start, $end)
    {
        $queryBuilder->withFilter(new TaskDueDateRangeFilter([$start, $end]));

        $formatter = new TaskICalFormatter($this->container);
        $formatter->setColumns('date_due');
        $formatter->setCalendar($calendar);
        $formatter->withQuery($queryBuilder->getQuery());
        $formatter->addFullDayEvents();
    }
}
