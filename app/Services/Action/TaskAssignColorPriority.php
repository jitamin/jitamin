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
 * Assign a color to a priority.
 */
class TaskAssignColorPriority extends Base
{
    /**
     * Get automatic action description.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('Assign automatically a color based on a priority');
    }

    /**
     * Get the list of compatible events.
     *
     * @return array
     */
    public function getCompatibleEvents()
    {
        return [
            TaskModel::EVENT_CREATE_UPDATE,
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
            'color_id' => t('Color'),
            'priority' => t('Priority'),
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
                'priority',
            ],
        ];
    }

    /**
     * Execute the action (change the task color).
     *
     * @param array $data Event data dictionary
     *
     * @return bool True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $values = [
            'id'       => $data['task_id'],
            'color_id' => $this->getParam('color_id'),
        ];

        return $this->taskModel->update($values, false);
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
        return $data['task']['priority'] == $this->getParam('priority');
    }
}
