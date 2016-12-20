<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Filter;

use Hiject\Core\Filter\FilterInterface;
use Hiject\Model\SubtaskModel;
use Hiject\Model\TaskModel;
use Hiject\Model\UserModel;
use PicoDb\Database;
use PicoDb\Table;

/**
 * Filter tasks by subtasks assignee.
 */
class TaskSubtaskAssigneeFilter extends BaseFilter implements FilterInterface
{
    /**
     * Database object.
     *
     * @var Database
     */
    private $db;

    /**
     * Current user id.
     *
     * @var int
     */
    private $currentUserId = 0;

    /**
     * Set current user id.
     *
     * @param int $userId
     *
     * @return TaskSubtaskAssigneeFilter
     */
    public function setCurrentUserId($userId)
    {
        $this->currentUserId = $userId;

        return $this;
    }

    /**
     * Set database object.
     *
     * @param Database $db
     *
     * @return TaskSubtaskAssigneeFilter
     */
    public function setDatabase(Database $db)
    {
        $this->db = $db;

        return $this;
    }

    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['subtask:assignee'];
    }

    /**
     * Apply filter.
     *
     * @return string
     */
    public function apply()
    {
        $task_ids = $this->getSubQuery()->findAllByColumn('task_id');

        if (!empty($task_ids)) {
            $this->query->in(TaskModel::TABLE.'.id', $task_ids);
        } else {
            $this->query->eq(TaskModel::TABLE.'.id', 0); // No match
        }
    }

    /**
     * Get subquery.
     *
     * @return Table
     */
    protected function getSubQuery()
    {
        $subquery = $this->db->table(SubtaskModel::TABLE)
            ->columns(
                SubtaskModel::TABLE.'.user_id',
                SubtaskModel::TABLE.'.task_id',
                UserModel::TABLE.'.name',
                UserModel::TABLE.'.username'
            )
            ->join(UserModel::TABLE, 'id', 'user_id', SubtaskModel::TABLE)
            ->neq(SubtaskModel::TABLE.'.status', SubtaskModel::STATUS_DONE);

        return $this->applySubQueryFilter($subquery);
    }

    /**
     * Apply subquery filter.
     *
     * @param Table $subquery
     *
     * @return Table
     */
    protected function applySubQueryFilter(Table $subquery)
    {
        if (is_int($this->value) || ctype_digit($this->value)) {
            $subquery->eq(SubtaskModel::TABLE.'.user_id', $this->value);
        } else {
            switch ($this->value) {
                case 'me':
                    $subquery->eq(SubtaskModel::TABLE.'.user_id', $this->currentUserId);
                    break;
                case 'nobody':
                    $subquery->eq(SubtaskModel::TABLE.'.user_id', 0);
                    break;
                default:
                    $subquery->beginOr();
                    $subquery->ilike(UserModel::TABLE.'.username', $this->value.'%');
                    $subquery->ilike(UserModel::TABLE.'.name', '%'.$this->value.'%');
                    $subquery->closeOr();
            }
        }

        return $subquery;
    }
}
