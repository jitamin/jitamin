<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Console;

use Pimple\Container;
use Symfony\Component\Console\Command\Command;

/**
 * Base command class.
 */
abstract class BaseCommand extends Command
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
        parent::__construct();
        $this->container = $container;
    }

    /**
     * Load automatically models.
     *
     * @param string $name Model name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->container[$name];
    }
}
