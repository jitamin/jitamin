<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller;

/**
 * Dashboard Controller.
 */
class DashboardController extends BaseController
{
    /**
     * Dashboard overview.
     */
    public function index()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/index', [
            'title'             => t('Dashboard for %s', $this->helper->user->getFullname($user)),
            'paginator'         => $this->projectPagination->getDashboardPaginator($user['id'], 'index', 10),
            'user'              => $user,
        ]));
    }

    /**
     * My tasks.
     */
    public function tasks()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/tasks', [
            'title'     => t('Tasks overview for %s', $this->helper->user->getFullname($user)),
            'paginator' => $this->taskPagination->getDashboardPaginator($user['id'], 'tasks', 50),
            'user'      => $user,
        ]));
    }

    /**
     * My subtasks.
     */
    public function subtasks()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/subtasks', [
            'title'     => t('Subtasks overview for %s', $this->helper->user->getFullname($user)),
            'paginator' => $this->subtaskPagination->getDashboardPaginator($user['id'], 'subtasks', 50),
            'user'      => $user,
        ]));
    }

    /**
     * My stars.
     */
    public function stars()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/stars', [
            'title'             => t('Dashboard for %s', $this->helper->user->getFullname($user)),
            'paginator'         => $this->starPagination->getDashboardPaginator($user['id'], 'stars', 10),
            'user'              => $user,
        ]));
    }

    /**
     * My activities.
     */
    public function activities()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/activities', [
            'title'  => t('Activities for %s', $this->helper->user->getFullname($user)),
            'events' => $this->helper->projectActivity->getProjectsEvents($this->projectPermissionModel->getActiveProjectIds($user['id']), 100),
            'user'   => $user,
        ]));
    }

    /**
     * My calendar.
     */
    public function calendar()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/calendar', [
            'title' => t('Calendar for %s', $this->helper->user->getFullname($user)),
            'user'  => $user,
        ]));
    }

    /**
     * My notifications.
     */
    public function notifications()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/notifications', [
            'title'         => t('Notifications for %s', $this->helper->user->getFullname($user)),
            'notifications' => $this->userUnreadNotificationModel->getAll($user['id']),
            'user'          => $user,
        ]));
    }
}
