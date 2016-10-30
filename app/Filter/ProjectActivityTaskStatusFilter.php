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
 * Filter activity events by task status
 */
class ProjectActivityTaskStatusFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('status');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        if ($this->value === 'open') {
            $this->query->eq(TaskModel::TABLE.'.is_active', TaskModel::STATUS_OPEN);
        } elseif ($this->value === 'closed') {
            $this->query->eq(TaskModel::TABLE.'.is_active', TaskModel::STATUS_CLOSED);
        }

        return $this;
    }
}
