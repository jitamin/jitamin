<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Authorization;

/**
 * Class SubtaskAuthorization.
 */
class SubtaskAuthorization extends ProjectAuthorization
{
    public function check($class, $method, $subtask_id)
    {
        if ($this->userSession->isLogged()) {
            $this->checkProjectPermission($class, $method, $this->subtaskModel->getProjectId($subtask_id));
        }
    }
}
