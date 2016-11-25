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

use Hiject\Model\TaskLinkModel;

/**
 * Set a category automatically according to a task link
 */
class TaskAssignCategoryLink extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Assign automatically a category based on a link');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return [
            TaskLinkModel::EVENT_CREATE_UPDATE,
        ];
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return [
            'category_id' => t('Category'),
            'link_id' => t('Link type'),
        ];
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return [
            'task_link' => [
                'task_id',
                'link_id',
            ]
        ];
    }

    /**
     * Execute the action (change the category)
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $values = [
            'id' => $data['task_link']['task_id'],
            'category_id' => $this->getParam('category_id'),
        ];

        return $this->taskModificationModel->update($values);
    }

    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        if ($data['task_link']['link_id'] == $this->getParam('link_id')) {
            return empty($data['task']['category_id']);
        }

        return false;
    }
}
