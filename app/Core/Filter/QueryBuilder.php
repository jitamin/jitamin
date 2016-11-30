<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Filter;

use PicoDb\Table;

/**
 * Class QueryBuilder.
 */
class QueryBuilder
{
    /**
     * Query object.
     *
     * @var Table
     */
    protected $query;

    /**
     * Set the query.
     *
     * @param Table $query
     *
     * @return QueryBuilder
     */
    public function withQuery(Table $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Set a filter.
     *
     * @param FilterInterface $filter
     *
     * @return QueryBuilder
     */
    public function withFilter(FilterInterface $filter)
    {
        $filter->withQuery($this->query)->apply();

        return $this;
    }

    /**
     * Set a criteria.
     *
     * @param CriteriaInterface $criteria
     *
     * @return QueryBuilder
     */
    public function withCriteria(CriteriaInterface $criteria)
    {
        $criteria->withQuery($this->query)->apply();

        return $this;
    }

    /**
     * Set a formatter.
     *
     * @param FormatterInterface $formatter
     *
     * @return string|array
     */
    public function format(FormatterInterface $formatter)
    {
        return $formatter->withQuery($this->query)->format();
    }

    /**
     * Get the query result as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->query->findAll();
    }

    /**
     * Get Query object.
     *
     * @return Table
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Clone object with deep copy.
     */
    public function __clone()
    {
        $this->query = clone $this->query;
    }
}
