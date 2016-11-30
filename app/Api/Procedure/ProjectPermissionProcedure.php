<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Api\Procedure;

use Hiject\Api\Authorization\ProjectAuthorization;
use Hiject\Core\Security\Role;

/**
 * Project Permission API controller.
 */
class ProjectPermissionProcedure extends BaseProcedure
{
    public function getProjectUsers($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getProjectUsers', $project_id);

        return $this->projectUserRoleModel->getAllUsers($project_id);
    }

    public function getAssignableUsers($project_id, $prepend_unassigned = false)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getAssignableUsers', $project_id);

        return $this->projectUserRoleModel->getAssignableUsersList($project_id, $prepend_unassigned);
    }

    public function addProjectUser($project_id, $user_id, $role = Role::PROJECT_MEMBER)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'addProjectUser', $project_id);

        return $this->projectUserRoleModel->addUser($project_id, $user_id, $role);
    }

    public function addProjectGroup($project_id, $group_id, $role = Role::PROJECT_MEMBER)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'addProjectGroup', $project_id);

        return $this->projectGroupRoleModel->addGroup($project_id, $group_id, $role);
    }

    public function removeProjectUser($project_id, $user_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeProjectUser', $project_id);

        return $this->projectUserRoleModel->removeUser($project_id, $user_id);
    }

    public function removeProjectGroup($project_id, $group_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeProjectGroup', $project_id);

        return $this->projectGroupRoleModel->removeGroup($project_id, $group_id);
    }

    public function changeProjectUserRole($project_id, $user_id, $role)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'changeProjectUserRole', $project_id);

        return $this->projectUserRoleModel->changeUserRole($project_id, $user_id, $role);
    }

    public function changeProjectGroupRole($project_id, $group_id, $role)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'changeProjectGroupRole', $project_id);

        return $this->projectGroupRoleModel->changeGroupRole($project_id, $group_id, $role);
    }

    public function getProjectUserRole($project_id, $user_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getProjectUserRole', $project_id);

        return $this->projectUserRoleModel->getUserRole($project_id, $user_id);
    }
}
