<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Job;

use Jitamin\Bus\EventBuilder\ProjectFileEventBuilder;

/**
 * Class ProjectFileEventJob.
 */
class ProjectFileEventJob extends BaseJob
{
    /**
     * Set job params.
     *
     * @param int    $fileId
     * @param string $eventName
     *
     * @return $this
     */
    public function withParams($fileId, $eventName)
    {
        $this->jobParams = [$fileId, $eventName];

        return $this;
    }

    /**
     * Execute job.
     *
     * @param int    $fileId
     * @param string $eventName
     *
     * @return $this
     */
    public function execute($fileId, $eventName)
    {
        $event = ProjectFileEventBuilder::getInstance($this->container)
            ->withFileId($fileId)
            ->buildEvent();

        if ($event !== null) {
            $this->dispatcher->dispatch($eventName, $event);
        }
    }
}
