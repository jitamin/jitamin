<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller;

use Jitamin\Filter\TaskAssigneeFilter;
use Jitamin\Filter\TaskProjectFilter;
use Jitamin\Filter\TaskStatusFilter;
use Jitamin\Model\TaskModel;

/**
 * Calendar Controller.
 */
class CalendarController extends BaseController
{
    /**
     * Show calendar view for projects.
     */
    public function show()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->app('calendar/show', [
            'project'        => $project,
            'title'          => $project['name'],
            'description'    => $this->helper->projectHeader->getDescription($project),
            'check_interval' => $this->settingModel->get('board_private_refresh_interval'),
        ]));
    }

    /**
     * Get tasks to display on the calendar (project view).
     */
    public function project()
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $start = $this->request->getStringParam('start');
        $end = $this->request->getStringParam('end');
        $search = $this->userSession->getFilters($project_id);
        $queryBuilder = $this->taskLexer->build($search)->withFilter(new TaskProjectFilter($project_id));

        $events = $this->helper->calendar->getTaskDateDueEvents(clone $queryBuilder, $start, $end);
        $events = array_merge($events, $this->helper->calendar->getTaskEvents(clone $queryBuilder, $start, $end));

        $events = $this->hook->merge('controller:calendar:project:events', $events, [
            'project_id' => $project_id,
            'start'      => $start,
            'end'        => $end,
        ]);

        $this->response->json($events);
    }

    /**
     * Get tasks to display on the calendar (user view).
     */
    public function user()
    {
        $user_id = $this->request->getIntegerParam('user_id');
        $start = $this->request->getStringParam('start');
        $end = $this->request->getStringParam('end');
        $queryBuilder = $this->taskQuery
            ->withFilter(new TaskAssigneeFilter($user_id))
            ->withFilter(new TaskStatusFilter(TaskModel::STATUS_OPEN));

        $events = $this->helper->calendar->getTaskDateDueEvents(clone $queryBuilder, $start, $end);
        $events = array_merge($events, $this->helper->calendar->getTaskEvents(clone $queryBuilder, $start, $end));

        if ($this->settingModel->get('calendar_user_subtasks_time_tracking') == 1) {
            $events = array_merge($events, $this->helper->calendar->getSubtaskTimeTrackingEvents($user_id, $start, $end));
        }

        $events = $this->hook->merge('controller:calendar:user:events', $events, [
            'user_id' => $user_id,
            'start'   => $start,
            'end'     => $end,
        ]);

        $this->response->json($events);
    }

    /**
     * Update task due date.
     */
    public function store()
    {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $values = $this->request->getJson();

            $this->taskModel->update([
                'id'       => $values['task_id'],
                'date_due' => substr($values['date_due'], 0, 10),
            ]);
        }
    }
}
