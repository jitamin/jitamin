<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Event;

use Hiject\Model\TaskModel;
use Hiject\Model\TaskLinkModel;

/**
 * Event Manager
 */
class EventManager
{
    /**
     * Extended events
     *
     * @access private
     * @var array
     */
    private $events = [];

    /**
     * Add new event
     *
     * @access public
     * @param  string  $event
     * @param  string  $description
     * @return EventManager
     */
    public function register($event, $description)
    {
        $this->events[$event] = $description;
        return $this;
    }

    /**
     * Get the list of events and description that can be used from the user interface
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        $events = [
            TaskLinkModel::EVENT_CREATE_UPDATE => t('Task link creation or modification'),
            TaskModel::EVENT_MOVE_COLUMN       => t('Move a task to another column'),
            TaskModel::EVENT_UPDATE            => t('Task modification'),
            TaskModel::EVENT_CREATE            => t('Task creation'),
            TaskModel::EVENT_OPEN              => t('Reopen a task'),
            TaskModel::EVENT_CLOSE             => t('Closing a task'),
            TaskModel::EVENT_CREATE_UPDATE     => t('Task creation or modification'),
            TaskModel::EVENT_ASSIGNEE_CHANGE   => t('Task assignee change'),
            TaskModel::EVENT_DAILY_CRONJOB     => t('Daily background job for tasks'),
            TaskModel::EVENT_MOVE_SWIMLANE     => t('Move a task to another swimlane'),
        ];

        $events = array_merge($events, $this->events);
        asort($events);

        return $events;
    }
}
