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
 * Lexer Builder.
 */
class LexerBuilder
{
    /**
     * Lexer object.
     *
     * @var Lexer
     */
    protected $lexer;

    /**
     * Query object.
     *
     * @var Table
     */
    protected $query;

    /**
     * List of filters.
     *
     * @var FilterInterface[]
     */
    protected $filters;

    /**
     * QueryBuilder object.
     *
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->lexer = new Lexer();
        $this->queryBuilder = new QueryBuilder();
    }

    /**
     * Add a filter.
     *
     * @param FilterInterface $filter
     * @param bool            $default
     *
     * @return LexerBuilder
     */
    public function withFilter(FilterInterface $filter, $default = false)
    {
        $attributes = $filter->getAttributes();

        foreach ($attributes as $attribute) {
            $this->filters[$attribute] = $filter;
            $this->lexer->addToken(sprintf('/^(%s:)/i', $attribute), $attribute);

            if ($default) {
                $this->lexer->setDefaultToken($attribute);
            }
        }

        return $this;
    }

    /**
     * Set the query.
     *
     * @param Table $query
     *
     * @return LexerBuilder
     */
    public function withQuery(Table $query)
    {
        $this->query = $query;
        $this->queryBuilder->withQuery($this->query);

        return $this;
    }

    /**
     * Parse the input and build the query.
     *
     * @param string $input
     *
     * @return QueryBuilder
     */
    public function build($input)
    {
        $tokens = $this->lexer->tokenize($input);

        foreach ($tokens as $token => $values) {
            if (isset($this->filters[$token])) {
                $this->applyFilters($this->filters[$token], $values);
            }
        }

        return $this->queryBuilder;
    }

    /**
     * Apply filters to the query.
     *
     * @param FilterInterface $filter
     * @param array           $values
     */
    protected function applyFilters(FilterInterface $filter, array $values)
    {
        $len = count($values);

        if ($len > 1) {
            $criteria = new OrCriteria();
            $criteria->withQuery($this->query);

            foreach ($values as $value) {
                $currentFilter = clone $filter;
                $criteria->withFilter($currentFilter->withValue($value));
            }

            $this->queryBuilder->withCriteria($criteria);
        } elseif ($len === 1) {
            $this->queryBuilder->withFilter($filter->withValue($values[0]));
        }
    }

    /**
     * Clone object with deep copy.
     */
    public function __clone()
    {
        $this->lexer = clone $this->lexer;
        $this->query = clone $this->query;
        $this->queryBuilder = clone $this->queryBuilder;
    }
}
