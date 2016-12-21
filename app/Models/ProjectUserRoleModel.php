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

/**
 * Project User Role.
 */
class ProjectUserRoleModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'project_has_users';

    /**
     * Get the list of active project for the given user.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getActiveProjectsByUser($user_id)
    {
        return $this->getProjectsByUser($user_id, [ProjectModel::ACTIVE]);
    }

    /**
     * Get the list of project visible for the given user.
     *
     * @param int   $user_id
     * @param array $status
     *
     * @return array
     */
    public function getProjectsByUser($user_id, $status = [ProjectModel::ACTIVE, ProjectModel::INACTIVE])
    {
        $userProjects = $this->db
            ->hashtable(ProjectModel::TABLE)
            ->beginOr()
            ->eq(self::TABLE.'.user_id', $user_id)
            ->eq(ProjectModel::TABLE.'.is_everybody_allowed', 1)
            ->closeOr()
            ->in(ProjectModel::TABLE.'.is_active', $status)
            ->join(self::TABLE, 'project_id', 'id')
            ->getAll(ProjectModel::TABLE.'.id', ProjectModel::TABLE.'.name');

        $groupProjects = $this->projectGroupRoleModel->getProjectsByUser($user_id, $status);
        $projects = $userProjects + $groupProjects;

        asort($projects);

        return $projects;
    }

    /**
     * For a given project get the role of the specified user.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return string
     */
    public function getUserRole($project_id, $user_id)
    {
        $projectInfo = $this->db->table(ProjectModel::TABLE)
            ->eq('id', $project_id)
            ->columns('owner_id', 'is_everybody_allowed')
            ->findOne();

        if ($projectInfo['is_everybody_allowed'] == 1) {
            return $projectInfo['owner_id'] == $user_id ? Role::PROJECT_MANAGER : Role::PROJECT_MEMBER;
        }

        $role = $this->db->table(self::TABLE)->eq('user_id', $user_id)->eq('project_id', $project_id)->findOneColumn('role');

        if (empty($role)) {
            $role = $this->projectGroupRoleModel->getUserRole($project_id, $user_id);
        }

        return $role;
    }

    /**
     * Get all users associated directly to the project.
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getUsers($project_id)
    {
        return $this->db->table(self::TABLE)
            ->columns(UserModel::TABLE.'.id', UserModel::TABLE.'.username', UserModel::TABLE.'.name', self::TABLE.'.role')
            ->join(UserModel::TABLE, 'id', 'user_id')
            ->eq('project_id', $project_id)
            ->asc(UserModel::TABLE.'.username')
            ->asc(UserModel::TABLE.'.name')
            ->findAll();
    }

    /**
     * Get all users (fetch users from groups).
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getAllUsers($project_id)
    {
        $userMembers = $this->getUsers($project_id);
        $groupMembers = $this->projectGroupRoleModel->getUsers($project_id);
        $members = array_merge($userMembers, $groupMembers);

        return $this->userModel->prepareList($members);
    }

    /**
     * Get users grouped by role.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getAllUsersGroupedByRole($project_id)
    {
        $users = [];

        $userMembers = $this->getUsers($project_id);
        $groupMembers = $this->projectGroupRoleModel->getUsers($project_id);
        $members = array_merge($userMembers, $groupMembers);

        foreach ($members as $user) {
            if (!isset($users[$user['role']])) {
                $users[$user['role']] = [];
            }

            $users[$user['role']][$user['id']] = $user['name'] ?: $user['username'];
        }

        return $users;
    }

    /**
     * Get list of users that can be assigned to a task (only Manager and Member).
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getAssignableUsers($project_id)
    {
        if ($this->projectPermissionModel->isEverybodyAllowed($project_id)) {
            return $this->userModel->getActiveUsersList();
        }

        $userMembers = $this->db->table(self::TABLE)
            ->columns(UserModel::TABLE.'.id', UserModel::TABLE.'.username', UserModel::TABLE.'.name')
            ->join(UserModel::TABLE, 'id', 'user_id')
            ->eq(UserModel::TABLE.'.is_active', 1)
            ->eq(self::TABLE.'.project_id', $project_id)
            ->neq(self::TABLE.'.role', Role::PROJECT_VIEWER)
            ->findAll();

        $groupMembers = $this->projectGroupRoleModel->getAssignableUsers($project_id);
        $members = array_merge($userMembers, $groupMembers);

        return $this->userModel->prepareList($members);
    }

    /**
     * Get list of users that can be assigned to a task (only Manager and Member).
     *
     * @param int  $project_id Project id
     * @param bool $unassigned Prepend the 'Unassigned' value
     * @param bool $everybody  Prepend the 'Everbody' value
     * @param bool $singleUser If there is only one user return only this user
     *
     * @return array
     */
    public function getAssignableUsersList($project_id, $unassigned = true, $everybody = false, $singleUser = false)
    {
        $users = $this->getAssignableUsers($project_id);

        if ($singleUser && count($users) === 1) {
            return $users;
        }

        if ($unassigned) {
            $users = [t('Unassigned')] + $users;
        }

        if ($everybody) {
            $users = [UserModel::EVERYBODY_ID => t('Everybody')] + $users;
        }

        return $users;
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
    public function addUser($project_id, $user_id, $role)
    {
        return $this->db->table(self::TABLE)->insert([
            'user_id'    => $user_id,
            'project_id' => $project_id,
            'role'       => $role,
        ]);
    }

    /**
     * Remove a user from the project.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return bool
     */
    public function removeUser($project_id, $user_id)
    {
        return $this->db->table(self::TABLE)->eq('user_id', $user_id)->eq('project_id', $project_id)->remove();
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
    public function changeUserRole($project_id, $user_id, $role)
    {
        return $this->db->table(self::TABLE)
            ->eq('user_id', $user_id)
            ->eq('project_id', $project_id)
            ->update([
                'role' => $role,
            ]);
    }

    /**
     * Copy user access from a project to another one.
     *
     * @param int $project_src_id
     * @param int $project_dst_id
     *
     * @return bool
     */
    public function duplicate($project_src_id, $project_dst_id)
    {
        $rows = $this->db->table(self::TABLE)->eq('project_id', $project_src_id)->findAll();

        foreach ($rows as $row) {
            $result = $this->db->table(self::TABLE)->save([
                'project_id' => $project_dst_id,
                'user_id'    => $row['user_id'],
                'role'       => $row['role'],
            ]);

            if (!$result) {
                return false;
            }
        }

        return true;
    }
}
