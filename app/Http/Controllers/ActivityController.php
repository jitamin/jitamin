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
 * Activity Controller
 */
class ActivityController extends BaseController
{
    /**
     * Activity page for a project
     *
     * @access public
     */
    public function project()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->app('activity/project', [
            'events' => $this->helper->projectActivity->getProjectEvents($project['id']),
            'project' => $project,
            'title' => t('%s\'s activity', $project['name'])
        ]));
    }

    /**
     * Display task activities
     *
     * @access public
     */
    public function task()
    {
        $task = $this->getTask();

        $this->response->html($this->helper->layout->task('activity/task', [
            'title' => $task['title'],
            'task' => $task,
            'project' => $this->projectModel->getById($task['project_id']),
            'events' => $this->helper->projectActivity->getTaskEvents($task['id']),
            'tags' => $this->taskTagModel->getList($task['id']),
        ]));
    }
}
