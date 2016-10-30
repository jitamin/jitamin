<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Hiject\Core\Plugin\Loader;

/**
 * Plugin Provider
 */
class PluginProvider implements ServiceProviderInterface
{
    /**
     * Register providers
     *
     * @access public
     * @param  \Pimple\Container $container
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $container['pluginLoader'] = new Loader($container);
        $container['pluginLoader']->scan();

        return $container;
    }
}
