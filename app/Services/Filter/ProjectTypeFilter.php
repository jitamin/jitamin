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
use Jitamin\Model\ProjectModel;

/**
 * Filter project by type.
 */
class ProjectTypeFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['type'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        if (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(ProjectModel::TABLE.'.is_private', $this->value);
        } elseif ($this->value === 'private') {
            $this->query->eq(ProjectModel::TABLE.'.is_private', ProjectModel::TYPE_PRIVATE);
        } else {
            $this->query->eq(ProjectModel::TABLE.'.is_private', ProjectModel::TYPE_TEAM);
        }

        return $this;
    }
}
