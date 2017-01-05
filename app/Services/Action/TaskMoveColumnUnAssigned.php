<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Action;

use Jitamin\Model\TaskModel;

/**
 * Move a task to another column when an assignee is cleared.
 */
class TaskMoveColumnUnAssigned extends Base
{
    /**
     * Get automatic action description.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('Move the task to another column when assignee is cleared');
    }

    /**
     * Get the list of compatible events.
     *
     * @return array
     */
    public function getCompatibleEvents()
    {
        return [
            TaskModel::EVENT_ASSIGNEE_CHANGE,
            TaskModel::EVENT_UPDATE,
        ];
    }

    /**
     * Get the required parameter for the action (defined by the user).
     *
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return [
            'src_column_id'  => t('Source column'),
            'dest_column_id' => t('Destination column'),
        ];
    }

    /**
     * Get the required parameter for the event.
     *
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return [
            'task_id',
            'task' => [
                'project_id',
                'column_id',
                'owner_id',
                'position',
                'swimlane_id',
            ],
        ];
    }

    /**
     * Execute the action (move the task to another column).
     *
     * @param array $data Event data dictionary
     *
     * @return bool True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        return $this->taskPositionModel->movePosition(
            $data['task']['project_id'],
            $data['task_id'],
            $this->getParam('dest_column_id'),
            $data['task']['position'],
            $data['task']['swimlane_id'],
            false
        );
    }

    /**
     * Check if the event data meet the action condition.
     *
     * @param array $data Event data dictionary
     *
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        return $data['task']['column_id'] == $this->getParam('src_column_id') && $data['task']['owner_id'] == 0;
    }
}
