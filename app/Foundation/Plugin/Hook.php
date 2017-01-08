<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Plugin;

/**
 * Plugin Hooks Handler.
 */
class Hook
{
    /**
     * List of hooks.
     *
     * @var array
     */
    private $hooks = [];

    /**
     * Bind something on a hook.
     *
     * @param string $hook
     * @param mixed  $value
     */
    public function on($hook, $value)
    {
        if (!isset($this->hooks[$hook])) {
            $this->hooks[$hook] = [];
        }

        $this->hooks[$hook][] = $value;
    }

    /**
     * Get all bindings for a hook.
     *
     * @param string $hook
     *
     * @return array
     */
    public function getListeners($hook)
    {
        return isset($this->hooks[$hook]) ? $this->hooks[$hook] : [];
    }

    /**
     * Return true if the hook is used.
     *
     * @param string $hook
     *
     * @return bool
     */
    public function exists($hook)
    {
        return isset($this->hooks[$hook]);
    }

    /**
     * Merge listener results with input array.
     *
     * @param string $hook
     * @param array  $values
     * @param array  $params
     *
     * @return array
     */
    public function merge($hook, array &$values, array $params = [])
    {
        foreach ($this->getListeners($hook) as $listener) {
            $result = call_user_func_array($listener, $params);

            if (is_array($result) && !empty($result)) {
                $values = array_merge($values, $result);
            }
        }

        return $values;
    }

    /**
     * Execute only first listener.
     *
     * @param string $hook
     * @param array  $params
     *
     * @return mixed
     */
    public function first($hook, array $params = [])
    {
        foreach ($this->getListeners($hook) as $listener) {
            return call_user_func_array($listener, $params);
        }
    }

    /**
     * Hook with reference.
     *
     * @param string $hook
     * @param mixed  $param
     *
     * @return mixed
     */
    public function reference($hook, &$param)
    {
        foreach ($this->getListeners($hook) as $listener) {
            $listener($param);
        }

        return $param;
    }
}
