<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
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
    /**
     * Determine if the current user has permissions.
     *
     * @param string $class
     * @param string $method
     * @param int    $subtask_id
     *
     * @throws \JsonRPC\Exception\AccessDeniedException
     */
    public function check($class, $method, $subtask_id)
    {
        if ($this->userSession->isLogged()) {
            $this->checkProjectPermission($class, $method, $this->subtaskModel->getProjectId($subtask_id));
        }
    }
}
