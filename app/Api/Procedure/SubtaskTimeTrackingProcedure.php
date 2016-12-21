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
    public function hasSubtaskTimer($subtask_id, $user_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'hasSubtaskTimer', $subtask_id);

        return $this->subtaskTimeTrackingModel->hasTimer($subtask_id, $user_id);
    }

    public function setSubtaskStartTime($subtask_id, $user_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'setSubtaskStartTime', $subtask_id);

        return $this->subtaskTimeTrackingModel->logStartTime($subtask_id, $user_id);
    }

    public function setSubtaskEndTime($subtask_id, $user_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'setSubtaskEndTime', $subtask_id);

        return $this->subtaskTimeTrackingModel->logEndTime($subtask_id, $user_id);
    }

    public function getSubtaskTimeSpent($subtask_id, $user_id)
    {
        SubtaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getSubtaskTimeSpent', $subtask_id);

        return $this->subtaskTimeTrackingModel->getTimeSpent($subtask_id, $user_id);
    }
}
