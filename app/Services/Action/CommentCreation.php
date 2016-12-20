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

/**
 * Create automatically a comment from a webhook.
 */
class CommentCreation extends Base
{
    /**
     * Get automatic action description.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('Create a comment from an external provider');
    }

    /**
     * Get the list of compatible events.
     *
     * @return string[]
     */
    public function getCompatibleEvents()
    {
        return [];
    }

    /**
     * Get the required parameter for the action (defined by the user).
     *
     * @return string[]
     */
    public function getActionRequiredParameters()
    {
        return [];
    }

    /**
     * Get the required parameter for the event.
     *
     * @return array
     */
    public function getEventRequiredParameters()
    {
        return [
            'task_id',
        ];
    }

    /**
     * Execute the action (create a new comment).
     *
     * @param array $data Event data dictionary
     *
     * @return bool True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        return (bool) $this->commentModel->create([
            'reference' => isset($data['reference']) ? $data['reference'] : '',
            'comment'   => $data['comment'],
            'task_id'   => $data['task_id'],
            'user_id'   => isset($data['user_id']) && $this->projectPermissionModel->isAssignable($this->getProjectId(), $data['user_id']) ? $data['user_id'] : 0,
        ]);
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
        return !empty($data['comment']);
    }
}
