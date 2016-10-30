<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Api\Authorization;

/**
 * Class TaskLinkAuthorization
 */
class TaskLinkAuthorization extends ProjectAuthorization
{
    public function check($class, $method, $task_link_id)
    {
        if ($this->userSession->isLogged()) {
            $this->checkProjectPermission($class, $method, $this->taskLinkModel->getProjectId($task_link_id));
        }
    }
}
