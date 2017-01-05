<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

use Jitamin\Api\Authorization\ProjectAuthorization;
use Jitamin\Api\Authorization\TaskAuthorization;
use Jitamin\Filter\TaskProjectFilter;
use Jitamin\Model\TaskModel;

/**
 * Task API controller.
 */
class TaskProcedure extends BaseProcedure
{
    /**
     * Get the tasks for applied filter.
     *
     * @param int    $project_id
     * @param string $query
     *
     * @return array
     */
    public function searchTasks($project_id, $query)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'searchTasks', $project_id);

        return $this->taskLexer->build($query)->withFilter(new TaskProjectFilter($project_id))->toArray();
    }

    /**
     * Fetch a task by the id.
     *
     * @param int $task_id Task id
     *
     * @return array
     */
    public function getTask($task_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getTask', $task_id);

        return $this->formatTask($this->taskFinderModel->getById($task_id));
    }

    /**
     * Fetch a task by the reference (external id).
     *
     * @param int    $project_id Project id
     * @param string $reference  Task reference
     *
     * @return array
     */
    public function getTaskByReference($project_id, $reference)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getTaskByReference', $project_id);

        return $this->formatTask($this->taskFinderModel->getByReference($project_id, $reference));
    }

    /**
     * Get all tasks for a given project and status.
     *
     * @param int $project_id Project id
     * @param int $status_id  Status id
     *
     * @return array
     */
    public function getAllTasks($project_id, $status_id = TaskModel::STATUS_OPEN)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getAllTasks', $project_id);

        return $this->formatTasks($this->taskFinderModel->getAll($project_id, $status_id));
    }

    /**
     * Get a list of overdue tasks for all projects.
     *
     * @return array
     */
    public function getOverdueTasks()
    {
        return $this->taskFinderModel->getOverdueTasks();
    }

    /**
     * Get a list of overdue tasks by project.
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getOverdueTasksByProject($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getOverdueTasksByProject', $project_id);

        return $this->taskFinderModel->getOverdueTasksByProject($project_id);
    }

    /**
     * Mark a task open.
     *
     * @param int $task_id Task id
     *
     * @return bool
     */
    public function openTask($task_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'openTask', $task_id);

        return $this->taskStatusModel->open($task_id);
    }

    /**
     * Mark a task closed.
     *
     * @param int $task_id Task id
     *
     * @return bool
     */
    public function closeTask($task_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'closeTask', $task_id);

        return $this->taskStatusModel->close($task_id);
    }

    /**
     * Remove a task.
     *
     * @param int $task_id Task id
     *
     * @return bool
     */
    public function removeTask($task_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeTask', $task_id);

        return $this->taskModel->remove($task_id);
    }

    /**
     * Move a task to another column or to another position.
     *
     * @param int  $project_id  Project id
     * @param int  $task_id     Task id
     * @param int  $column_id   Column id
     * @param int  $position    Position (must be >= 1)
     * @param int  $swimlane_id Swimlane id
     * @param bool $fire_events Fire events
     * @param bool $onlyOpen    Do not move closed tasks
     *
     * @return bool
     */
    public function moveTaskPosition($project_id, $task_id, $column_id, $position, $swimlane_id = 0)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'moveTaskPosition', $project_id);

        return $this->taskPositionModel->movePosition($project_id, $task_id, $column_id, $position, $swimlane_id);
    }

    /**
     * Move a task to another project.
     *
     * @param int $task_id
     * @param int $project_id
     * @param int $swimlane_id
     * @param int $column_id
     * @param int $category_id
     * @param int $owner_id
     *
     * @return bool
     */
    public function moveTaskToProject($task_id, $project_id, $swimlane_id = null, $column_id = null, $category_id = null, $owner_id = null)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'moveTaskToProject', $project_id);

        return $this->taskProjectMoveModel->moveToProject($task_id, $project_id, $swimlane_id, $column_id, $category_id, $owner_id);
    }

    /**
     * Duplicate a task to another project.
     *
     * @param int $task_id
     * @param int $project_id
     * @param int $swimlane_id
     * @param int $column_id
     * @param int $category_id
     * @param int $owner_id
     *
     * @return bool|int
     */
    public function duplicateTaskToProject($task_id, $project_id, $swimlane_id = null, $column_id = null, $category_id = null, $owner_id = null)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'duplicateTaskToProject', $project_id);

        return $this->taskProjectDuplicationModel->duplicateToProject($task_id, $project_id, $swimlane_id, $column_id, $category_id, $owner_id);
    }

    /**
     * Create a task.
     *
     * @param string $title
     * @param int    $project_id
     * @param string $color_id
     * @param int    $column_id
     * @param int    $owner_id
     * @param int    $creator_id
     * @param string $date_due
     * @param string $description
     * @param int    $category_id
     * @param int    $score
     * @param int    $swimlane_id
     * @param int    $priority
     * @param int    $recurrence_status
     * @param int    $recurrence_trigger
     * @param int    $recurrence_factor
     * @param int    $recurrence_timeframe
     * @param int    $recurrence_basedate
     * @param string $reference
     *
     * @return int
     */
    public function createTask($title, $project_id, $color_id = '', $column_id = 0, $owner_id = 0, $creator_id = 0,
                                $date_due = '', $description = '', $category_id = 0, $score = 0, $swimlane_id = 0, $priority = 0,
                                $recurrence_status = 0, $recurrence_trigger = 0, $recurrence_factor = 0, $recurrence_timeframe = 0,
                                $recurrence_basedate = 0, $reference = '')
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'createTask', $project_id);

        if ($owner_id !== 0 && !$this->projectPermissionModel->isAssignable($project_id, $owner_id)) {
            return false;
        }

        if ($this->userSession->isLogged()) {
            $creator_id = $this->userSession->getId();
        }

        $values = [
            'title'                => $title,
            'project_id'           => $project_id,
            'color_id'             => $color_id,
            'column_id'            => $column_id,
            'owner_id'             => $owner_id,
            'creator_id'           => $creator_id,
            'date_due'             => $date_due,
            'description'          => $description,
            'category_id'          => $category_id,
            'score'                => $score,
            'swimlane_id'          => $swimlane_id,
            'recurrence_status'    => $recurrence_status,
            'recurrence_trigger'   => $recurrence_trigger,
            'recurrence_factor'    => $recurrence_factor,
            'recurrence_timeframe' => $recurrence_timeframe,
            'recurrence_basedate'  => $recurrence_basedate,
            'reference'            => $reference,
            'priority'             => $priority,
        ];

        list($valid) = $this->taskValidator->validateCreation($values);

        return $valid ? $this->taskModel->create($values) : false;
    }

    /**
     * Update a task.
     *
     * @param int    $id
     * @param string $title
     * @param int    $project_id
     * @param string $color_id
     * @param int    $column_id
     * @param int    $owner_id
     * @param int    $creator_id
     * @param string $date_due
     * @param string $description
     * @param int    $category_id
     * @param int    $score
     * @param int    $swimlane_id
     * @param int    $priority
     * @param int    $recurrence_status
     * @param int    $recurrence_trigger
     * @param int    $recurrence_factor
     * @param int    $recurrence_timeframe
     * @param int    $recurrence_basedate
     * @param string $reference
     *
     * @return int
     */
    public function updateTask($id, $title = null, $color_id = null, $owner_id = null,
                                $date_due = null, $description = null, $category_id = null, $score = null, $priority = null,
                                $recurrence_status = null, $recurrence_trigger = null, $recurrence_factor = null,
                                $recurrence_timeframe = null, $recurrence_basedate = null, $reference = null)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'updateTask', $id);
        $project_id = $this->taskFinderModel->getProjectId($id);

        if ($project_id === 0) {
            return false;
        }

        if ($owner_id !== null && $owner_id != 0 && !$this->projectPermissionModel->isAssignable($project_id, $owner_id)) {
            return false;
        }

        $values = $this->filterValues([
            'id'                   => $id,
            'title'                => $title,
            'color_id'             => $color_id,
            'owner_id'             => $owner_id,
            'date_due'             => $date_due,
            'description'          => $description,
            'category_id'          => $category_id,
            'score'                => $score,
            'recurrence_status'    => $recurrence_status,
            'recurrence_trigger'   => $recurrence_trigger,
            'recurrence_factor'    => $recurrence_factor,
            'recurrence_timeframe' => $recurrence_timeframe,
            'recurrence_basedate'  => $recurrence_basedate,
            'reference'            => $reference,
            'priority'             => $priority,
        ]);

        list($valid) = $this->taskValidator->validateApiModification($values);

        return $valid && $this->taskModel->update($values);
    }
}
