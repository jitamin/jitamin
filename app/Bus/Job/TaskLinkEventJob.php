<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Job;

use Hiject\Bus\EventBuilder\TaskLinkEventBuilder;

/**
 * Class TaskLinkEventJob
 */
class TaskLinkEventJob extends BaseJob
{
    /**
     * Set job params
     *
     * @param  int    $taskLinkId
     * @param  string $eventName
     * @return $this
     */
    public function withParams($taskLinkId, $eventName)
    {
        $this->jobParams = array($taskLinkId, $eventName);
        return $this;
    }

    /**
     * Execute job
     *
     * @param  int    $taskLinkId
     * @param  string $eventName
     * @return $this
     */
    public function execute($taskLinkId, $eventName)
    {
        $event = TaskLinkEventBuilder::getInstance($this->container)
            ->withTaskLinkId($taskLinkId)
            ->buildEvent();

        if ($event !== null) {
            $this->dispatcher->dispatch($eventName, $event);
        }
    }
}
