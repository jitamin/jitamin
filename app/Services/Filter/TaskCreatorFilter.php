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

/**
 * Filter tasks by creator.
 */
class TaskCreatorFilter extends BaseFilter implements FilterInterface
{
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
     * @return TaskAssigneeFilter
     */
    public function setCurrentUserId($userId)
    {
        $this->currentUserId = $userId;

        return $this;
    }

    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['creator'];
    }

    /**
     * Apply filter.
     *
     * @return string
     */
    public function apply()
    {
        if (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(TaskModel::TABLE.'.creator_id', $this->value);
        } else {
            switch ($this->value) {
                case 'me':
                    $this->query->eq(TaskModel::TABLE.'.creator_id', $this->currentUserId);
                    break;
                case 'nobody':
                    $this->query->eq(TaskModel::TABLE.'.creator_id', 0);
                    break;
                default:
                    $this->query->beginOr();
                    $this->query->ilike('uc.username', '%'.$this->value.'%');
                    $this->query->ilike('uc.name', '%'.$this->value.'%');
                    $this->query->closeOr();
            }
        }
    }
}
