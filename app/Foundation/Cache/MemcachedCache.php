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

use Carbon\Carbon;

/**
 * Memcached Cache Driver.
 */
class MemcachedCache extends BaseCache
{
    /**
     * The Memcached instance.
     *
     * @var \Memcached
     */
    protected $memcached;

    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Container.
     *
     * @var array
     */
    private $storage = [];

    /**
     * Create a new Memcached store.
     *
     * @param \Memcached $memcached
     * @param string     $prefix
     *
     * @return void
     */
    public function __construct($memcached, $prefix = '')
    {
        $this->setPrefix($prefix);
        $this->memcached = $memcached;
    }

    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     */
    public function set($key, $value, $minutes = 0)
    {
        $this->memcached->set($this->prefix.$key, $value, $this->toTimestamp($minutes));
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
        $value = $this->memcached->get($this->prefix.$key);
        if ($this->memcached->getResultCode() == 0) {
            return $value;
        }
    }

    /**
     * Clear all cache.
     */
    public function flush()
    {
        $this->memcached->flush();
    }

    /**
     * Remove cached value.
     *
     * @param string $key
     */
    public function remove($key)
    {
        return $this->memcached->delete($this->prefix.$key);
    }

    /**
     * Get the UNIX timestamp for the given number of minutes.
     *
     * @parma  int  $minutes
     *
     * @return int
     */
    protected function toTimestamp($minutes)
    {
        return $minutes > 0 ? Carbon::now()->addMinutes($minutes)->getTimestamp() : 0;
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set the cache key prefix.
     *
     * @param string $prefix
     *
     * @return void
     */
    public function setPrefix($prefix)
    {
        $this->prefix = !empty($prefix) ? $prefix.':' : '';
    }
}
