<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Api;

use Jitamin\Policy\ColumnPolicy;
use Jitamin\Policy\ProjectPolicy;

/**
 * Column API controller.
 */
class ColumnController extends Controller
{
    /**
     * Get all columns sorted by position for a given project.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getColumns($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getColumns', $project_id);

        return $this->columnModel->getAll($project_id);
    }

    /**
     * Get a column by the id.
     *
     * @param int $column_id Column id
     *
     * @return array
     */
    public function getColumn($column_id)
    {
        ColumnPolicy::getInstance($this->container)->check($this->getClassName(), 'getColumn', $column_id);

        return $this->columnModel->getById($column_id);
    }

    /**
     * Update a column.
     *
     * @param int    $column_id   Column id
     * @param string $title       Column title
     * @param int    $task_limit  Task limit
     * @param string $description Optional description
     *
     * @return bool
     */
    public function updateColumn($column_id, $title, $task_limit = 0, $description = '')
    {
        ColumnPolicy::getInstance($this->container)->check($this->getClassName(), 'updateColumn', $column_id);

        return $this->columnModel->update($column_id, $title, $task_limit, $description);
    }

    /**
     * Add a new column to the board.
     *
     * @param int    $project_id  Project id
     * @param string $title       Column title
     * @param int    $task_limit  Task limit
     * @param string $description Column description
     *
     * @return bool|int
     */
    public function addColumn($project_id, $title, $task_limit = 0, $description = '')
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'addColumn', $project_id);

        return $this->columnModel->create($project_id, $title, $task_limit, $description);
    }

    /**
     * Remove a column and all tasks associated to this column.
     *
     * @param int $column_id Column id
     *
     * @return bool
     */
    public function removeColumn($column_id)
    {
        ColumnPolicy::getInstance($this->container)->check($this->getClassName(), 'removeColumn', $column_id);

        return $this->columnModel->remove($column_id);
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
    public function changeColumnPosition($project_id, $column_id, $position)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'changeColumnPosition', $project_id);

        return $this->columnModel->changePosition($project_id, $column_id, $position);
    }
}
