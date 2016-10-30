<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core;

use Pimple\Container;

/**
 * Base Class
 */
abstract class Base
{
    /**
     * Container instance
     *
     * @access protected
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @access public
     * @param  \Pimple\Container   $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Load automatically dependencies
     *
     * @access public
     * @param  string $name Class name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->container[$name];
    }

    /**
     * Get object instance
     *
     * @static
     * @access public
     * @param  Container $container
     * @return static
     */
    public static function getInstance(Container $container)
    {
        return new static($container);
    }
}
