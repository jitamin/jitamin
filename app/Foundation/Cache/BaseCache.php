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
 * Base Class for Cache Drivers.
 */
abstract class BaseCache implements CacheInterface
{
    /**
     * Proxy cache.
     *
     * Note: Arguments must be scalar types
     *
     * @param string $class  Class instance
     * @param string $method Container method
     *
     * @return mixed
     */
    public function proxy($class, $method)
    {
        $args = func_get_args();
        array_shift($args);

        $key = 'proxy:'.get_class($class).':'.implode(':', $args);
        $result = $this->get($key);

        if ($result === null) {
            $result = call_user_func_array([$class, $method], array_splice($args, 1));
            $this->set($key, $result);
        }

        return $result;
    }
}
