<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use Jitamin\Foundation\Http\Route;
use Jitamin\Foundation\Http\Router;
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
            if (file_exists(JITAMIN_DIR.'/bootstrap/cache/routes.php')) {
                $routes = require JITAMIN_DIR.'/bootstrap/cache/routes.php';
                $container['route']->loadCacheData($routes);
            } else {
                foreach (glob(JITAMIN_DIR.DIRECTORY_SEPARATOR.'routes'.DIRECTORY_SEPARATOR.'*.php') as $file) {
                    $routes = require $file;
                    foreach ($routes as $path => $entry) {
                        list($controller, $action) = explode('@', $entry);
                        $container['route']->addRoute($path, $controller, $action);
                    }
                }
            }
        }

        return $container;
    }
}
