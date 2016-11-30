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

use Hiject\Filter\TaskIdExclusionFilter;
use Hiject\Filter\TaskIdFilter;
use Hiject\Filter\TaskProjectsFilter;
use Hiject\Filter\TaskTitleFilter;
use Hiject\Formatter\TaskAutoCompleteFormatter;

/**
 * Task Ajax Controller.
 */
class TaskAjaxController extends BaseController
{
    /**
     * Task auto-completion (Ajax).
     */
    public function autocomplete()
    {
        $search = $this->request->getStringParam('term');
        $project_ids = $this->projectPermissionModel->getActiveProjectIds($this->userSession->getId());
        $exclude_task_id = $this->request->getIntegerParam('exclude_task_id');

        if (empty($project_ids)) {
            $this->response->json([]);
        } else {
            $filter = $this->taskQuery->withFilter(new TaskProjectsFilter($project_ids));

            if (!empty($exclude_task_id)) {
                $filter->withFilter(new TaskIdExclusionFilter([$exclude_task_id]));
            }

            if (ctype_digit($search)) {
                $filter->withFilter(new TaskIdFilter($search));
            } else {
                $filter->withFilter(new TaskTitleFilter($search));
            }

            $this->response->json($filter->format(new TaskAutoCompleteFormatter($this->container)));
        }
    }
}
