<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Foundation\Database\Model;
use Jitamin\Foundation\Security\Role;

/**
 * Project Duplication.
 */
class ProjectDuplicationModel extends Model
{
    /**
     * Get list of optional models to duplicate.
     *
     * @return string[]
     */
    public function getOptionalSelection()
    {
        return [
            'categoryModel',
            'projectPermissionModel',
            'actionModel',
            'swimlaneModel',
            'tagDuplicationModel',
            'projectMetadataModel',
            'projectTaskDuplicationModel',
        ];
    }

    /**
     * Get list of all possible models to duplicate.
     *
     * @return string[]
     */
    public function getPossibleSelection()
    {
        return [
            'boardModel',
            'categoryModel',
            'projectPermissionModel',
            'actionModel',
            'swimlaneModel',
            'tagDuplicationModel',
            'projectMetadataModel',
            'projectTaskDuplicationModel',
        ];
    }

    /**
     * Get a valid project name for the duplication.
     *
     * @param string $name       Project name
     * @param int    $max_length Max length allowed
     *
     * @return string
     */
    public function getClonedProjectName($name, $max_length = 50)
    {
        $suffix = ' ('.t('Clone').')';

        if (strlen($name.$suffix) > $max_length) {
            $name = substr($name, 0, $max_length - strlen($suffix));
        }

        return $name.$suffix;
    }

    /**
     * Clone a project with all settings.
     *
     * @param int    $src_project_id Project Id
     * @param array  $selection      Selection of optional project parts to duplicate
     * @param int    $owner_id       Owner of the project
     * @param string $name           Name of the project
     * @param bool   $private        Force the project to be private
     *
     * @return int Cloned Project Id
     */
    public function duplicate($src_project_id, $selection = ['projectPermissionModel', 'categoryModel', 'actionModel'], $owner_id = 0, $name = null, $private = null)
    {
        $this->db->startTransaction();

        // Get the cloned project Id
        $dst_project_id = $this->copy($src_project_id, $owner_id, $name, $private);

        if ($dst_project_id === false) {
            $this->db->cancelTransaction();

            return false;
        }

        // Clone Columns, Categories, Permissions and Actions
        foreach ($this->getPossibleSelection() as $model) {

            // Skip if optional part has not been selected
            if (in_array($model, $this->getOptionalSelection()) && !in_array($model, $selection)) {
                continue;
            }

            // Skip permissions for private projects
            if ($private && $model === 'projectPermissionModel') {
                continue;
            }

            if (!$this->$model->duplicate($src_project_id, $dst_project_id)) {
                $this->db->cancelTransaction();

                return false;
            }
        }

        if (!$this->makeOwnerManager($dst_project_id, $owner_id)) {
            $this->db->cancelTransaction();

            return false;
        }

        $this->db->closeTransaction();

        return (int) $dst_project_id;
    }

    /**
     * Create a project from another one.
     *
     * @param int    $src_project_id
     * @param int    $owner_id
     * @param string $name
     * @param bool   $private
     *
     * @return int
     */
    private function copy($src_project_id, $owner_id = 0, $name = null, $private = null)
    {
        $project = $this->projectModel->getById($src_project_id);
        $is_private = empty($project['is_private']) ? 0 : 1;

        $values = [
            'name'             => $name ?: $this->getClonedProjectName($project['name']),
            'is_active'        => 1,
            'last_modified'    => time(),
            'token'            => '',
            'is_public'        => 0,
            'is_private'       => $private ? 1 : $is_private,
            'owner_id'         => $owner_id,
            'priority_default' => $project['priority_default'],
            'priority_start'   => $project['priority_start'],
            'priority_end'     => $project['priority_end'],
        ];

        if (!$this->db->table(ProjectModel::TABLE)->save($values)) {
            return false;
        }

        return $this->db->getLastId();
    }

    /**
     * Make sure that the creator of the duplicated project is also owner.
     *
     * @param int $dst_project_id
     * @param int $owner_id
     *
     * @return bool
     */
    private function makeOwnerManager($dst_project_id, $owner_id)
    {
        if ($owner_id > 0) {
            $this->projectUserRoleModel->removeUser($dst_project_id, $owner_id);

            if (!$this->projectUserRoleModel->addUser($dst_project_id, $owner_id, Role::PROJECT_MANAGER)) {
                return false;
            }
        }

        return true;
    }
}
