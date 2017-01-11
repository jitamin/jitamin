<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Api;

use Jitamin\Policy\TaskPolicy;

/**
 * Class TaskMetadata Controller.
 */
class TaskMetadataController extends Controller
{
    /**
     * Get all metadata for the task.
     *
     * @param int $task_id
     *
     * @return array
     */
    public function getTaskMetadata($task_id)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'getTask', $task_id);

        return $this->taskMetadataModel->getAll($task_id);
    }

    /**
     * Get a metadata for the given entity.
     *
     * @param int    $task_id
     * @param string $name
     *
     * @return mixed
     */
    public function getTaskMetadataByName($task_id, $name)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'getTask', $task_id);

        return $this->taskMetadataModel->get($task_id, $name);
    }

    /**
     * Update or insert new metadata.
     *
     * @param int   $task_id
     * @param array $values
     *
     * @return bool
     */
    public function saveTaskMetadata($task_id, array $values)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'updateTask', $task_id);

        return $this->taskMetadataModel->save($task_id, $values);
    }

    /**
     * Remove a metadata.
     *
     * @param int    $task_id
     * @param string $name
     *
     * @return bool
     */
    public function removeTaskMetadata($task_id, $name)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'updateTask', $task_id);

        return $this->taskMetadataModel->remove($task_id, $name);
    }
}
