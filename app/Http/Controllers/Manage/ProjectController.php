<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Manage;

use Jitamin\Filter\ProjectIdsFilter;
use Jitamin\Filter\ProjectStatusFilter;
use Jitamin\Filter\ProjectTypeFilter;
use Jitamin\Formatter\ProjectGanttFormatter;
use Jitamin\Http\Controllers\Controller;
use Jitamin\Model\ProjectModel;

/**
 * Class ProjectController.
 */
class ProjectController extends Controller
{
    /**
     * List of projects.
     */
    public function index()
    {
        if ($this->userSession->isAdmin()) {
            $project_ids = $this->projectModel->getAllIds();
        } else {
            $project_ids = $this->projectPermissionModel->getProjectIds($this->userSession->getId());
        }

        $nb_projects = count($project_ids);

        $paginator = $this->paginator
            ->setUrl('Manage/ProjectController', 'index')
            ->setMax(20)
            ->setOrder('id')
            ->setDirection('DESC')
            ->setQuery($this->projectModel->getQueryColumnStats($project_ids))
            ->calculate();

        $this->response->html($this->helper->layout->app('manage/projects', [
            'paginator'   => $paginator,
            'nb_projects' => $nb_projects,
            'title'       => t('Manage').' &raquo; '.t('Projects list'),
        ]));
    }

    /**
     * Display Gantt chart for all projects.
     */
    public function gantt()
    {
        $project_ids = $this->projectPermissionModel->getActiveProjectIds($this->userSession->getId());
        $filter = $this->projectQuery
            ->withFilter(new ProjectTypeFilter(ProjectModel::TYPE_TEAM))
            ->withFilter(new ProjectStatusFilter(ProjectModel::ACTIVE))
            ->withFilter(new ProjectIdsFilter($project_ids));

        $filter->getQuery()->asc(ProjectModel::TABLE.'.start_date');

        $this->response->html($this->helper->layout->app('manage/gantt', [
            'projects' => $filter->format(new ProjectGanttFormatter($this->container)),
            'title'    => t('Manage').' &raquo; '.t('Projects Gantt chart'),
        ]));
    }
}
