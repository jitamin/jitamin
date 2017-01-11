<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers;

use Eluceo\iCal\Component\Calendar as iCalendar;
use Jitamin\Filter\TaskAssigneeFilter;
use Jitamin\Filter\TaskProjectFilter;
use Jitamin\Filter\TaskStatusFilter;
use Jitamin\Formatter\TaskICalFormatter;
use Jitamin\Foundation\Exceptions\AccessForbiddenException;
use Jitamin\Foundation\Filter\QueryBuilder;
use Jitamin\Model\TaskModel;

/**
 * iCalendar Controller.
 */
class ICalendarController extends Controller
{
    /**
     * Get user iCalendar.
     */
    public function user()
    {
        $token = $this->request->getStringParam('token');
        $user = $this->userModel->getByToken($token);

        // Token verification
        if (empty($user)) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }

        // Common filter
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->withQuery($this->taskFinderModel->getICalQuery())
            ->withFilter(new TaskStatusFilter(TaskModel::STATUS_OPEN))
            ->withFilter(new TaskAssigneeFilter($user['id']));

        // Calendar properties
        $calendar = new iCalendar('Jitamin');
        $calendar->setName($user['name'] ?: $user['username']);
        $calendar->setDescription($user['name'] ?: $user['username']);
        $calendar->setPublishedTTL('PT1H');

        $this->renderCalendar($queryBuilder, $calendar);
    }

    /**
     * Get project iCalendar.
     */
    public function project()
    {
        $token = $this->request->getStringParam('token');
        $project = $this->projectModel->getByToken($token);

        // Token verification
        if (empty($project)) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }

        // Common filter
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->withQuery($this->taskFinderModel->getICalQuery())
            ->withFilter(new TaskStatusFilter(TaskModel::STATUS_OPEN))
            ->withFilter(new TaskProjectFilter($project['id']));

        // Calendar properties
        $calendar = new iCalendar('Jitamin');
        $calendar->setName($project['name']);
        $calendar->setDescription($project['name']);
        $calendar->setPublishedTTL('PT1H');

        $this->renderCalendar($queryBuilder, $calendar);
    }

    /**
     * Common method to render iCal events.
     *
     * @param QueryBuilder $queryBuilder
     * @param iCalendar    $calendar
     */
    protected function renderCalendar(QueryBuilder $queryBuilder, iCalendar $calendar)
    {
        $start = $this->request->getStringParam('start', strtotime('-2 month'));
        $end = $this->request->getStringParam('end', strtotime('+6 months'));

        $this->helper->ical->addTaskDateDueEvents($queryBuilder, $calendar, $start, $end);

        $formatter = new TaskICalFormatter($this->container);
        $this->response->ical($formatter->setCalendar($calendar)->format());
    }
}
