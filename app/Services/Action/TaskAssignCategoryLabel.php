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
 * Set a category automatically according to a label.
 */
class TaskAssignCategoryLabel extends Base
{
    /**
     * Get automatic action description.
     *
     * @return string
     */
    public function getDescription()
    {
        return t('Change the category based on an external label');
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
        return [
            'label'       => t('Label'),
            'category_id' => t('Category'),
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
            'label',
        ];
    }

    /**
     * Execute the action (change the category).
     *
     * @param array $data Event data dictionary
     *
     * @return bool True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $values = [
            'id'          => $data['task_id'],
            'category_id' => $this->getParam('category_id'),
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
        return $data['label'] == $this->getParam('label') && empty($data['category_id']);
    }
}
