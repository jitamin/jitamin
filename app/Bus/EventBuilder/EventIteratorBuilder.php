<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\EventBuilder;

use Iterator;

/**
 * Class EventIteratorBuilder
 */
class EventIteratorBuilder implements Iterator
{
    private $position = 0;
    private $builders = array();

    /**
     * Set builder
     *
     * @access public
     * @param  BaseEventBuilder $builder
     * @return $this
     */
    public function withBuilder(BaseEventBuilder $builder)
    {
        $this->builders[] = $builder;
        return $this;
    }

    /**
     * Rewind
     *
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Current
     *
     * @return BaseEventBuilder
     */
    public function current()
    {
        return $this->builders[$this->position];
    }

    /**
     * Key
     *
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Next
     *
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Check if position valid
     *
     */
    public function valid()
    {
        return isset($this->builders[$this->position]);
    }
}
