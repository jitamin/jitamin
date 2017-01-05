<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Dashboard;

use Jitamin\Controller\BaseController;

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
        list($className, $method) = $this->helper->app->getDashboard(true);
        $controllerObject = new $className($this->container);

        return $controllerObject->{$method}();
    }

    /**
     * My tasks.
     */
    public function tasks()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/tasks', [
            'title'     => t('My tasks'),
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
            'title'     => t('My subtasks'),
            'paginator' => $this->subtaskPagination->getDashboardPaginator($user['id'], 'subtasks', 50),
            'user'      => $user,
        ]));
    }

    /**
     * My activities.
     */
    public function activities()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/activities', [
            'title'  => t('My activities'),
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
            'title' => t('My calendar'),
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
            'title'         => t('My notifications'),
            'notifications' => $this->userUnreadNotificationModel->getAll($user['id']),
            'user'          => $user,
        ]));
    }
}
