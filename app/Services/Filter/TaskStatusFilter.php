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

use Jitamin\Core\Filter\FilterInterface;
use Jitamin\Model\TaskModel;

/**
 * Filter tasks by status.
 */
class TaskStatusFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['status'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        if ($this->value === 'open' || $this->value === 'closed') {
            $this->query->eq(TaskModel::TABLE.'.is_active', $this->value === 'open' ? TaskModel::STATUS_OPEN : TaskModel::STATUS_CLOSED);
        } elseif (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(TaskModel::TABLE.'.is_active', $this->value);
        }

        return $this;
    }
}
