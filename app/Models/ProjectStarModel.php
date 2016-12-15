<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Model;

use Hiject\Core\Database\Model;

/**
 * Project Star Model.
 */
class ProjectStarModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'project_has_stars';

    /**
     * Get query to fetch all projects.
     *
     * @param int $user_id
     *
     * @return \PicoDb\Table
     */
    public function getQuery($user_id)
    {
        return $this->db->table(self::TABLE)
            ->join(ProjectModel::TABLE, 'id', 'project_id')
            ->eq('user_id', $user_id);
    }

    /**
     * Get query to fetch all stargazers.
     *
     * @param int $project_id
     *
     * @return \PicoDb\Table
     */
    public function getQueryForStargazers($project_id)
    {
        return $this->db->table(self::TABLE)
            ->join(UserModel::TABLE, 'id', 'user_id')
            ->eq('project_id', $project_id);
    }

    /**
     * Get query to fetch all project ids.
     *
     * @param int $project_id
     *
     * @return \PicoDb\Table
     */
    public function getProjectIds($user_id)
    {
        return array_map(function ($project) {
            return $project['id'];
        }, $this->getProjects($user_id));
    }

    /**
     * Get all stargazers.
     *
     * @param int $group_id
     *
     * @return array
     */
    public function getStargazers($project_id)
    {
        return $this->getQueryForStargazers($project_id)->findAll();
    }

    /**
     * Add stargazer to a project.
     *
     * @param int $group_id
     * @param int $user_id
     *
     * @return bool
     */
    public function addStargazer($project_id, $user_id)
    {
        return $this->db->table(self::TABLE)->insert([
            'project_id' => $project_id,
            'user_id'    => $user_id,
        ]);
    }

    /**
     * Remove stargazer from a project.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return bool
     */
    public function removeStargazer($project_id, $user_id)
    {
        return $this->db->table(self::TABLE)
            ->eq('project_id', $project_id)
            ->eq('user_id', $user_id)
            ->remove();
    }

    /**
     * Check if a user is stargazer.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return bool
     */
    public function isStargazer($project_id, $user_id)
    {
        return $this->db->table(self::TABLE)
            ->eq('project_id', $project_id)
            ->eq('user_id', $user_id)
            ->exists();
    }

    /**
     * Get all projects for a given stargazer.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getProjects($user_id)
    {
        return $this->db->table(self::TABLE)
            ->columns(ProjectModel::TABLE.'.id', ProjectModel::TABLE.'.name')
            ->join(ProjectModel::TABLE, 'id', 'project_id')
            ->eq(self::TABLE.'.user_id', $user_id)
            ->asc(ProjectModel::TABLE.'.name')
            ->findAll();
    }
}
