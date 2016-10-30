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
use Hiject\Model\ColorModel;
use Hiject\Model\TaskModel;

/**
 * Filter tasks by color
 */
class TaskColorFilter extends BaseFilter implements FilterInterface
{
    /**
     * Color object
     *
     * @access private
     * @var    ColorModel
     */
    private $colorModel;

    /**
     * Set color model object
     *
     * @access public
     * @param  ColorModel $colorModel
     * @return TaskColorFilter
     */
    public function setColorModel(ColorModel $colorModel)
    {
        $this->colorModel = $colorModel;
        return $this;
    }

    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('color', 'colour');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query->eq(TaskModel::TABLE.'.color_id', $this->colorModel->find($this->value));
        return $this;
    }
}
