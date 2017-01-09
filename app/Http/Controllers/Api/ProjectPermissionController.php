<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Api;

use Jitamin\Foundation\Security\Role;
use Jitamin\Policy\ProjectPolicy;

/**
 * Project Permission API controller.
 */
class ProjectPermissionController extends Controller
{
    /**
     * Get all users (fetch users from groups).
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getProjectUsers($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getProjectUsers', $project_id);

        return $this->projectUserRoleModel->getAllUsers($project_id);
    }

    /**
     * Get list of users that can be assigned to a task (only Manager and Member).
     *
     * @param int  $project_id Project id
     * @param bool $unassigned Prepend the 'Unassigned' value
     *
     * @return array
     */
    public function getAssignableUsers($project_id, $prepend_unassigned = false)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getAssignableUsers', $project_id);

        return $this->projectUserRoleModel->getAssignableUsersList($project_id, $prepend_unassigned);
    }

    /**
     * Add a user to the project.
     *
     * @param int    $project_id
     * @param int    $user_id
     * @param string $role
     *
     * @return bool
     */
    public function addProjectUser($project_id, $user_id, $role = Role::PROJECT_MEMBER)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'addProjectUser', $project_id);

        return $this->projectUserRoleModel->addUser($project_id, $user_id, $role);
    }

    /**
     * Add a group to the project.
     *
     * @param int    $project_id
     * @param int    $group_id
     * @param string $role
     *
     * @return bool
     */
    public function addProjectGroup($project_id, $group_id, $role = Role::PROJECT_MEMBER)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'addProjectGroup', $project_id);

        return $this->projectGroupRoleModel->addGroup($project_id, $group_id, $role);
    }

    /**
     * Remove a user from the project.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return bool
     */
    public function removeProjectUser($project_id, $user_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'removeProjectUser', $project_id);

        return $this->projectUserRoleModel->removeUser($project_id, $user_id);
    }

    /**
     * Remove a group from the project.
     *
     * @param int $project_id
     * @param int $group_id
     *
     * @return bool
     */
    public function removeProjectGroup($project_id, $group_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'removeProjectGroup', $project_id);

        return $this->projectGroupRoleModel->removeGroup($project_id, $group_id);
    }

    /**
     * Change a user role for the project.
     *
     * @param int    $project_id
     * @param int    $user_id
     * @param string $role
     *
     * @return bool
     */
    public function changeProjectUserRole($project_id, $user_id, $role)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'changeProjectUserRole', $project_id);

        return $this->projectUserRoleModel->changeUserRole($project_id, $user_id, $role);
    }

    /**
     * Change a group role for the project.
     *
     * @param int    $project_id
     * @param int    $group_id
     * @param string $role
     *
     * @return bool
     */
    public function changeProjectGroupRole($project_id, $group_id, $role)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'changeProjectGroupRole', $project_id);

        return $this->projectGroupRoleModel->changeGroupRole($project_id, $group_id, $role);
    }

    /**
     * For a given project get the role of the specified user.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return string
     */
    public function getProjectUserRole($project_id, $user_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getProjectUserRole', $project_id);

        return $this->projectUserRoleModel->getUserRole($project_id, $user_id);
    }
}
