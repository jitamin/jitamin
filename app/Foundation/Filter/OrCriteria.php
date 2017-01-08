<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Filter;

use PicoDb\Table;

/**
 * OR criteria.
 */
class OrCriteria implements CriteriaInterface
{
    /**
     * @var Table
     */
    protected $query;

    /**
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * Set the Query.
     *
     * @param Table $query
     *
     * @return CriteriaInterface
     */
    public function withQuery(Table $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Set filter.
     *
     * @param FilterInterface $filter
     *
     * @return CriteriaInterface
     */
    public function withFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Apply condition.
     *
     * @return CriteriaInterface
     */
    public function apply()
    {
        $this->query->beginOr();

        foreach ($this->filters as $filter) {
            $filter->withQuery($this->query)->apply();
        }

        $this->query->closeOr();

        return $this;
    }
}
