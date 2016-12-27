<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

use Jitamin\Api\Authorization\SubtaskAuthorization;

/**
 * Subtask Time Tracking API controller.
 */
class SubtaskTimeTrackingProcedure extends BaseProcedure
{
    /**
     * Return true if a timer is started for this use and subtask.
     *
     * @param int $subtask_id
     * @param int $user_id
     *
     * @return bool
     */
    public function hasSubtaskTimer($subtask_id, $user_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'hasSubtaskTimer', $subtask_id);

        return $this->subtaskTimeTrackingModel->hasTimer($subtask_id, $user_id);
    }

    /**
     * Log start time.
     *
     * @param int $subtask_id
     * @param int $user_id
     *
     * @return bool
     */
    public function setSubtaskStartTime($subtask_id, $user_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'setSubtaskStartTime', $subtask_id);

        return $this->subtaskTimeTrackingModel->logStartTime($subtask_id, $user_id);
    }

    /**
     * Log end time.
     *
     * @param int $subtask_id
     * @param int $user_id
     *
     * @return bool
     */
    public function setSubtaskEndTime($subtask_id, $user_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'setSubtaskEndTime', $subtask_id);

        return $this->subtaskTimeTrackingModel->logEndTime($subtask_id, $user_id);
    }

    /**
     * Calculate the time spent when the clock is stopped.
     *
     * @param int $subtask_id
     * @param int $user_id
     *
     * @return float
     */
    public function getSubtaskTimeSpent($subtask_id, $user_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getSubtaskTimeSpent', $subtask_id);

        return $this->subtaskTimeTrackingModel->getTimeSpent($subtask_id, $user_id);
    }
}
