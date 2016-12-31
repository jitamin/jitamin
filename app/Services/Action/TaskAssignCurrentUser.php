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
 * Assign a task to the logged user.
 */
class TaskAssignCurrentUser extends Base
{
    /**
     * Get automatic action description.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('Assign the task to the person who does the action');
    }

    /**
     * Get the list of compatible events.
     *
     * @return array
     */
    public function getCompatibleEvents()
    {
        return [
            TaskModel::EVENT_CREATE,
        ];
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
        return [
            'task_id',
        ];
    }

    /**
     * Execute the action.
     *
     * @param array $data Event data dictionary
     *
     * @return bool True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        if (!$this->userSession->isLogged()) {
            return false;
        }

        $values = [
            'id'       => $data['task_id'],
            'owner_id' => $this->userSession->getId(),
        ];

        return $this->taskModel->update($values);
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
