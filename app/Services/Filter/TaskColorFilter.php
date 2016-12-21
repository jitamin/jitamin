<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Filter;

use Jitamin\Core\Filter\FilterInterface;
use Jitamin\Model\ColorModel;
use Jitamin\Model\TaskModel;

/**
 * Filter tasks by color.
 */
class TaskColorFilter extends BaseFilter implements FilterInterface
{
    /**
     * Color object.
     *
     * @var ColorModel
     */
    private $colorModel;

    /**
     * Set color model object.
     *
     * @param ColorModel $colorModel
     *
     * @return TaskColorFilter
     */
    public function setColorModel(ColorModel $colorModel)
    {
        $this->colorModel = $colorModel;

        return $this;
    }

    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['color', 'colour'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query->eq(TaskModel::TABLE.'.color_id', $this->colorModel->find($this->value));

        return $this;
    }
}
