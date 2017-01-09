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

use Exception;
use Jitamin\Bus\Job\BaseJob;
use Jitamin\Foundation\Base;
use SimpleQueue\Job;

/**
 * Class JobHandler.
 */
class JobHandler extends Base
{
    /**
     * Serialize a job.
     *
     * @param BaseJob $job
     *
     * @return Job
     */
    public function serializeJob(BaseJob $job)
    {
        return new Job([
            'class'   => get_class($job),
            'params'  => $job->getJobParams(),
            'user_id' => $this->userSession->getId(),
        ]);
    }

    /**
     * Execute a job.
     *
     * @param Job $job
     */
    public function executeJob(Job $job)
    {
        $payload = $job->getBody();

        try {
            $className = $payload['class'];
            $this->prepareJobSession($payload['user_id']);
            $this->prepareJobEnvironment();

            if (DEBUG) {
                $this->logger->debug(__METHOD__.' Received job => '.$className.' ('.getmypid().')');
                $this->logger->debug(__METHOD__.' => '.json_encode($payload));
            }

            $worker = new $className($this->container);
            call_user_func_array([$worker, 'execute'], $payload['params']);
        } catch (Exception $e) {
            $this->logger->error(__METHOD__.': Error during job execution: '.$e->getMessage());
            $this->logger->error(__METHOD__.' => '.json_encode($payload));
        }
    }

    /**
     * Create the session for the job.
     *
     * @param int $user_id
     */
    protected function prepareJobSession($user_id)
    {
        $session = [];
        $this->sessionStorage->setStorage($session);

        if ($user_id > 0) {
            $user = $this->userModel->getById($user_id);
            $this->userSession->initialize($user);
        }
    }

    /**
     * Flush in-memory caching and specific events.
     */
    protected function prepareJobEnvironment()
    {
        $this->memoryCache->flush();
        $this->actionManager->removeEvents();
        $this->dispatcher->dispatch('app.bootstrap');
    }
}
