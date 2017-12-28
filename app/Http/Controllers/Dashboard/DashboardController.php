<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Dashboard;

use Jitamin\Http\Controllers\Controller;
use Jitamin\Model\SubtaskModel;
use Jitamin\Model\TaskModel;

/**
 * Dashboard Controller.
 */
class DashboardController extends Controller
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

        $query = $this->taskFinderModel->getUserQuery($user['id']);
        $this->hook->reference('pagination:dashboard:task:query', $query);

        $paginator = $this->paginator
            ->setUrl('Dashboard/DashboardController', 'tasks', ['pagination' => 'tasks', 'user_id' => $user['id']])
            ->setMax(20)
            ->setOrder(TaskModel::TABLE.'.id')
            ->setDirection('DESC')
            ->setQuery($query)
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'tasks');

        $this->response->html($this->helper->layout->dashboard('dashboard/tasks', [
            'title'     => t('My tasks'),
            'paginator' => $paginator,
            'user'      => $user,
        ]));
    }

    /**
     * My subtasks.
     */
    public function subtasks()
    {
        $user = $this->getUser();

        $query = $this->subtaskModel->getUserQuery($user['id'], [SubtaskModel::STATUS_TODO, SubtaskModel::STATUS_INPROGRESS]);
        $this->hook->reference('pagination:dashboard:subtask:query', $query);

        $paginator = $this->paginator
            ->setUrl('Dashboard/DashboardController', 'subtasks', ['pagination' => 'subtasks', 'user_id' => $user['id']])
            ->setMax(20)
            ->setOrder(TaskModel::TABLE.'.id')
            ->setDirection('DESC')
            ->setQuery($query)
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'subtasks');

        $this->response->html($this->helper->layout->dashboard('dashboard/subtasks', [
            'title'     => t('My subtasks'),
            'paginator' => $paginator,
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
     * My slider.
     */
    public function slider()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->app('dashboard/slider', [
            'title'            => t('My slider'),
            'starred_projects' => $this->prepareForSlider($this->projectStarModel->getProjectIds($user['id'])),
            'recent_projects'  => $this->prepareForSlider($this->userSession->getRecentProjects()),
            'user'             => $user,
        ]));
    }

    /**
     * Prepare data for slider.
     *
     * @param array $projectIds
     */
    protected function prepareForSlider(array $projectIds)
    {
        $projects = $this->projectModel->getAllByIds($projectIds);

        $old = $new = [];
        foreach ($projects as $item) {
            $old[$item['id']] = $item;
        }

        foreach ($projectIds as $id) {
            if (!isset($old[$id])) {
                continue;
            }
            $new[] = $old[$id];
        }

        return $new;
    }
}
