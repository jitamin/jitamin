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
 * Column Model.
 */
class ColumnModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'columns';

    /**
     * Get a column by the id.
     *
     * @param int $column_id Column id
     *
     * @return array
     */
    public function getById($column_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $column_id)->findOne();
    }

    /**
     * Get projectId by the columnId.
     *
     * @param int $column_id Column id
     *
     * @return int
     */
    public function getProjectId($column_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $column_id)->findOneColumn('project_id');
    }

    /**
     * Get the first column id for a given project.
     *
     * @param int $project_id Project id
     *
     * @return int
     */
    public function getFirstColumnId($project_id)
    {
        return $this->db->table(self::TABLE)->eq('project_id', $project_id)->asc('position')->findOneColumn('id');
    }

    /**
     * Get the last column id for a given project.
     *
     * @param int $project_id Project id
     *
     * @return int
     */
    public function getLastColumnId($project_id)
    {
        return $this->db->table(self::TABLE)->eq('project_id', $project_id)->desc('position')->findOneColumn('id');
    }

    /**
     * Get the position of the last column for a given project.
     *
     * @param int $project_id Project id
     *
     * @return int
     */
    public function getLastColumnPosition($project_id)
    {
        return (int) $this->db
                        ->table(self::TABLE)
                        ->eq('project_id', $project_id)
                        ->desc('position')
                        ->findOneColumn('position');
    }

    /**
     * Get a column id by the name.
     *
     * @param int    $project_id
     * @param string $title
     *
     * @return int
     */
    public function getColumnIdByTitle($project_id, $title)
    {
        return (int) $this->db->table(self::TABLE)->eq('project_id', $project_id)->eq('title', $title)->findOneColumn('id');
    }

    /**
     * Get a column title by the id.
     *
     * @param int $column_id
     *
     * @return int
     */
    public function getColumnTitleById($column_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $column_id)->findOneColumn('title');
    }

    /**
     * Get all columns sorted by position for a given project.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getAll($project_id)
    {
        return $this->db->table(self::TABLE)->eq('project_id', $project_id)->asc('position')->findAll();
    }

    /**
     * Get the list of columns sorted by position [ column_id => title ].
     *
     * @param int  $project_id Project id
     * @param bool $prepend    Prepend a default value
     *
     * @return array
     */
    public function getList($project_id, $prepend = false)
    {
        $listing = $this->db->hashtable(self::TABLE)->eq('project_id', $project_id)->asc('position')->getAll('id', 'title');

        return $prepend ? [-1 => t('All columns')] + $listing : $listing;
    }

    /**
     * Add a new column to the board.
     *
     * @param int    $project_id        Project id
     * @param string $title             Column title
     * @param int    $task_limit        Task limit
     * @param string $description       Column description
     * @param int    $hide_in_dashboard
     *
     * @return bool|int
     */
    public function create($project_id, $title, $task_limit = 0, $description = '', $hide_in_dashboard = 0)
    {
        $values = [
            'project_id'        => $project_id,
            'title'             => $title,
            'task_limit'        => intval($task_limit),
            'position'          => $this->getLastColumnPosition($project_id) + 1,
            'hide_in_dashboard' => $hide_in_dashboard,
            'description'       => $description,
        ];

        return $this->db->table(self::TABLE)->persist($values);
    }

    /**
     * Update a column.
     *
     * @param int    $column_id         Column id
     * @param string $title             Column title
     * @param int    $task_limit        Task limit
     * @param string $description       Optional description
     * @param int    $hide_in_dashboard
     *
     * @return bool
     */
    public function update($column_id, $title, $task_limit = 0, $description = '', $hide_in_dashboard = 0)
    {
        return $this->db->table(self::TABLE)->eq('id', $column_id)->update([
            'title'             => $title,
            'task_limit'        => intval($task_limit),
            'hide_in_dashboard' => $hide_in_dashboard,
            'description'       => $description,
        ]);
    }

    /**
     * Remove a column and all tasks associated to this column.
     *
     * @param int $column_id Column id
     *
     * @return bool
     */
    public function remove($column_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $column_id)->remove();
    }

    /**
     * Change column position.
     *
     * @param int $project_id
     * @param int $column_id
     * @param int $position
     *
     * @return bool
     */
    public function changePosition($project_id, $column_id, $position)
    {
        if ($position < 1 || $position > $this->db->table(self::TABLE)->eq('project_id', $project_id)->count()) {
            return false;
        }

        $column_ids = $this->db->table(self::TABLE)->eq('project_id', $project_id)->neq('id', $column_id)->asc('position')->findAllByColumn('id');
        $offset = 1;
        $results = [];

        foreach ($column_ids as $current_column_id) {
            if ($offset == $position) {
                $offset++;
            }

            $results[] = $this->db->table(self::TABLE)->eq('id', $current_column_id)->update(['position' => $offset]);
            $offset++;
        }

        $results[] = $this->db->table(self::TABLE)->eq('id', $column_id)->update(['position' => $position]);

        return !in_array(false, $results, true);
    }
}
