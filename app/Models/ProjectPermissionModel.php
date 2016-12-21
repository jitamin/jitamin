<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Core\Database\Model;
use Jitamin\Core\Security\Role;
use Jitamin\Filter\ProjectGroupRoleProjectFilter;
use Jitamin\Filter\ProjectGroupRoleUsernameFilter;
use Jitamin\Filter\ProjectUserRoleProjectFilter;
use Jitamin\Filter\ProjectUserRoleUsernameFilter;

/**
 * Project Permission.
 */
class ProjectPermissionModel extends Model
{
    /**
     * Get query for project users overview.
     *
     * @param array  $project_ids
     * @param string $role
     *
     * @return \PicoDb\Table
     */
    public function getQueryByRole(array $project_ids, $role)
    {
        if (empty($project_ids)) {
            $project_ids = [-1];
        }

        return $this
            ->db
            ->table(ProjectUserRoleModel::TABLE)
            ->join(UserModel::TABLE, 'id', 'user_id')
            ->join(ProjectModel::TABLE, 'id', 'project_id')
            ->eq(ProjectUserRoleModel::TABLE.'.role', $role)
            ->eq(ProjectModel::TABLE.'.is_private', 0)
            ->in(ProjectModel::TABLE.'.id', $project_ids)
            ->columns(
                UserModel::TABLE.'.id',
                UserModel::TABLE.'.username',
                UserModel::TABLE.'.name',
                ProjectModel::TABLE.'.name AS project_name',
                ProjectModel::TABLE.'.id'
            );
    }

    /**
     * Get all usernames (fetch users from groups).
     *
     * @param int    $project_id
     * @param string $input
     *
     * @return array
     */
    public function findUsernames($project_id, $input)
    {
        $userMembers = $this->projectUserRoleQuery
            ->withFilter(new ProjectUserRoleProjectFilter($project_id))
            ->withFilter(new ProjectUserRoleUsernameFilter($input))
            ->getQuery()
            ->findAllByColumn('username');

        $groupMembers = $this->projectGroupRoleQuery
            ->withFilter(new ProjectGroupRoleProjectFilter($project_id))
            ->withFilter(new ProjectGroupRoleUsernameFilter($input))
            ->getQuery()
            ->findAllByColumn('username');

        $members = array_unique(array_merge($userMembers, $groupMembers));

        sort($members);

        return $members;
    }

    /**
     * Return true if everybody is allowed for the project.
     *
     * @param int $project_id Project id
     *
     * @return bool
     */
    public function isEverybodyAllowed($project_id)
    {
        return $this->db
                    ->table(ProjectModel::TABLE)
                    ->eq('id', $project_id)
                    ->eq('is_everybody_allowed', 1)
                    ->exists();
    }

    /**
     * Return true if the user is allowed to access a project.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return bool
     */
    public function isUserAllowed($project_id, $user_id)
    {
        if ($this->userSession->isAdmin()) {
            return true;
        }

        return in_array(
            $this->projectUserRoleModel->getUserRole($project_id, $user_id),
            [Role::PROJECT_MANAGER, Role::PROJECT_MEMBER, Role::PROJECT_VIEWER]
        );
    }

    /**
     * Return true if the user is assignable.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return bool
     */
    public function isAssignable($project_id, $user_id)
    {
        if ($this->userModel->isActive($user_id)) {
            $role = $this->projectUserRoleModel->getUserRole($project_id, $user_id);

            return !empty($role) && $role !== Role::PROJECT_VIEWER;
        }

        return false;
    }

    /**
     * Return true if the user is member.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return bool
     */
    public function isMember($project_id, $user_id)
    {
        return in_array($this->projectUserRoleModel->getUserRole($project_id, $user_id), [Role::PROJECT_MEMBER, Role::PROJECT_MANAGER, Role::PROJECT_VIEWER]);
    }

    /**
     * Get active project ids by user.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getActiveProjectIds($user_id)
    {
        return array_keys($this->projectUserRoleModel->getActiveProjectsByUser($user_id));
    }

    /**
     * Get all project ids by user.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getProjectIds($user_id)
    {
        return array_keys($this->projectUserRoleModel->getProjectsByUser($user_id));
    }

    /**
     * Copy permissions to another project.
     *
     * @param int $project_src_id Project Template
     * @param int $project_dst_id Project that receives the copy
     *
     * @return bool
     */
    public function duplicate($project_src_id, $project_dst_id)
    {
        return $this->projectUserRoleModel->duplicate($project_src_id, $project_dst_id) &&
            $this->projectGroupRoleModel->duplicate($project_src_id, $project_dst_id);
    }
}
