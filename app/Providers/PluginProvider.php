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

use Hiject\Core\Plugin\Loader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Plugin Provider.
 */
class PluginProvider implements ServiceProviderInterface
{
    /**
     * Register providers.
     *
     * @param \Pimple\Container $container
     *
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $container['pluginLoader'] = new Loader($container);
        $container['pluginLoader']->scan();

        return $container;
    }
}
