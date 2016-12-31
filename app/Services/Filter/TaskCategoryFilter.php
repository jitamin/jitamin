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
use Jitamin\Model\CategoryModel;
use Jitamin\Model\TaskModel;

/**
 * Filter tasks by category.
 */
class TaskCategoryFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['category'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        if (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(TaskModel::TABLE.'.category_id', $this->value);
        } elseif ($this->value === 'none') {
            $this->query->eq(TaskModel::TABLE.'.category_id', 0);
        } else {
            $this->query->eq(CategoryModel::TABLE.'.name', $this->value);
        }

        return $this;
    }
}
