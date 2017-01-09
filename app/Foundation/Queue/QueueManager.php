<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation\Queue;

use Jitamin\Bus\Job\BaseJob;
use Jitamin\Foundation\Base;
use LogicException;
use SimpleQueue\Queue;

/**
 * Class QueueManager.
 */
class QueueManager extends Base
{
    /**
     * @var Queue
     */
    protected $queue = null;

    /**
     * Set queue driver.
     *
     * @param Queue $queue
     *
     * @return $this
     */
    public function setQueue(Queue $queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Send a new job to the queue.
     *
     * @param BaseJob $job
     *
     * @return $this
     */
    public function push(BaseJob $job)
    {
        $jobClassName = get_class($job);

        if ($this->queue !== null) {
            $this->logger->debug(__METHOD__.': Job pushed in queue: '.$jobClassName);
            $this->queue->push(JobHandler::getInstance($this->container)->serializeJob($job));
        } else {
            $this->logger->debug(__METHOD__.': Job executed synchronously: '.$jobClassName);
            call_user_func_array([$job, 'execute'], $job->getJobParams());
        }

        return $this;
    }

    /**
     * Wait for new jobs.
     *
     * @throws LogicException
     */
    public function listen()
    {
        if ($this->queue === null) {
            throw new LogicException('No queue driver defined!');
        }

        while ($job = $this->queue->pull()) {
            JobHandler::getInstance($this->container)->executeJob($job);
            $this->queue->completed($job);
        }
    }
}
