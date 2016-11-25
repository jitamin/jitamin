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
use Hiject\Model\ProjectActivityModel;

/**
 * Filter activity events by projectId
 */
class ProjectActivityProjectIdFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return ['project_id'];
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query->eq(ProjectActivityModel::TABLE.'.project_id', $this->value);
        return $this;
    }
}
