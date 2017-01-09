<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Middleware;

use Jitamin\Foundation\Controller\AccessForbiddenException;
use Jitamin\Foundation\Controller\BaseMiddleware;

/**
 * Class ProjectAuthorizationMiddleware.
 */
class ProjectAuthorizationMiddleware extends BaseMiddleware
{
    /**
     * Execute middleware.
     */
    public function execute()
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $task_id = $this->request->getIntegerParam('task_id');

        if ($task_id > 0 && $project_id === 0) {
            $project_id = $this->taskFinderModel->getProjectId($task_id);
        }

        if ($project_id > 0 && !$this->helper->user->hasProjectAccess($this->router->getController(), $this->router->getAction(), $project_id)) {
            throw new AccessForbiddenException();
        }

        $this->next();
    }
}
