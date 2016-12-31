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
 * Close automatically a task after when inactive.
 */
class TaskCloseNoActivity extends Base
{
    /**
     * Get automatic action description.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('Close a task when there is no activity');
    }

    /**
     * Get the list of compatible events.
     *
     * @return array
     */
    public function getCompatibleEvents()
    {
        return [TaskModel::EVENT_DAILY_CRONJOB];
    }

    /**
     * Get the required parameter for the action (defined by the user).
     *
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return [
            'duration' => t('Duration in days'),
        ];
    }

    /**
     * Get the required parameter for the event.
     *
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return ['tasks'];
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
        $results = [];
        $max = $this->getParam('duration') * 86400;

        foreach ($data['tasks'] as $task) {
            $duration = time() - $task['date_modification'];

            if ($duration > $max) {
                $results[] = $this->taskStatusModel->close($task['id']);
            }
        }

        return in_array(true, $results, true);
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
        return count($data['tasks']) > 0;
    }
}
