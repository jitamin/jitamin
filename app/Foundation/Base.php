<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core;

use Pimple\Container;

/**
 * Base Class.
 */
abstract class Base
{
    /**
     * Container instance.
     *
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param \Pimple\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Load automatically dependencies.
     *
     * @param string $name Class name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->container[$name];
    }

    /**
     * Get object instance.
     *
     * @static
     *
     * @param Container $container
     *
     * @return static
     */
    public static function getInstance(Container $container)
    {
        return new static($container);
    }
}
