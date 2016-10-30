<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

use Hiject\Core\Controller\AccessForbiddenException;
use Hiject\Core\Filter\QueryBuilder;
use Hiject\Filter\TaskAssigneeFilter;
use Hiject\Filter\TaskProjectFilter;
use Hiject\Filter\TaskStatusFilter;
use Hiject\Formatter\TaskICalFormatter;
use Hiject\Model\TaskModel;
use Eluceo\iCal\Component\Calendar as iCalendar;

/**
 * iCalendar Controller
 */
class ICalendarController extends BaseController
{
    /**
     * Get user iCalendar
     *
     * @access public
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
        $calendar = new iCalendar('Hiject');
        $calendar->setName($user['name'] ?: $user['username']);
        $calendar->setDescription($user['name'] ?: $user['username']);
        $calendar->setPublishedTTL('PT1H');

        $this->renderCalendar($queryBuilder, $calendar);
    }

    /**
     * Get project iCalendar
     *
     * @access public
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
        $calendar = new iCalendar('Hiject');
        $calendar->setName($project['name']);
        $calendar->setDescription($project['name']);
        $calendar->setPublishedTTL('PT1H');

        $this->renderCalendar($queryBuilder, $calendar);
    }

    /**
     * Common method to render iCal events
     *
     * @access private
     * @param QueryBuilder $queryBuilder
     * @param iCalendar    $calendar
     */
    private function renderCalendar(QueryBuilder $queryBuilder, iCalendar $calendar)
    {
        $start = $this->request->getStringParam('start', strtotime('-2 month'));
        $end = $this->request->getStringParam('end', strtotime('+6 months'));

        $this->helper->ical->addTaskDateDueEvents($queryBuilder, $calendar, $start, $end);

        $formatter = new TaskICalFormatter($this->container);
        $this->response->ical($formatter->setCalendar($calendar)->format());
    }
}
