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

use Hiject\Core\Tool;
use LogicException;

/**
 * Class FileCache
 */
class FileCache extends BaseCache
{
    /**
     * Store an item in the cache
     *
     * @access public
     * @param  string $key
     * @param  mixed  $value
     */
    public function set($key, $value)
    {
        $this->createCacheFolder();
        file_put_contents($this->getFilenameFromKey($key), serialize($value));
    }

    /**
     * Retrieve an item from the cache by key
     *
     * @access public
     * @param  string $key
     * @return mixed            Null when not found, cached value otherwise
     */
    public function get($key)
    {
        $filename = $this->getFilenameFromKey($key);

        if (file_exists($filename)) {
            return unserialize(file_get_contents($filename));
        }

        return null;
    }

    /**
     * Remove all items from the cache
     *
     * @access public
     */
    public function flush()
    {
        $this->createCacheFolder();
        Tool::removeAllFiles(CACHE_DIR, false);
    }

    /**
     * Remove an item from the cache
     *
     * @access public
     * @param  string $key
     */
    public function remove($key)
    {
        $filename = $this->getFilenameFromKey($key);

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * Get absolute filename from the key
     *
     * @access protected
     * @param  string $key
     * @return string
     */
    protected function getFilenameFromKey($key)
    {
        return CACHE_DIR.DIRECTORY_SEPARATOR.$key;
    }

    /**
     * Create cache folder if missing
     *
     * @access protected
     * @throws LogicException
     */
    protected function createCacheFolder()
    {
        if (! is_dir(CACHE_DIR)) {
            if (! mkdir(CACHE_DIR, 0755)) {
                throw new LogicException('Unable to create cache directory: '.CACHE_DIR);
            }
        }
    }
}
