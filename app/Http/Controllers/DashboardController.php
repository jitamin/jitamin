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

/**
 * Dashboard Controller
 */
class DashboardController extends BaseController
{
    /**
     * Dashboard overview
     *
     * @access public
     */
    public function show()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/show', [
            'title' => t('Dashboard for %s', $this->helper->user->getFullname($user)),
            'project_paginator' => $this->projectPagination->getDashboardPaginator($user['id'], 'show', 10),
            'events' => $this->helper->projectActivity->getProjectsEvents($this->projectPermissionModel->getActiveProjectIds($user['id']), 10),
            'user' => $user,
        ]));
    }

    /**
     * My tasks
     *
     * @access public
     */
    public function tasks()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/tasks', [
            'title' => t('Tasks overview for %s', $this->helper->user->getFullname($user)),
            'paginator' => $this->taskPagination->getDashboardPaginator($user['id'], 'tasks', 50),
            'user' => $user,
        ]));
    }

    /**
     * My subtasks
     *
     * @access public
     */
    public function subtasks()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/subtasks', [
            'title' => t('Subtasks overview for %s', $this->helper->user->getFullname($user)),
            'paginator' => $this->subtaskPagination->getDashboardPaginator($user['id'], 'subtasks', 50),
            'user' => $user,
        ]));
    }

    /**
     * My projects
     *
     * @access public
     */
    public function projects()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/projects', [
            'title' => t('Projects overview for %s', $this->helper->user->getFullname($user)),
            'paginator' => $this->projectPagination->getDashboardPaginator($user['id'], 'projects', 25),
            'user' => $user,
        ]));
    }

    /**
     * My activity stream
     *
     * @access public
     */
    public function activity()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/activity', [
            'title' => t('Activity stream for %s', $this->helper->user->getFullname($user)),
            'events' => $this->helper->projectActivity->getProjectsEvents($this->projectPermissionModel->getActiveProjectIds($user['id']), 100),
            'user' => $user,
        ]));
    }

    /**
     * My calendar
     *
     * @access public
     */
    public function calendar()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/calendar', [
            'title' => t('Calendar for %s', $this->helper->user->getFullname($user)),
            'user' => $user,
        ]));
    }

    /**
     * My notifications
     *
     * @access public
     */
    public function notifications()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/notifications', [
            'title' => t('Notifications for %s', $this->helper->user->getFullname($user)),
            'notifications' => $this->userUnreadNotificationModel->getAll($user['id']),
            'user' => $user,
        ]));
    }
}
