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

use PicoDb\Table;

/**
 * Base filter class
 */
abstract class BaseFilter
{
    /**
     * @var Table
     */
    protected $query;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * BaseFilter constructor
     *
     * @access public
     * @param  mixed $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Get object instance
     *
     * @static
     * @access public
     * @param  mixed $value
     * @return static
     */
    public static function getInstance($value = null)
    {
        return new static($value);
    }

    /**
     * Set query
     *
     * @access public
     * @param  Table $query
     * @return \Hiject\Core\Filter\FilterInterface
     */
    public function withQuery(Table $query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Set the value
     *
     * @access public
     * @param  string $value
     * @return \Hiject\Core\Filter\FilterInterface
     */
    public function withValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
