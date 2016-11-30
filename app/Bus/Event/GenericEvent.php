<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Event;

use ArrayAccess;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Generic event.
 */
class GenericEvent extends BaseEvent implements ArrayAccess
{
    protected $container = [];

    /**
     * Constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->container = $values;
    }

    /**
     * Get all.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->container;
    }

    /**
     * Set offset.
     *
     * @param string $offset
     * @param string $value
     *
     * @return null
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Check if the offset exists.
     *
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Unset offset.
     *
     * @param string $offset
     *
     * @return null
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Get offset by it's key.
     *
     * @param string $offset
     *
     * @return array|null
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}
