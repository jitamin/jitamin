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

use JsonRPC\Exception\AccessDeniedException;
use Hiject\Core\Base;

/**
 * Class ProjectAuthorization
 */
class ProjectAuthorization extends Base
{
    public function check($class, $method, $project_id)
    {
        if ($this->userSession->isLogged()) {
            $this->checkProjectPermission($class, $method, $project_id);
        }
    }
    
    protected function checkProjectPermission($class, $method, $project_id)
    {
        if (empty($project_id)) {
            throw new AccessDeniedException('Project not found');
        }
        
        $role = $this->projectUserRoleModel->getUserRole($project_id, $this->userSession->getId());

        if (! $this->apiProjectAuthorization->isAllowed($class, $method, $role)) {
            throw new AccessDeniedException('Project access denied');
        }
    }
}
