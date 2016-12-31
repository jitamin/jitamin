<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Core\Database\Model;

/**
 * Task Duplication.
 */
class TaskDuplicationModel extends Model
{
    /**
     * Fields to copy when duplicating a task.
     *
     * @var string[]
     */
    protected $fieldsToDuplicate = [
        'title',
        'description',
        'date_due',
        'color_id',
        'project_id',
        'column_id',
        'owner_id',
        'score',
        'priority',
        'category_id',
        'time_estimated',
        'swimlane_id',
        'recurrence_status',
        'recurrence_trigger',
        'recurrence_factor',
        'recurrence_timeframe',
        'recurrence_basedate',
    ];

    /**
     * Duplicate a task to the same project.
     *
     * @param int $task_id Task id
     *
     * @return bool|int Duplicated task id
     */
    public function duplicate($task_id)
    {
        $new_task_id = $this->save($task_id, $this->copyFields($task_id));

        if ($new_task_id !== false) {
            $this->tagDuplicationModel->duplicateTaskTags($task_id, $new_task_id);
        }

        return $new_task_id;
    }

    /**
     * Check if the assignee and the category are available in the destination project.
     *
     * @param array $values
     *
     * @return array
     */
    public function checkDestinationProjectValues(array &$values)
    {
        // Check if the assigned user is allowed for the destination project
        if ($values['owner_id'] > 0 && !$this->projectPermissionModel->isUserAllowed($values['project_id'], $values['owner_id'])) {
            $values['owner_id'] = 0;
        }

        // Check if the category exists for the destination project
        if ($values['category_id'] > 0) {
            $values['category_id'] = $this->categoryModel->getIdByName(
                $values['project_id'],
                $this->categoryModel->getNameById($values['category_id'])
            );
        }

        // Check if the swimlane exists for the destination project
        if ($values['swimlane_id'] > 0) {
            $values['swimlane_id'] = $this->swimlaneModel->getIdByName(
                $values['project_id'],
                $this->swimlaneModel->getNameById($values['swimlane_id'])
            );
        }

        // Check if the column exists for the destination project
        if ($values['column_id'] > 0) {
            $values['column_id'] = $this->columnModel->getColumnIdByTitle(
                $values['project_id'],
                $this->columnModel->getColumnTitleById($values['column_id'])
            );

            $values['column_id'] = $values['column_id'] ?: $this->columnModel->getFirstColumnId($values['project_id']);
        }

        // Check if priority exists for destination project
        $values['priority'] = $this->projectTaskPriorityModel->getPriorityForProject(
            $values['project_id'],
            empty($values['priority']) ? 0 : $values['priority']
        );

        return $values;
    }

    /**
     * Duplicate fields for the new task.
     *
     * @param int $task_id Task id
     *
     * @return array
     */
    protected function copyFields($task_id)
    {
        $task = $this->taskFinderModel->getById($task_id);
        $values = [];

        foreach ($this->fieldsToDuplicate as $field) {
            $values[$field] = $task[$field];
        }

        return $values;
    }

    /**
     * Create the new task and duplicate subtasks.
     *
     * @param int   $task_id Task id
     * @param array $values  Form values
     *
     * @return bool|int
     */
    protected function save($task_id, array $values)
    {
        $new_task_id = $this->taskModel->create($values);

        if ($new_task_id !== false) {
            $this->subtaskModel->duplicate($task_id, $new_task_id);
        }

        return $new_task_id;
    }
}
