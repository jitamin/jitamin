<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Event;

/**
 * Task list event.
 */
class TaskListEvent extends GenericEvent
{
    /**
     * Set tasks.
     *
     * @param array $tasks
     *
     * @return null
     */
    public function setTasks(array &$tasks)
    {
        $this->container['tasks'] = &$tasks;
    }
}
