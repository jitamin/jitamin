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
 * Class TaskAuthorization.
 */
class TaskAuthorization extends ProjectAuthorization
{
    /**
     * Determine if the current user has permissions.
     *
     * @param string $class
     * @param string $method
     * @param int    $category_id
     *
     * @throws \JsonRPC\Exception\AccessDeniedException
     */
    public function check($class, $method, $category_id)
    {
        if ($this->userSession->isLogged()) {
            $this->checkProjectPermission($class, $method, $this->taskFinderModel->getProjectId($category_id));
        }
    }
}
