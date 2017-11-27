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

/**
 * Class UserNameFilter.
 */
class UserNameFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['name'];
    }

    /**
     * Apply filter.
     *
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query
            ->eq('is_active', 1)
            ->beginOr()
            ->ilike('username', '%'.$this->value.'%')
            ->ilike('name', '%'.$this->value.'%')
            ->closeOr();

        return $this;
    }
}
