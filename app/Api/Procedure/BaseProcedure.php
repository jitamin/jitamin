<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

use Jitamin\Api\Authorization\ProcedureAuthorization;
use Jitamin\Api\Authorization\UserAuthorization;
use Jitamin\Core\Base;
use ReflectionClass;

/**
 * Base class.
 */
abstract class BaseProcedure extends Base
{
    /**
     * Before method of procedure.
     *
     * @param string $role
     *
     * @return void
     */
    public function beforeProcedure($procedure)
    {
        ProcedureAuthorization::getInstance($this->container)->check($procedure);
        UserAuthorization::getInstance($this->container)->check($this->getClassName(), $procedure);
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
            $task['url'] = $this->helper->url->to('TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], '', true);
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
                'board'    => $this->helper->url->to('BoardController', 'show', ['project_id' => $project['id']], '', true),
                'calendar' => $this->helper->url->to('CalendarController', 'show', ['project_id' => $project['id']], '', true),
                'list'     => $this->helper->url->to('TaskController', 'index', ['project_id' => $project['id']], '', true),
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
