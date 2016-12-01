<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Cache;

/**
 * Memory Cache Driver.
 */
class MemoryCache extends BaseCache
{
    /**
     * Container.
     *
     * @var array
     */
    private $storage = [];

    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     * @param int $minutes
     */
    public function set($key, $value, $minutes = 0)
    {
        $this->storage[$key] = $value;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     *
     * @return mixed Null when not found, cached value otherwise
     */
    public function get($key)
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }

    /**
     * Clear all cache.
     */
    public function flush()
    {
        $this->storage = [];
    }

    /**
     * Remove cached value.
     *
     * @param string $key
     */
    public function remove($key)
    {
        unset($this->storage[$key]);
    }
}
