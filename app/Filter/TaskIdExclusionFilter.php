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
 * Exclude task ids
 */
class TaskIdExclusionFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return ['exclude'];
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query->notin(TaskModel::TABLE.'.id', $this->value);
        return $this;
    }
}
