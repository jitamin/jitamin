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
 * Filter tasks by title
 */
class TaskTitleFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return ['title'];
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        if (ctype_digit($this->value) || (strlen($this->value) > 1 && $this->value{0} === '#' && ctype_digit(substr($this->value, 1)))) {
            $this->query->beginOr();
            $this->query->eq(TaskModel::TABLE.'.id', str_replace('#', '', $this->value));
            $this->query->ilike(TaskModel::TABLE.'.title', '%'.$this->value.'%');
            $this->query->closeOr();
        } else {
            $this->query->ilike(TaskModel::TABLE.'.title', '%'.$this->value.'%');
        }

        return $this;
    }
}
