<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Event;

/**
 * Task list event
 */
class TaskListEvent extends GenericEvent
{
    /**
     * Set tasks
     *
     * @access public
     * @param  array $tasks
     * @return null
     */
    public function setTasks(array &$tasks)
    {
        $this->container['tasks'] =& $tasks;
    }
}
