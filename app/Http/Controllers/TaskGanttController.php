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

use Hiject\Filter\TaskProjectFilter;
use Hiject\Formatter\TaskGanttFormatter;
use Hiject\Model\TaskModel;

/**
 * Tasks Gantt Controller.
 */
class TaskGanttController extends BaseController
{
    /**
     * Show Gantt chart for one project.
     */
    public function show()
    {
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);
        $sorting = $this->request->getStringParam('sorting', 'board');
        $filter = $this->taskLexer->build($search)->withFilter(new TaskProjectFilter($project['id']));

        if ($sorting === 'date') {
            $filter->getQuery()->asc(TaskModel::TABLE.'.date_started')->asc(TaskModel::TABLE.'.date_creation');
        } else {
            $filter->getQuery()->asc('column_position')->asc(TaskModel::TABLE.'.position');
        }

        $this->response->html($this->helper->layout->app('task_gantt/show', [
            'project'     => $project,
            'title'       => $project['name'],
            'description' => $this->helper->projectHeader->getDescription($project),
            'sorting'     => $sorting,
            'tasks'       => $filter->format(new TaskGanttFormatter($this->container)),
        ]));
    }

    /**
     * Save new task start date and due date.
     */
    public function save()
    {
        $this->getProject();
        $values = $this->request->getJson();

        $result = $this->taskModificationModel->update([
            'id'           => $values['id'],
            'date_started' => strtotime($values['start']),
            'date_due'     => strtotime($values['end']),
        ]);

        if (!$result) {
            $this->response->json(['message' => 'Unable to save task'], 400);
        } else {
            $this->response->json(['message' => 'OK'], 201);
        }
    }
}
