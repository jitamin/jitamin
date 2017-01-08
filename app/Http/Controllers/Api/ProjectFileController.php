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

use Jitamin\Policy\ProjectPolicy;
use Jitamin\Core\ObjectStorage\ObjectStorageException;

/**
 * Project File API controller.
 */
class ProjectFileController extends Controller
{
    /**
     * Get a file by the id.
     *
     * @param int $project_id
     * @param int $file_id
     *
     * @return array
     */
    public function getProjectFile($project_id, $file_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getProjectFile', $project_id);

        return $this->projectFileModel->getById($file_id);
    }

    /**
     * Get all tasks for a given project.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getAllProjectFiles($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getAllProjectFiles', $project_id);

        return $this->projectFileModel->getAll($project_id);
    }

    /**
     * Download a file by the id.
     *
     * @param int $project_id
     * @param int $file_id
     *
     * @return array
     */
    public function downloadProjectFile($project_id, $file_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'downloadProjectFile', $project_id);

        try {
            $file = $this->projectFileModel->getById($file_id);

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
     * @param string $filename
     * @param string $blob
     *
     * @return bool|int
     */
    public function createProjectFile($project_id, $filename, $blob)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'createProjectFile', $project_id);

        try {
            return $this->projectFileModel->uploadContent($project_id, $filename, $blob);
        } catch (ObjectStorageException $e) {
            $this->logger->error(__METHOD__.': '.$e->getMessage());

            return false;
        }
    }

    /**
     * Remove a file.
     *
     * @param int $project_id
     * @param int $file_id
     *
     * @return bool
     */
    public function removeProjectFile($project_id, $file_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'removeProjectFile', $project_id);

        return $this->projectFileModel->remove($file_id);
    }

    /**
     * Remove all files.
     *
     * @param int $project_id
     *
     * @return bool
     */
    public function removeAllProjectFiles($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'removeAllProjectFiles', $project_id);

        return $this->projectFileModel->removeAll($project_id);
    }
}
