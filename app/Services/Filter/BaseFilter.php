<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Filter;

use PicoDb\Table;

/**
 * Base filter class.
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
     * BaseFilter constructor.
     *
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Get object instance.
     *
     * @static
     *
     * @param mixed $value
     *
     * @return static
     */
    public static function getInstance($value = null)
    {
        return new static($value);
    }

    /**
     * Set query.
     *
     * @param Table $query
     *
     * @return \Jitamin\Core\Filter\FilterInterface
     */
    public function withQuery(Table $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Set the value.
     *
     * @param string $value
     *
     * @return \Jitamin\Core\Filter\FilterInterface
     */
    public function withValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
