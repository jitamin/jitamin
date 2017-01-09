<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation;

use Pimple\Container;

/**
 * Helper base class.
 */
class Helper
{
    /**
     * Helper instances.
     *
     * @var \Pimple\Container
     */
    private $helpers;

    /**
     * Container instance.
     *
     * @var \Pimple\Container
     */
    private $container;

    /**
     * Constructor.
     *
     * @param \Pimple\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->helpers = new Container();
    }

    /**
     * Expose helpers with magic getter.
     *
     * @param string $helper
     *
     * @return mixed
     */
    public function __get($helper)
    {
        return $this->getHelper($helper);
    }

    /**
     * Expose helpers with method.
     *
     * @param string $helper
     *
     * @return mixed
     */
    public function getHelper($helper)
    {
        return $this->helpers[$helper];
    }

    /**
     * Register a new Helper.
     *
     * @param string $property
     * @param string $className
     *
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
