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

use Jitamin\Policy\ProjectPolicy;

/**
 * Swimlane API controller.
 */
class SwimlaneController extends Controller
{
    /**
     * Get active swimlanes.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getActiveSwimlanes($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getActiveSwimlanes', $project_id);

        return $this->swimlaneModel->getSwimlanes($project_id);
    }

    /**
     * Get all swimlanes for a given project.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getAllSwimlanes($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getAllSwimlanes', $project_id);

        return $this->swimlaneModel->getAll($project_id);
    }

    /**
     * Get a swimlane by the id.
     *
     * @param int $swimlane_id Swimlane id
     *
     * @return array
     */
    public function getSwimlaneById($swimlane_id)
    {
        $swimlane = $this->swimlaneModel->getById($swimlane_id);
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getSwimlaneById', $swimlane['project_id']);

        return $swimlane;
    }

    /**
     * Get a swimlane by the project and the name.
     *
     * @param int    $project_id Project id
     * @param string $name       Swimlane name
     *
     * @return array
     */
    public function getSwimlaneByName($project_id, $name)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getSwimlaneByName', $project_id);

        return $this->swimlaneModel->getByName($project_id, $name);
    }

    /**
     * Get a swimlane by the id.
     *
     * @param int $swimlane_id Swimlane id
     *
     * @return array
     */
    public function getSwimlane($swimlane_id)
    {
        return $this->swimlaneModel->getById($swimlane_id);
    }

    /**
     * Get default swimlane properties.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getDefaultSwimlane($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getDefaultSwimlane', $project_id);

        return $this->swimlaneModel->getDefault($project_id);
    }

    /**
     * Add a new swimlane.
     *
     * @param int    $project_id
     * @param string $name
     * @param string $description
     *
     * @return int|bool
     */
    public function addSwimlane($project_id, $name, $description = '')
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'addSwimlane', $project_id);

        return $this->swimlaneModel->create(['project_id' => $project_id, 'name' => $name, 'description' => $description]);
    }

    /**
     * Update a swimlane.
     *
     * @param int    $swimlane_id
     * @param string $name
     * @param string $description
     *
     * @return bool
     */
    public function updateSwimlane($swimlane_id, $name, $description = null)
    {
        $values = ['id' => $swimlane_id, 'name' => $name];

        if (!is_null($description)) {
            $values['description'] = $description;
        }

        return $this->swimlaneModel->update($values);
    }

    /**
     * Remove a swimlane.
     *
     * @param int $project_id  Project id
     * @param int $swimlane_id Swimlane id
     *
     * @return bool
     */
    public function removeSwimlane($project_id, $swimlane_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'removeSwimlane', $project_id);

        return $this->swimlaneModel->remove($project_id, $swimlane_id);
    }

    /**
     * Disable a swimlane.
     *
     * @param int $project_id  Project id
     * @param int $swimlane_id Swimlane id
     *
     * @return bool
     */
    public function disableSwimlane($project_id, $swimlane_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'disableSwimlane', $project_id);

        return $this->swimlaneModel->disable($project_id, $swimlane_id);
    }

    /**
     * Enable a swimlane.
     *
     * @param int $project_id  Project id
     * @param int $swimlane_id Swimlane id
     *
     * @return bool
     */
    public function enableSwimlane($project_id, $swimlane_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'enableSwimlane', $project_id);

        return $this->swimlaneModel->enable($project_id, $swimlane_id);
    }

    /**
     * Change swimlane position.
     *
     * @param int $project_id
     * @param int $swimlane_id
     * @param int $position
     *
     * @return bool
     */
    public function changeSwimlanePosition($project_id, $swimlane_id, $position)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'changeSwimlanePosition', $project_id);

        return $this->swimlaneModel->changePosition($project_id, $swimlane_id, $position);
    }
}
