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

use Jitamin\Policy\ActionPolicy;
use Jitamin\Policy\ProjectPolicy;

/**
 * Action API controller.
 */
class ActionController extends Controller
{
    /**
     * Get available automatic actions.
     *
     * @return array
     */
    public function getAvailableActions()
    {
        return $this->actionManager->getAvailableActions();
    }

    /**
     * Get the list of events and description that can be used from the user interface.
     *
     * @return array
     */
    public function getAvailableActionEvents()
    {
        return $this->eventManager->getAll();
    }

    /**
     * Get list of compatible events for a given action.
     *
     * @param string $name
     *
     * @return array
     */
    public function getCompatibleActionEvents($action_name)
    {
        return $this->actionManager->getCompatibleEvents($action_name);
    }

    /**
     * Remove an action.
     *
     * @param int $action_id
     *
     * @return bool
     */
    public function removeAction($action_id)
    {
        ActionPolicy::getInstance($this->container)->check($this->getClassName(), 'removeAction', $action_id);

        return $this->actionModel->remove($action_id);
    }

    /**
     * Return actions and parameters for a given project.
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getActions($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getActions', $project_id);

        return $this->actionModel->getAllByProject($project_id);
    }

    /**
     * Create an action.
     *
     * @param int    $project_id
     * @param string $event_name
     * @param string $action_name
     * @param array  $params
     *
     * @return bool|int
     */
    public function createAction($project_id, $event_name, $action_name, array $params)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'createAction', $project_id);
        $values = [
            'project_id'  => $project_id,
            'event_name'  => $event_name,
            'action_name' => $action_name,
            'params'      => $params,
        ];

        list($valid) = $this->actionValidator->validateCreation($values);

        if (!$valid) {
            return false;
        }

        // Check if the action exists
        $actions = $this->actionManager->getAvailableActions();

        if (!isset($actions[$action_name])) {
            return false;
        }

        // Check the event
        $action = $this->actionManager->getAction($action_name);

        if (!in_array($event_name, $action->getEvents())) {
            return false;
        }

        $required_params = $action->getActionRequiredParameters();

        // Check missing parameters
        foreach ($required_params as $param => $value) {
            if (!isset($params[$param])) {
                return false;
            }
        }

        // Check extra parameters
        foreach ($params as $param => $value) {
            if (!isset($required_params[$param])) {
                return false;
            }
        }

        return $this->actionModel->create($values);
    }
}
