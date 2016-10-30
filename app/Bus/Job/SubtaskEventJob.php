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

use Hiject\Bus\EventBuilder\SubtaskEventBuilder;

/**
 * Class SubtaskEventJob
 */
class SubtaskEventJob extends BaseJob
{
    /**
     * Set job params
     *
     * @param  int     $subtaskId
     * @param  string  $eventName
     * @param  array   $values
     * @return $this
     */
    public function withParams($subtaskId, $eventName, array $values = array())
    {
        $this->jobParams = array($subtaskId, $eventName, $values);
        return $this;
    }

    /**
     * Execute job
     *
     * @param  int    $subtaskId
     * @param  string $eventName
     * @param  array  $values
     * @return $this
     */
    public function execute($subtaskId, $eventName, array $values = array())
    {
        $event = SubtaskEventBuilder::getInstance($this->container)
            ->withSubtaskId($subtaskId)
            ->withValues($values)
            ->buildEvent();

        if ($event !== null) {
            $this->dispatcher->dispatch($eventName, $event);
        }
    }
}
