<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

use Jitamin\Api\Authorization\ProjectAuthorization;

/**
 * Project API controller.
 */
class ProjectProcedure extends BaseProcedure
{
    /**
     * Get a project by the id.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getProjectById($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getProjectById', $project_id);

        return $this->formatProject($this->projectModel->getById($project_id));
    }

    /**
     * Get a project by the name.
     *
     * @param string $name Project name
     *
     * @return array
     */
    public function getProjectByName($name)
    {
        $project = $this->projectModel->getByName($name);
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getProjectByName', $project['id']);

        return $this->formatProject($project);
    }

    /**
     * Get a project by the identifier (code).
     *
     * @param string $identifier
     *
     * @return array|bool
     */
    public function getProjectByIdentifier($identifier)
    {
        $project = $this->formatProject($this->projectModel->getByIdentifier($identifier));
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getProjectByIdentifier', $project['id']);

        return $this->formatProject($project);
    }

    /**
     * Get all projects.
     *
     * @return array
     */
    public function getAllProjects()
    {
        return $this->formatProjects($this->projectModel->getAll());
    }

    /**
     * Remove a project.
     *
     * @param int $project_id Project id
     *
     * @return bool
     */
    public function removeProject($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeProject', $project_id);

        return $this->projectModel->remove($project_id);
    }

    /**
     * Enable a project.
     *
     * @param int $project_id Project id
     *
     * @return bool
     */
    public function enableProject($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'enableProject', $project_id);

        return $this->projectModel->enable($project_id);
    }

    /**
     * Disable a project.
     *
     * @param int $project_id Project id
     *
     * @return bool
     */
    public function disableProject($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'disableProject', $project_id);

        return $this->projectModel->disable($project_id);
    }

    /**
     * Enable public access for a project.
     *
     * @param int $project_id Project id
     *
     * @return bool
     */
    public function enableProjectPublicAccess($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'enableProjectPublicAccess', $project_id);

        return $this->projectModel->enablePublicAccess($project_id);
    }

    /**
     * Disable public access for a project.
     *
     * @param int $project_id Project id
     *
     * @return bool
     */
    public function disableProjectPublicAccess($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'disableProjectPublicAccess', $project_id);

        return $this->projectModel->disablePublicAccess($project_id);
    }

    /**
     * Get projects activity events.
     *
     * @param int[] $project_ids
     *
     * @return array
     */
    public function getProjectActivities(array $project_ids)
    {
        foreach ($project_ids as $project_id) {
            ProjectAuthorization::getInstance($this->container)
                ->check($this->getClassName(), 'getProjectActivities', $project_id);
        }

        return $this->helper->projectActivity->getProjectsEvents($project_ids);
    }

    /**
     * Get project activity events.
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getProjectActivity($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getProjectActivity', $project_id);

        return $this->helper->projectActivity->getProjectEvents($project_id);
    }

    /**
     * Create a project.
     *
     * @param string $name
     * @param string $description
     * @param int    $owner_id
     * @param string $identifier
     *
     * @return int Project id
     */
    public function createProject($name, $description = null, $owner_id = 0, $identifier = null)
    {
        $values = $this->filterValues([
            'name'        => $name,
            'description' => $description,
            'identifier'  => $identifier,
        ]);

        list($valid) = $this->projectValidator->validateCreation($values);

        return $valid ? $this->projectModel->create($values, $owner_id, $this->userSession->isLogged()) : false;
    }

    /**
     * Update a project.
     *
     * @param int    $project_id
     * @param string $name
     * @param string $description
     * @param int    $owner_id
     * @param string $identifier
     *
     * @return bool
     */
    public function updateProject($project_id, $name = null, $description = null, $owner_id = null, $identifier = null)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'updateProject', $project_id);

        $values = $this->filterValues([
            'id'          => $project_id,
            'name'        => $name,
            'description' => $description,
            'owner_id'    => $owner_id,
            'identifier'  => $identifier,
        ]);

        list($valid) = $this->projectValidator->validateModification($values);

        return $valid && $this->projectModel->update($values);
    }
}
