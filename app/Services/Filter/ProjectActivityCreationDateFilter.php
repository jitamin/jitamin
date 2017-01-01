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
use Jitamin\Model\ProjectActivityModel;

/**
 * Filter activity events by creation date.
 */
class ProjectActivityCreationDateFilter extends BaseDateFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['created'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        $this->applyDateFilter(ProjectActivityModel::TABLE.'.date_creation');

        return $this;
    }
}
