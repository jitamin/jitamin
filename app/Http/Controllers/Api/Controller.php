<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Api;

use Jitamin\Foundation\Base;
use Jitamin\Policy\MethodPolicy;
use Jitamin\Policy\UserPolicy;
use ReflectionClass;

/**
 * Base controller class.
 */
abstract class Controller extends Base
{
    /**
     * Before action.
     *
     * @param string $role
     *
     * @return void
     */
    public function beforeAction($method)
    {
        MethodPolicy::getInstance($this->container)->check($method);
        UserPolicy::getInstance($this->container)->check($this->getClassName(), $method);
    }

    /**
     * Format the given task.
     *
     * @param array $task
     *
     * @return array
     */
    protected function formatTask($task)
    {
        if (!empty($task)) {
            $task['url'] = $this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], '', true);
            $task['color'] = $this->colorModel->getColorProperties($task['color_id']);
        }

        return $task;
    }

    /**
     * Format the given tasks.
     *
     * @param array $tasks
     *
     * @return array
     */
    protected function formatTasks($tasks)
    {
        if (!empty($tasks)) {
            foreach ($tasks as &$task) {
                $task = $this->formatTask($task);
            }
        }

        return $tasks;
    }

    /**
     * Format the given project.
     *
     * @param array $project
     *
     * @return array
     */
    protected function formatProject($project)
    {
        if (!empty($project)) {
            $project['url'] = [
                'board'    => $this->helper->url->to('Project/Board/BoardController', 'show', ['project_id' => $project['id']], '', true),
                'calendar' => $this->helper->url->to('CalendarController', 'show', ['project_id' => $project['id']], '', true),
                'list'     => $this->helper->url->to('Task/TaskController', 'index', ['project_id' => $project['id']], '', true),
            ];
        }

        return $project;
    }

    /**
     * Format the given projects.
     *
     * @param array $projects
     *
     * @return array
     */
    protected function formatProjects($projects)
    {
        if (!empty($projects)) {
            foreach ($projects as &$project) {
                $project = $this->formatProject($project);
            }
        }

        return $projects;
    }

    /**
     * Values filter.
     *
     * @param array $values
     *
     * @return array
     */
    protected function filterValues(array $values)
    {
        foreach ($values as $key => $value) {
            if (is_null($value)) {
                unset($values[$key]);
            }
        }

        return $values;
    }

    /**
     * Returns the short name of called class.
     *
     * @return string
     */
    protected function getClassName()
    {
        $reflection = new ReflectionClass(get_called_class());

        return $reflection->getShortName();
    }
}
