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
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;

/**
 * Filter tasks by project.
 */
class TaskProjectFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['project'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        if (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(TaskModel::TABLE.'.project_id', $this->value);
        } else {
            $this->query->ilike(ProjectModel::TABLE.'.name', $this->value);
        }

        return $this;
    }
}
