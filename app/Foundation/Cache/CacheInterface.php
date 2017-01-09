<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation\Cache;

/**
 * Interface CacheInterface.
 */
interface CacheInterface
{
    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     */
    public function set($key, $value, $minutes);

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     *
     * @return mixed Null when not found, cached value otherwise
     */
    public function get($key);

    /**
     * Remove all items from the cache.
     */
    public function flush();

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     */
    public function remove($key);
}
