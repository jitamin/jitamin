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

use Hiject\Filter\ProjectIdsFilter;
use Hiject\Filter\ProjectStatusFilter;
use Hiject\Filter\ProjectTypeFilter;
use Hiject\Formatter\ProjectGanttFormatter;
use Hiject\Model\ProjectModel;

/**
 * Projects Gantt Controller.
 */
class ProjectGanttController extends BaseController
{
    /**
     * Show Gantt chart for all projects.
     */
    public function show()
    {
        $project_ids = $this->projectPermissionModel->getActiveProjectIds($this->userSession->getId());
        $filter = $this->projectQuery
            ->withFilter(new ProjectTypeFilter(ProjectModel::TYPE_TEAM))
            ->withFilter(new ProjectStatusFilter(ProjectModel::ACTIVE))
            ->withFilter(new ProjectIdsFilter($project_ids));

        $filter->getQuery()->asc(ProjectModel::TABLE.'.start_date');

        $this->response->html($this->helper->layout->app('project_gantt/show', [
            'projects' => $filter->format(new ProjectGanttFormatter($this->container)),
            'title'    => t('Gantt chart for all projects'),
        ]));
    }

    /**
     * Save new project start date and end date.
     */
    public function save()
    {
        $values = $this->request->getJson();

        $result = $this->projectModel->update([
            'id'         => $values['id'],
            'start_date' => $this->dateParser->getIsoDate(strtotime($values['start'])),
            'end_date'   => $this->dateParser->getIsoDate(strtotime($values['end'])),
        ]);

        if (!$result) {
            $this->response->json(['message' => 'Unable to save project'], 400);
        } else {
            $this->response->json(['message' => 'OK'], 201);
        }
    }
}
