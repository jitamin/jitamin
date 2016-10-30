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
 * Filter Interface
 */
interface FilterInterface
{
    /**
     * BaseFilter constructor
     *
     * @access public
     * @param  mixed $value
     */
    public function __construct($value = null);

    /**
     * Set the value
     *
     * @access public
     * @param  string $value
     * @return FilterInterface
     */
    public function withValue($value);

    /**
     * Set query
     *
     * @access public
     * @param  Table $query
     * @return FilterInterface
     */
    public function withQuery(Table $query);

    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes();

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply();
}
