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

use Jitamin\Foundation\Filter\FilterInterface;
use Jitamin\Model\UserModel;

/**
 * Filter ProjectUserRole users by username.
 */
class ProjectUserRoleUsernameFilter extends BaseFilter implements FilterInterface
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
        $this->query
            ->join(UserModel::TABLE, 'id', 'user_id')
            ->ilike(UserModel::TABLE.'.username', $this->value.'%');

        return $this;
    }
}
