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
use Hiject\Model\CommentModel;
use Hiject\Model\TaskModel;

/**
 * Filter tasks by comment
 */
class TaskCommentFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return ['comment'];
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query->ilike(CommentModel::TABLE.'.comment', '%'.$this->value.'%');
        $this->query->join(CommentModel::TABLE, 'task_id', 'id', TaskModel::TABLE);

        return $this;
    }
}
