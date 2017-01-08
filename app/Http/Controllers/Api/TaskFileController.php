<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Api;

use Jitamin\Core\ObjectStorage\ObjectStorageException;
use Jitamin\Policy\ProjectPolicy;
use Jitamin\Policy\TaskFilePolicy;
use Jitamin\Policy\TaskPolicy;

/**
 * Task File API controller.
 */
class TaskFileController extends Controller
{
    /**
     * Get a file by the id.
     *
     * @param int $file_id File id
     *
     * @return array
     */
    public function getTaskFile($file_id)
    {
        TaskFilePolicy::getInstance($this->container)->check($this->getClassName(), 'getTaskFile', $file_id);

        return $this->taskFileModel->getById($file_id);
    }

    /**
     * Get all files for a given task.
     *
     * @param int $task_id
     *
     * @return array
     */
    public function getAllTaskFiles($task_id)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'getAllTaskFiles', $task_id);

        return $this->taskFileModel->getAll($task_id);
    }

    /**
     * Download a file by the id.
     *
     * @param int $file_id File id
     *
     * @return string
     */
    public function downloadTaskFile($file_id)
    {
        TaskFilePolicy::getInstance($this->container)->check($this->getClassName(), 'downloadTaskFile', $file_id);

        try {
            $file = $this->taskFileModel->getById($file_id);

            if (!empty($file)) {
                return base64_encode($this->objectStorage->get($file['path']));
            }
        } catch (ObjectStorageException $e) {
            $this->logger->error($e->getMessage());
        }

        return '';
    }

    /**
     * Handle file upload (base64 encoded content).
     *
     * @param int    $project_id
     * @param int    $task_id
     * @param string $filename
     * @param string $blob
     *
     * @return bool|int
     */
    public function createTaskFile($project_id, $task_id, $filename, $blob)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'createTaskFile', $project_id);

        try {
            return $this->taskFileModel->uploadContent($task_id, $filename, $blob);
        } catch (ObjectStorageException $e) {
            $this->logger->error(__METHOD__.': '.$e->getMessage());

            return false;
        }
    }

    /**
     * Remove a file.
     *
     * @param int $file_id File id
     *
     * @return bool
     */
    public function removeTaskFile($file_id)
    {
        TaskFilePolicy::getInstance($this->container)->check($this->getClassName(), 'removeTaskFile', $file_id);

        return $this->taskFileModel->remove($file_id);
    }

    /**
     * Remove all files for a given task.
     *
     * @param int $task_id
     *
     * @return bool
     */
    public function removeAllTaskFiles($task_id)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'removeAllTaskFiles', $task_id);

        return $this->taskFileModel->removeAll($task_id);
    }
}
