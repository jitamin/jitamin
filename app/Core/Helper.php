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
 * Helper base class
 */
class Helper
{
    /**
     * Helper instances
     *
     * @access private
     * @var \Pimple\Container
     */
    private $helpers;

    /**
     * Container instance
     *
     * @access private
     * @var \Pimple\Container
     */
    private $container;

    /**
     * Constructor
     *
     * @access public
     * @param  \Pimple\Container   $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->helpers = new Container;
    }

    /**
     * Expose helpers with magic getter
     *
     * @access public
     * @param  string $helper
     * @return mixed
     */
    public function __get($helper)
    {
        return $this->getHelper($helper);
    }

    /**
     * Expose helpers with method
     *
     * @access public
     * @param  string $helper
     * @return mixed
     */
    public function getHelper($helper)
    {
        return $this->helpers[$helper];
    }

    /**
     * Register a new Helper
     *
     * @access public
     * @param  string $property
     * @param  string $className
     * @return Helper
     */
    public function register($property, $className)
    {
        $container = $this->container;

        $this->helpers[$property] = function () use ($className, $container) {
            return new $className($container);
        };

        return $this;
    }
}
