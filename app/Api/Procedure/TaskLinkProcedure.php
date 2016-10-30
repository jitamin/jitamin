<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Api\Procedure;

use Hiject\Api\Authorization\TaskAuthorization;
use Hiject\Api\Authorization\TaskLinkAuthorization;

/**
 * TaskLink API controller
 */
class TaskLinkProcedure extends BaseProcedure
{
    /**
     * Get a task link
     *
     * @access public
     * @param  integer   $task_link_id   Task link id
     * @return array
     */
    public function getTaskLinkById($task_link_id)
    {
        TaskLinkAuthorization::getInstance($this->container)->check($this->getClassName(), 'getTaskLinkById', $task_link_id);
        return $this->taskLinkModel->getById($task_link_id);
    }

    /**
     * Get all links attached to a task
     *
     * @access public
     * @param  integer   $task_id   Task id
     * @return array
     */
    public function getAllTaskLinks($task_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getAllTaskLinks', $task_id);
        return $this->taskLinkModel->getAll($task_id);
    }

    /**
     * Create a new link
     *
     * @access public
     * @param  integer   $task_id            Task id
     * @param  integer   $opposite_task_id   Opposite task id
     * @param  integer   $link_id            Link id
     * @return integer                       Task link id
     */
    public function createTaskLink($task_id, $opposite_task_id, $link_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'createTaskLink', $task_id);
        return $this->taskLinkModel->create($task_id, $opposite_task_id, $link_id);
    }

    /**
     * Update a task link
     *
     * @access public
     * @param  integer   $task_link_id          Task link id
     * @param  integer   $task_id               Task id
     * @param  integer   $opposite_task_id      Opposite task id
     * @param  integer   $link_id               Link id
     * @return boolean
     */
    public function updateTaskLink($task_link_id, $task_id, $opposite_task_id, $link_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'updateTaskLink', $task_id);
        return $this->taskLinkModel->update($task_link_id, $task_id, $opposite_task_id, $link_id);
    }

    /**
     * Remove a link between two tasks
     *
     * @access public
     * @param  integer   $task_link_id
     * @return boolean
     */
    public function removeTaskLink($task_link_id)
    {
        TaskLinkAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeTaskLink', $task_link_id);
        return $this->taskLinkModel->remove($task_link_id);
    }
}
