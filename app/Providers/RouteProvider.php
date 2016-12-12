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

use Hiject\Core\Http\Route;
use Hiject\Core\Http\Router;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Route Provider.
 */
class RouteProvider implements ServiceProviderInterface
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
        $container['router'] = new Router($container);
        $container['route'] = new Route($container);

        if (ENABLE_URL_REWRITE) {
            $container['route']->enable();
            foreach (glob(HIJECT_DIR . DIRECTORY_SEPARATOR . 'routes'.DIRECTORY_SEPARATOR . '*.php') as $file) {
                require $file;
            }
        }

        return $container;
    }
}
