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

/**
 * Filter project by status
 */
class ProjectStatusFilter extends BaseFilter implements FilterInterface
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
        if (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(ProjectModel::TABLE.'.is_active', $this->value);
        } elseif ($this->value === 'inactive' || $this->value === 'closed' || $this->value === 'disabled') {
            $this->query->eq(ProjectModel::TABLE.'.is_active', 0);
        } else {
            $this->query->eq(ProjectModel::TABLE.'.is_active', 1);
        }

        return $this;
    }
}
