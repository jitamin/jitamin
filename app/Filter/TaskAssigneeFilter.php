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
use Hiject\Model\TaskModel;
use Hiject\Model\UserModel;

/**
 * Filter tasks by assignee
 */
class TaskAssigneeFilter extends BaseFilter implements FilterInterface
{
    /**
     * Current user id
     *
     * @access private
     * @var int
     */
    private $currentUserId = 0;

    /**
     * Set current user id
     *
     * @access public
     * @param  integer $userId
     * @return TaskAssigneeFilter
     */
    public function setCurrentUserId($userId)
    {
        $this->currentUserId = $userId;
        return $this;
    }

    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('assignee');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return string
     */
    public function apply()
    {
        if (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(TaskModel::TABLE.'.owner_id', $this->value);
        } else {
            switch ($this->value) {
                case 'me':
                    $this->query->eq(TaskModel::TABLE.'.owner_id', $this->currentUserId);
                    break;
                case 'nobody':
                    $this->query->eq(TaskModel::TABLE.'.owner_id', 0);
                    break;
                default:
                    $this->query->beginOr();
                    $this->query->ilike(UserModel::TABLE.'.username', '%'.$this->value.'%');
                    $this->query->ilike(UserModel::TABLE.'.name', '%'.$this->value.'%');
                    $this->query->closeOr();
            }
        }
    }
}
