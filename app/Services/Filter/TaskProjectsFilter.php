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
use Jitamin\Model\TaskModel;

/**
 * Filter tasks by project ids.
 */
class TaskProjectsFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['projects'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        if (empty($this->value)) {
            $this->query->eq(TaskModel::TABLE.'.project_id', 0);
        } else {
            $this->query->in(TaskModel::TABLE.'.project_id', $this->value);
        }

        return $this;
    }
}
