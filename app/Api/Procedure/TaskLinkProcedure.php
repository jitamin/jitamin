<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

use Jitamin\Api\Authorization\TaskAuthorization;
use Jitamin\Api\Authorization\TaskLinkAuthorization;

/**
 * TaskLink API controller.
 */
class TaskLinkProcedure extends BaseProcedure
{
    /**
     * Get a task link.
     *
     * @param int $task_link_id Task link id
     *
     * @return array
     */
    public function getTaskLinkById($task_link_id)
    {
        TaskLinkAuthorization::getInstance($this->container)->check($this->getClassName(), 'getTaskLinkById', $task_link_id);

        return $this->taskLinkModel->getById($task_link_id);
    }

    /**
     * Get all links attached to a task.
     *
     * @param int $task_id Task id
     *
     * @return array
     */
    public function getAllTaskLinks($task_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getAllTaskLinks', $task_id);

        return $this->taskLinkModel->getAll($task_id);
    }

    /**
     * Create a new link.
     *
     * @param int $task_id          Task id
     * @param int $opposite_task_id Opposite task id
     * @param int $link_id          Link id
     *
     * @return int Task link id
     */
    public function createTaskLink($task_id, $opposite_task_id, $link_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'createTaskLink', $task_id);

        return $this->taskLinkModel->create($task_id, $opposite_task_id, $link_id);
    }

    /**
     * Update a task link.
     *
     * @param int $task_link_id     Task link id
     * @param int $task_id          Task id
     * @param int $opposite_task_id Opposite task id
     * @param int $link_id          Link id
     *
     * @return bool
     */
    public function updateTaskLink($task_link_id, $task_id, $opposite_task_id, $link_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'updateTaskLink', $task_id);

        return $this->taskLinkModel->update($task_link_id, $task_id, $opposite_task_id, $link_id);
    }

    /**
     * Remove a link between two tasks.
     *
     * @param int $task_link_id
     *
     * @return bool
     */
    public function removeTaskLink($task_link_id)
    {
        TaskLinkAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeTaskLink', $task_link_id);

        return $this->taskLinkModel->remove($task_link_id);
    }
}
