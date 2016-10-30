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

use Hiject\Core\Base;
use Hiject\Core\Filter\QueryBuilder;
use Hiject\Filter\TaskDueDateRangeFilter;
use Hiject\Formatter\TaskICalFormatter;
use Eluceo\iCal\Component\Calendar as iCalendar;

/**
 * ICal Helper
 */
class ICalHelper extends Base
{
    /**
     * Get formatted calendar task due events
     *
     * @access public
     * @param  QueryBuilder  $queryBuilder
     * @param  iCalendar     $calendar
     * @param  string        $start
     * @param  string        $end
     */
    public function addTaskDateDueEvents(QueryBuilder $queryBuilder, iCalendar $calendar, $start, $end)
    {
        $queryBuilder->withFilter(new TaskDueDateRangeFilter(array($start, $end)));

        $formatter = new TaskICalFormatter($this->container);
        $formatter->setColumns('date_due');
        $formatter->setCalendar($calendar);
        $formatter->withQuery($queryBuilder->getQuery());
        $formatter->addFullDayEvents();
    }
}
