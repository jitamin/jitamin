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
use Hiject\Model\SwimlaneModel;
use Hiject\Model\TaskModel;

/**
 * Filter tasks by swimlane.
 */
class TaskSwimlaneFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['swimlane'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        if (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(TaskModel::TABLE.'.swimlane_id', $this->value);
        } elseif ($this->value === 'default') {
            $this->query->eq(TaskModel::TABLE.'.swimlane_id', 0);
        } else {
            $this->query->beginOr();
            $this->query->ilike(SwimlaneModel::TABLE.'.name', $this->value);
            $this->query->ilike(ProjectModel::TABLE.'.default_swimlane', $this->value);
            $this->query->closeOr();
        }

        return $this;
    }
}
