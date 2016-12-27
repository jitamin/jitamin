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

use Jitamin\Core\Base;
use JsonRPC\Exception\AccessDeniedException;

/**
 * Class ProjectAuthorization.
 */
class ProjectAuthorization extends Base
{
    /**
     * Determine if the current user has permissions.
     *
     * @param string $class
     * @param string $method
     * @param int    $project_id
     *
     * @throws \JsonRPC\Exception\AccessDeniedException
     */
    public function check($class, $method, $project_id)
    {
        if ($this->userSession->isLogged()) {
            $this->checkProjectPermission($class, $method, $project_id);
        }
    }

    /**
     * Check project permmision.
     *
     * @param string $class
     * @param string $method
     * @param int    $project_id
     *
     * @throws \JsonRPC\Exception\AccessDeniedException
     */
    protected function checkProjectPermission($class, $method, $project_id)
    {
        if (empty($project_id)) {
            throw new AccessDeniedException('Project not found');
        }

        $role = $this->projectUserRoleModel->getUserRole($project_id, $this->userSession->getId());

        if (!$this->apiProjectAuthorization->isAllowed($class, $method, $role)) {
            throw new AccessDeniedException('Project access denied');
        }
    }
}
