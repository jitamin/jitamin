<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Action;

use Jitamin\Bus\Event\GenericEvent;

/**
 * Base class for automatic actions.
 */
abstract class Base extends \Jitamin\Core\Base
{
    /**
     * Extended events.
     *
     * @var array
     */
    private $compatibleEvents = [];

    /**
     * Flag for called listener.
     *
     * @var bool
     */
    private $called = false;

    /**
     * Project id.
     *
     * @var int
     */
    private $projectId = 0;

    /**
     * User parameters.
     *
     * @var array
     */
    private $params = [];

    /**
     * Get automatic action name.
     *
     * @final
     *
     * @return string
     */
    final public function getName()
    {
        return '\\'.get_called_class();
    }

    /**
     * Get automatic action description.
     *
     * @abstract
     *
     * @return string
     */
    abstract public function getDescription();

    /**
     * Execute the action.
     *
     * @abstract
     *
     * @param array $data Event data dictionary
     *
     * @return bool True if the action was executed or false when not executed
     */
    abstract public function doAction(array $data);

    /**
     * Get the required parameter for the action (defined by the user).
     *
     * @abstract
     *
     * @return array
     */
    abstract public function getActionRequiredParameters();

    /**
     * Get the required parameter for the event (check if for the event data).
     *
     * @abstract
     *
     * @return array
     */
    abstract public function getEventRequiredParameters();

    /**
     * Get the compatible events.
     *
     * @abstract
     *
     * @return array
     */
    abstract public function getCompatibleEvents();

    /**
     * Check if the event data meet the action condition.
     *
     * @param array $data Event data dictionary
     *
     * @return bool
     */
    abstract public function hasRequiredCondition(array $data);

    /**
     * Return class information.
     *
     * @return string
     */
    public function __toString()
    {
        $params = [];

        foreach ($this->params as $key => $value) {
            $params[] = $key.'='.var_export($value, true);
        }

        return $this->getName().'('.implode('|', $params).')';
    }

    /**
     * Set project id.
     *
     * @param int $project_id
     *
     * @return Base
     */
    public function setProjectId($project_id)
    {
        $this->projectId = $project_id;

        return $this;
    }

    /**
     * Get project id.
     *
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set an user defined parameter.
     *
     * @param string $name  Parameter name
     * @param mixed  $value Value
     *
     * @return Base
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    /**
     * Get an user defined parameter.
     *
     * @param string $name    Parameter name
     * @param mixed  $default Default value
     *
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

    /**
     * Check if an action is executable (right project and required parameters).
     *
     * @param array  $data
     * @param string $eventName
     *
     * @return bool
     */
    public function isExecutable(array $data, $eventName)
    {
        return $this->hasCompatibleEvent($eventName) &&
               $this->hasRequiredProject($data) &&
               $this->hasRequiredParameters($data) &&
               $this->hasRequiredCondition($data);
    }

    /**
     * Check if the event is compatible with the action.
     *
     * @param string $eventName
     *
     * @return bool
     */
    public function hasCompatibleEvent($eventName)
    {
        return in_array($eventName, $this->getEvents());
    }

    /**
     * Check if the event data has the required project.
     *
     * @param array $data Event data dictionary
     *
     * @return bool
     */
    public function hasRequiredProject(array $data)
    {
        return (isset($data['project_id']) && $data['project_id'] == $this->getProjectId()) ||
            (isset($data['task']['project_id']) && $data['task']['project_id'] == $this->getProjectId());
    }

    /**
     * Check if the event data has required parameters to execute the action.
     *
     * @param array $data Event data dictionary
     *
     * @return bool True if all keys are there
     */
    public function hasRequiredParameters(array $data, array $parameters = [])
    {
        $parameters = $parameters ?: $this->getEventRequiredParameters();

        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                return isset($data[$key]) && $this->hasRequiredParameters($data[$key], $value);
            } elseif (!isset($data[$value])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Execute the action.
     *
     * @param \Jitamin\Event\GenericEvent $event
     * @param string                      $eventName
     *
     * @return bool
     */
    public function execute(GenericEvent $event, $eventName)
    {
        // Avoid infinite loop, a listener instance can be called only one time
        if ($this->called) {
            return false;
        }

        $data = $event->getAll();
        $executable = $this->isExecutable($data, $eventName);
        $executed = false;

        if ($executable) {
            $this->called = true;
            $executed = $this->doAction($data);
        }

        $this->logger->debug($this.' ['.$eventName.'] => executable='.var_export($executable, true).' exec_success='.var_export($executed, true));

        return $executed;
    }

    /**
     * Register a new event for the automatic action.
     *
     * @param string $event
     * @param string $description
     *
     * @return Base
     */
    public function addEvent($event, $description = '')
    {
        if ($description !== '') {
            $this->eventManager->register($event, $description);
        }

        $this->compatibleEvents[] = $event;

        return $this;
    }

    /**
     * Get all compatible events of an automatic action.
     *
     * @return array
     */
    public function getEvents()
    {
        return array_unique(array_merge($this->getCompatibleEvents(), $this->compatibleEvents));
    }
}
