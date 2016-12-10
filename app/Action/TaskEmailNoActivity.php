<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Action;

use Hiject\Model\TaskModel;

/**
 * Email a task with no activity.
 */
class TaskEmailNoActivity extends Base
{
    /**
     * Get automatic action description.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('Send email when there is no activity on a task');
    }

    /**
     * Get the list of compatible events.
     *
     * @return array
     */
    public function getCompatibleEvents()
    {
        return [
            TaskModel::EVENT_DAILY_CRONJOB,
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
            'user_id'  => t('User that will receive the email'),
            'subject'  => t('Email subject'),
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

    /**
     * Execute the action (move the task to another column).
     *
     * @param array $data Event data dictionary
     *
     * @return bool True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $results = [];
        $max = $this->getParam('duration') * 86400;
        $user = $this->userModel->getById($this->getParam('user_id'));

        if (!empty($user['email'])) {
            foreach ($data['tasks'] as $task) {
                $duration = time() - $task['date_modification'];

                if ($duration > $max) {
                    $results[] = $this->sendEmail($task['id'], $user);
                }
            }
        }

        return in_array(true, $results, true);
    }

    /**
     * Send email.
     *
     * @param int   $task_id
     * @param array $user
     *
     * @return bool
     */
    private function sendEmail($task_id, array $user)
    {
        $task = $this->taskFinderModel->getDetails($task_id);

        $this->emailClient->send(
            $user['email'],
            $user['name'] ?: $user['username'],
            $this->getParam('subject'),
            $this->template->render('notification/task_create', ['task' => $task, 'application_url' => $this->settingModel->get('application_url')])
        );

        return true;
    }
}
