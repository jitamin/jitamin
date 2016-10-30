<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Middleware;

use Hiject\Core\Controller\AccessForbiddenException;
use Hiject\Core\Controller\BaseMiddleware;

/**
 * Class ProjectAuthorizationMiddleware
 */
class ProjectAuthorizationMiddleware extends BaseMiddleware
{
    /**
     * Execute middleware
     */
    public function execute()
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $task_id = $this->request->getIntegerParam('task_id');

        if ($task_id > 0 && $project_id === 0) {
            $project_id = $this->taskFinderModel->getProjectId($task_id);
        }

        if ($project_id > 0 && ! $this->helper->user->hasProjectAccess($this->router->getController(), $this->router->getAction(), $project_id)) {
            throw new AccessForbiddenException();
        }

        $this->next();
    }
}
