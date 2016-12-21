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

/**
 * Class ProjectMetricJob.
 */
class ProjectMetricJob extends BaseJob
{
    /**
     * Set job parameters.
     *
     * @param int $projectId
     *
     * @return $this
     */
    public function withParams($projectId)
    {
        $this->jobParams = [$projectId];

        return $this;
    }

    /**
     * Execute job.
     *
     * @param int $projectId
     */
    public function execute($projectId)
    {
        $this->logger->debug(__METHOD__.' Run project metrics calculation');
        $now = date('Y-m-d');

        $this->projectDailyColumnStatsModel->updateTotals($projectId, $now);
        $this->projectDailyStatsModel->updateTotals($projectId, $now);
    }
}
