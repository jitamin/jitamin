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
 * Criteria Interface
 */
interface CriteriaInterface
{
    /**
     * Set the Query
     *
     * @access public
     * @param Table $query
     * @return CriteriaInterface
     */
    public function withQuery(Table $query);

    /**
     * Set filter
     *
     * @access public
     * @param  FilterInterface $filter
     * @return CriteriaInterface
     */
    public function withFilter(FilterInterface $filter);

    /**
     * Apply condition
     *
     * @access public
     * @return CriteriaInterface
     */
    public function apply();
}
