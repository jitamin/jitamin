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
use Hiject\Model\ProjectGroupRoleModel;

/**
 * Filter ProjectGroupRole users by project.
 */
class ProjectGroupRoleProjectFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return [];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query->eq(ProjectGroupRoleModel::TABLE.'.project_id', $this->value);

        return $this;
    }
}
