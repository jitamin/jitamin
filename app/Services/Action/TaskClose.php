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

/**
 * Close automatically a task.
 */
class TaskClose extends Base
{
    /**
     * Get automatic action description.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('Close a task');
    }

    /**
     * Get the list of compatible events.
     *
     * @return array
     */
    public function getCompatibleEvents()
    {
        return [];
    }

    /**
     * Get the required parameter for the action (defined by the user).
     *
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return [];
    }

    /**
     * Get the required parameter for the event.
     *
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return ['task_id'];
    }

    /**
     * Execute the action (close the task).
     *
     * @param array $data Event data dictionary
     *
     * @return bool True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        return $this->taskStatusModel->close($data['task_id']);
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
        return true;
    }
}
