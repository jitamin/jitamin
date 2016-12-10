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

use Hiject\Core\Base;

/**
 * Board model.
 */
class BoardModel extends Base
{
    /**
     * Get Hiject default columns.
     *
     * @return string[]
     */
    public function getDefaultColumns()
    {
        return [t('Backlog'), t('Ready'), t('Work in progress'), t('Done')];
    }

    /**
     * Get user default columns.
     *
     * @return array
     */
    public function getUserColumns()
    {
        $column_names = explode(',', $this->settingModel->get('board_columns', implode(',', $this->getDefaultColumns())));
        $columns = [];

        foreach ($column_names as $column_name) {
            $column_name = trim($column_name);

            if (!empty($column_name)) {
                $columns[] = ['title' => $column_name, 'task_limit' => 0, 'description' => ''];
            }
        }

        return $columns;
    }

    /**
     * Create a board with default columns, must be executed inside a transaction.
     *
     * @param int   $project_id Project id
     * @param array $columns    Column parameters [ 'title' => 'boo', 'task_limit' => 2 ... ]
     *
     * @return bool
     */
    public function create($project_id, array $columns)
    {
        $position = 0;

        foreach ($columns as $column) {
            $values = [
                'title'       => $column['title'],
                'position'    => ++$position,
                'project_id'  => $project_id,
                'task_limit'  => $column['task_limit'],
                'description' => $column['description'],
            ];

            if (!$this->db->table(ColumnModel::TABLE)->save($values)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Copy board columns from a project to another one.
     *
     * @author Antonio Rabelo
     *
     * @param int $project_from Project Template
     * @param int $project_to   Project that receives the copy
     *
     * @return bool
     */
    public function duplicate($project_from, $project_to)
    {
        $columns = $this->db->table(ColumnModel::TABLE)
                            ->columns('title', 'task_limit', 'description')
                            ->eq('project_id', $project_from)
                            ->asc('position')
                            ->findAll();

        return $this->boardModel->create($project_to, $columns);
    }

    /**
     * Get the total of tasks per column.
     *
     * @param int  $project_id
     * @param bool $prepend    Prepend default value
     *
     * @return array
     */
    public function getColumnStats($project_id, $prepend = false)
    {
        $listing = $this->db
                        ->hashtable(TaskModel::TABLE)
                        ->eq('project_id', $project_id)
                        ->eq('is_active', 1)
                        ->groupBy('column_id')
                        ->getAll('column_id', 'COUNT(*) AS total');

        return $prepend ? [-1 => t('All columns')] + $listing : $listing;
    }
}
