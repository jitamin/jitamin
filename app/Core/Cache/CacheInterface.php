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
 * Interface CacheInterface
 */
interface CacheInterface
{
    /**
     * Store an item in the cache
     *
     * @access public
     * @param  string  $key
     * @param  mixed   $value
     */
    public function set($key, $value);

    /**
     * Retrieve an item from the cache by key
     *
     * @access public
     * @param  string  $key
     * @return mixed            Null when not found, cached value otherwise
     */
    public function get($key);

    /**
     * Remove all items from the cache
     *
     * @access public
     */
    public function flush();

    /**
     * Remove an item from the cache
     *
     * @access public
     * @param  string  $key
     */
    public function remove($key);
}
