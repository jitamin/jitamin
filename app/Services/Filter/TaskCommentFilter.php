<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Filter;

use Jitamin\Foundation\Filter\FilterInterface;
use Jitamin\Model\CommentModel;
use Jitamin\Model\TaskModel;

/**
 * Filter tasks by comment.
 */
class TaskCommentFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['comment'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query->ilike(CommentModel::TABLE.'.comment', '%'.$this->value.'%');
        $this->query->join(CommentModel::TABLE, 'task_id', 'id', TaskModel::TABLE);

        return $this;
    }
}
