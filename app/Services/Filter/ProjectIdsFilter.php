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
use Jitamin\Model\ProjectModel;

/**
 * Filter project by ids.
 */
class ProjectIdsFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['project_ids'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        if (empty($this->value)) {
            $this->query->eq(ProjectModel::TABLE.'.id', 0);
        } else {
            $this->query->in(ProjectModel::TABLE.'.id', $this->value);
        }

        return $this;
    }
}
