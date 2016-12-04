<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Helper;

use Eluceo\iCal\Component\Calendar as iCalendar;
use Hiject\Core\Base;
use Hiject\Core\Filter\QueryBuilder;
use Hiject\Filter\TaskDueDateRangeFilter;
use Hiject\Formatter\TaskICalFormatter;

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
