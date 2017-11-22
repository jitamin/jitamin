<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\EventBuilder;

use Iterator;

/**
 * Class EventIteratorBuilder.
 */
class EventIteratorBuilder implements Iterator
{
    private $position = 0;
    private $builders = [];

    /**
     * Set builder.
     *
     * @param BaseEventBuilder $builder
     *
     * @return $this
     */
    public function withBuilder(BaseEventBuilder $builder)
    {
        $this->builders[] = $builder;

        return $this;
    }

    /**
     * Rewind.
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Current.
     *
     * @return BaseEventBuilder
     */
    public function current()
    {
        return $this->builders[$this->position];
    }

    /**
     * Key.
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Next.
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Check if position valid.
     */
    public function valid()
    {
        return isset($this->builders[$this->position]);
    }
}
